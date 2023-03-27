<?php

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class ResultController
{
    public function viewResult(): void
    {
        $html = new View(ViewPath::Result);
        (new Response('success', $html, []))->echo();
    }
}