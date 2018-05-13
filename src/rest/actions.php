<?php
use \Firebase\JWT\JWT;
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/12/2018
 * Time: 9:00 PM
 */

function connexion ( $action ) {
    $result = array();
    $req = $action->request();
    $post = $req->post();

    $user = new User();
    $user->setEmail( $post['email'] );
    $user->setPassword( $post['password'] );

    $uService = new UserService();
    $user = $uService->authenticate($user);

//    var_dump($user);


    $key = "5wu{@N\"i!^G>M5z0Zzk,e8,w1G$5[#";

    if ( $user ) {
        $token = array (
            'id_user' => $user['id_user'],
            'type' => $user['type'],
            'email' => $user['email']
        );

        $result['success'] = true;
        $result['token'] = JWT::encode($token, $key, 'HS256');

   } else {
       $result['success'] = false;
   }

    $action->response()->toJson($result);
    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
//    $decoded = JWT::decode($jwt, $key, array('HS256'));

//    var_dump($jwt);

    /*$decoded_array = (array) $decoded;
    var_dump($decoded_array);*/
}