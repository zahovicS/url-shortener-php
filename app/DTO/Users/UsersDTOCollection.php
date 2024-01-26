<?php

namespace App\DTO\Users;

class UsersDTOCollection {

    private array $rows = [];

    public function __construct(array $rows) {
        foreach ($rows as $key => $row) {
            $this->rows[] = new UsersDTO($row);
        }
    }

    public function all(){
        return $this->rows;
    }
}