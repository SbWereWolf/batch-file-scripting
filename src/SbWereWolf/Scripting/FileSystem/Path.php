<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\FileSystem;

class Path
{
    private readonly string $root;
    private readonly string $pathSeparator;

    public function __construct(
        string $root = '',
        string $pathSeparator = DIRECTORY_SEPARATOR
    ) {
        $this->root = $root;
        $this->pathSeparator = $pathSeparator;
    }

    public function make(array $parts): string
    {
        $path = join($this->pathSeparator, $parts);
        if ($this->root) {
            $path =
                $this->root .
                $this->pathSeparator .
                $path;
        }

        return $path;
    }
}