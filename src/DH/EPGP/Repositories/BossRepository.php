<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;


use DH\EPGP\Models\BossModel;
use DH\EPGP\Traits\DBAwareTrait;

class BossRepository
{
    use DBAwareTrait;

    /**
     * Returns all boss records.
     * @return array
     */
    public function fetchAll() : array
    {
        $boss = [];
        $query = "SELECT id from Boss";
        $stmt = $this->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $boss[] = new BossModel((int) $row['id']);
        }

        return $boss;
    }
}
