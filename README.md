# php-fpm-cli

Run script in PHP-FPM

## Usage

1. Download [phar file](https://github.com/zhwei/php-fpm-cli/blob/master/dist/php-fpm-cli.phar) (for PHP 7.3)

2. Create PHP script

```bash
echo '<?php echo "hello world"; ?>' > test.php
```

3. Run
```bash
php php-fpm-cli.phar 127.0.0.1:9090 test.php
// or
php php-fpm-cli.phar /var/run/php/php7.3-fpm.sock test.php
```

Result:

```
*** socket ***
UnixDomainSocket /var/run/php/php7.3-fpm.sock
*** script ***
/tmp/test.php


*** headers ***
array(2) {
  ["X-Powered-By"]=>
  array(1) {
    [0]=>
    string(9) "PHP/7.3.8"
  }
  ["Content-type"]=>
  array(1) {
    [0]=>
    string(24) "text/html; charset=UTF-8"
  }
}

*** body ***
hello world%
```

## Build phar file

```php
composer update # update for your php version
php cli/create-phar.php
```
