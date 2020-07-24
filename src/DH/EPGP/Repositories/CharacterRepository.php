<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;


use DH\EPGP\Models\CharacterModel;
use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class CharacterRepository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class CharacterRepository
{
    use DBAwareTrait;

    /**
     * Returns all active Users by default, Inactive users if passed false.
     * @param bool $active
     * @return array
     */
    public function fetchAll(bool $active = true) : array
    {
        $characters = [];

        $active = ($active) ? 1 : 0;
        $query = "SELECT id 
                    FROM Characters 
                   WHERE active = :active";
        $stmt = $this->pdo()->prepare($query);
        $stmt->execute([':active' => $active]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $characters[] = new CharacterModel((int) $row['id']);
        }

        return $characters;
    }

    /**
     * Returns all inactive characters.
     * @return array
     */
    public function fetchAllInactive() : array
    {
        return $this->fetchAll(false);
    }

    /**
     * Returns all active characters in a guild.
     * @param int $guildId
     * @return array
     */
    public function fetchAllByGuild(int $guildId) : array
    {
        $characters = [];

        $query = "SELECT id 
                    FROM Characters 
                   WHERE active = 1
                     AND guild_id = :guild_id";
        $stmt = $this->pdo()->prepare($query);
        $stmt->execute([':guild_id' => $guildId]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $characters[] = new CharacterModel((int) $row['id']);
        }

        return $characters;
    }

    /**
     * Returns all active characters of the same class.
     * @param string $class
     * @return array
     */
    public function fetchAllByClass(string $class) : array
    {
        $characters = [];

        $query = "SELECT id 
                    FROM Characters 
                   WHERE active = 1
                     AND class = :class";
        $stmt = $this->pdo()->prepare($query);
        $stmt->execute([':class' => $class]);
        $ids = $stmt->fetchAll();

        foreach ($ids as $row) {
            $characters[] = new CharacterModel((int) $row['id']);
        }

        return $characters;
    }
}
