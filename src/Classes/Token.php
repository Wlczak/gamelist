<?php

namespace Gamelist\Classes;

use DateTime;

class Token
{
    public function generateSessionToken()
    {
        return hash("snefru256", $this->generateRandomString(pow("2", random_int(16, 17/*28*/))));
    }
    private function generateRandomString($length)
    {
        return bin2hex(random_bytes($length / 2));
    }
    public function setSessionToken($uid, $token)
    {

        $_SESSION['authMsg'] = "";
        /*  $Database = new Database;
        $expire = $this->getTimestampAfter(20);
        if (!$Database->checkIfExists('tokens', "token", $token)) {

            $_SESSION['authMsg'] = $_SESSION['authMsg'] . "nono";
            //token doesn't exist
            $Database->query("INSERT INTO `tokens` (`id`, `uid`, `token`, `expires`) VALUES (NULL, '$uid', '$token', '$expire');");
        } else {

            //token exists
            /*$result = $Database->query("SELECT id,uid FROM tokens WHERE token = '$token'")->fetch_assoc();
            $tokenUid = $result['uid'];
            $tokenId = $result['id'];
            $_SESSION['authMsg'] = $_SESSION['authMsg'] . "y";
            if ($uid == $tokenUid) {
                //token belongs to the current user -> update time
                $expire = $this->getTimestampAfter(20);
                $Database->query("UPDATE `tokens` SET `expires` = '$expire' WHERE `id` = $tokenId");
                $_SESSION['authMsg'] = $_SESSION['authMsg'] . "y1";
            } else {
                //doesn't belong to current user -> create new token
                $this->setSessionToken($uid, $this->generateSessionToken());
                $_SESSION['authMsg'] = $_SESSION['authMsg'] . "y2";
            }
        }*/
    }
    public function checkTokens()
    {
        $Database = new Database;
        $result = $Database->query("SELECT token, expires AS fukcyxo FROM tokens");
        while ($row = $result->fetch_assoc()) {
            $date = date_format(new DateTime($row['fukcyxo']), "U");
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
