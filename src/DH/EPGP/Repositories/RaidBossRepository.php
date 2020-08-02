<?php declare(strict_types=1);

namespace DH\EPGP\Repositories;

use DH\EPGP\Models\RaidBossModel;
use Exception;

/**
 * Class RaidBossRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class RaidBossRepository extends Repository
{

    /**
     * Returns all boss records.
     * @param int $raidId
     * @return array
     * @throws Exception
     */
    public function fetchAllByRaid(int $raidId) : array
    {
        $boss = [];
        $query = "SELECT boss_id
                    FROM RaidBoss
                   WHERE raid_id = :raidId";
        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([':raidId' => $raidId]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $boss[] = new RaidBossModel($raidId, (int) $row['boss_id']);
        }

        return $boss;
    }
}
