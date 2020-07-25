<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\BossModel;
use DH\EPGP\Models\LocationModel;
use DH\EPGP\Repositories\BossRepository;
use DH\EPGP\Repositories\LocationRepository;
use DH\EPGP\Views\BossManagementView;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class BossController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class BossController extends Controller
{

    /**
     * Lists the current active characters
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list()
    {
        $view = new BossManagementView();
        $bossData = (new BossRepository())->fetchAll();
        $view->setBossData($bossData);
        $view->setLocationdata((new LocationRepository())->fetchAll());
        $view->view();
    }

    /**
     * Creates a new Boss entry
     */
    public function create(): void
    {
        parse_str($_POST['boss'], $bossData);

        if (empty($bossData['name'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid boss name',
            ]);
        } elseif (empty($bossData['location'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid boss location',
            ]);
        } elseif (empty($bossData['effort-points'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid number of effort points (greater than 0) for this boss',
            ]);
        } elseif (empty($bossData['boss-order'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid boss kill order',
            ]);
        } else {
            // Check if the boss kill order is within acceptable bounds for the location.
            $location = new LocationModel((int) $bossData['location']);
            if (!$location->checkKillOrder((int) $bossData['boss-order'])) {
                $this->toJson([
                    'success' => false,
                    'message' => 'You must include a valid boss kill order',
                ]);
            } else {
                $boss = new BossModel();
                $boss->setName($bossData['name'])
                    ->setLocationId((int) $bossData['location'])
                    ->setEffortPoints((int) $bossData['effort-points'])
                    ->setKillOrder((int) $bossData['boss-order']);
                $result = $boss->save();

                if (!$result) {
                    $this->toJson(
                        [
                            'success' => false,
                            'message' => 'Failed to save boss.'
                        ]
                    );
                } else {
                    $this->toJson(
                        [
                            'success' => true,
                            'message' => 'Successfully saved boss.',
                            'boss' => $boss,
                        ]
                    );
                }
            }
        }
    }

    /**
     * Edits an existing boss record. We only really want to allow editing of EP
     * values, anything else would be a pain.
     */
    public function edit(): void {
        parse_str($_POST['boss'], $bossData);

        if (empty($_POST['boss_id'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid boss id',
            ]);
        } elseif (empty($bossData['effort-points-edit'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid number of effort points (greater than 0) for this boss',
            ]);
        } else {
            $boss = new BossModel((int) $_POST['boss_id']);
            $boss->setEffortPoints((int) $bossData['effort-points-edit']);
            $result = $boss->save();

            if (!$result) {
                $this->toJson(
                    [
                        'success' => false,
                        'message' => 'Failed to update boss EP.'
                    ]
                );
            } else {
                $this->toJson(
                    [
                        'success' => true,
                        'message' => 'Successfully saved boss EP.',
                        'boss' => $boss,
                    ]
                );
            }
        }
    }
}
