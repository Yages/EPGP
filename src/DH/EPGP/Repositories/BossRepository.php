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
        $query = "SELECT id from Boss";
        $stmt = $this->db->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $boss[] = new BossModel((int) $row['id']);
        }

        return $boss;
    }
}
