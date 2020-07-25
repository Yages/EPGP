<?php declare(strict_types=1);


namespace DH\EPGP\Models;

use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class LocationModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LocationModel extends AbstractModel
{
    use DBAwareTrait;

    /** @var int */
    private int $id;

    /** @var string */
    private string $name;

    /** @var int */
    private int $bossCount;

    /**
     * LocationModel constructor.
     * @param int|null $id
     */
    public function __construct(?int $id = null)
    {
        if (!empty($id)) {
            $this->id = $id;
            $this->load();
        }
    }

    /**
     * Loads the Location.
     * @return bool
     */
    public function load(): bool
    {
        if (empty($this->id)) {
            return false;
        }

        $query = "SELECT name,
                         boss_count
                    FROM Locations 
                   WHERE id = :id";

        $stmt = $this->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            return false;
        }

        $locationData = $stmt->fetch();
        $this->name = $locationData['name'];
        $this->bossCount = (int) $locationData['boss_count'];

        return true;
    }

    /**
     * Saves the Location
     * @return bool
     */
    public function save(): bool
    {
        if (empty($this->name)) {
            return false;
        }

        if (empty($this->id)) {
            $query = "INSERT INTO Locations (name, boss_count) 
                           VALUES (:name, :boss_count)";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':boss_count' => $this->bossCount,
            ]);
            $this->id = (int) $this->pdo()->lastInsertId();
        } else {
            $query = "UPDATE Locations
                         SET name = :name,
                             boss_count = :boss_count
                       WHERE id = :id";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':boss_count' => $this->bossCount,
                ':id' => $this->id,
            ]);
        }

        return $result;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bossCount' => $this->bossCount,
        ];
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return LocationModel
     */
    public function setName(string $name) : LocationModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getBossCount(): int
    {
        return $this->bossCount;
    }

    /**
     * @param int $count
     * @return LocationModel
     */
    public function setBossCount(int $count): LocationModel
    {
        $this->bossCount = $count;
        return $this;
    }

    /**
     * Checks a given kill order to see if there's an issue with the new boss
     * record clashing.
     * @param int $order
     * @return bool
     */
    public function checkKillOrder(int $order): bool
    {
        // Cant be zero
        if ($order === 0) {
            return false;
        }

        // Cant be greater than our max boss count
        if ($order > $this->bossCount) {
            return false;
        }

        // Cant exist already
        $query = "SELECT COUNT(*) 
                    FROM Boss b
                         INNER JOIN Locations l 
                         ON b.location_id = l.id
                   WHERE b.kill_order = :killOrder
                     AND l.id = :id";
        $stmt = $this->pdo()->prepare($query);
        $result = $stmt->execute([
            ':killOrder' => $order,
            ':id' => $this->id,
        ]);

        if (!$result) return false;

        $collisions = $stmt->fetch()[0];

        if ($collisions > 0) return false;

        return true;
    }
}
