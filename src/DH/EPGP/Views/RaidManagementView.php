<?php declare(strict_types=1);


namespace DH\EPGP\Views;

/**
 * Class RaidManagementView
 * @package DH\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class RaidManagementView extends AbstractView
{
    /** @var array */
    private array $raidData;

    /** @var array */
    private $locationData;

    /**
     * RaidManagementView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->template = 'raidManagement.twig';
    }

    /**
     * Abstract View method, to be implemented on subclasses.
     */
    public function view()
    {
        $this->twig->display(
            $this->template,
            [
                'raids' => $this->raidData,
                'locations' => $this->locationData,
                'user' => $this->user,
            ]
        );
    }

    /**
     * @param array $data
     * @return RaidManagementView
     */
    public function setRaidData(array $data): RaidManagementView
    {
        $this->raidData = $data;
        return $this;
    }

    /**
     * @param array $data
     * @return RaidManagementView
     */
    public function setLocationData(array $data): RaidManagementView
    {
        $this->locationData = $data;
        return $this;
    }
}
