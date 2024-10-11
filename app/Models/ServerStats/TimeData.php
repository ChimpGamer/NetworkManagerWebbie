<?php

namespace App\Models\ServerStats;

use JsonSerializable;

class TimeData implements JsonSerializable
{
    private float $time;
    private int $count;

    public function __construct(float $time, int $count)
    {
        $this->count = $count;
        $this->time = $time;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    public function jsonSerialize(): array
    {
        return array($this->time, $this->count);
    }
}
