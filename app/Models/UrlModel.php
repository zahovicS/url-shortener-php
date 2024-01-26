<?php

namespace App\Models;

use App\DTO\Urls\UrlDTO;
use App\DTO\Urls\UrlDTOCollection;
use PDO;
use PDOException;
use UrlShortenerPhp\Base\Model\BaseModel;

class UrlModel extends BaseModel
{
    protected $table = "urls";
    protected ?UrlDTO $url;
    function __construct()
    {
        parent::__construct();
    }
    public function setUrl(UrlDTO $url)
    {
        $this->url = $url;
    }
    public function get(int $id)
    {
        $query = $this->find($id);
        if (!$query) return false;
        return new UrlDTO($query);
    }
    public function getAll()
    {
        $query = $this->findAll();
        return (new UrlDTOCollection($query))->all();
    }
    public function countUrlByUser(int $id_user)
    {
        $query = $this->countBy(["id_user" => $id_user]);
        if (!$query) return 0;
        return $query->count;
    }
    public function getUrlByUserAndUrlId(int $id_user, int $id_url)
    {
        $user = $this->findBy(["id_user" => $id_user, "id" => $id_url]);
        if (!$user) return false;
        return new UrlDTO($user);
    }
    public function getAllByUser(int $id_user)
    {
        $query = $this->findAllBy(["id_user" => $id_user]);
        return (new UrlDTOCollection($query))->all();
    }
    public function getWhereAndLike(array $wheres, array $like)
    {
        $query = $this->findAllBy($wheres, $like);
        return (new UrlDTOCollection($query))->all();
    }
    public function getBySlug(string $slug, $exeption = null)
    {
        $user = $this->findBy(["slug" => $slug]);
        if (!$user) return false;
        return new UrlDTO($user);
    }

    public function save()
    {
        try {
            $query = $this->prepare("INSERT INTO urls(id_user, url, slug, description) VALUES (:id_user, :url, :slug, :description);");

            $query->bindParam(':id_user', $this->url->id_user, PDO::PARAM_INT);
            $query->bindParam(':url', $this->url->url, PDO::PARAM_STR);
            $query->bindParam(':slug', $this->url->slug, PDO::PARAM_STR);
            $query->bindParam(':description', $this->url->description, PDO::PARAM_STR);
            $this->url = null;
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function update()
    {
        try {
            $query = $this->prepare("UPDATE urls SET url = :url, description = :description WHERE id = :id;");

            $query->bindParam(':id', $this->url->id, PDO::PARAM_INT);
            $query->bindParam(':url', $this->url->url, PDO::PARAM_STR);
            $query->bindParam(':description', $this->url->description, PDO::PARAM_STR);
            $this->url = null;
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function delete(){
        try {
            $query = $this->prepare("DELETE FROM `urls` WHERE id = :id;");

            $query->bindParam(':id', $this->url->id, PDO::PARAM_INT);
            $this->url = null;
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function refuseRegisterNewUrl(){
        $query = $this->count();
        if (!$query) return 0;
        return $query->count >= 30;
    }
}
