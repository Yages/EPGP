<?php

namespace DH\EPGP;

use DH\EPGP\Controllers\AuthController;
use DH\EPGP\Controllers\BossController;
use DH\EPGP\Controllers\CharacterController;
use DH\EPGP\Controllers\GuildController;
use DH\EPGP\Controllers\LocationController;
use DH\EPGP\Controllers\LootController;
use DH\EPGP\Controllers\RaidController;
use DH\EPGP\Controllers\TotalsController;
use DH\EPGP\Utilities\Router;

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

    // Location Stuff
    $locationController = new LocationController();
    $router->get('/locations', function() use ($locationController) {
        $locationController->list();
    });
    $router->post('/locations/create', function() use ($locationController) {
        $locationController->create();
    });

    // Boss Stuff
    $bossController = new BossController();
    $router->get('/bosses', function () use ($bossController) {
        $bossController->list();
    });
    $router->post('/bosses/create', function() use ($bossController) {
        $bossController->create();
    })->post('/bosses/edit', function() use ($bossController) {
        $bossController->edit();
    });

    // Loot Stuff
    $lootController = new LootController();
    $router->get('/loot', function () use ($lootController) {
        $lootController->list();
    });

    // Raid Stuff
    $raidController = new RaidController();
    $router->get('/raids', function() use ($raidController) {
        $raidController->list();
    })->get('/raids/detail', function() use ($raidController) {
        $raidController->show();
    });
    $router->post('/raids/create', function() use ($raidController) {
        $raidController->create();
    })->post('/raids/boss/update', function() use ($raidController) {
        $raidController->updateBoss();
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
