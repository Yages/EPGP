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
        $filterData = [];
        $view = new LootManagementView();
        if (!empty($_POST['location-filter'])) {
            $filterData['location'] = (int) $_POST['location-filter'];
            $view->setLocationFilter($filterData['location']);
        }
        if (!empty($_POST['boss-filter'])) {
            $filterData['boss'] = (int) $_POST['boss-filter'];
            $view->setLocationFilter($filterData['boss']);
        }

        $locationData = (new LocationRepository())->fetchAll();
        $bossData = (new BossRepository())->fetchAll();
        $lootData = (new LootRepository())->fetchAll($filterData);

        $view->setLocationData($locationData)
            ->setBossData($bossData)
            ->setlootData($lootData);

        $view->view();
    }
}
