<?php declare(strict_types=1);


namespace DH\EPGP\Views;

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

    /** @var bool */
    protected bool $admin = false;

    /**
     * AbstractView constructor.
     * @param bool $admin
     */
    public function __construct(bool $admin = false)
    {
        $this->admin = $admin;
        $this->basePath = dirname(__FILE__, 5) . '/assets/Templates/';
        $this->loader = new FilesystemLoader($this->basePath);
        $this->twig = new Environment($this->loader);
        $this->twig->addGlobal('admin', $this->admin);
    }

    /**
     * Abstract View method, to be implemented on subclasses.
     */
    abstract public function view();
}
