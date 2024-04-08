<?php

namespace App\DTO\JSON;

class SuccessResponse implements JsonResponseInterface
{
    public function __construct(
        private readonly array $data = []
    )
    {
    }

    public function displayAndStopPhp(): never
    {
        echo json_encode(
            [
                'success' => true,
                'data' => $this->data
            ]
        );
        exit();
    }
}