<?php declare(strict_types=1);


namespace DH\EPGP\Models;

use DateTime;
use DH\EPGP\Repositories\BossRepository;
use DH\EPGP\Repositories\RaidBossRepository;
use Exception;

/**
 * Class RaidModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class RaidModel extends AbstractModel
{
    /** @var int|null */
    private ?int $id;

    /** @var int */
    private int $locationId;

    /** @var string */
    private string $locationName;

    /** @var DateTime */
    private DateTime $date;

    /** @var bool */
    private $finalised;

    /** @var array */
    private $bosses;

    /**
     * RaidModel constructor.
     * @param int|null $id
     * @throws Exception
     */
    public function __construct(?int $id = null)
    {
        parent::__construct();
        if (!empty($id)) {
            $this->id = $id;
            $this->load();
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function load(): bool
    {
        if (empty($this->id)) {
            return false;
        }

        $query = "SELECT r.location_id,
                         l.name AS location_name,
                         r.date,
                         r.finalised
                    FROM Raid r 
                         INNER JOIN Locations l ON r.location_id = l.id
                   WHERE r.id = :id";

        $stmt = $this->db->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            error_log(print_r($stmt->errorInfo()));
            return false;
        }

        $raidData = $stmt->fetch();

        $this->locationId = (int) $raidData['location_id'];
        $this->locationName = $raidData['location_name'];
        $this->date = new DateTime($raidData['date']);
        $this->finalised = (bool) $raidData['finalised'];

        $this->updateBosses();

        return true;
    }

    /**
     * Saves the Raid Data
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        if (empty($this->id)) {
            if (empty($this->locationId)) {
                return false;
            }

            $this->date = new DateTime();

            $query = "INSERT INTO Raid (location_id,
                                        date,
                                        finalised)
                           VALUES (:location_id,
                                   :date,
                                   0)";

            $stmt = $this->db->pdo()->prepare($query);
            $result = $stmt->execute([
                ':location_id' => $this->locationId,
                ':date' => $this->date->format('Y-m-d H:i:s'),
            ]);

            if (!$result) {
                error_log(var_export($stmt->errorInfo(), true));
                return false;
            }

            $this->id = (int) $this->db->pdo()->lastInsertId();
            $this->updateLocationName();
            $this->updateBosses();

            return true;
        }
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'locationId' => $this->locationId,
            'locationName' => $this->locationName,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'finalised' => $this->finalised,
            'bosses' => $this->bosses,
        ];
    }

    /**
     * Used to update the location name in the model, this is only when
     * constructing a new entry from scratch.
     */
    private function updateLocationName(): void
    {
        if (!empty($this->locationId)) {
            $query = "SELECT name FROM Locations WHERE id = :id";
            $stmt = $this->db->pdo()->prepare($query);
            $result = $stmt->execute([':id' => $this->locationId]);
            if ($result) {
                $this->locationName = $stmt->fetch()[0];
            }
        }
    }

    /**
     * Used to update the location name in the model, this is only when
     * constructing a new entry from scratch.
     * @throws Exception
     */
    private function updateBosses(): void
    {
        if (!empty($this->locationId)) {
            $this->bosses = (new RaidBossRepository())->fetchAllByRaid($this->id);
        }

        // Means its a new raid.
        if (empty($this->bosses)) {
            $bosses = (new BossRepository())->fetchAllIdsByLocation($this->locationId);
            foreach ($bosses as $bossId) {
                $raidBoss = new RaidBossModel($this->id, $bossId);
                $raidBoss->save();
                $this->bosses[] = $raidBoss;
            }
        }
    }

    /**
     * Get raid attendees.
     * @return array
     */
    public function getRaidAttendees(): array
    {
        $query = "SELECT character_id 
                    FROM RaidCharacter 
                   WHERE raid_id = :id";
        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([':id' => $this->id]);
        $ids = $stmt->fetchAll();

        $characters = [];

         foreach ($ids as $row) {
             $characters[] = new CharacterModel((int) $row['id']);
         }

        return $characters;
    }

    /**
     * Add a Raid Attendee.
     * @param int $id
     * @return bool
     */
    public function addRaidAttendee(int $id): bool
    {
        $query = "INSERT INTO RaidCharacter(raid_id, character_id, join_time)
                       VALUES (:raid_id, :character_id, GETDATE())";
        $stmt = $this->db->pdo()->prepare($query);

        return $stmt->execute([
            ':raid_id' => $this->id,
            ':character_id' => $id,
        ]);
    }

    /**
     * Add multiple Raid Attendees.
     * @param array $ids
     */
    public function addRaidAttendees(array $ids)
    {
        foreach ($ids as $id) {
            $this->addRaidAttendee($id);
        }
    }

    /**
     * Removes a Raid Attendee.
     * @param int $id
     * @return bool
     */
    public function removeRaidAttendee(int $id): bool {
        $query = "DELETE FROM RaidCharacter
                        WHERE raid_id = :raid_id
                          AND character_id = :character_id";
        $stmt = $this->db->pdo()->prepare($query);

        return $stmt->execute([
            ':raid_id' => $this->id,
            ':character_id' => $id,
        ]);
    }

    /**
     * Removes multiple Raid Attendees.
     * @param array $ids
     */
    public function removeRaidAttendees(array $ids)
    {
        foreach ($ids as $id) {
            $this->removeRaidAttendee($id);
        }
    }

    /**
     * Mark a Raid Attendee as left.
     * @param int $id
     * @return bool
     */
    public function markAttendeeAsLeft(int $id): bool
    {
        $query = "UPDATE RaidCharacter
                     SET leave_time = GETDATE()
                   WHERE raid_id = :raid_id 
                     AND character_id = :character_id";
        $stmt = $this->db->pdo()->prepare($query);
        return $stmt->execute([
            ':raid_id' => $this->id,
            ':character_id' => $id,
        ]);
    }

    /**
     * Finalise Raid.
     * @return bool
     */
    public function markAsFinalised(): bool
    {
        $query = "UPDATE Raid
                     SET finalised = 1 
                   WHERE raid_id = :raid_id";
        $stmt = $this->db->pdo()->prepare($query);
        return $stmt->execute([':raid_id' => $this->id]);
    }

    /**
     * @param int|null $id
     * @return RaidModel
     */
    public function setId(?int $id): RaidModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param int $locationId
     * @return RaidModel
     */
    public function setLocationId(int $locationId): RaidModel
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * @param DateTime $date
     * @return RaidModel
     */
    public function setDate(DateTime $date): RaidModel
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param bool $finalised
     * @return RaidModel
     */
    public function setFinalised(bool $finalised): RaidModel
    {
        $this->finalised = $finalised;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLocationId(): int
    {
        return $this->locationId;
    }

    /**
     * @return string
     */
    public function getLocationName(): string
    {
        return $this->locationName;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    /**
     * @return bool
     */
    public function isFinalised(): bool
    {
        return $this->finalised;
    }

    public function getBosses(): array
    {
        return $this->bosses;
    }

    /**
     * @param int $id
     * @return RaidBossModel|null
     */
    public function getBossById(int $id): ?RaidBossModel
    {
        $returnBoss = null;

        foreach ($this->bosses as $boss) {
            if ($boss->getBoss()->getId() === $id) {
                $returnBoss = $boss;
                break;
            }
        }

        return $returnBoss;
    }
}
