<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/14/2018
 * Time: 5:06 PM
 */

function addClient ( \Esmt\Pharmaliv\Action $action ) {
    $req = $action->request();
    $result = array(
        'success' => false
    );

    if ($req->type() === 'POST') {
        $post = $req->post();

        if ($post['password'] === $post['password_confirmation']) {
            $client = new Client();
            $client->setEmail( $post['email'] );
            $client->setTelephone( $post['telephone'] );
            $client->setCoordGeo( $post['coordGeo'] );
            $client->setNom( $post['nom'] );
            $client->setPrenom( $post['prenom'] );
            $client->setPassword( $post['password'] );

            $cService = new ClientService();
            if ($cService->create( $client )){
                $result['success'] = true;
            } else {
                $result['message'] = "L'inscription a échouée. Veuillez réessayer plus tard";
            }
        } else {
            $result['message'] = "Les deux mots de passe ne correspondent pas.";
        }

    } else {
        $result['message'] = "Cette route est uniquement accessible en GET";
        http_response_code(400);
    }

    $action->response()->toJson( $result );
}