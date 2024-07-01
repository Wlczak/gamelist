<?php

namespace Gamelist\Classes;

use DateTime;

class Token
{
    public function generateSessionToken()
    {
        return hash("snefru256", $this->generateRandomString(pow("2", random_int(16, 28))));
    }
    private function generateRandomString($length)
    {
        return bin2hex(random_bytes($length / 2));
    }
    public function setSessionToken($uid, $token)
    {
        $token = $this->generateSessionToken();
        $_SESSION['tokenSecret'] = $token;
        $_SESSION['authToken'] = $token;
        $this->checkTokens();
        $Database = new Database;
        $expires = $this->getTimestampAfter(20);
        if ($Database->checkIfExists("tokens", "token", $token)) {
            //exists
            $result = $Database->query("SELECT id, uid FROM tokens WHERE token = '$token'");
            $row = $result->fetch_assoc();
            $_SESSION['authMsg'] = $row['uid'];
            if ($uid == $row['uid']) {
                $_SESSION['authMsg'] = "same";
                $id = $row['id'];
                $Database->query("UPDATE `tokens` SET `expires` = '$expires' WHERE id = $id");
            } else {
                $_SESSION['authMsg'] = "not same";
                $this->setSessionToken($uid, $this->generateSessionToken());
            }
        } else {
            //doesnt exist
            $Database->query("INSERT INTO `tokens` (`id`, `uid`, `token`, `expires`) VALUES (NULL, $uid, '$token', '$expires');");
            $_SESSION['authMsg'] = "new token created";
        }
    }
    public function checkTokens()
    {
        $Database = new Database;
        $result = $Database->query("SELECT token, expires FROM tokens");
        while ($row = $result->fetch_assoc()) {
            $date = date_format(new DateTime($row['expires']), "U");
            if ($date < time()) {
                $token = $row['token'];
                $Database->query("DELETE FROM tokens WHERE token = '$token';");
            }
        }
    }
    function getTimestampAfter($min)
    {
        $now = time();
        $time = $now + ($min * 60);
        return date('Y-m-d H:i:s', $time);
    }
}
