<?php

namespace App\Controllers;

use App\DTO\Urls\UrlDTO;
use App\Models\UrlModel;
use Error;
use Exception;
use UrlShortenerPhp\Base\Controller\BaseController;
use UrlShortenerPhp\Errors\Errors;
use UrlShortenerPhp\Http\Response;
use UrlShortenerPhp\Session\Session;

class DashboardController extends BaseController
{
    protected UrlModel $urlModel;
    function __construct(string $url)
    {
        $this->urlModel = new UrlModel;
        parent::__construct($url);
    }
    public function index()
    {
        $hasLinks = $this->urlModel->countUrlByUser(Session::get("userId"));
        return view("Dashboard.index", [
            "hasLinks" => $hasLinks
        ]);
    }
    public function list()
    {
        try {
            $urls = $this->urlModel->getAllByUser(Session::get("userId"));
            return Response::json([
                "status" => "success",
                "urls" => $urls
            ]);
        } catch (Exception $e) {
            return Response::json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
    public function search(string $search)
    {
        try {
            $likes = [];
            if ($search != "all") {
                $likes = [
                    "slug" => "%$search%",
                    "url" => "%$search%",
                ];
            }
            $urls = $this->urlModel->getWhereAndLike([
                "id_user" => Session::get("userId"),
            ], $likes);
            return Response::json([
                "status" => "success",
                "urls" => $urls
            ]);
        } catch (Exception $e) {
            return Response::json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
    public function create()
    {
        return view("Dashboard.create");
    }
    public function save()
    {
        $refuse = $this->urlModel->refuseRegisterNewUrl();
        if($refuse){
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Lo sentimos, nuestra pequeña app solo puede guardar 30 url por usuario :(");
            return redirect("dashboard/create");
        }
        if (!$this->existsPOST(["url_link"])) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Ingrese el campo de enlace.");
            return redirect("dashboard/create");
        }
        if (!filter_var($this->getPost("url_link"), FILTER_VALIDATE_URL)) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El enlace ingresado no un URL válido..");
            Session::flash("sendData", [
                "url_link" => $this->getPost("url_link"),
                "url_slug" => $this->getPost("url_slug"),
                "url_descripcion" => $this->getPost("url_descripcion"),
            ]);
            return redirect("dashboard/create");
        }
        $dominio_url = parse_url($this->getPost("url_link"), PHP_URL_HOST);
        if ($dominio_url == $_SERVER['HTTP_HOST']) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El enlace ingresado no puede ser el mismo dominio de la app..");
            Session::flash("sendData", [
                "url_link" => $this->getPost("url_link"),
                "url_slug" => $this->getPost("url_slug"),
                "url_descripcion" => $this->getPost("url_descripcion"),
            ]);
            return redirect("dashboard/create");
        }
        $hasSlug = $this->getPost("url_slug") ? true : false;
        $hasDescripcion = $this->getPost("url_descripcion") ? true : false;
        if ($hasSlug && $this->urlModel->getBySlug($this->getPost("url_slug"))) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El slug ya existe.");
            Session::flash("sendData", [
                "url_link" => $this->getPost("url_link"),
                "url_slug" => $this->getPost("url_slug"),
                "url_descripcion" => $this->getPost("url_descripcion"),
            ]);
            return redirect("dashboard/create");
        }
        if ($hasSlug && strlen($this->getPost("url_slug")) < 6) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El slug ingresado debe contener minímo 6 carácteres.");
            Session::flash("sendData", [
                "url_link" => $this->getPost("url_link"),
                "url_slug" => $this->getPost("url_slug"),
                "url_descripcion" => $this->getPost("url_descripcion"),
            ]);
            return redirect("dashboard/create");
        }
        $slug_save = !$hasSlug ? substr(md5(microtime()), rand(0, 26), 6) : substr($this->getPost("url_slug"), 0, 6);
        $dataSave = [
            "id_user" => Session::get("userId"),
            "url" => $this->getPost("url_link"),
            "slug" => $slug_save,
            "description" => $hasDescripcion ? $this->getPost("url_descripcion") : null
        ];
        $urlDTO = new UrlDTO((object) $dataSave);
        $this->urlModel->setUrl($urlDTO);
        if (!$this->urlModel->save()) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Error al insertar URL.");
            Session::flash("sendData", [
                "url_link" => $this->getPost("url_link"),
                "url_slug" => $this->getPost("url_slug"),
                "url_descripcion" => $this->getPost("url_descripcion"),
            ]);
            return redirect("dashboard/create");
        }
        Session::flash("status", "Success");
        Session::flash("color", "is-success");
        Session::flash("message", "Url guardado correctamente.");
        return redirect("dashboard/create");
    }
    public function edit(int $id_url)
    {
        try {
            $url = $this->urlModel->getUrlByUserAndUrlId(Session::get("userId"), $id_url);
            if (!$url) {
                (new Errors)->render(404);
            }
            return view("Dashboard.edit", [
                "url" => $url
            ]);
        } catch (Exception $e) {
            (new Errors)->render(404);
        }
    }
    public function update(int $id_url)
    {
        $url = $this->urlModel->getUrlByUserAndUrlId(Session::get("userId"), $id_url);
        if (!$url) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El ID del enlace no le pertenece o no existe.");
            return redirect("dashboard/edit/{$id_url}");
        }
        if (!$this->existsPOST(["url_link"])) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Ingrese el campo de enlace.");
            return redirect("dashboard/edit/{$id_url}");
        }
        if (!filter_var($this->getPost("url_link"), FILTER_VALIDATE_URL)) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "El enlace ingresado no un URL válido..");
            return redirect("dashboard/edit/{$id_url}");
        }
        $hasDescripcion = $this->getPost("url_descripcion") ? true : false;
        $dataUpdate = [
            "id" => $id_url,
            "url" => $this->getPost("url_link"),
            "description" => $hasDescripcion ? $this->getPost("url_descripcion") : null
        ];
        $urlDTO = new UrlDTO((object) $dataUpdate);
        $this->urlModel->setUrl($urlDTO);
        if (!$this->urlModel->update()) {
            Session::flash("status", "Error");
            Session::flash("color", "is-danger");
            Session::flash("message", "Error al actualizar URL.");
            return redirect("dashboard/edit/{$id_url}");
        }
        Session::flash("status", "Success");
        Session::flash("color", "is-success");
        Session::flash("message", "Url actualizado correctamente.");
        return redirect("dashboard/edit/{$id_url}");
    }
    public function delete(int $id_url){
        try {
            $url = $this->urlModel->getUrlByUserAndUrlId(Session::get("userId"), $id_url);
            if (!$url) {
                return Response::json([
                    "status" => "error",
                    "message" => "El ID del enlace no le pertenece o no existe",
                ]);
            }
            $dataDelete = [
                "id" => $id_url,
            ];
            $urlDTO = new UrlDTO((object) $dataDelete);
            $this->urlModel->setUrl($urlDTO);
            if (!$this->urlModel->delete()) {
                return Response::json([
                    "status" => "error",
                    "message" => "Error al eliminar tu enlace."
                ]);
            }
            return Response::json([
                "status" => "success",
                "message" => "Enlace eliminado."
            ]);
        } catch (Exception $e) {
            return Response::json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
