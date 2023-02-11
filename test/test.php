<?php

$pathParts = [__DIR__, '..', 'vendor', 'autoload.php',];
$path = join(DIRECTORY_SEPARATOR, $pathParts);
require_once($path);

$pathMaker = (new SbWereWolf\Scripting\FileSystem\Path());
$path = $pathMaker->make(['.', 'config', 'test.env',]);
echo $path . PHP_EOL;
/* .\config\test.env */

$seconds =
    new SbWereWolf\Scripting\Convert\SecondsConverter('%dd, %H:%I:%S');
echo $seconds->print(0) . PHP_EOL;
/* 0d, 00:00:00 */
echo $seconds->print(100000.111) . PHP_EOL;
/* 1d, 03:46:40 */

$nanoseconds =
    new SbWereWolf\Scripting\Convert\NanosecondsConverter('%dd, %H:%I:%S.%F%N');
echo $nanoseconds->print(0) . PHP_EOL;
/* 0d, 00:00:00.000000000 */
echo $nanoseconds->print(100000999888777.999) . PHP_EOL;
/* 1d, 03:46:40.999888778 */

$shortFormatNanoseconds =
    new SbWereWolf\Scripting\Convert\NanosecondsConverter('%L ms %U mcs %N ns');
echo $shortFormatNanoseconds->print(0) . PHP_EOL;
/* 000 ms 000 mcs 000 ns */
echo $shortFormatNanoseconds->print(99088077.999) . PHP_EOL;
/* 099 ms 088 mcs 077 ns */

$path =
    (new SbWereWolf\Scripting\FileSystem\Path())
        ->make(['.', 'config', 'test.env',]);

$env =
    new SbWereWolf\Scripting\Config\EnvReader($path);
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
class SbWereWolf\Scripting\Config\EnvReader#3 (1) {
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