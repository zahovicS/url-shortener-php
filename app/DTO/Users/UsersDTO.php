<?php

namespace App\DTO\Users;

use DateTime;
use stdClass;

class UsersDTO
{
    public ?int $id;
    public ?string $name;
    public ?string $username;
    public ?string $email;
    public ?string $password;
    public ?DateTime $registered_at;
    public ?DateTime $deleted_at;
    public int $estado;
    public function __construct(stdClass $row)
    {
        $this->id = $row->id ?? null;
        $this->name = $row->name ?? "";
        $this->username = $row->username ?? null;
        $this->email = $row->email ?? null;
        $this->password = $row->password ?? null;
        $this->registered_at = !empty($row->registered_at) && isset($row->registered_at) ? new DateTime($row->registered_at) : null;
        $this->deleted_at = !empty($row->deleted_at) && isset($row->deleted_at) ? new DateTime($row->deleted_at) : null;
    }
}
