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

    public function login($username, $password)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM admins WHERE username=? AND password=?');
            $stmt->execute([$username, md5($password)]);

            if($stmt->rowCount() == 1) :
                $authUser = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['authUser'] = [
                    'id' => $authUser['id'],
                    'username' => $authUser['username'],
                    'fullname' => $authUser['fullname'],
                    'avatar' => $authUser['avatar'],
                ];
                return ['status' => true, 'message' => 'Login Successfully'];
            else :
                return ['status' => false, 'message' => 'Username or password incorrect'];
            endif;
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}