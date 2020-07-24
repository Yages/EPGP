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

        $query = "SELECT name
                    FROM Locations 
                   WHERE id = :id";

        $stmt = $this->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            return false;
        }

        $locationData = $stmt->fetch();
        $this->name = $locationData['name'];


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
            $query = "INSERT INTO Locations (name) 
                           VALUES (:name)";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([':name' => $this->name]);
            $this->id = (int) $this->pdo()->lastInsertId();
        } else {
            $query = "UPDATE Locations
                         SET name = :name
                       WHERE id = :id";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([':name' => $this->name]);
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
}
