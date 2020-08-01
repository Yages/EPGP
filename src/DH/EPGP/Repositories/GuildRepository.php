<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;

use DH\EPGP\Models\GuildModel;

/**
 * Class GuildRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class GuildRepository extends Repository
{
    /**
     * Returns all guild records.
     * @return array
     */
    public function fetchAll() : array
    {
        $guilds = [];
        $query = "SELECT id FROM Guild";
        $stmt = $this->db->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $guilds[] = new GuildModel((int) $row['id']);
        }

        return $guilds;
    }
}
