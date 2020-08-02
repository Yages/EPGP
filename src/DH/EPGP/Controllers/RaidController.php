<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DateTimeImmutable;
use DH\EPGP\Models\RaidBossModel;
use DH\EPGP\Models\RaidModel;
use DH\EPGP\Repositories\LocationRepository;
use DH\EPGP\Repositories\RaidRepository;
use DH\EPGP\Views\RaidDetailView;
use DH\EPGP\Views\RaidManagementView;

/**
 * Class RaidController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class RaidController extends Controller
{
    public function list(): void
    {
        $view = new RaidManagementView();

        $locationData = (new LocationRepository())->fetchAll();
        $raidData = (new RaidRepository())->fetchAll();

        $view->setRaidData($raidData)
            ->setLocationData($locationData)
            ->view();
    }

    public function create(): void
    {
        if (empty($_POST['location'])) {
            $this->toJson([
                'success' => false,
                'message' => 'A Location ID must be specified to create a Raid.',
            ]);
        } else {
            $raid = new RaidModel();
            $raid->setLocationId((int)$_POST['location']);
            $result = $raid->save();

            if (!$result) {
                $this->toJson(
                    [
                        'success' => false,
                        'message' => 'Failed to create Raid.',
                    ]
                );
            } else {
                $this->toJson(
                    [
                        'success' => true,
                        'message' => 'Successfully created Raid.',
                        'raid' => $raid,
                    ]
                );
            }
        }
    }

    public function show(): void
    {
        $view = new RaidDetailView();

        if (empty($_GET['id'])) {
            $view->setError();
            $view->view();
        } else {
            $raid = new RaidModel((int) $_GET['id']);
            $view->setRaid($raid)
                ->view();
        }
    }

    public function updateBoss(): void
    {
        $validation = $this->validate(
            'post',
            [
                [
                    'key' => 'raid',
                    'message' => 'A Raid ID must be specified.',
                ],
                [
                    'key' => 'boss',
                    'message' => 'A Boss ID must be specified.',
                ],
                [
                    'key' => 'action',
                    'message' => 'A valid action must be specified.',
                ],
            ]
        );

        if ($validation['valid']) {
            $raid = new RaidModel((int) $_POST['raid']);
            $boss = $raid->getBossById((int) $_POST['boss']);
            if ($boss) {
                if ($_POST['action'] === 'mark-alive') {
                    $boss->setStatus(RaidBossModel::ALIVE);
                    $boss->setDate(null);
                } else {
                    $boss->setStatus(RaidBossModel::DEAD);
                    $boss->setDate(new DateTimeImmutable());
                }

                $boss->save();

                $this->toJson([
                    'success' => true,
                    'boss' => $boss,
                ]);
            }
        } else {
            $this->toJson($validation);
        }
    }
}
