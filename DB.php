<?php

class DB {
    public $pdo;

    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=work_of_tracker';
        $this->pdo = new PDO($dsn, 'root', 'maftuna2005');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPDO() {
        return $this->pdo;
    }
}
