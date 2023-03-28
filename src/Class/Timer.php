<?php

namespace App\Class;

use App\Interfaces\TimeInterface;

class Timer implements TimeInterface
{
    public function __construct(
        public int $timeFullMinute,
        public int $timeFullSecond,

        public int $timeEndMinute,
        public int $timeEndSecond
    )
    {
    }

    public function getTimeTransitMinute(): int
    {
        return $this->timeFullMinute - $this->timeEndMinute;
    }

    public function getTimeTransitSecond(): int
    {
        return $this->timeFullSecond - $this->timeEndSecond;
    }
}