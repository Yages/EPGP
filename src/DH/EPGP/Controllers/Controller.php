<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\UserModel;
use DH\EPGP\Utilities\DB;

/**
 * Class Controller
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class Controller
{
    /** @var UserModel|null */
    protected ?UserModel $user;

    /** @var DB|null */
    protected ?DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
        $this->user = AuthController::getLoggedInUser();
    }

    /**
     * Converts stuff to JSON and outputs it.
     * @param array|string|object $content
     */
    protected function toJson($content)
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

    /**
     * @param string $method
     * @param array $validationData
     * @return array
     */
    protected function validate(string $method, array $validationData): array
    {
        $valid = true;
        $messages = [];

        if ($method === 'post') {
            foreach ($validationData as $data) {
                if (empty($_POST[$data['key']])) {
                    error_log(${$method}[$data['key']]);
                    $valid = false;
                    $messages[] = $data['message'];
                }
            }
        } else {
            foreach ($validationData as $data) {
                if (empty($_GET[$data['key']])) {
                    error_log(${$method}[$data['key']]);
                    $valid = false;
                    $messages[] = $data['message'];
                }
            }
        }

        return [
            'valid' => $valid,
            'message' => implode(', ', $messages),
        ];
    }
}
