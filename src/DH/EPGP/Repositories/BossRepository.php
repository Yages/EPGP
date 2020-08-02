<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;


use DH\EPGP\Models\BossModel;

/**
 * Class BossRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class BossRepository extends Repository
{
    /**
     * Returns all boss records.
     * @return array
     */
    public function fetchAll() : array
    {
        $boss = [];
        $query = "SELECT id FROM Boss";
        $stmt = $this->db->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $boss[] = new BossModel((int) $row['id']);
        }

        return $boss;
    }

    /**
     * Returns all boss records.
     * @param int $location
     * @return array
     */
    public function fetchAllByLocation(int $location) : array
    {
        $boss = [];
        $query = "SELECT id 
                    FROM Boss
                   WHERE location_id = :location
                ORDER BY kill_order ASC";
        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([':location' => $location]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $boss[] = new BossModel((int) $row['id']);
        }

        return $boss;
    }

    /**
     * Returns all boss records.
     * @param int $location
     * @return array
     */
    public function fetchAllIdsByLocation(int $location) : array
    {
        $boss = [];
        $query = "SELECT id 
                    FROM Boss
                   WHERE location_id = :location
                ORDER BY kill_order ASC";
        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([':location' => $location]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $boss[] = (int) $row['id'];
        }

        return $boss;
    }
}
