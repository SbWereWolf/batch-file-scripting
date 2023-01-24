<?php

use SbWereWolf\BatchFileScripting\Configuration\EnvReader;
use SbWereWolf\BatchFileScripting\Convertation\DurationPrinter;

$pathParts = [__DIR__, '..', 'vendor', 'autoload.php',];
$path = join(DIRECTORY_SEPARATOR, $pathParts);
require_once($path);

$printer =
    new DurationPrinter();
echo $printer->printSeconds(0) . PHP_EOL;
/* 00:00:00 */
echo $printer->printNanoseconds(0) . PHP_EOL;
/* 00:00:00 000 ms 000 mcs 000 ns ns */
echo $printer->printSeconds(100000) . PHP_EOL;
/* 27:46:40 */
echo $printer->printNanoseconds(100000999888777) . PHP_EOL;
/* 27:46:40 999 ms 888 mcs 777 ns */

$pathParts = [__DIR__, 'config', 'test.env',];
$path = join(DIRECTORY_SEPARATOR, $pathParts);

$env =
    new EnvReader($path);
echo json_encode($env, JSON_PRETTY_PRINT) . PHP_EOL;
/*
{
    "variables": {
        "USER": "root",
        "PORT": "80",
        "DATE": "2023-01-25",
        "FLAG": "FALSE"
    }
}
*/
echo var_dump($env) . PHP_EOL;
/*
class SbWereWolf\BatchFileScripting\Configuration\EnvReader#3 (1) {
  private array $variables =>
  array(4) {
    'USER' =>
    string(4) "root"
    'PORT' =>
    string(2) "80"
    'DATE' =>
    string(10) "2023-01-25"
    'FLAG' =>
    string(5) "FALSE"
  }
}
*/
$env->defineConstants();
echo constant('USER') . PHP_EOL;
/* root */

$env->defineVariables();
echo getenv('FLAG') . PHP_EOL;
/* FALSE */