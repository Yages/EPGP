<?php


namespace DH\EPGP\Views;


use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GuildManagementView extends AbstractView
{
    /** @var array */
    private array $guildData;

    public function __construct()
    {
        parent::__construct();
        $this->template = 'guildManagement.twig';
    }

    public function setGuildData(array $guildData) : void
    {
        $this->guildData = $guildData;
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
                'guilds' => $this->guildData,
                'user' => $this->user,
            ]
        );
    }
}
