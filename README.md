php-cli-helpers
===============

Utility classes to write PHP command-line scripts

[![Build Status](https://img.shields.io/travis/amercier/php-cli-helpers/master.svg?style=flat-square)](https://travis-ci.org/amercier/php-cli-helpers)
[![Code Climate](https://img.shields.io/codeclimate/github/amercier/php-cli-helpers.svg?style=flat-square)](https://codeclimate.com/github/amercier/php-cli-helpers)
[![Test Coverage](https://img.shields.io/codeclimate/coverage/github/amercier/php-cli-helpers.svg?style=flat-square)](https://codeclimate.com/github/amercier/php-cli-helpers)
[![Dependency Status](https://img.shields.io/gemnasium/amercier/php-cli-helpers.svg?style=flat-square)](https://gemnasium.com/amercier/php-cli-helpers)

[![Latest Stable Version](https://img.shields.io/packagist/v/amercier/cli-helpers.svg?style=flat-square)](https://packagist.org/packages/amercier/cli-helpers)


Installation
------------

_php-cli-helpers_ is available through [Composer](http://getcomposer.org/).
```json
  "require": {
    "amercier/cli-helpers": "1.*"
  }
```
```bash
php composer.phar install
```

If you are not familiar with _Composer_, please read the
[full installation intructions](docs/install.md).


Usage
-----


### \Cli\Helpers\Parameter

Utility class to handle command-line parameters.

```php
$options = \Cli\Helpers\Parameter::getFromCommandLine(array(
    'host'     => new Parameter('h', 'host'    , '127.0.0.1'),
    'username' => new Parameter('u', 'username', Parameter::VALUE_REQUIRED),
    'password' => new Parameter('p', 'password', Parameter::VALUE_REQUIRED),
    'verbose'  => new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE),
));

$options['host'];     // given -h/--host, or 127.0.0.1 otherwise
$options['username']; // given -u/--username
$options['password']; // given -p/--password
$options['verbose'];  // true if -v/--verbose is given, false otherwise
```

See [API Documentation for Parameter](docs/api-parameter.md)


### \Cli\Helpers\Script and \Cli\Helpers\DocumentedScript

Utility class to write scripts as objects.

```php
#!/usr/bin/env php
<?php
require_once dirname(__FILE__) . '/path/to/vendor/autoload.php';

use Cli\Helpers\DocumentedScript;
use Cli\Helpers\Parameter;

$script = new DocumentedScript();
$script
    ->setName('test-documented-script.php')
    ->setVersion('1.0')
    ->setDescription('Test script for Cli\Helpers\DocumentedScript')
    ->setCopyright('Copyright (c) Alexandre Mercier 2014')
    ->addParameter(new Parameter('H', 'host'    , '127.0.0.1')              , 'Host.')
    ->addParameter(new Parameter('u', 'username', Parameter::VALUE_REQUIRED), 'User name.')
    ->addParameter(new Parameter('p', 'password', Parameter::VALUE_REQUIRED), 'Password.')
    ->addParameter(new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE), 'Enable verbosity.')
    ->setProgram(function ($options, $arguments) {
        var_dump($arguments);
        var_dump($options);
    })
    ->start();
```

While `Script` doesn't have any pre-configured switch, `DocumentedScript` has 
`--h, --help` and `-V, --version`. This provides an automatic handling of this
two switches.

Version example:

`test-documented-script.php -V`
```
test-documented-script.php v1.0
Copyright (c) 2014 Alexandre Mercier
```

Help example:

`test-documented-script.php -h`
```
Usage: test-documented-script.php -p PASSWORD -u USERNAME [OPTIONS]

Test script for Cli\Helpers\DocumentedScript

  -H, --host     HOST        Host (defaults to '127.0.0.1').
  -u, --username USERNAME    User name.
  -p, --password PASSWORD    Password.
  -v, --verbose              Enable verbosity.
  -h, --help                 Display this help and exit.
  -V, --version              Output version information and exit.

test-documented-script.php v1.0
Copyright (c) 2014 Alexandre Mercier
```


### \Cli\Helpers\Job

Utility class to run a job and catch exceptions.

On successful jobs:
```php
\Cli\Helpers\Job::run('Doing awesome stuff', function() {
    ... // awesome stuff
});
```
```
Doing awesome stuff... OK
```

On unsuccessful jobs:
```php
\Cli\Helpers\Job::run('Fighting Chuck Norris', function() {
    ... // throws a RoundHouseKickException('You've received a round-house kick', 'face')
});
```
```
Fighting Chuck Norris... NOK - You've received a round-house kick in the face
```

You can also add parameters to the function:

```php
\Cli\Helpers\Job::run(
    'Doing awesome stuff',
    function($a, $b) {
        $a; // => 1337;
        $b; // => 'good luck, im behind 7 firewalls';
    },
    array(1337, 'im behind 7 firewalls')
});
```

See [API Documentation for Job](docs/api-job.md)


###  \Cli\Helpers\IO

Utility class to handle standard input/output.

#### IO::form

Usage
-----

```php
\Cli\Helpers\IO::form('an apple', array(
    'Golden Delicious',
    'Granny Smith',
    'Pink Lady',
    'Royal Gala',
));
```
will display:
```
1. Golden Delicious
2. Granny Smith
3. Pink Lady
4. Royal Gala

Choose an apple: |
```
Then, user is asked to make a choice between 1 and 3 on standard input.

#### IO::strPadAll

```php
echo IO::strPadAll(
    array( // items
        array('#', 'EN', 'FR', 'ES'),
        '',
        array('1', 'One', 'Un', 'Uno'),
        array('2', 'Two', 'Deux', 'Dos'),
        array('3', 'Three', 'Trois', 'Tres'),
        array('4', 'Four', 'Quatre', 'Cuatro'),
        array('5', 'Five', 'Cinq', 'Cinco'),
    ),
    array( // alignment
        2 => STR_PAD_LEFT,
        3 => STR_PAD_RIGHT,
    ),
    "\n", // line separator
    '   ' // field separator
));
```
will display:
```
#   EN          FR   ES

1   One         Un   Uno
2   Two       Deux   Dos
3   Three    Trois   Tres
4   Four    Quatre   Cuatro
5   Five      Cinq   Cinco
```

See [API Documentation for IO](docs/api-io.md)



Contributing
------------

Contributions (issues ♥, pull requests ♥♥♥) are more than welcome! Feel free to
clone, fork, modify, extend, etc, as long as you respect the
[license terms](LICENSE).

See [contributing intructions](docs/contributing.md) for details.


Licensing
---------

This project is released under [MIT License](LICENSE) license. If this license
does not fit your requirement for whatever reason, but you would be interested
in using the work (as defined below) under another license, please contact
[authors](docs/authors.md).
