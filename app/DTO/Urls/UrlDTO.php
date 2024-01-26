<?php

namespace App\DTO\Urls;

use DateTime;
use stdClass;

class UrlDTO
{
    public ?int $id;
    public ?int $id_user;
    public ?string $url;
    public ?string $slug;
    public string $og_url;
    public string $full_url;
    public ?string $description;
    public ?DateTime $registered_at;
    public int $estado;
    public function __construct(stdClass $row)
    {
        $this->id = $row->id ?? null;
        $this->id_user = $row->id_user ?? null;
        $this->url = $row->url ?? null;
        $this->slug = $row->slug ?? null;
        $this->og_url = mb_strimwidth(($row->url ?? ""), 0, 27, "...", "utf-8");
        $this->full_url = base_url() . "url/"  . ($row->slug ?? "");
        $this->description = $row->description ?? null;
        $this->registered_at = !empty($row->registered_at) && isset($row->registered_at) ? new DateTime($row->registered_at) : null;
    }
}
