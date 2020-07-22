<?php declare(strict_types=1);


namespace DH\EPGP\Migrations;

/**
 * Class InitialMigration
 * @package DH\EPGP\Migrations
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class InitialMigration extends Migration
{
    /**
     * Performs the migration.
     * @return bool
     */
    public function migrate()
    {
        $this->begin();

        // Create Admin Table
        $query = "CREATE TABLE Administrators (
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      username VARCHAR(50) NOT NULL,
                      password VARCHAR(255) NOT NULL,
                      role TINYINT NOT NULL,
                      date_created TIMESTAMP DEFAULT 0 NOT NULL, 
                      date_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (id) 
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create Admins Table');
        }

        // Create Guild Table
        $query = "CREATE TABLE Guild (
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      name VARCHAR(32) NOT NULL,
                      logo VARCHAR(50),
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create Guild Table');
        }

        // Create Character Table
        $query = "CREATE TABLE Characters (
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      name VARCHAR(50) NOT NULL,
                      class VARCHAR(10) NOT NULL,
                      role VARCHAR(10) NOT NULL,
                      guild_id INT(6) NOT NULL,
                      date_created TIMESTAMP DEFAULT 0 NOT NULL, 
                      date_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      active TINYINT DEFAULT 1,
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create Character Table');
        }

        // Create Locations Table
        $query = "CREATE TABLE Locations (
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      name VARCHAR(100) NOT NULL,
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create Locations Table');
        }

        // Create Boss Table
        $query = "CREATE TABLE Boss (
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      location_id INT(6) NOT NULL, 
                      name VARCHAR(100) NOT NULL,
                      effort_points INT NOT NULL,
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create Boss Table');
        }

        // Create Raid Table
        $query = "CREATE TABLE Raid(
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      location_id INT(6) NOT NULL,
                      date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create Raid Table');
        }

        // Create RandomLoot Table
        $query = "CREATE TABLE RandomLoot (
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      event_id INT(6) NOT NULL,
                      item VARCHAR(100) NOT NULL,
                      description VARCHAR(255),
                      guild_id INT(6) NOT NULL,
                      raid_id INT(6) NOT NULL,
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create RandomLoot Table');
        }

        // Create RaidCharacter Table
        $query = "CREATE TABLE RaidCharacter(
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      raid_id INT(6) NOT NULL,
                      character_id INT(6) NOT NULL,
                      join_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      leave_time TIMESTAMP DEFAULT 0,
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create RaidCharacter Table');
        }

        // Create PointsRegister Table
        $query = "CREATE TABLE PointsRegister(
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      event_id INT(6) NOT NULL,
                      character_id INT(6) NOT NULL,
                      type CHAR(1) NOT NULL,
                      value INT(6) NOT NULL,
                      description VARCHAR(255) NOT NULL,
                      date_entered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      user_entered INT(6),
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create PointsRegister Table');
        }

        // Create CharacterLoot Table
        $query = "CREATE TABLE CharacterLoot(
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      event_id INT(6) NOT NULL,
                      character_id INT(6) NOT NULL,
                      loot_id INT(6) NOT NULL,
                      gear_points INT(6) NOT NULL,
                      loot_description VARCHAR(255) NOT NULL,
                      raid_id INT(6) NOT NULL,
                      date_entered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      user_entered INT(6),
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create CharacterLoot Table');
        }

        // Create PointEvent Table
        $query = "CREATE TABLE PointEvents(
                      id INT(6) UNSIGNED AUTO_INCREMENT,
                      event_type CHAR(1) NOT NULL,
                      event_description VARCHAR(255) NOT NULL,
                      raid_id INT(6),
                      date_entered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      user_entered INT(6),
                      PRIMARY KEY (id)
                  )";

        $result = $this->pdo()->exec($query);

        if ($result === false) {
            $this->rollback();
            die('Failed to create PointEvent Table');
        }


        $this->finalise();
    }
}