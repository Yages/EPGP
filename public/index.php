<?php

namespace DH\EPGP;

use DH\EPGP\Controllers\AuthController;
use DH\EPGP\Controllers\CharacterController;
use DH\EPGP\Controllers\GuildController;
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
    $router->get('/', function() use ($totalsController) {
        $totalsController->list();
    })->get('/logout', function() use ($authController) {
        $authController->logout();
        header('Location: /');
    });

    // Character stuff
    $characterController = new CharacterController();
    $router->get('/characters', function() use ($characterController) {
        $characterController->list();
    })->get('/characters/inactive', function() use ($characterController) {
        $characterController->list(true);
    });
    $router->post('/characters/deactivate', function() use ($characterController) {
        $characterController->deactivate();
    })->post('/characters/activate', function() use ($characterController) {
        $characterController->activate();
    })->post('/characters/edit', function() use ($characterController) {
        $characterController->edit();
    })->post('/characters/create', function() use ($characterController) {
        $characterController->create();
    });

    // Guild Stuff
    $guildController = new GuildController();
    $router->get('/guilds', function() use ($guildController) {
        $guildController->list();
    });
    $router->post('/guilds/create', function() use ($guildController) {
        $guildController->create();
    });

} else {
    // Add Catchall Routes - for when session times out
    $router->get('*', function() use ($authController) {
        header('Location: /login');
    })->post('*', function() use ($authController) {
        header('Location: /login');
    });

    // Allow totals to be accessible to all without login
    $router->get('/', function() use ($totalsController) {
        $totalsController->list();
    });

    // Login and logout routes
    $router->get('/login', function() use ($authController) {
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
