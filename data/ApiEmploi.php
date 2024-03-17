<?php

namespace data;

class ApiEmploi
{

    private function getToken(){
        $curl = curl_init();

        $url = "https://entreprise.pole-emploi.fr/connexion/oauth2/access_token";

        $auth_data = array(
            "grant_type" => "client_credentials",
            "client_id" => CLIENT_ID,
            "client_secret" => CLIENT_SECRET,
            "scope" => "api_offresdemploiv2 o2dsoffre"
        );

        $params = array(
            CURLOPT_URL =>  $url."?realm=%2Fpartenaire",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($auth_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            )
        );

        curl_setopt_array($curl, $params);

        $response = curl_exec($curl);

        if(!$response)
            die("Connection Failure");

        curl_close($curl);

        return json_decode($response, true);
    }


    public function getAllAnnonces()
    {
        $token = $this->getToken() ;

        $api_url = "https://api.pole-emploi.io/partenaire/offresdemploi/v2/offres/search";

        $curlConnection = curl_init();
        $params = array(
            CURLOPT_URL => $api_url."?sort=1&domaine=M18",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("Authorization: Bearer " . $token['access_token'] )
        );

        curl_setopt_array($curlConnection, $params);
        $response = curl_exec($curlConnection);
        curl_close($curlConnection);

        if( !$response )
            echo curl_error($curlConnection);

        $response = json_decode($response, true);


        // parcourt du tableau associatif pour extraire les offres d'emploi dans le domaine des systèmes d'information et de télécommunication
        $annonces = array();
        foreach ($response['resultats'] as $offre){
            $id = $offre['id'];
            $title = $offre['intitule'];
            $body = $offre['description'];

            if(isset($offre['salaire']['libelle']))
                $body .= '; '. $offre['salaire']['libelle'];
            if(isset($offre['entreprise']['nom']))
                $body .= '; '. $offre['entreprise']['nom'];
            if(isset($offre['contact']['coordonnees1']))
                $body .= '; '. $offre['contact']['coordonnees1'];

            $currentPost = new Post($id, $title, $body, date("Y-m-d H:i:s"));
            $annonces[$id] = $currentPost;
        }

        return $annonces;
    }

    public function getPost($id)
    {
        $token = $this->getToken() ;

        $api_url = "https://api.pole-emploi.io/partenaire/offresdemploi/v2/offres/";

        $curlConnection  = curl_init();
        $params = array(
            CURLOPT_URL =>  $api_url.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("Authorization: Bearer " . $token['access_token'] )
        );
        curl_setopt_array($curlConnection, $params);
        $response = curl_exec($curlConnection);
        curl_close($curlConnection);

        if( !$response )
            echo curl_error($curlConnection);

        $response = json_decode( $response, true );

        // récupération des informations et création du Post
        $id = $response['id'];
        $title = $response['intitule'];
        $body = $response['description'];

        if( isset($response['salaire']['libelle']) )
            $body.='; '.$response['salaire']['libelle'];
        if( isset($response['entreprise']['nom']) )
            $body.='; '.$response['entreprise']['nom'];
        if ( isset($response['contact']['coordonnees1']) )
            $body.='; '.$response['contact']['coordonnees1'];

        return  new Post($id, $title, $body, date("Y-m-d H:i:s") );
    }


}