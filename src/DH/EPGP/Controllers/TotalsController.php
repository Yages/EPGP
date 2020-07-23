<?php


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\UserModel;
use DH\EPGP\Views\TotalsView;

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
    public function list(?UserModel $user = null)
    {
        $view = new TotalsView($user);
        $view->view();
    }
}
