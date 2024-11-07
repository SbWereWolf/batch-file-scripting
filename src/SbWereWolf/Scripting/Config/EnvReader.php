<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Config;

use InvalidArgumentException;
use JsonSerializable;
use RuntimeException;
use SbWereWolf\JsonSerializable\JsonSerializeTrait;

/**
 * Parse the `.env` files to array and define constants or
 * environment variables
 */
class EnvReader implements EnvReading, JsonSerializable
{
    use JsonSerializeTrait;

    private array $variables;

    /**
     * @param string $path path to file with variables
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(
                "The file `$path` does not exist"
            );
        }
        if (!is_readable($path)) {
            throw new RuntimeException(
                "The file `$path` is not readable"
            );
        }

        $lines = file(
            $path,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );
        if ($lines === false) {
            $lines = [];
        }

        $variables = [];
        foreach ($lines as $line) {
            $line = trim($line);
            $comment = strpos($line, '#');
            if ($comment !== false) {
                $line = substr($line, 0, $comment);
            }
            $assign = strpos($line, '=');
            if ($assign !== false) {
                list($name, $value) = explode('=', $line, 2);

                $name = trim($name);
                $value = trim($value);

                $variables[$name] = $value;
            }
        }

        $this->variables = $variables;
    }

    /** @inheritdoc */
    public function defineConstants(): void
    {
        foreach ($this->getVariables() as $name => $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }

    /** @inheritdoc */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /** @inheritdoc */
    public function defineVariables(): void
    {
        foreach ($this->getVariables() as $name => $value) {
            /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
            putenv("{$name}={$value}");
        }
    }
}