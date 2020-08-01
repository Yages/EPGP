<?php declare(strict_types=1);


namespace DH\EPGP\Utilities;


use DH\EPGP\Models\BossModel;
use DH\EPGP\Models\LootModel;
use DH\EPGP\Traits\DBAwareTrait;
use Goutte\Client;

/**
 * Class WowheadDataScraper
 * @package DH\EPGP\Utilities
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class WowheadDataScraper
{
    use DBAwareTrait;

    /** @var Client */
    private Client $client;

    /** @var string */
    private string $baseUrl;

    const ONYXIAS_LAIR = 2159;

    const MOLTEN_CORE = 2717;

    const BLACKWING_LAIR = 2677;

    /**
     * WowheadDataScraper constructor.
     */
    public function __construct()
    {
        $this->baseUrl = 'https://classic.wowhead.com/item=%d?xml=true';
        $this->client = new Client();
    }

    /**
     * Given an Item ID, fetch its data from the Wowhead Classic API and return
     * the JSON data from the XML.
     * @param int $itemId
     * @return LootModel|null
     */
    public function getItemData(int $itemId) : ?LootModel
    {
        $loot = null;
        $url = sprintf($this->baseUrl, $itemId);
        $crawler = $this->client->request('GET', $url);
        $data = json_decode('{' . $crawler->filter('item json')->text() . '}');

        if ($data) {
            $loot = new LootModel($itemId);
            $newLoot = $loot->checkId($itemId);
            if ($newLoot) {
                $loot->setId($data->id)
                    ->setName($data->name)
                    ->setILvl($data->level)
                    ->setRarity($data->quality)
                    ->setSlotId($this->determineSlotId($data))
                    ->setLocationId($this->getLocalLocationIdFromWowheadId($data->sourcemore[0]->z));

                $bosses = [];
                foreach ($data->sourcemore as $dropData) {
                    if (isset($dropData->n)) {
                        $bosses[] = new BossModel($this->getLocalBossIdFromWowheadName($dropData->n));
                    }
                }

                $loot->setBosses($bosses);

                if ($loot->save()) {
                    return $loot;
                } else return null;
            } else return null;
        }

        return $loot;
    }

    /**
     * Gets the local location ID from the database for the Wowhead Location ID.
     * @param int $id
     * @return int
     */
    private function getLocalLocationIdFromWowheadId(int $id) : int
    {
        $query = "SELECT id FROM Locations WHERE name = :name";
        $stmt = $this->pdo()->prepare($query);
        $name = '';
        switch ($id) {
            case self::MOLTEN_CORE:
                $name = 'Molten Core';
                break;
            case self::BLACKWING_LAIR:
                $name = "Blackwing Lair";
                break;
            case self::ONYXIAS_LAIR:
                $name = "Onyxia's Lair";
                break;
        }

        $stmt->execute([':name' => $name]);
        return (int) $stmt->fetch()['id'];
    }

    /**
     * Gets the local Boss Id from the database for the Wowhead Boss Name.
     * @param string $name
     * @return int
     */
    private function getLocalBossIdFromWowheadName(string $name) : int
    {
        $query = "SELECT id FROM Boss WHERE name = :name";
        $stmt = $this->pdo()->prepare($query);
        $stmt->execute([':name' => $name]);
        return (int) $stmt->fetch()['id'];
    }

    /**
     * Used to determine Slot ID from Wowhead data, we need seperate entries for
     * Wand vs All other ranged weapons.
     */
    private function determineSlotId($data): int
    {
        if ($data->slot === 15) {
            if ($data->subclass === 19) {
                return 4; // Wand
            }
        }

        return $data->slot;
    }
}
