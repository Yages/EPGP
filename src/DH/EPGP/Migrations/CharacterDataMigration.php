<?php declare(strict_types=1);


namespace DH\EPGP\Migrations;

/**
 * Class CharacterDataMigration
 * @package DH\EPGP\Migrations
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class CharacterDataMigration extends Migration
{
    public function migrate()
    {
        $this->begin();

        // Populate Guilds
        $query = "INSERT INTO Guild (name, logo) 
                       VALUES ('Divine Heresy', null),
                              ('Syzygy', null),
                              ('Raiders of the Lost Orc', null)";
        $this->pdo()->exec($query);

        // Populate Char Data
        $query = "INSERT INTO Characters (name, class, role, guild_id, date_created, date_updated, active)
                       VALUES ('Bearskin', 'Druid', 'Tank', 1, NOW(), NOW(), 1),
                              ('Beertanky', 'Druid', 'Tank', 2, NOW(), NOW(), 1),
                              ('Belagorn', 'Warrior', 'Tank', 1, NOW(), NOW(), 1),
                              ('Cais', 'Rogue', 'Melee', 1, NOW(), NOW(), 1),
                              ('Carnifexus', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Colomius', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Cornholio', 'Druid', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Danyen', 'Priest', 'Healer', 2, NOW(), NOW(), 1),
                              ('Drdisco', 'Shaman', 'Melee', 2, NOW(), NOW(), 1),
                              ('Droody', 'Druid', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Fartalota', 'Druid', 'Healer', 2, NOW(), NOW(), 1),
                              ('Grashak', 'Hunter', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Henchie', 'Druid', 'Healer', 2, NOW(), NOW(), 1),
                              ('Hermes', 'Druid', 'Healer', 2, NOW(), NOW(), 1),
                              ('Icecoldbro', 'Mage', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Jorna', 'Priest', 'Healer', 1, NOW(), NOW(), 1),
                              ('Jug', 'Warrior', 'Melee', 1, NOW(), NOW(), 1),
                              ('Kaytoo', 'Priest', 'Healer', 2, NOW(), NOW(), 1),
                              ('Krulz', 'Warlock', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Lgi', 'Priest', 'Healer', 2, NOW(), NOW(), 1),
                              ('Maraina', 'Druid', 'Healer', 1, NOW(), NOW(), 1),
                              ('Mooherder', 'Druid', 'Healer', 2, NOW(), NOW(), 1),
                              ('Moomighty', 'Druid', 'Healer', 2, NOW(), NOW(), 1),
                              ('Moovurass', 'Druid', 'Healer', 1, NOW(), NOW(), 1),
                              ('NinetyNine', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Nitebabe', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Orcopalypse', 'Warrior', 'Tank', 1, NOW(), NOW(), 1),
                              ('Quake', 'Warrior', 'Tank', 1, NOW(), NOW(), 1),
                              ('Ramster', 'Hunter', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Roth', 'Rogue', 'Melee', 2, NOW(), NOW(), 1),
                              ('Rummy', 'Warrior', 'Tank', 2, NOW(), NOW(), 1),
                              ('Shadevar', 'Rogue', 'Melee', 1, NOW(), NOW(), 1),
                              ('Shamer', 'Shaman', 'Healer', 1, NOW(), NOW(), 1),
                              ('Sinazaela', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Skiip', 'Hunter', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Smegoff', 'Hunter', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Tabletopd', 'Priest', 'Healer', 1, NOW(), NOW(), 1),
                              ('Taima', 'Priest', 'Healer', 1, NOW(), NOW(), 1),
                              ('Tanksmun', 'Warrior', 'Tank', 2, NOW(), NOW(), 1),
                              ('Tehguns', 'Shaman', 'Melee', 1, NOW(), NOW(), 1),
                              ('Teracious', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Tronn', 'Warlock', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Unholymonk', 'Warrior', 'Melee', 2, NOW(), NOW(), 1),
                              ('Unholysaph', 'Priest', 'Healer', 2, NOW(), NOW(), 1),
                              ('Volkuhn', 'Warrior', 'Melee', 1, NOW(), NOW(), 1),
                              ('Winnieblue', 'Priest', 'Healer', 1, NOW(), NOW(), 1),
                              ('Wizzjab', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Wotyournam', 'Mage', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Xelzenov', 'Mage', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Yag', 'Warlock', 'Ranged', 1, NOW(), NOW(), 1),
                              ('Zingerwork', 'Warlock', 'Ranged', 2, NOW(), NOW(), 1),
                              ('Zurajita', 'Warrior', 'Melee', 1, NOW(), NOW(), 1),
                              ('Tankyboi', 'Warrior', 'Melee', 1, NOW(), NOW(), 1)";
        $this->pdo()->exec($query);

        $this->finalise();
    }
}
