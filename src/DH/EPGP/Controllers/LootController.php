<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Repositories\BossRepository;
use DH\EPGP\Repositories\LocationRepository;
use DH\EPGP\Repositories\LootRepository;
use DH\EPGP\Views\LootManagementView;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LootController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LootController extends Controller
{
    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list() : void
    {
        $view = new LootManagementView();

        $locationData = (new LocationRepository())->fetchAll();
        $bossData = (new BossRepository())->fetchAll();
        $lootData = (new LootRepository())->fetchAll();

        $view->setLocationData($locationData)
            ->setBossData($bossData)
            ->setLootData($lootData)
            ->view();
    }
}
