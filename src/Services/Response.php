<?php
namespace App\Services;

readonly class Response
{
    public function __construct(
        private string $status,
        private string $html,
        private ?array $data
    )
    {
    }

    public function echo(): void
    {
        echo $this->html; //json_encode(['status' => $this->status, 'html' => $this->html, 'data' => $this->data]);
    }
}