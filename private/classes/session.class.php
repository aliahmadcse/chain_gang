<?php

class Session
{
    private $admin_id;
    public $username;
    public $last_login;

    public function __construct()
    {
        session_start();
        $this->check_stored_login();
    }
    public function login($admin)
    {
        if ($admin) {
            // prevents the session fixation attacks
            session_regenerate_id();

            $this->admin_id = $_SESSION["admin_id"] = $admin->id;
            $this->username = $_SESSION["username"] = $admin->username;
            $this->last_login = $_SESSION["last_login"] = time();
        }
        return true;
    }

    public function is_logged_in()
    {
        return isset($this->admin_id);
    }

    public function logged_out()
    {
        unset($_SESSION["admin_id"]);
        unset($_SESSION["username"]);
        unset($_SESSION["last_login"]);
        unset($this->admin_id);
        unset($this->last_login);
        unset($this->username);
    }

    private function check_stored_login()
    {
        if (isset($_SESSION['admin_id'])) {
            $this->admin_id = $_SESSION["admin_id"];
            $this->last_login = $_SESSION["last_login"];
            $this->username = $_SESSION["username"];
        }
    }

    
}
