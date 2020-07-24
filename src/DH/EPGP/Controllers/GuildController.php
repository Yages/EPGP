<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\GuildModel;
use DH\EPGP\Repositories\GuildRepository;
use DH\EPGP\Views\GuildManagementView;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class GuildController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class GuildController extends Controller
{
    /**
     * Lists all current Guilds.
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list()
    {
        $view = new GuildManagementView();
        $guildData = (new GuildRepository())->fetchAll();
        $view->setGuildData($guildData);
        $view->view();
    }

    /**
     * Creates a new Guild entry.
     */
    public function create() : void
    {
        parse_str($_POST['guild'], $guildData);

        if (empty($guildData['name'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid guild Name',
            ]);
        } else {
            $guild = new GuildModel();
            $guild->setName($guildData['name']);
            if (!empty($guildData['logo'])) {
                $guild->setLogo($guildData['logo']);
            }
            $result = $guild->save();

            if (!$result) {
                $this->toJson(
                    [
                        'success' => false,
                        'message' => 'Failed to save guild.'
                    ]
                );
            } else {
                $this->toJson(
                    [
                        'success' => true,
                        'message' => 'Successfully saved guild.',
                        'guild' => $guild,
                    ]
                );
            }
        }
    }
}
