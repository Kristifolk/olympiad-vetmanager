<?php
namespace App\Services;

readonly class Response
{
    public function __construct(
        private string $html,
    )
    {
    }

    public function echo(): void
    {
        echo $this->html;
    }
}