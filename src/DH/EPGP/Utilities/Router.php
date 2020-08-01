<?php declare(strict_types=1);


namespace DH\EPGP\Utilities;


use Closure;
use Exception;

class Router
{

    /** @var array */
    private $routes;

    /** @var array */
    private $allowedMethods = [
        'GET',
        'POST',
    ];

    /**
     * Registers a GET request action.
     * @param string $action
     * @param Closure $function
     * @return Router
     */
    public function get(string $action, Closure $function): Router
    {
        $this->routes['GET'][$action] = $function;

        return $this;
    }

    /**
     * Registers a POST request action.
     * @param string $action
     * @param Closure $function
     * @return Router
     */
    public function post(string $action, Closure $function): Router
    {
        $this->routes['POST'][$action] = $function;

        return $this;
    }

    /**
     * Prints the route table as it is, just for debugging.
     */
    public function print(): void
    {
        $output = <<<HTML
        <table cellpadding="3px" border="1">
            <thead>
                <tr>
                    <th>Request Method</th>
                    <th>Request URI</th>
                </tr>
            </thead>
            <tbody
HTML;
        $rowTemplate = "<tr><td>%s</td><td>%s</td></tr>";
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $routeKey => $routeValue) {
                $output .= sprintf($rowTemplate, $method, $routeKey);
            }
        }
        $output .= "</tbody></table>";

        echo $output;
    }

    /**
     * Handles the dispatching of routes, assuming that they exist.
     */
    public function dispatch(): void
    {
        // Action and method set from $_SERVER vars.
        $action = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $action = parse_url($action);
            $action['query'] = $action['query'] ?? '';
            parse_str($action['query'], $data);
            $action = $action['path'];
        }

        // Only look at requests we expect.
        if (!in_array($method, $this->allowedMethods)) {
            header('HTTP/1.0 405 Method Not Allowed', true, 403);
            exit();
        }

        // Check to see if the action has been registered with the correct request method.
        if (!array_key_exists($action, $this->routes[$method])) {
            if (array_key_exists('*', $this->routes[$method])) {
                try {
                    $callback = $this->routes[$method]['*'];
                    call_user_func($callback);
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            } else {
                header('HTTP/1.0 403 Forbidden', true, 403);
            }
            exit();
        }

        // we're here, so now we go and actually call the route function.
        try {
            $callback = $this->routes[$method][$action];
            call_user_func($callback);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}

