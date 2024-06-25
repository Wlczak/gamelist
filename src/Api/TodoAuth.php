<?php

namespace Gamelist\Api;

use Gamelist\Classes\Database;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TodoAuth
{
    public $Database;
    function main(RequestInterface $request, ResponseInterface $html): ResponseInterface
    {
        //var_dump($_POST);
        $this->Database = new Database;

        ob_start();
        //echo "<title>Loading...</title>";
        return $this->handleAuthRequest($html);
    }
    function handleAuthRequest($html)
    {
        switch ($_POST['type']) {
            case 'login':
                $html = $this->login($html);
                break;
            case 'register':
                $html = $this->register($html);
                break;

            default:
                $_SESSION['authError'] = true;
                $_SESSION['authMsg'] = "Auth request type not recognised.";
                $html = $html->withHeader('Location', '/login')->withStatus(302);
                break;
        }
        return $html;
    }
    function login($html)
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password_hash = $this->Database->query("SELECT password FROM users WHERE username = '$username'")->fetch_column();;


        if (!$this->Database->checkIfExists("users", "username", $username)) {
            $_SESSION['authError'] = true;
            $_SESSION['authMsg'] = "This user doesn't exist.";
            return $html->withHeader('Location', 'login')->withStatus(302);
        }
        if (!password_verify($password, $password_hash)) {
            $_SESSION['authError'] = true;
            $_SESSION['authMsg'] = "Wrong password.";
            return $html->withHeader('Location', 'login')->withStatus(302);
        } else {
            $_SESSION['authError'] = false;
            $_SESSION['authMsg'] = "Login succesfull.";
            return $html->withHeader('Location', 'login')->withStatus(302);
        }

        return $html;
    }
    function register($html)
    {
        #variable declaration
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        #db checks
        if ($_POST['password'] !== $_POST['re-password']) {
            $_SESSION['authError'] = true;
            $_SESSION['authMsg'] = "Passwords don't match.";
            return $html->withHeader('Location', 'register')->withStatus(302);
        }
        if ($this->Database->checkIfExists("users", "username", $username)) {
            $_SESSION['authError'] = true;
            $_SESSION['authMsg'] = "This username is already in use.";
            return $html->withHeader('Location', 'register')->withStatus(302);
        }

        $this->Database->createUser($username, $password);

        $_SESSION['authError'] = false;
        $_SESSION['authMsg'] = "Registered succesfully.";
        return $html->withHeader('Location', 'login')->withStatus(302);
    }
    function test(RequestInterface $request, ResponseInterface $html): ResponseInterface
    {
        ob_start();
        echo "<title>Testing</title>";
        $html->getBody()->write(ob_get_clean());
        return $html;
    }
}
