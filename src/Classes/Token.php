<?php

namespace Gamelist\Classes;

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
        $uid;
        $Database = new Database;
        $expire = $this->getTimestampAfter(20);
        if (!$Database->checkIfExists('tokens', "token", $token)) {
            //token doesn't exist
            $Database->query("INSERT INTO `tokens` (`id`, `uid`, `token`, `expires`) VALUES (NULL, '$uid', '$token', '$expire');");
        } else {
            //token exists
            $result = $Database->query("SELECT id,uid FROM tokens WHERE token = '$token'")->fetch_assoc();
            $tokenUid = $result['uid'];
            $tokenId = $result['id'];

            if ($uid == $tokenUid) {
                //token belongs to the current user -> update time
                $expire = $this->getTimestampAfter(20);
                $Database->query("UPDATE `tokens` SET `expires` = '$expire' WHERE `tokens`.`id` = $tokenId");
            } else {
                //doesn't belong to current user -> create new token
                $this->setSessionToken($uid, $this->generateSessionToken());
            }
        }
    }
    public function checkTokens()
    {
        $Database = new Database;
        $Database->query("DELETE FROM tokens WHERE expires < NOW();");
    }
    function getTimestampAfter($min)
    {
        $now = time();
        $time = $now + ($min * 60);
        return date('Y-m-d H:i:s', $time);
    }
}
