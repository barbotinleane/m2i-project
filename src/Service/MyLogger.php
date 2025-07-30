<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class MyLogger
{
    public function __construct(private Filesystem $filesystem, private string $filename, private $clockService)
    {

    }
    
    public function log(string $message): void
    {
        $this->filesystem->appendToFile($this->filename, $message . ' ' . $this->clockService->now() . PHP_EOL);
    }
}