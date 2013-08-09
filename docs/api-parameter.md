
\Cli\Helpers\Parameter
======================

Utility class to handle command-line parameters.


Example
-------

Considering the following command:

    my-script.php -u myname -p mypassord
    my-script.php --username myname --password mypassord
    my-script.php -u myname -p
    my-script.php -u myname -p mypassord -v
    my-script.php -u myname -p mypassord --verbose
    my-script.php -u myname -p mypassord -h server.example.com
    my-script.php -u myname -p mypassord --host server.example.com

Where:

    -u USERNAME, --username USERNAME   User name (required)
    -p PASSWORD, --password PASSWORD   Password (required)
    -u HOST, --host HOST               Host (optional, defaults to 127.0.0.1)
    -v, --verbose                      Verbose switch (optional)

Usage:

```php
$options = \Cli\Helpers\Parameter::getFromCommandLine(array(
    'host'     => new Parameter('h', 'host'    , '127.0.0.1'),
    'username' => new Parameter('u', 'username', Parameter::VALUE_REQUIRED),
    'password' => new Parameter('p', 'password', Parameter::VALUE_REQUIRED),
    'verbose'  => new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE),
));
var_dump( $options );
```

Executing this with `my-script.php -u myname -p mypassord` or `my-script.php
--username=myname --password=mypassord` will display:

```php
array(4) {
    'host'     => '127.0.0.1',
    'username' => 'myusername',
    'password' => 'mypassword',
    'verbose'  => false,
}
```

Executing this with `my-script.php -u myname -p mypassord -h
server.example.com -v` will display:

```php
array(4) {
    'host'     => 'server.example.com',
    'username' => 'myusername',
    'password' => 'mypassword',
    'verbose'  => true
}
```


Troubleshooting
---------------

Executing this with `my-script.php -u myname` will result in a
`Cli\Helpers\Exception\MissingRequiredParameter` exception because the
options '-p/--password' is required.

Executing this with `my-script.php -u myname -p` will result in a
`Cli\Helpers\Exception\MissingParameterValue` exception because
the options '-p/--password' requires a value.

Executing this with `my-script.php -u myname -p password --passsword
password` will result in a
`Cli\Helpers\Exception\ConflictingParameters` exception because
the options '-p/--password' cannot be used with the short and long switches
cannot be used simultaneously.
