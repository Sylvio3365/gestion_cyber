<?php

namespace app\models;

use Flight;


class ConnexionModel
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
