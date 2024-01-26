<?php

namespace UrlShortenerPhp\Auth;

use App\DTO\Users\UsersDTO;

class Auth
{
    public $sites;
    public $defaultSite;
    public $defaultLogin;
    public $user;
    public $userId;
    public $url;

    public function __construct($url)
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $this->userId = $_SESSION["userId"] ?? "";
        $this->user = $_SESSION["user"] ?? "";

        $this->url = $url;

        $this->defaultSite = "";

        $this->defaultLogin = "auth";

        $this->sites = $this->sites();

        $this->validateSession();
        // parent::__construct($this->user, $this->userType);
    }

    public function sites()
    {
        return [
            "no-protected" => [
                "home/index",
                "auth/index",
                "auth/login",
                "auth/register",
                "auth/save",
                "auth/logout",
                "url/redirect"
            ],
            "protected" => [
                "dashboard",
                "dashboard/index",
                "dashboard/list",
                "dashboard/search",
                "dashboard/create",
                "dashboard/save",
                "dashboard/edit",
                "dashboard/update",
                "dashboard/delete",
            ]
        ];
    }

    public function validateSession()
    {
        $arr_url = explode("/",$this->url);
        if ($this->existsSession() && $arr_url[0] === "auth") {
            return redirect($this->defaultSite);
        }
        if ($this->isAuthorized($this->url, "no-protected")) {
            return;
        }
        if (!$this->existsSession()) {
            return redirect($this->defaultLogin);
        }
        if (!$this->isAuthorized($this->url)) {
            return redirect($this->defaultSite);
        }
    }

    public function existsSession()
    {
        return isset($_SESSION["userId"]);
    }

    public function initializeUser(UsersDTO $user)
    {
        $_SESSION["userId"] = $user->id;
        $_SESSION["user"] = $user;
    }

    public function isAuthorized($view, $mode = "protected")
    {
        return in_array($view, $this->sites[$mode]); // En codigo
    }

    public function logoutUser()
    {
        session_unset();
        session_destroy();
    }
}
