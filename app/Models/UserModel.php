<?php

namespace App\Models;

use App\DTO\Users\UsersDTO;
use App\DTO\Users\UsersDTOCollection;
use PDO;
use PDOException;
use UrlShortenerPhp\Base\Model\BaseModel;

class UserModel extends BaseModel
{
    protected $table = "users";
    protected ?UsersDTO $user;
    function __construct()
    {
        parent::__construct();
    }
    public function setUser(UsersDTO $user){
        $this->user = $user;
    }
    public function get($id)
    {
        $query = $this->find($id);
        if (!$query) return false;
        return new UsersDTO($query);
    }
    public function getAll()
    {
        $query = $this->findAll();
        return (new UsersDTOCollection($query))->all();
    }
    public function getByUsername(string $username)
    {
        $user = $this->findBy(["username" => $username]);
        if (!$user) return false;
        return new UsersDTO($user);
    }
    public function save()
    {
        try {
            $query = $this->prepare("INSERT INTO users(name, username, email, password) VALUES (:name, :username, :email, :password);");

            $query->bindParam(':name', $this->user->name, PDO::PARAM_STR);
            $query->bindParam(':username', $this->user->username, PDO::PARAM_STR);
            $query->bindParam(':email', $this->user->email, PDO::PARAM_STR);
            $query->bindParam(':password', $this->user->password, PDO::PARAM_STR);
            $this->user = null;
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function refuseRegisterNewUsers(){
        $query = $this->count();
        if (!$query) return 0;
        return $query->count >= 20;
    }
}

