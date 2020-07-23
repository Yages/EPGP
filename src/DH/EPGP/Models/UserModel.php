<?php


namespace DH\EPGP\Models;

use DateTime;
use DateTimeImmutable;
use DH\EPGP\Traits\DBAwareTrait;
use Exception;

/**
 * Class UserModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class UserModel extends AbstractModel
{
    use DBAwareTrait;

    /** @var int */
    private int $id;

    /** @var string */
    private string $username;

    /** @var string */
    private string $password;

    /** @var int */
    private int $role;

    /** @var DateTimeImmutable */
    private DateTimeImmutable $created;

    /** @var DateTime */
    private DateTime $updated;

    /** @var int Indicates the User is an Administrator */
    public const ADMINISTRATOR = 3;

    /** @var int Indicates the User is a Master Looter */
    public const MASTER_LOOTER = 2;

    /** @var int Indicates the User is an Officer */
    public const OFFICER = 1;

    /**
     * UserModel constructor.
     * @param mixed $id
     */
    public function __construct($id = false)
    {
        if (!empty($id)) {
            if (is_numeric($id)) {
                $this->id = $id;
            } else $this->username = $id;
            $this->load();
        }
    }

    /**
     * Loads the user data from the database, requires an id to be set first.
     * @return bool
     */
    public function load() : bool
    {
        // Can't load if we don't know which one.
        if (empty($this->id) && empty($this->username)) {
            return false;
        }

        $query = "SELECT username,
                         role,
                         date_created,
                         date_updated
                    FROM Administrators 
        ";
        if (!empty($this->id)) {
            $query .= "WHERE id = :id";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([':id' => $this->id]);
        } else {
            $query .= "WHERE username = :username";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([':username' => $this->username]);
        }

        if (!$result) {
            return false;
        }

        $userData = $stmt->fetch();

        $this->username = $userData['username'];
        $this->role = $userData['role'];
        try {
            $this->created = new DateTimeImmutable($userData['date_created']);
            $this->updated = new DateTime($userData['date_updated']);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return true;
    }

    public function save() : bool
    {
        if (empty($this->username) || empty($this->role)) {
            return false;
        }

        if (empty($this->id)) {
            $query = "INSERT INTO Administrators (username, password, role, date_created, date_updated)
                           VALUES (:username, :password, :role, NOW(), NOW())";
            $stmt = $this->pdo()->prepare($query);
            $this->password = $this->generatePassword();
            $password = password_hash($this->password, PASSWORD_DEFAULT);
            $result = $stmt->execute([
                ':username' => $this->username,
                ':password' => $password,
                ':role' => $this->role,
            ]);
            $this->id = $this->pdo()->lastInsertId();
        } else {
            $query = "UPDATE Administrators 
                         SET username = :username, 
                             role = :role, 
                             date_updated = NOW()";
            $stmt = $this->pdo()->prepare($query);
            $result = $stmt->execute([
                ':username' => $this->username,
                ':role' => $this->role,
            ]);
        }

        return $result;
    }

    /**
     * Determines a user's role description.
     * @return string|null
     */
    public function getRoleDescription() : ?string
    {
        switch ($this->role) {
            case self::ADMINISTRATOR:
                return "Administrator";
                break;
            case self::MASTER_LOOTER:
                return "Master Looter";
                break;
            case self::OFFICER:
                return "Officer";
                break;
            default:
                return null;
        }
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
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * Handles JSON return formatting.
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'username' => $this->username,
            'role' => $this->role,
            'roleDescription' => $this->getRoleDescription(),
            'created' => $this->getCreated(),
            'updated' => $this->getUpdated(),
        ];
    }

    /**
     * Checks if the user is an administrator.
     * @return bool
     */
    public function isAdmin() : bool
    {
        return $this->role === self::ADMINISTRATOR;
    }

    /**
     * Checks if the user is a looter (i.e. ML or above.
     * @return bool
     */
    public function isLooter() : bool
    {
        return ($this->role >= self::MASTER_LOOTER);
    }

    /**
     * Checks if the user is an officer, i.e. has any role set at all.
     * @return bool
     */
    public function isOfficer() : bool
    {
        return ($this->role > 0);
    }

    /**
     * Generates a random password.
     * @return string A 10 character random password.
     */
    private function generatePassword(): string
    {
        // NOTE: Secure, random, only works on POSIX systems.
        $randomData = file_get_contents('/dev/urandom', false, null, 0, 10).uniqid(''.mt_rand(), true);
        return substr(str_replace(['/', '=', '+'], '', base64_encode($randomData)), 0, 10);
    }
}
