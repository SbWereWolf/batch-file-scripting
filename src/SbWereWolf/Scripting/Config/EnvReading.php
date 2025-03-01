<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Config;


interface EnvReading
{
    /** Define PHP constants from .env file
     * @return void
     */
    public function defineConstants(): void;

    /** Define environment variables from .env file
     * @return void
     */
    public function defineVariables(): void;

    /** Returns variables defined in .env file
     * @return array
     */
    public function getVariables(): array;
}
