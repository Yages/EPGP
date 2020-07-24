<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\CharacterModel;
use DH\EPGP\Repositories\CharacterRepository;
use DH\EPGP\Repositories\GuildRepository;
use DH\EPGP\Views\CharacterManagementView;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class CharacterController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class CharacterController extends Controller
{
    /**
     * Lists the current active characters
     * @param bool $inactive
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list(bool $inactive = false)
    {
        $view = new CharacterManagementView();
        if ($inactive) {
            $characterData = (new CharacterRepository())->fetchAllInactive();
        } else $characterData = (new CharacterRepository())->fetchAll();
        $guildData = (new GuildRepository())->fetchAll();
        $view->setCharacterData($characterData);
        $view->setGuildData($guildData);
        $view->setInactive($inactive);
        $view->view();
    }

    /**
     * Deactivate a character.
     */
    public function deactivate()
    {
        if (empty($_POST['character_id'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character ID',
            ]);
        } else {
            $character = new CharacterModel((int) $_POST['character_id']);

            $result = $character->deactivate();
            $this->toJson([
                'success' => $result,
            ]);
        }
    }

    /**
     * Activates a character.
     */
    public function activate()
    {
        if (empty($_POST['character_id'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character ID',
            ]);
        } else {
            $character = new CharacterModel((int) $_POST['character_id']);

            $result = $character->activate();
            $this->toJson([
                'success' => $result,
            ]);
        }
    }

    /**
     * Handles editing existing Character entry (via Ajax).
     */
    public function edit() : void
    {
        parse_str($_POST['character'], $characterData);

        if (empty($_POST['character_id'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character ID',
            ]);
        } elseif (empty($characterData['name'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Name',
            ]);
        } elseif (empty($characterData['class'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Class',
            ]);
        } elseif (empty($characterData['role'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Role',
            ]);
        } elseif (empty($characterData['guild'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Guild',
            ]);
        }
        else {
            $character = new CharacterModel((int) $_POST['character_id']);
            $character->setName($characterData['name']);
            $character->setClass($characterData['class']);
            $character->setRole($characterData['role']);
            $character->setGuildId((int) $characterData['guild']);
            $result = $character->save();

            if (!$result) {
                $this->toJson(
                    [
                        'success' => false,
                        'message' => 'Failed to save changes to character.'
                    ]
                );
            } else {
                $this->toJson(
                    [
                        'success' => true,
                        'message' => 'Successfully saved changes to character.',
                        'character' => $character,
                    ]
                );
            }
        }
    }

    /**
     * Handles creating a new Character entry (via Ajax).
     */
    public function create() : void
    {
        parse_str($_POST['character'], $characterData);

        if (empty($characterData['name'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Name',
            ]);
        } elseif (empty($characterData['class'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Class',
            ]);
        } elseif (empty($characterData['role'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Role',
            ]);
        } elseif (empty($characterData['guild'])) {
            $this->toJson([
                'success' => false,
                'message' => 'You must include a valid character Guild',
            ]);
        }
        else {
            $character = new CharacterModel();
            $character->setName($characterData['name']);
            $character->setClass($characterData['class']);
            $character->setRole($characterData['role']);
            $character->setGuildId((int) $characterData['guild']);
            $character->setActive(true);
            $result = $character->save();

            if (!$result) {
                $this->toJson(
                    [
                        'success' => false,
                        'message' => 'Failed to save character.'
                    ]
                );
            } else {
                $this->toJson(
                    [
                        'success' => true,
                        'message' => 'Successfully saved character.',
                        'character' => $character,
                    ]
                );
            }
        }
    }
}
