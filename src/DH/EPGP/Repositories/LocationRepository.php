<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;


use DH\EPGP\Models\LocationModel;
use DH\EPGP\Traits\DBAwareTrait;

class LocationRepository
{
    use DBAwareTrait;

    /**
     * Returns all location records.
     * @return array
     */
    public function fetchAll() : array
    {
        $locations = [];
        $query = "SELECT id from Locations";
        $stmt = $this->pdo()->query($query);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $locations[] = new LocationModel((int) $row['id']);
        }

        return $locations;
    }
}
