<?php declare(strict_types=1);


namespace DH\EPGP\Controllers;

use DH\EPGP\Models\UserModel;
use DH\EPGP\Views\LoginView;
use PDO;
use PDOException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class AuthController
 * @package DH\EPGP\Controllers
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class AuthController extends Controller
{
    /**
     * Checks to make sure that the user is logged in currently, making use of
     * session data.
     * @return bool A flag indicating if the User is logged in.
     */
    public function checkSession() : bool
    {
        $result = false;

        if (isset($_SESSION['username']) && isset($_SESSION['loginConfimationString'])) {
            $loginConfimationString = hash(
                'sha512',
                $_SESSION['username'].$_SERVER['HTTP_USER_AGENT'].date("Ymd").$_SERVER['REMOTE_ADDR']
            );

            if ($loginConfimationString === $_SESSION['loginConfimationString']) {
                $result = true;
            }
        }

        session_write_close();

        return $result;
    }

    /**
     * Sets the appropriate session variables to indicate that the user is
     * successfully authenticated and logged in.
     * @param string $username The username used to log in to the system.
     */
    public function login(string $username): void
    {
        // Start session
        session_start();
        session_regenerate_id(true);

        $_SESSION['username'] = $username;
        $_SESSION['loginConfimationString'] = hash(
            'sha512',
            $username.$_SERVER['HTTP_USER_AGENT'].date("Ymd").$_SERVER['REMOTE_ADDR']
        );

        session_write_close();
    }

    /**
     * Gets the user model for the currently logged in user.
     * @return UserModel|null
     */
    public static function getLoggedInUser() : ?UserModel
    {
        $user = null;

        if (!empty($_SESSION['username'])) {
            $user = new UserModel($_SESSION['username']);
        }

        return $user;
    }

    /**
     * Checks that a user exists in the database and that their password as
     * provided is valid using PHP's password_* functions.
     * @param string $username The username provided.
     * @param string $password The password provided.
     * @return bool
     */
    public function check(string $username, string $password): bool
    {
        $return = false;
        try {
            $query = "SELECT password
                        FROM Administrators 
                       WHERE username = :username";
            $stmt = $this->pdo()->prepare($query);
            $stmt->execute([':username' => $username]);
            $results = $stmt->fetch(PDO::FETCH_NUM);

            if ($results && count($results) === 1) {
                $return = password_verify($password, $results[0]);
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }

        return $return;
    }


    /**
     * Called when the user explicitly logs out of the application. This
     * destroys their session, as the assumption is that the user has logged out
     * explicitly for a reason. They will become logged out immediately, and
     * will need to re-authenticate to connect.
     */
    public function logout()
    {
        session_start();

        // Unset all of the session variables.
        $_SESSION = [];

        // Clear session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * Shows the Login Page
     * @param bool $error
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(bool $error = false)
    {
        $view = new LoginView();
        if ($error) {
            $view->setError();
        }
        $view->view();
    }
}
