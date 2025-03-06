<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Convert;

use DateInterval;
use Exception;
use SbWereWolf\JsonSerializable\JsonSerializeTrait;

/**
 * Prints duration in seconds with human-readable format
 */
class SecondsConverter extends DurationConverter
{
    use JsonSerializeTrait;

    private const SEC_TO_DAYS = 60 * 60 * 24;
    private const SEC_TO_H = 60 * 60;
    private const SEC_TO_MIN = 60;
    private const SEC_TO_SEC = 1;

    public function __construct(
        string $format = '%dd, %H:%I:%S',
        array $substitution = [],
        array $ratios = [],
    ) {
        parent::__construct($format, $substitution, $ratios);
    }

    /**
     * @param array $parts
     * @return DateInterval
     * @throws Exception
     */
    protected function toInterval(array $parts): DateInterval
    {
        $d = $parts[static::DAYS];
        $h = $parts[static::HOURS];
        $i = $parts[static::MINUTES];
        $s = $parts[static::SECONDS];

        $template = "P{$d}DT{$h}H{$i}M{$s}S";
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $interval = new DateInterval($template);

        return $interval;
    }

    protected function getDefaultAdditionalFormats(): array
    {
        return [];
    }

    protected function getDefaultRatios(): array
    {
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $defaultRatios = [
            static::DAYS => static::SEC_TO_DAYS,
            static::HOURS => static::SEC_TO_H,
            static::MINUTES => static::SEC_TO_MIN,
            static::SECONDS => static::SEC_TO_SEC,
        ];

        return $defaultRatios;
    }
}