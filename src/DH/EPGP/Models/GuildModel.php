<?php declare(strict_types=1);


namespace DH\EPGP\Models;


use DH\EPGP\Traits\DBAwareTrait;

/**
 * Class GuildModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class GuildModel extends AbstractModel
{
    use DBAwareTrait;

    /** @var int */
    private int $id;

    /** @var string */
    private string $name;

    /** @var string */
    private ?string $logo = null;

    /**
     * GuildModel constructor.
     * @param int|null $id
     */
    public function __construct(?int $id = null)
    {
        if (!empty($id)) {
            $this->id = $id;
            $this->load();
        }
    }

    public function load(): bool
    {
        if (empty($this->id)) {
            return false;
        }

        $query = "SELECT name,
                         logo
                    FROM Guild 
                   WHERE id = :id";

        $stmt = $this->pdo()->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if (!$result) {
            return false;
        }

        $guildData = $stmt->fetch();
        $this->name = $guildData['name'];
        $this->logo = $guildData['logo'];

        return true;
    }


    public function save(): bool
    {
        if (empty($this->name)) {
            return false;
        }

        if (empty($this->id)) {
            $query = "INSERT INTO Guild (name, logo) 
                           VALUES (:name, :logo)";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':logo' => $this->logo,
            ]);
            $this->id = $this->pdo()->lastInsertId();
        } else {
            $query = "UPDATE Guild 
                         SET name = :name, 
                             logo = :logo
                       WHERE id = :id";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':logo' => $this->logo,
                ':id' => $this->id,
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
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo,
        ];
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLogo() : string
    {
        return $this->logo;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
}
