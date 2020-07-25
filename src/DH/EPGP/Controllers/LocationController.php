<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\LocationModel;
use DH\EPGP\Repositories\LocationRepository;
use DH\EPGP\Views\LocationManagementView;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LocationController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LocationController extends Controller
{
    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list() {
        $view = new LocationManagementView();
        $locationData = (new LocationRepository())->fetchAll();
        $view->setLocationData($locationData);
        $view->view();
    }

    /**
     * Creates a new Location from user data.
     * @return void
     */
    public function create(): void
    {
        parse_str($_POST['location'], $locationData);

        if (empty($locationData['name'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid location Name',
            ]);
        } if (empty($locationData['boss-count'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid number of Boss Encounters',
            ]);
        } else {
            $location = new LocationModel();
            $location->setName($locationData['name']);
            $location->setBossCount((int) $locationData['boss-count']);
            $result = $location->save();

            if (!$result) {
                $this->toJson(
                    [
                        'success' => false,
                        'message' => 'Failed to save location.'
                    ]
                );
            } else {
                $this->toJson(
                    [
                        'success' => true,
                        'message' => 'Successfully saved location.',
                        'location' => $location,
                    ]
                );
            }
        }
    }
}
