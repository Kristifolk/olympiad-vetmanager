<?php declare(strict_types=1);

namespace App\Class;
session_start();
class PercentageCompletion
{
    public int $startPercent = 0;
    public function __construct(
        public string $loginUser,
        public int    $currentPercent
    )
    {
    }

    public function getPercentageCompletion(): string
    {
        return $this->startPercent . "%";
    }

    public function calculatePercentageCompletion(int $endPercent, int $resultPercent): string
    {
        return ($this->currentPercent + $this->calculatePercentageCountParagraph()) . "%";
    }

    public function calculatePercentageCountParagraph(): int
    {
        return 100 / 16;
    }
}