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

    private const DAYS = 'D';
    private const HOURS = 'H';
    private const MINUTES = 'm';
    private const SECONDS = 's';

    protected const RATIOS = [
        self::DAYS => self::SEC_TO_DAYS,
        self::HOURS => self::SEC_TO_H,
        self::MINUTES => self::SEC_TO_MIN,
        self::SECONDS => self::SEC_TO_SEC,
    ];

    public function __construct(
        string $format = '%dd, %H:%I:%S'
    ) {
        parent::__construct($format);
    }

    /**
     * @param array $parts
     * @return DateInterval
     * @throws Exception
     */
    protected function toInterval(array $parts): DateInterval
    {
        $d = $parts[self::DAYS];
        $h = $parts[self::HOURS];
        $i = $parts[self::MINUTES];
        $s = $parts[self::SECONDS];

        $template = "P{$d}DT{$h}H{$i}M{$s}S";
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $interval = new DateInterval($template);

        return $interval;
    }
}