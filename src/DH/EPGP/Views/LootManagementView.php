<?php declare(strict_types=1);


namespace DH\EPGP\Views;


use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LootManagmentView
 * @package DH\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LootManagementView extends AbstractView
{
    /** @var array */
    private array $locationData;

    /** @var array */
    private array $bossData;

    /** @var array */
    private array $lootData;

    /**
     * LootManagementView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->template = 'lootManagement.twig';
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
                'loot' => $this->lootData,
                'locations' => $this->locationData,
                'bosses' => $this->bossData,
                'user' => $this->user,
            ]
        );
    }

    /**
     * @param array $data
     * @return LootManagementView
     */
    public function setLocationData(array $data): LootManagementView
    {
        $this->locationData = $data;
        return $this;
    }

    /**
     * @param array $data
     * @return LootManagementView
     */
    public function setBossData(array $data): LootManagementView
    {
        $this->bossData = $data;
        return $this;
    }

    /**
     * @param array $data
     * @return LootManagementView
     */
    public function setLootData(array $data): LootManagementView
    {
        $this->lootData = $data;
        return $this;
    }
}
