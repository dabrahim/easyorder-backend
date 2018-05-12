<?php
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

    $result['user'] = $user;

    $action->response()->toJson($result);
}