<?php

namespace App\Controllers;

use App\DTO\Users\UsersDTO;
use App\Models\UserModel;
use UrlShortenerPhp\Base\Controller\BaseController;
use UrlShortenerPhp\Http\Response;
use UrlShortenerPhp\Session\Session;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    function __construct(string $url)
    {
        $this->userModel = new UserModel();
        parent::__construct($url);
    }
    public function index()
    {
        return view("Auth.index");
    }
    public function register()
    {
        return view("Auth.register");
    }
    public function save()
    {
        $refuse = $this->userModel->refuseRegisterNewUsers();
        if($refuse){
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Lo sentimos, nuestra pequeña app solo está disponible para las primeras 20 personas que se registren :(");
            return redirect("auth/register");
        }
        if (!$this->existsPOST(["email", "username", "password"])) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Ingrese los campos correctamente.");
            return redirect("auth/register");
        }
        $usuario = $this->userModel->getByUsername($this->getPost("username"));
        if ($usuario) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El nombre de usuario no está disponible.");
            Session::flash("sendData", [
                "name" => $this->getPost("name"),
                "username" => $this->getPost("username"),
                "email" => $this->getPost("email"),
            ]);
            return redirect("auth/register");
        }
        if (strlen($this->getPost("username")) < 6) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El nombre de usuario debe tener minimo 6 carácteres.");
            Session::flash("sendData", [
                "name" => $this->getPost("name"),
                "username" => $this->getPost("username"),
                "email" => $this->getPost("email"),
            ]);
            return redirect("auth/register");
        }
        $valid_email = filter_var($this->getPost("email"), FILTER_VALIDATE_EMAIL);
        if (!$valid_email) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El email no es válido.");
            Session::flash("sendData", [
                "name" => $this->getPost("name"),
                "username" => $this->getPost("username"),
                "email" => $this->getPost("email"),
            ]);
            return redirect("auth/register");
        }
        $newUser = [
            "name" => $this->getPost("name") ?? null,
            "email" => $this->getPost("email"),
            "username" => substr($this->getPost("username"), 0, 10),
            "password" => password_hash($this->getPost('password'), PASSWORD_DEFAULT, ["cost" => 10]),
        ];
        $urlDTO = new UsersDTO((object) $newUser);
        $this->userModel->setUser($urlDTO);

        if (!$this->userModel->save()) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Error al crear tu Usuario.");
            Session::flash("sendData", [
                "name" => $this->getPost("name"),
                "username" => $this->getPost("username"),
                "email" => $this->getPost("email"),
            ]);
            return redirect("auth/register");
        }
        $usuario = $this->userModel->getByUsername($this->getPost("username"));
        $this->initializeUser($usuario);
        redirect("dashboard");
    }
    public function login()
    {
        if (!$this->existsPOST(["username", "password"])) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Ingrese los campos correctamente.");
            return redirect("auth");
            // return Response::json([
            //     "status" => "error",
            //     "message" => "Ingrese los campos correctamente"
            // ]);
        }
        $usuario = $this->userModel->getByUsername($this->getPost("username"));
        if (!$usuario) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "No existe el usuario.");
            return redirect("auth");
        }
        if (!password_verify($this->getPost("password"), $usuario->password)) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Contraseña incorrecta.");
            return redirect("auth");
        }
        $this->initializeUser($usuario);
        redirect("dashboard");
    }
    public function logout()
    {
        $this->logoutUser();
        redirect("home");
    }
}
