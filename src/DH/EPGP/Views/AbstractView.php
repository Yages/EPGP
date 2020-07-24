<?php declare(strict_types=1);


namespace DH\EPGP\Views;

use DH\EPGP\Controllers\AuthController;
use DH\EPGP\Models\UserModel;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Class AbstractView
 * @package DH\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
abstract class AbstractView
{
    /** @var string */
    protected string $template;

    /** @var string */
    protected string $basePath;

    /** @var FilesystemLoader */
    protected FilesystemLoader $loader;

    /** @var Environment */
    protected Environment $twig;

    /** @var UserModel|null */
    protected ?UserModel $user;

    /**
     * AbstractView constructor.
     */
    public function __construct()
    {
        $this->user = AuthController::getLoggedInUser();
        $this->basePath = dirname(__FILE__, 5) . '/assets/Templates/';
        $this->loader = new FilesystemLoader($this->basePath);
        $this->twig = new Environment($this->loader);
        $this->twig->addGlobal('admin', $this->user);
    }

    /**
     * Abstract View method, to be implemented on subclasses.
     */
    abstract public function view();
}
