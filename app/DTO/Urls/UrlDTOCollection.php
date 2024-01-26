<?php

namespace App\DTO\Urls;

class UrlDTOCollection {

    private array $rows = [];

    public function __construct(array $rows) {
        foreach ($rows as $key => $row) {
            $this->rows[] = new UrlDTO($row);
        }
    }

    public function all(){
        return $this->rows;
    }
}