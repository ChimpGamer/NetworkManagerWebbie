<?php

namespace App\Models\ServerStats;

use JsonSerializable;

class ServerData implements JsonSerializable
{
    private string $name;
    public array $data;

    public function __construct($name)
    {
        $this->name = $name;
        $this->data = array();
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return array('name' => $this->name, 'data' => $this->data);
    }
}
