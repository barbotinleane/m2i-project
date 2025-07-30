<?php

namespace App\Service;

class ClockService
{
    public function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}