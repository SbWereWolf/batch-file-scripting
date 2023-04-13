<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Convert;

use JsonSerializable;
use SbWereWolf\JsonSerializable\JsonSerializeTrait;

/**
 * Split value to chunk with given ratios
 */
class NumbersSplitter implements JsonSerializable
{
    use JsonSerializeTrait;

    private float $remainder = 0.0;
    private readonly array $sliceRatio;

    public function __construct(array $conversionRatio)
    {
        $this->sliceRatio = $conversionRatio;
    }

    /**
     * @param float $whole
     * @return array
     */
    public function sliceUp(float $whole): array
    {
        $this->remainder = $whole;
        $parts = $this->sliceRatio;
        array_walk($parts, [$this, 'extractPart']);

        return $parts;
    }

    /**
     * @param float $ratio
     * @return void
     */
    private function extractPart(float &$ratio)
    {
        $integer = (int)floor($this->remainder / $ratio);
        $this->remainder = $this->remainder - $integer * $ratio;

        $ratio = $integer;
    }
}