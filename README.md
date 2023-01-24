# Batch File Scripting

Kit of utility classes for batch file scripting.

- `EnvReader` - parse the `.env` files to array and define constants
  or environment variables
- `DurationPrinter` - prints duration in seconds or nanoseconds with
  human-readable format

Code examples in [test.php](test/test.php)

## How to install

`composer require sbwerewolf/batch-file-scripting`

## How to use EnvReader

```php
$env =
    new \SbWereWolf\BatchFileScripting\Configuration\EnvReader($path);

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

## How to use DurationPrinter

```php
$printer =
    new \SbWereWolf\BatchFileScripting\Convertation\DurationPrinter();
echo $printer->printSeconds(100000) . PHP_EOL;
/* 27:46:40 */
echo $printer->printNanoseconds(100000999888777) . PHP_EOL;
/* 27:46:40 999 ms 888 mcs 777 ns */
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