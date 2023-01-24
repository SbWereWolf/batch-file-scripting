<?php

declare(strict_types=1);

namespace SbWereWolf\BatchFileScripting\Convertation;

use JsonSerializable;
use SbWereWolf\JsonSerializable\JsonSerializeTrait;

/**
 * Prints duration in seconds or nanoseconds with human-readable
 * format
 */
class DurationPrinter implements JsonSerializable
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

    const DAYS = 'D';
    const HOURS = 'H';
    const MINUTES = 'm';
    const SECONDS = 's';
    const MILLISECONDS = 'ms';
    const MICROSECONDS = 'mcs';
    const NANOSECONDS = 'ns';

    private const NANOSECONDS_PARTS = [
        self::HOURS => self::NS_TO_H,
        self::MINUTES => self::NS_TO_MIN,
        self::SECONDS => self::NS_TO_SEC,
        self::MILLISECONDS => self::NS_TO_MS,
        self::MICROSECONDS => self::NS_TO_MCS,
        self::NANOSECONDS => self::NS_TO_NS,
    ];

    private float $remainder = 0.0;
    private string $output = '';

    /**
     * @param int $seconds
     * @return string
     */
    public function printSeconds(int $seconds): string
    {
        $this->remainder = $seconds * static::NS_TO_SEC;

        $parts = $this->splitToParts();
        $seconds = $this->formatSeconds($parts);

        $this->output = $seconds;

        return $this->output;
    }

    /**
     * @return array
     */
    private function splitToParts(): array
    {
        $parts = static::NANOSECONDS_PARTS;
        array_walk($parts, [$this, 'extractPart']);

        return $parts;
    }

    /**
     * @param array $parts
     * @return string
     */
    private function formatSeconds(array $parts): string
    {
        $secondParts = array_slice($parts, 0, 3);
        array_walk($parts, function (&$val, $key) {
            $val = str_pad((string)$val, 2, '0', STR_PAD_LEFT);
        });

        $formatted = implode(':', $secondParts);

        return $formatted;
    }

    /**
     * @param int $hrTime
     * @return string
     */
    public function printNanoseconds(int $hrTime): string
    {
        $this->remainder = $hrTime;
        $parts = $this->splitToParts();

        $seconds = $this->formatSeconds($parts);
        $nanoseconds = $this->formatNanoseconds($parts);

        $formatted = "$seconds $nanoseconds";
        $this->output = $formatted;

        return $this->output;
    }

    /**
     * @param array $parts
     * @return string
     */
    private function formatNanoseconds(
        array $parts
    ): string {
        $nanosecondParts = array_slice($parts, 3, 3);
        array_walk($nanosecondParts, function (&$val, $key) {
            $val = str_pad((string)$val, 3, '0', STR_PAD_LEFT);

            $val = "$val $key";
        });

        $formatted = implode(' ', $nanosecondParts);

        return $formatted;
    }

    /**
     * @param float $other
     * @return void
     */
    private function extractPart(float &$other)
    {
        $integer = floor($this->remainder / $other);
        $this->remainder = $this->remainder - $integer * $other;

        $other = $integer;
    }
}