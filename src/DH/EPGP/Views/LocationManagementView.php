<?php declare(strict_types=1);


namespace DH\EPGP\Views;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LocationManagementView
 * @package DH\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LocationManagementView extends AbstractView
{
    /** @var array */
    private array $locationData;

    public function __construct()
    {
        parent::__construct();
        $this->template = 'locationManagement.twig';
    }

    /**
     * @param array $locationData
     * @return LocationManagementView
     */
    public function setLocationData(array $locationData) : LocationManagementView
    {
        $this->locationData = $locationData;
        return $this;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view()
    {
        $this->twig->display(
            $this->template,
            [
                'locations' => $this->locationData,
                'user' => $this->user,
            ]
        );
    }
}
