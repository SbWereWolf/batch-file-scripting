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

    private const DAYS = 'D';
    private const HOURS = 'H';
    private const MINUTES = 'm';
    private const SECONDS = 's';

    protected const RATIOS = [
        self::DAYS => self::NS_TO_DAYS,
        self::HOURS => self::NS_TO_H,
        self::MINUTES => self::NS_TO_MIN,
        self::SECONDS => self::NS_TO_SEC,
        self::MILLISECONDS_UNITS => self::NS_TO_MS,
        self::MICROSECONDS_UNITS => self::NS_TO_MCS,
        self::NANOSECONDS_UNITS => self::NS_TO_NS,
    ];

    public function __construct(
        string $format = '%dd, %H:%I:%S.%F%N'
    ) {
        parent::__construct($format);
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
}