<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class Controller
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class Controller
{
    use DBAwareTrait;

    public function __construct()
    {
        $this->connect();
    }

    /**
     * Converts stuff to JSON and outputs it.
     * @param array|string|object $content
     */
    private function toJson($content)
    {
        header('Content-type: application/json');
        echo json_encode($content);
    }

    /**
     * Trim all data in a given array that is passed by reference.
     * @param array &$array The data to trim.
     */
    protected function trim(array &$array) : void
    {
        // Handle Keys
        $keys = array_map(
            function($key) {
                return htmlentities(trim(''.$key));
            },
            array_keys($array)
        );
        // Handle Values
        $values = array_map(
            function($value) {
                return htmlentities(trim(''.$value));
            },
            $array
        );
        $array = array_combine($keys, $values);
    }

}
