<?php declare(strict_types=1);


namespace DH\EPGP\Views;


use DH\EPGP\Models\UserModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class TotalsView
 * @package Divine Heresy\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class TotalsView extends AbstractView
{
    /**
     * @var array
     */
    private array $characterData;

    /**
     * TotalsView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->template = 'totals.twig';
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view() : void
    {
        $this->twig->display($this->template, ['characters' => $this->characterData, 'user' => $this->user]);
    }

    /**
     * Sets the character data for the view.
     * @param array $data
     * @return TotalsView
     */
    public function setCharacterData(array $data): TotalsView
    {
        $this->characterData = $data;
        return $this;
    }
}
