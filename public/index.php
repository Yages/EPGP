<?php

namespace DH\EPGP;

use DH\EPGP\Controllers\AuthController;
use DH\EPGP\Views\TotalsView;


date_default_timezone_set('Australia/Melbourne');

session_start();

require_once('../vendor/autoload.php');
require_once('../src/autoload.php');

$auth = new AuthController();

// Already logged in via session
if ($auth->checkSession()) {

} else {
    $view = new TotalsView();
    $view->view();


//    $loginView = new LoginView();
//
//    if (empty($_POST)) {
//        $loginView->view();
//    } else {
//        $username = trim($_POST['username'] ?? 'default');
//        $password = trim($_POST['password'] ?? 'default');
//        if ($auth->check($username, $password)) {
//            $auth->login($username);
//            header('Location: /');
//        } else $loginView->setError()->view();
//    }
}
