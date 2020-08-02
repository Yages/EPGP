<?php declare(strict_types=1);


namespace DH\EPGP\Views;

use DH\EPGP\Models\RaidModel;

/**
 * Class RaidDetailView
 * @package DH\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class RaidDetailView extends AbstractView
{
    /** @var bool */
    private $error = false;

    /** @var RaidModel */
    private RaidModel $raid;

    public function __construct()
    {
        parent::__construct();
        $this->template = 'raidDetailDisplay.twig';
    }

    /**
     * Abstract View method, to be implemented on subclasses.
     */
    public function view()
    {
        $this->twig->display(
            $this->template,
            [
                'raid' => $this->raid,
                'error' => $this->error,
                'user' => $this->user,
            ]
        );
    }

    /**
     * Sets the view to display an error.
     * @return RaidDetailView
     */
    public function setError(): RaidDetailView
    {
        $this->error = true;
        return $this;
    }

    /**
     * Sets the raid detail to display.
     * @param RaidModel $raid
     * @return RaidDetailView
     */
    public function setRaid(RaidModel $raid): RaidDetailView
    {
        $this->raid = $raid;
        return $this;
    }
}
