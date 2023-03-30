<?php
session_start();
require_once '../../../config/db.php';

class Crud {

    private $db;
    private $dbhost = DBHOST;
    private $dbuser = DBUSER;
    private $dbpass = DBPASS;
    private $dbname = DBNAME;

    public function __construct()
    {
        try {
            $this->db = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser, $this->dbpass);
        } catch (Exception $e) {
            die('Connection Failed:'. $e->getMessage());
        }
    }
}