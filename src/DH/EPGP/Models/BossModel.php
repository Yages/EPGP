<?php declare(strict_types=1);


namespace DH\EPGP\Models;


use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class BossModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class BossModel extends AbstractModel
{
    use DBAwareTrait;

    /** @var int */
    private int $id;

    /** @var int */
    private int $locationId;

    /** @var string */
    private string $name;

    /** @var int */
    private int $effortPoints;

    /** @var int */
    private int $killOrder;

    /** @var string */
    private $locationName;

    /**
     * BossModel constructor.
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
     * Loads the Boss.
     * @return bool
     */
    public function load(): bool
    {
        if (empty($this->id)) {
            return false;
        }

        $query = "SELECT location_id,
                         l.name as location_name,
                         b.name,
                         b.effort_points,
                         b.kill_order
                    FROM Boss b 
                         INNER JOIN Locations l 
                         ON b.location_id = l.id 
                   WHERE b.id = :id";

        $stmt = $this->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            return false;
        }

        $bossData = $stmt->fetch();
        $this->name = $bossData['name'];
        $this->locationId = (int) $bossData['location_id'];
        $this->locationName = $bossData['location_name'];
        $this->effortPoints = (int) $bossData['effort_points'];
        $this->killOrder = (int) $bossData['kill_order'];

        return true;
    }

    /**
     * Saves a Raid boss, creating a record if required.
     * @return bool
     */
    public function save(): bool
    {
        if (empty($this->name)) {
            return false;
        }

        if (empty($this->id)) {
            $query = "INSERT INTO Boss (location_id, name, effort_points, kill_order) 
                           VALUES (:location_id, :name, :effort_points, :kill_order)";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                'location_id' => $this->locationId,
                ':name' => $this->name,
                ':effort_points' => $this->effortPoints,
                ':kill_order' => $this->killOrder,
            ]);
            $this->id = (int) $this->pdo()->lastInsertId();
            $this->updateLocationName();
        } else {
            $query = "UPDATE Boss
                         SET location_id = :location_id,
                             name = :name,
                             effort_points = :effort_points,
                             kill_order = :kill_order
                       WHERE id = :id";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                'location_id' => $this->locationId,
                ':name' => $this->name,
                ':effort_points' => $this->effortPoints,
                ':id' => $this->id,
                ':kill_order' => $this->killOrder,
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
            'name' => $this->name,
            'locationId' => $this->locationId,
            'location' => $this->locationName,
            'effortPoints' => $this->effortPoints,
            'killOrder' => $this->killOrder,
        ];
    }

    /**
     * @return string
     */
    public function getLocationName(): string
    {
        return $this->locationName;
    }

    /**
     * Used to update the location name in the model, this is only when
     * constructing a new boss from scratch.
     */
    private function updateLocationName(): void
    {
        if (!empty($this->locationId)) {
            $query = "SELECT name FROM Locations WHERE id = :id";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([':id' => $this->locationId]);
            if ($result) {
                $this->locationName = $stmt->fetch()[0];
            }
        }
    }

    /**
     * @param int $locationId
     * @return BossModel
     */
    public function setLocationId(int $locationId) : BossModel
    {
        $this->locationId = $locationId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setName(string $name): BossModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $order
     * @return BossModel
     */
    public function setKillOrder(int $order): BossModel
    {
        $this->killOrder = $order;
        return $this;
    }

    /**
     * @return int
     */
    public function getKillOrder(): int
    {
        return $this->killOrder;
    }

    public function setEffortPoints(int $points): BossModel
    {
        $this->effortPoints = $points;
        return $this;
    }

    /**
     * @return int
     */
    public function getEffortPoints(): int
    {
        return $this->effortPoints;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
