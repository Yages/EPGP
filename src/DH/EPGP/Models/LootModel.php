<?php declare(strict_types=1);


namespace DH\EPGP\Models;

/**
 * Class LootModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LootModel extends AbstractModel
{
    /** Item Rarity constants */
    const ITEM_RARITY_LEGENDARY = 5;
    const ITEM_RARITY_EPIC = 4;
    const ITEM_RARITY_RARE = 3;
    const ITEM_RARITY_UNCOMMON = 2;
    const ITEM_RARITY_COMMON = 1;

    /** @var int|null */
    private int $id;

    /** @var string */
    private string $name;

    /** @var int */
    private int $slotId;

    /** @var string */
    private string $slotName;

    /** @var int */
    private int $locationId;

    /** @var string */
    private string $locationName;

    /** @var int */
    private int $iLvl;

    /** @var int */
    private int $rarity;

    /** @var array */
    private array $bosses;


    /**
     * LootModel constructor.
     * @param int|null $id
     */
    public function __construct(int $id)
    {
        parent::__construct();
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LootModel
     */
    public function setId(int $id): LootModel
    {
        $this->id = $id;
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
     * @param string $name
     * @return LootModel
     */
    public function setName(string $name): LootModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSlotId(): int
    {
        return $this->slotId;
    }

    /**
     * @param int $slotId
     * @return LootModel
     */
    public function setSlotId(int $slotId): LootModel
    {
        $this->slotId = $slotId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlotName(): string
    {
        return $this->slotName;
    }

    /**
     * @return int
     */
    public function getLocationId(): int
    {
        return $this->locationId;
    }

    /**
     * @param int $locationId
     * @return LootModel
     */
    public function setLocationId(int $locationId): LootModel
    {
        $this->locationId = $locationId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocationName(): string
    {
        return $this->locationName;
    }

    /**
     * @return int
     */
    public function getILvl(): int
    {
        return $this->iLvl;
    }

    /**
     * @param int $iLvl
     * @return LootModel
     */
    public function setILvl(int $iLvl): LootModel
    {
        $this->iLvl = $iLvl;
        return $this;
    }

    /**
     * @return int
     */
    public function getRarity(): int
    {
        return $this->rarity;
    }

    /**
     * @param int $rarity
     * @return LootModel
     */
    public function setRarity(int $rarity): LootModel
    {
        $this->rarity = $rarity;
        return $this;
    }

    /**
     * @return array
     */
    public function getBosses(): array
    {
        return $this->bosses;
    }

    /**
     * Returns an array of Boss Names for display purposes.
     */
    public function getBossNames(): array {
        if (empty($this->bosses)) {
            return ['Trash'];
        } else {
            $return = [];
            foreach($this->bosses as $boss) {
                $return[] = $boss->getName();
            }
            return $return;
        }
    }

    /**
     * @param array $bosses
     * @return LootModel
     */
    public function setBosses(array $bosses): LootModel
    {
        $this->bosses = $bosses;
        return $this;
    }

    /**
     * Loads the Loot data.
     * @return bool
     */
    public function load(): bool
    {
        if (empty($this->id)) {
            return false;
        }

        $query = "SELECT l.name,                         
                         l.location_id,
                         ll.name as location,
                         l.slot as slot_id,
                         g.description as slot,
                         l.item_level,
                         l.rarity
                    FROM Loot l
                         INNER JOIN Locations ll ON ll.id = l.location_id
                         INNER JOIN GearPoints g ON g.slot = l.slot
                   WHERE l.id = :id";

        $stmt = $this->db->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            error_log(print_r($stmt->errorInfo()));
            return false;
        }

        $lootData = $stmt->fetch();

        $this->name = $lootData['name'];
        $this->locationId = (int) $lootData['location_id'];
        $this->locationName = $lootData['location'];
        $this->slotId = (int) $lootData['slot_id'];
        $this->slotName = $lootData['slot'];
        $this->iLvl = (int) $lootData['item_level'];
        $this->rarity = (int) $lootData['rarity'];

        // Get Boss Drop Info
        $query = "SELECT bl.boss_id
                    FROM Boss b 
                         INNER JOIN BossLoot bl 
                         ON b.id = bl.boss_id
                   WHERE bl.loot_id = :id";
        $stmt = $this->db->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            error_log(print_r($stmt->errorInfo()));
            return false;
        }

        $bossData = $stmt->fetchAll();

        foreach ($bossData as $bossId) {
            $this->bosses[] = new BossModel((int) $bossId['boss_id']);
        }

        return true;
    }

    /**
     * Saves the loot data.
     * @return bool
     */
    public function save(): bool
    {
        if (empty($this->id)
            || empty($this->name)
            || empty($this->locationId)
            || !isset($this->slotId)
            || empty($this->iLvl)
            || empty($this->rarity)) {
            return false;
        }


        $query = "INSERT INTO Loot (id,
                                    name,                       
                                    location_id,
                                    slot,
                                    item_level,
                                    rarity)
                       VALUES (:id, 
                               :name, 
                               :location_id, 
                               :slot, 
                               :item_level, 
                               :rarity)";
        $stmt = $this->db->pdo()->prepare($query);
        $result = $stmt->execute([
            ':id' => $this->id,
            ':name' => $this->name,
            ':location_id' => $this->locationId,
            ':slot' => $this->slotId,
            ':item_level' => $this->iLvl,
            ':rarity' => $this->rarity,
        ]);

        if (!$result) {
            error_log(var_export($stmt->errorInfo(), true));
            return false;
        }

        $this->saveBossData();
        $this->updateLocationName();
        $this->updateSlotName();

        return true;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'locationId' => $this->locationId,
            'location' => $this->locationName,
            'slotId' => $this->slotId,
            'slot' => $this->slotName,
            'iLvl' => $this->iLvl,
            'rarity' => $this->rarity,
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
     * Used to update the slot name in the model, this is only when constructing
     * a new entry from scratch.
     */
    private function updateSlotName(): void
    {
        if (!empty($this->slotId)) {
            $query = "SELECT description FROM GearPoints WHERE slot = :slot";
            $stmt = $this->db->pdo()->prepare($query);
            $result = $stmt->execute([':slot' => $this->slotId]);
            if ($result) {
                $this->slotName = $stmt->fetch()[0];
            }
        }
    }

    /**
     * Saves the boss data in the database.
     */
    private function saveBossData(): void
    {
        if (!empty($this->bosses)) {
            $this->clearBossData();
            $query = "INSERT INTO BossLoot (loot_id, boss_id)
                           VALUES (:loot_id, :boss_id)";
            $stmt = $this->db->pdo()->prepare($query);

            foreach ($this->bosses as $boss) {
                $stmt->execute([
                    ':loot_id' => $this->id,
                    ':boss_id' => $boss->getId(),
                ]);
            }
        }
    }

    /**
     * Clears the boss data from the database.
     */
    private function clearBossData(): void
    {
        if ($this->id) {
            $query = "DELETE FROM BossLoot
                            WHERE loot_id = :id";
            $stmt = $this->db->pdo()->prepare($query);
            $stmt->execute([':id' => $this->id]);
        }
    }

    /**
     * Checks a given loot id to make sure we dont already have an entry for it.
     * @param int $lootId
     * @return bool
     */
    public function checkId(int $lootId) : bool
    {
        $query = "SELECT COUNT(*) 
                    FROM Loot 
                   WHERE id = :id";
        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([':id' => $lootId]);
        $count = (int) $stmt->fetch()[0];
        return ($count === 0);
    }
}
