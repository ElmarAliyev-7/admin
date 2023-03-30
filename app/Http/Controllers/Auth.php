<?php
session_start();
require_once '../../../config/db.php';

class Auth
{
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

    public function login($username, $password, $remember_me)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM admins WHERE username=? AND password=?');
            if(isset($_COOKIE['authUser'])) :
                $stmt->execute([$username, md5(openssl_decrypt($password, 'AES-128-ECB', 'my_remember_key'))]);
            else :
                $stmt->execute([$username, md5($password)]);
            endif;

            if($stmt->rowCount() == 1) :
                /* Get Auth User Data and Save to Session*/
                $authUser = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['authUser'] = [
                    'id' => $authUser['id'],
                    'username' => $authUser['username'],
                    'fullname' => $authUser['fullname'],
                    'avatar' => $authUser['avatar'],
                ];
                /* Remember Me Cookie Data */
                $authUser = [
                    'username' => $username,
                    'password' => openssl_encrypt($password, 'AES-128-ECB', 'my_remember_key')
                ];
                if(!empty($remember_me) and empty($_COOKIE['authUser'])) :
                    setcookie('authUser', json_encode($authUser), strtotime('+30 day'), '/');
                elseif(empty($remember_me)) :
                    setcookie('authUser', json_encode($authUser), strtotime('-30 day'), '/');
                endif;

                return ['status' => true, 'message' => 'Login Successfully'];
            else :
                return ['status' => false, 'message' => 'Username or password incorrect'];
            endif;
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}