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
    private ?UserModel $user;

    /**
     * @var array
     */
    private array $characterData;

    /**
     * TotalsView constructor.
     * @param UserModel|null $user
     */
    public function __construct(?UserModel $user = null)
    {
        parent::__construct();
        $this->template = 'totals.twig';
        $this->user = $user;
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
     */
    public function setCharacterData(array $data)
    {
        $this->characterData = $data;
    }
}
