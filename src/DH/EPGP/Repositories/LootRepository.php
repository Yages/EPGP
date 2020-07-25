<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;

use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class LootRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LootRepository
{
    use DBAwareTrait;

    /**
     * @param array|null $filters
     * @return array
     */
    public function fetchAll(?array $filters): array
    {
        $loot = [];
        $params = [];

        $query = "SELECT id 
                    FROM Loot
                   WHERE 1=1";
        if (array_key_exists('location', $filters)) {
            $query .= " AND location_id = :location";
            $params[':location'] = $filters['location'];
        }
        if (array_key_exists('boss', $filters)) {
            $query .= " AND boss_id = :boss";
            $params[':boss'] = $filters['boss'];
        }

        $stmt = $this->pdo()->prepare($query);
        $stmt->execute($params);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $loot[] = new LootModel((int) $row['id']);
        }

        return $loot;
    }
}
