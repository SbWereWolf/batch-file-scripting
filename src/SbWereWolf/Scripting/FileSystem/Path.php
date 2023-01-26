<?php

namespace SbWereWolf\Scripting\FileSystem;

class Path
{
    private string $root;
    private string $pathSeparator;

    public function __construct(
        string $root = '',
        string $pathSeparator = DIRECTORY_SEPARATOR
    ) {
        $this->root = $root;
        $this->pathSeparator = $pathSeparator;
    }

    public function make(array $parts)
    {
        $path =
            $this->root .
            $this->pathSeparator .
            join($this->pathSeparator, $parts);

        return $path;
    }
}