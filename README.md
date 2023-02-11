# Batch File Scripting

Kit of utility classes for batch file scripting.

- `EnvReader` - parse the `.env` files to array and define constants
  or environment variables
- `DurationConverter` - prints duration in seconds or nanoseconds with
  human-readable format
- `Path` - glue up file system path

Code examples in [test.php](test/test.php)

## How to install

`composer require sbwerewolf/batch-file-scripting`

## How to use EnvReader

```php
$path = (new Path())->make(['.', 'config', 'test.env',]);
/* .env file location is './config/test.env' */
$env = new SbWereWolf\Scripting\Config\EnvReader($path);

var_dump($env->getVariables());
/*
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
*/
$env->defineConstants();
echo constant('USER') . PHP_EOL;
/* root */

$env->defineVariables();
echo getenv('FLAG') . PHP_EOL;
/* FALSE */
```

## How to use DurationConverter

```php
$printer = 
new SbWereWolf\Scripting\Convert\SecondsConverter('%dd, %H:%I:%S');
echo $printer->print(100000.111) . PHP_EOL;
/* 1d, 03:46:40 */

$printer = 
new SbWereWolf\Scripting\Convert\NanosecondsConverter('%dd, %H:%I:%S.%F%N');
echo $printer->print(100000999888777.999) . PHP_EOL;
/* 1d, 03:46:40.999888778 */

$printer = 
new SbWereWolf\Scripting\Convert\NanosecondsConverter('%L ms %U mcs %N ns');
echo $printer->print(99088077.999) . PHP_EOL;
/* 099 ms 088 mcs 077 ns */
```

## How to use Path

```php
$pathMaker = (new SbWereWolf\Scripting\FileSystem\Path());
$path = $pathMaker->make(['.', 'config', 'test.env',]);
echo $path . PHP_EOL;
/* .\config\test.env */
```

## Contacts

```
Volkhin Nikolay
e-mail ulfnew@gmail.com
phone +7-902-272-65-35
Telegram @sbwerewolf
```

Chat with me via messenger

- [Telegram chat with me](https://t.me/SbWereWolf)
- [WhatsApp chat with me](https://wa.me/79022726535) 