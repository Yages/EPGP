<?php


namespace DH\EPGP\Views;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class BossManagementView
 * @package DH\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class BossManagementView extends AbstractView
{
    /** @var array */
    private array $bossData;

    /** @var array */
    private $locationData;

    public function __construct()
    {
        parent::__construct();
        $this->template = 'bossManagement.twig';
    }

    /**
     * Sets boss data for the view.
     * @param array $bossData
     * @return BossManagementView
     */
    public function setBossData(array $bossData) : BossManagementView
    {
        $this->bossData = $bossData;
        return $this;
    }

    /**
     * Sets location data for the view.
     * @param array $locationData
     * @return BossManagementView
     */
    public function setLocationdata(array $locationData) : BossManagementView
    {
        $this->locationData = $locationData;
        return $this;
    }

    /**
     * Abstract View method, to be implemented on subclasses.
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view()
    {
        $this->twig->display(
            $this->template, [
                'bosses' => $this->bossData,
                'locations' => $this->locationData,
                'user' => $this->user,
            ]
        );
    }
}
