<?php

namespace Gamelist\Classes;

use mysqli;

class Database
{
    public $hostname = "192.168.0.106";
    public $username = "gamelist";
    public $password = "todolist";
    public $database = "gamelist";
    function query($array)
    {
        $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        $conn->close();
    }
}
