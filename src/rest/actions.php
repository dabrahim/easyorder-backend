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

    $key = "5wu{@N\"i!^G>M5z0Zzk,e8,w1G$5[#";

    if ( $user ) {
        $token = array (
            'id_user' => $user['id_user'],
            'type' => $user['type'],
            'email' => $user['email']
        );

        $result['success'] = true;
        $result['token'] = JWT::encode($token, $key, 'HS256');
        $result['user'] = $user;

        $cService = new CategorieService();
        $result['categories'] = $cService->getAll();

   } else {
       $result['success'] = false;
   }

    $action->response()->toJson($result);
}

function getAllCategories( $action ) {
    $cService = new CategorieService();
    $action->response()->toJson( $cService->getAll() ) ;
}