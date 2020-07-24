<?php


namespace DH\EPGP\Views;


use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CharacterManagementView extends AbstractView
{
    /** @var array */
    private $characterData;

    /** @var array */
    private $guildData;

    /**
     * CharacterManagementView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->template = 'characterManagement.twig';
    }

    /**
     * Sets the character data array with characters to show.
     * @param array $characterData
     */
    public function setCharacterData(array $characterData) : void
    {
        $this->characterData = $characterData;
    }

    /**
     * Sets the guild data array with guild info from the db, for dropdowns and stuff.
     * @param array $guildData
     */
    public function setGuildData(array $guildData) : void
    {
        $this->guildData = $guildData;
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
                'characters' => $this->characterData,
                'guilds' => $this->guildData,
            ]
        );
    }

}
