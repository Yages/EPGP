<?php declare(strict_types=1);


namespace DH\EPGP\Models;

use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class LootModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class LootModel extends AbstractModel
{
    use DBAwareTrait;

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
    private int $bossId;

    /** @var string */
    private string $bossName;

    /** @var int */
    private int $iLvl;

    /** @var int */
    private int $rarity;


    /**
     * LootModel constructor.
     * @param int|null $id
     */
    public function __construct(?int $id)
    {
        if (!empty($id)) {
            $this->id = $id;
            $this->load();
        }
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

        $query = "SELECT name, 
                         l.boss_id,
                         b.name as boss,
                         l.location_id,
                         ll.name as location,
                         l.slot as slot_id,
                         g.description as slot,
                         l.item_level,
                         l.rarity
                    FROM Loot l 
                         INNER JOIN Boss b
                         ON b.id = l.boss_id
                         INNER JOIN Locations ll 
                         ON ll.id = l.location_id
                         INNER JOIN GearPoints g 
                         ON g.slot = l.slot
                   WHERE l.id = :id";

        $stmt = $this->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            return false;
        }

        $lootData = $stmt->fetch();
        $this->bossId = (int) $lootData['boss_id'];
        $this->bossName = $lootData['boss'];
        $this->locationId = (int) $lootData['location_id'];
        $this->locationName = $lootData['location'];
        $this->slotId = (int) $lootData['slot_id'];
        $this->slotName = $lootData['slot'];
        $this->iLvl = (int) $lootData['item_level'];
        $this->rarity = (int) $lootData['rarity'];

        return true;
    }

    public function save(): bool
    {
        // TODO: Implement save() method.
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }


}
