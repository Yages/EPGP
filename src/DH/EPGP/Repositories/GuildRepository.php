<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;

use DH\EPGP\Models\GuildModel;
use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class GuildRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class GuildRepository
{
    use DBAwareTrait;

    /**
     * Returns all guild records.
     * @return array
     */
    public function fetchAll() : array
    {
        $guilds = [];
        $query = "SELECT id FROM Guild";
        $stmt = $this->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $guilds[] = new GuildModel((int) $row['id']);
        }

        return $guilds;
    }
}
