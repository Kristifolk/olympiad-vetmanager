<?php

namespace App\DTO\JSON;

interface JsonResponseInterface
{
    /** Возвращает JSON и прекращает выполнение */
    public function displayAndStopPhp(): never;
}