<?php

namespace App\DTO\JSON;

class ErrorResponse implements JsonResponseInterface
{
    public function __construct(
        private readonly string $message
    )
    {
    }

    public function displayAndStopPhp(): never
    {
        echo json_encode(
            [
                'success' => false,
                'message' => $this->message
            ]
        );
        exit();
    }
}