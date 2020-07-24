<?php declare(strict_types=1);


namespace DH\EPGP\Models;

use DateTime;
use DateTimeImmutable;
use DH\EPGP\Traits\DBAwareTrait;
use Exception;

/**
 * Class CharacterModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class CharacterModel extends AbstractModel
{
    use DBAwareTrait;

    private int $id;
    private string $name;
    private string $class;
    private string $role;
    private int $guildId;
    private string $guild;
    private DateTimeImmutable $created;
    private DateTime $updated;
    private bool $active = true;

    /**
     * CharacterModel constructor.
     * @param mixed|null $id
     */
    public function __construct($id = null)
    {
        if (!empty($id)) {
            if (is_numeric($id)) {
                $this->id = (int) $id;
            } else $this->name = $id;
            $this->load();
        }
    }

    /**
     * @return bool
     */
    public function load(): bool
    {
        // Can't load if we don't know which one.
        if (empty($this->id) && empty($this->username)) {
            return false;
        }

        $query = "SELECT c.id,
                         c.name,
                         c.class,
                         c.role,
                         g.name as guild,
                         c.guild_id,
                         c.active,
                         c.date_created,
                         c.date_updated
                    FROM Characters c 
                         INNER JOIN Guild g
                         ON c.guild_id = g.id  
        ";
        if (!empty($this->id)) {
            $query .= "WHERE c.id = :id";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([':id' => $this->id]);
        } else {
            $query .= "WHERE c.name = :name";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([':name' => $this->name]);
        }

        if (!$result) {
            return false;
        }

        $characterData = $stmt->fetch();
        $this->id = (int) $characterData['id'];
        $this->name = $characterData['name'];
        $this->class = $characterData['class'];
        $this->role = $characterData['role'];
        $this->guildId = (int) $characterData['guild_id'];
        $this->guild = $characterData['guild'];
        $this->active = (bool) $characterData['active'];

        try {
            $this->created = new DateTimeImmutable($characterData['date_created']);
            $this->updated = new DateTime($characterData['date_updated']);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return true;
    }

    public function save(): bool
    {
        if (empty($this->name)
            || empty($this->role)
            || empty($this->class)
            || empty($this->guildId)) {
            return false;
        }

        $active = ($this->active) ? 1 : 0;
        if (empty($this->id)) {
            $query = "INSERT INTO Characters (name, class, role, guild_id, active, date_created, date_updated)
                           VALUES (:name, :class, :role, :guildId, :active, NOW(), NOW())";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':class' => $this->class,
                ':role' => $this->role,
                ':guildId' => $this->guildId,
                ':active' => $active,
            ]);
            $this->id = (int) $this->pdo()->lastInsertId();

            // get guild name
            $this->guild = $this->pdo()->query("SELECT name FROM Guild WHERE id = {$this->getGuildId()}")->fetch()[0];
        } else {
            $query = "UPDATE Characters
                         SET name = :name,
                             class = :class, 
                             role = :role, 
                             guild_id = :guildId,
                             active = :active,
                             date_updated = NOW()
                       WHERE id = :id";
            $stmt = $this->pdo()->prepare($query);

            $result = $stmt->execute([
                ':name' => $this->name,
                ':class' => $this->class,
                ':role' => $this->role,
                ':guildId' => $this->guildId,
                ':active' => $active,
                ':id' => $this->id,
            ]);
        }

        return $result;
    }

    /**
     * Deactivates a character, only if they're not already inactive.
     * @return bool
     */
    public function deactivate() : bool
    {
        if ($this->active) {
            $this->active = false;
            return $this->save();
        } else return false;
    }

    /**
     * Activates a characer, only if they're already inactive.
     * @return bool
     */
    public function activate()
    {
        if (!$this->active) {
            $this->active = true;
            return $this->save();
        } else return false;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->name,
            'class' => $this->class,
            'role' => $this->role,
            'guild' => $this->guild,
            'guildId' => $this->guildId,
            'active' => $this->getActive(),
            'created' => $this->getCreated(),
            'updated' => $this->getUpdated(),
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getClass() : string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getRole() : string
    {
        return $this->role;
    }

    /**
     * @return int
     */
    public function getGuildId() : int
    {
        return $this->guildId;
    }

    /**
     * @return string
     */
    public function getGuild() : string
    {
        return $this->guild;
    }

    /**
     * @return string
     */
    public function getCreated() : string
    {
        return (empty($this->created)) ? '' : $this->created->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getUpdated() : string
    {
        return (empty($this->updated)) ? '' : $this->updated->format('Y-m-d H:i:s');
    }

    /**
     * @return bool
     */
    public function getActive() : bool
    {
        return $this->active;
    }

    /**
     * @param string $name
     * @return CharacterModel
     */
    public function setName(string $name) : CharacterModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $class
     * @return CharacterModel
     */
    public function setClass(string $class) : CharacterModel
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $role
     * @return CharacterModel
     */
    public function setRole(string $role) : CharacterModel
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param int $id
     * @return CharacterModel
     */
    public function setGuildId(int $id) : CharacterModel
    {
        $this->guildId = $id;
        return $this;
    }

    /**
     * @param bool $active
     * @return CharacterModel
     */
    public function setActive(bool $active) : CharacterModel
    {
        $this->active = $active;
        return $this;
    }
}
