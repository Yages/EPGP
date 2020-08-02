<?php declare(strict_types=1);


namespace DH\EPGP\Models;

use DateTimeImmutable;
use Exception;

/**
 * Class RaidBossModel
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class RaidBossModel extends AbstractModel
{
    /** @var int */
    private int $raidId;

    /** @var int */
    private int $bossId;

    /** @var BossModel */
    private BossModel $boss;

    /** @var int */
    private int $status;

    /** @var int  */
    const ALIVE = 1;

    /** @var int  */
    const DEAD = 0;

    /** @var DateTimeImmutable|null */
    private ?DateTimeImmutable $date;

    /**
     * RaidBossModel constructor.
     * @param int $raidId
     * @param int $bossId
     * @throws Exception
     */
    public function __construct(int $raidId, int $bossId)
    {
        parent::__construct();

        $this->raidId = $raidId;
        $this->bossId = $bossId;

        $this->load();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function load(): bool
    {
        $this->boss = new BossModel($this->bossId);

        // check if there's an entry for a kill
        $query = "SELECT date 
                    FROM RaidBoss
                   WHERE raid_id = :raidId
                     AND boss_id = :bossId";
        $stmt = $this->db->pdo()->prepare($query);
        $result = $stmt->execute([
            ':raidId' => $this->raidId,
            ':bossId' => $this->bossId,
        ]);

        if (!$result) {
            error_log(print_r($stmt->errorInfo()));
            return false;
        }

        $date = $stmt->fetch();
        if (empty($date['date'])) {
            $this->date = null;
            $this->status = self::ALIVE;
        } else {
            $this->date = new DateTimeImmutable($date['date']);
            $this->status = self::DEAD;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $query = "SELECT COUNT(*) 
                    FROM RaidBoss 
                   WHERE raid_id = :raidId
                     AND boss_id = :bossId";
        $stmt = $this->db->pdo()->prepare($query);
        $stmt->execute([
            ':raidId' => $this->raidId,
            ':bossId' => $this->bossId,
        ]);
        $exists = ((int) $stmt->fetch()[0] === 1);

        if ($exists) {
            $query = "UPDATE RaidBoss 
                         SET date = :date
                       WHERE raid_id = :raidId
                         AND boss_id = :bossId";
            $stmt = $this->db->pdo()->prepare($query);
            $result = $stmt->execute(
                [
                    ':raidId' => $this->raidId,
                    ':bossId' => $this->bossId,
                    ':date' => (empty($this->date)) ? null : $this->date->format('Y-m-d H:i:s'),
                ]
            );
        } else {
            $query = "INSERT INTO RaidBoss (raid_id, boss_id, date)
                           VALUES (:raidId, :bossId, :date)";
            $stmt = $this->db->pdo()->prepare($query);
            $result = $stmt->execute(
                [
                    ':raidId' => $this->raidId,
                    ':bossId' => $this->bossId,
                    ':date' => null,
                ]
            );
        }

        return $result;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'raidId' => $this->raidId,
            'boss' => $this->boss,
            'status' => $this->status,
            'date' => (empty($this->date)) ? null : $this->date->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return int
     */
    public function getRaidId(): int
    {
        return $this->raidId;
    }

    /**
     * @return int
     */
    public function getBossId(): int
    {
        return $this->bossId;
    }

    /**
     * @return BossModel
     */
    public function getBoss(): BossModel
    {
        return $this->boss;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param int $status
     * @return RaidBossModel
     */
    public function setStatus(int $status): RaidBossModel
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param DateTimeImmutable|null $date
     * @return RaidBossModel
     */
    public function setDate(?DateTimeImmutable $date): RaidBossModel
    {
        $this->date = $date;

        return $this;
    }
}
