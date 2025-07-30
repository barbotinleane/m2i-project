<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class PageTracker
{
    public function __construct(
        private Filesystem $fs,
        private string $filename
    ) {}

    public function add(string $route): int
    {
        if ($this->fs->exists($this->filename)) {
            $content = $this->fs->readFile($this->filename);
            $data = json_decode($content, true) ?? [];
        }

        if (!isset($data[$route])) {
            $data[$route] = 0;
        }

        $data[$route]++;

        $this->fs->dumpFile($this->filename, json_encode($data, JSON_PRETTY_PRINT));

        return $data[$route];
    }
}
