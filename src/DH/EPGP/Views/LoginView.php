<?php


namespace DH\EPGP\Views;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LoginView
 * @package DH\EPGP\Views
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LoginView extends AbstractView
{
    /** @var bool  */
    private bool $error;

    /**
     * LoginView constructor.
     * @param bool $admin
     */
    public function __construct(bool $admin = false)
    {
        parent::__construct($admin);
        $this->template = 'login.twig';
        $this->error = false;
    }

    public function setError() : LoginView
    {
        $this->error = true;
        return $this;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view() {
        $this->twig->display($this->template, ['error' => $this->error]);
    }
}
