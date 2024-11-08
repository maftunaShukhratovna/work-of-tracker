<?php

class DB {
    public $pdo;

    public function __construct() {
        $dsn = 'mysql:host=127.0.0.1;dbname=work_of_tracker';
        $this->pdo = new PDO($dsn, 'root', 'Maftuna@2005');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPDO() {
        return $this->pdo;
    }
}