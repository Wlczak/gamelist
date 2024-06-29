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
