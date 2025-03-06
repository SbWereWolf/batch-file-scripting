<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Convert;

use DateInterval;
use DateTime;
use SbWereWolf\JsonSerializable\JsonSerializeTrait;

/**
 * Prints duration in nanoseconds with human-readable format
 */
class NanosecondsConverter extends DurationConverter
{
    use JsonSerializeTrait;

    private const SEC_TO_NS = 1_000_000_000;

    private const NS_TO_DAYS = 60 * 60 * 24 * self::SEC_TO_NS;
    private const NS_TO_H = 60 * 60 * self::SEC_TO_NS;
    private const NS_TO_MIN = 60 * self::SEC_TO_NS;
    private const NS_TO_SEC = 1 * self::SEC_TO_NS;
    private const NS_TO_MS = 1_000_000;
    private const NS_TO_MCS = 1000;
    private const NS_TO_NS = 1;

    public function __construct(
        string $format = '%dd, %H:%I:%S.%F%N',
        array $substitution = [],
        array $ratios = [],
    ) {
        parent::__construct($format, $substitution, $ratios);
    }

    protected function toInterval(array $parts): DateInterval
    {
        $d = $parts[static::DAYS];
        $h = $parts[static::HOURS];
        $i = $parts[static::MINUTES];
        $s = $parts[static::SECONDS];

        $ms = $parts[static::MILLISECONDS_UNITS];
        $mcs = $parts[static::MICROSECONDS_UNITS];

        $base = new DateTime();
        $increment = clone $base;

        $base->setTime(0, 0);

        $increment->add(new DateInterval("P{$d}D"));
        $increment->setTime($h, $i, $s, $ms * 1000 + $mcs);

        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $interval = $increment->diff($base);

        return $interval;
    }

    /**
     * @return array
     */
    protected function getDefaultAdditionalFormats(): array
    {
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $defaultSubstitution = [
            [
                static::UNITS => static::MILLISECONDS_UNITS,
                static::PLACEHOLDER => static::MILLISECONDS_PLACEHOLDER,
                static::LENGTH => 3,
                static::PAD_LEFT_WITH_SYMBOL => '0'
            ],
            [
                static::UNITS => static::MICROSECONDS_UNITS,
                static::PLACEHOLDER => static::MICROSECONDS_PLACEHOLDER,
                static::LENGTH => 3,
                static::PAD_LEFT_WITH_SYMBOL => '0'
            ],
            [
                static::UNITS => static::NANOSECONDS_UNITS,
                static::PLACEHOLDER => static::NANOSECONDS_PLACEHOLDER,
                static::LENGTH => 3,
                static::PAD_LEFT_WITH_SYMBOL => '0'
            ],
        ];

        return $defaultSubstitution;
    }

    /**
     * @return array
     */
    protected function getDefaultRatios(): array
    {
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $defaultRatios = [
            static::DAYS => static::NS_TO_DAYS,
            static::HOURS => static::NS_TO_H,
            static::MINUTES => static::NS_TO_MIN,
            static::SECONDS => static::NS_TO_SEC,
            static::MILLISECONDS_UNITS => static::NS_TO_MS,
            static::MICROSECONDS_UNITS => static::NS_TO_MCS,
            static::NANOSECONDS_UNITS => static::NS_TO_NS,
        ];

        return $defaultRatios;
    }
}