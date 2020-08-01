<?php


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\UserModel;
use DH\EPGP\Repositories\CharacterRepository;
use DH\EPGP\Views\TotalsView;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class TotalsController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class TotalsController extends Controller
{
    /**
     * Lists the current EPGP Totals
     */
    public function list()
    {
        $view = new TotalsView();
        $data = (new CharacterRepository())->fetchAll();
        $view->setCharacterData($data);
        $view->view();
    }
}
