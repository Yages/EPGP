<?php declare(strict_types=1);


namespace DH\EPGP\Migrations;

/**
 * Class OtherDataMigration
 * @package DH\EPGP\Migrations
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class OtherDataMigration extends Migration
{
    /**
     * Adds the data to the DB
     */
    public function migrate()
    {
        $this->begin();

        // Locations
        $query = "INSERT INTO Locations (name, boss_count)
                       VALUES ('Molten Core', 10),
                              ('Blackwing Lair', 8),
                              ('Onyxia\'s Lair', 1)";
        $result = $this->pdo()->exec($query);

        if (!$result) {
            $this->rollback();
            die('Failed to insert location records');
        }

        // Bosses
        $query = "INSERT INTO Boss (location_id, 
                                    name,
                                    effort_points,
                                    kill_order)
                       VALUES (1, 'Lucifron', 10, 1),
                              (1, 'Magmadar', 10, 2),
                              (1, 'Gehennas', 10, 3),
                              (1, 'Garr', 10, 4),
                              (1, 'Baron Geddon', 10, 5),
                              (1, 'Shazzrah', 10, 6),
                              (1, 'Sulfuron Harbinger', 10, 7),
                              (1, 'Golemagg the Incinerator', 10, 8),
                              (1, 'Majordomo Executus', 12, 9),
                              (1, 'Ragnaros', 14, 10),
                              (2, 'Razorgore the Untamed', 12, 1),
                              (2, 'Vaelastrasz the Corrupt', 12, 2),
                              (2, 'Broodlord Lashlayer', 12, 3),
                              (2, 'Firemaw', 12, 4),
                              (2, 'Ebonroc', 12, 5),
                              (2, 'Flamegor', 12, 6),
                              (2, 'Chromaggus', 14, 7),
                              (2, 'Nefarian', 16, 8),
                              (3, 'Onyxia', 12, 1)";

        $result = $this->pdo()->exec($query);

        if (!$result) {
            $this->rollback();
            die('Failed to insert location records');
        }

        // Slot Data
        $query = "INSERT INTO GearPoints (slot, description, weighting) 
                       VALUES (0, 'Quest', 125),
                              (1, 'Head', 125),
                              (2, 'Neck', 75),
                              (3, 'Shoulder', 100),
                              (4, 'Wand', 50),
                              (5, 'Chest', 125), 
                              (6, 'Waist', 50),
                              (7, 'Legs', 125),
                              (8, 'Feet', 75),
                              (9, 'Wrist', 50),
                              (10, 'Hands', 75),
                              (11, 'Ring', 75),
                              (12, 'Trinket', 150), 
                              (13, 'One-Hand Weapon', 150),
                              (14, 'Shield', 75),
                              (15, 'Ranged', 75),
                              (16, 'Cloak', 50),  
                              (17, '2H Weapon', 200),
                              (18, 'Bag', 0),
                              (19, 'Relic', 75),
                              (21, 'Main Hand Weapon', 175),
                              (22, 'Off Hand Weapon', 175),
                              (23, 'Holdable Off Hand', 75),
                              (25, 'Throwing', 75)";

        $result = $this->pdo()->exec($query);

        if (!$result) {
            $this->rollback();
            die('Failed to insert gear slot records');
        }

        $this->finalise();
    }
}
