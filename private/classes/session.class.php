<?php

class Session
{
    private $admin_id;
    public $username;
    public $last_login;
    public const MAX_LOGIN_AGE = 60 * 60 * 24; //1 day

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
        return isset($this->admin_id) && $this->last_login_is_recent();
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

    private function last_login_is_recent()
    {
        if (!isset($this->last_login)) {
            return false;
        } elseif (($this->last_login + self::MAX_LOGIN_AGE) < time()) {
            return false;
        } else {
            return true;
        }
    }

    public function message($msj = "")
    {
        if (!empty($msj)) {
            // this is a set message
            $_SESSION["message"] = $msj;
            return true;
        } else {
            // this is a get message
            return $_SESSION["message"] ?? '';
        }
    }

    public function clear_message()
    {
        unset($_SESSION['message']);
    }
}
