<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Convert;

use DateInterval;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use SbWereWolf\JsonSerializable\JsonSerializeTrait;

/**
 * Prints duration with defined format
 */
abstract class DurationConverter implements JsonSerializable
{
    use JsonSerializeTrait;

    protected const RATIOS = [];
    protected const MILLISECONDS_UNITS = 'ms';
    private const MILLISECONDS_PLACEHOLDER = '%L';
    protected const MICROSECONDS_UNITS = 'mcs';
    private const MICROSECONDS_PLACEHOLDER = '%U';
    protected const NANOSECONDS_UNITS = 'ns';
    private const NANOSECONDS_PLACEHOLDER = '%N';

    private const UNITS = 'u';
    private const PLACEHOLDER = 'p';
    private const SYMBOL = 's';
    private const LENGTH = 'l';

    private const SUBSTITUTION = [
        [
            self::UNITS => self::MILLISECONDS_UNITS,
            self::PLACEHOLDER => self::MILLISECONDS_PLACEHOLDER,
            self::LENGTH => 3,
            self::SYMBOL => '0'
        ],
        [
            self::UNITS => self::MICROSECONDS_UNITS,
            self::PLACEHOLDER => self::MICROSECONDS_PLACEHOLDER,
            self::LENGTH => 3,
            self::SYMBOL => '0'
        ],
        [
            self::UNITS => self::NANOSECONDS_UNITS,
            self::PLACEHOLDER => self::NANOSECONDS_PLACEHOLDER,
            self::LENGTH => 3,
            self::SYMBOL => '0'
        ],
    ];

    private readonly string $format;
    private NumbersSplitter $splitter;

    #[Pure]
    public function __construct(
        string $format = '%y-%M-%D, %H:%I:%S.%F%N'
    ) {
        $this->splitter = new NumbersSplitter(static::RATIOS);
        $this->format = $format;
    }

    /**
     * @param float $duration
     * @return string
     */
    public function print(float $duration): string
    {
        $parts = $this->splitter->sliceUp($duration);
        $interval = $this->toInterval($parts);

        $output = $interval->format($this->format);
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $output = $this->substitute($output, $parts);

        return $output;
    }

    /**
     * @param array $parts
     * @return DateInterval
     */
    abstract protected function toInterval(array $parts): DateInterval;

    /**
     * @param array $parts
     * @param string $output
     * @return string
     */
    private function substitute(string $output, array $parts): string
    {
        foreach (self::SUBSTITUTION as $substitution) {
            $hasPlaceholder = str_contains(
                $this->format,
                $substitution[self::PLACEHOLDER]
            );
            if ($hasPlaceholder) {
                $value = $parts[$substitution[self::UNITS]] ?? 0;
                $filler = str_pad(
                    (string)$value,
                    $substitution[self::LENGTH],
                    $substitution[self::SYMBOL],
                    STR_PAD_LEFT
                );

                $output = str_replace(
                    $substitution[self::PLACEHOLDER],
                    $filler,
                    $output
                );
            }
        }

        return $output;
    }
}