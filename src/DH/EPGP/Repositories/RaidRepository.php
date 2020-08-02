<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;

use DH\EPGP\Models\RaidModel;
use Exception;

/**
 * Class RaidRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class RaidRepository extends Repository
{
    /**
     * @return array
     * @throws Exception
     */
    public function fetchAll(): array
    {
        $raids = [];

        $query = "SELECT id 
                    FROM Raid";

        $stmt = $this->db->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $item = new RaidModel((int) $row['id']);
            $raids[] = $item;
        }

        return $raids;
    }
}
