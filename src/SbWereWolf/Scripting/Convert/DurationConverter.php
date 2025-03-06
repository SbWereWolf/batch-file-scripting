<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Convert;

use DateInterval;
use JsonSerializable;
use SbWereWolf\JsonSerializable\JsonSerializeTrait;

/**
 * Prints duration with defined format
 */
abstract class DurationConverter implements JsonSerializable
{
    use JsonSerializeTrait;

    public const DAYS = 'D';
    public const HOURS = 'H';
    public const MINUTES = 'm';
    public const SECONDS = 's';
    public const MILLISECONDS_UNITS = 'ms';
    protected const MILLISECONDS_PLACEHOLDER = '%L';
    public const MICROSECONDS_UNITS = 'mcs';
    protected const MICROSECONDS_PLACEHOLDER = '%U';
    public const NANOSECONDS_UNITS = 'ns';
    protected const NANOSECONDS_PLACEHOLDER = '%N';

    public const UNITS = 'u';
    public const PLACEHOLDER = 'p';
    public const PAD_LEFT_WITH_SYMBOL = 's';
    public const LENGTH = 'l';
    private NumbersSplitter $splitter;

    public function __construct(
        private readonly string $format = '%y-%M-%D, %H:%I:%S.%F%N',
        private array $additionalFormats = [],
        protected array $ratios = [],
    ) {
        $letUseDefaultSubstitution = count($additionalFormats) === 0;
        if ($letUseDefaultSubstitution) {
            $this->additionalFormats =
                $this->getDefaultAdditionalFormats();
        }

        $letUseDefaultRatios = count($ratios) === 0;
        if ($letUseDefaultRatios) {
            $this->ratios = $this->getDefaultRatios();
        }

        $this->splitter = new NumbersSplitter($this->ratios);
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
        $output = $this->additionalFormat($output, $parts);

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
    private function additionalFormat(
        string $output,
        array $parts
    ): string {
        foreach ($this->additionalFormats as $substitution) {
            $hasPlaceholder = str_contains(
                $this->format,
                $substitution[static::PLACEHOLDER]
            );
            if ($hasPlaceholder) {
                $value = $parts[$substitution[static::UNITS]] ?? 0;
                $filler = str_pad(
                    (string)$value,
                    $substitution[static::LENGTH],
                    $substitution[static::PAD_LEFT_WITH_SYMBOL],
                    STR_PAD_LEFT
                );

                $output = str_replace(
                    $substitution[static::PLACEHOLDER],
                    $filler,
                    $output
                );
            }
        }

        return $output;
    }

    /**
     * @return array
     */
    abstract protected function getDefaultAdditionalFormats(): array;

    /**
     * @return array
     */
    abstract protected function getDefaultRatios(): array;
}