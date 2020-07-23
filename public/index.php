<?php

namespace DH\EPGP;

use DH\EPGP\Controllers\AuthController;
use DH\EPGP\Controllers\TotalsController;

date_default_timezone_set('Australia/Melbourne');

session_start();

require_once('../vendor/autoload.php');
require_once('../src/autoload.php');

$router = new Router();
$authController = new AuthController();
$totalsController = new TotalsController();

// Already logged in via session
if ($authController->checkSession()) {
    $user = $authController->getLoggedInUser();
    $router->get('/', function() use ($totalsController, $user) {
        $totalsController->list($user);
    })->get('/logout', function() use ($authController) {
        $authController->logout();
        header('Location: /');
    });
} else {
    $router->get('/', function() use ($totalsController) {
        $totalsController->list();
    })->get('/login', function() use ($authController) {
        $authController->show();
    })->post('/login', function() use ($authController) {
        $username = trim($_POST['username'] ?? 'default');
        $password = trim($_POST['password'] ?? 'default');
        if ($authController->check($username, $password)) {
            $authController->login($username);
            header('Location: /');
        } else $authController->show(true);
    });
}

$router->dispatch();
