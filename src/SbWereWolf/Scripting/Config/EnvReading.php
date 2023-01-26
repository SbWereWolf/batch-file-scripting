<?php

declare(strict_types=1);

namespace SbWereWolf\Scripting\Config;


interface EnvReading
{
    /** Define PHP constants from .env file
     * @return void
     */
    public function defineConstants();

    /** Define environment variables from .env file
     * @return void
     */
    public function defineVariables();

    /** Returns variables defined in .env file
     * @return array
     */
    public function getVariables(): array;
}
