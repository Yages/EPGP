<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;

use DH\EPGP\Models\LootModel;

/**
 * Class LootRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LootRepository extends Repository
{
    /**
     * @return array
     */
    public function fetchAll(): array
    {
        $loot = [];
        $params = [];

        $query = "SELECT id 
                    FROM Loot";

        $stmt = $this->db->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $item = new LootModel((int) $row['id']);
            $item->load();
            $loot[] = $item;
        }

        return $loot;
    }

    /**
     * Returns all loot for the specified boss.
     * @param int $bossId
     * @return array
     */
    public function fetchByBoss(int $bossId) : array
    {
        $loot = [];

        $query = "SELECT id 
                    FROM Loot
                   WHERE boss_id = :boss";

        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([':boss' => $bossId]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $item = new LootModel((int) $row['id']);
            $item->load();
            $loot[] = $item;
        }

        return $loot;
    }

    /**
     * Returns all loot for the specified location.
     * @param int $locationId
     * @return array
     */
    public function fetchByLocation(int $locationId) : array
    {
        $loot = [];

        $query = "SELECT id 
                    FROM Loot
                   WHERE location_id = :location";

        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([':location' => $locationId]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $item = new LootModel((int) $row['id']);
            $item->load();
            $loot[] = $item;
        }

        return $loot;
    }
}
