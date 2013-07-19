Full installation instructions
==============================

1. Prerequisites
----------------

### PHP >= 5.3.2

To ensure you have a compatible version of PHP, execute:

    php -v

### curl

To ensure you have curl installed, execute:

    curl --version


2. Composer
-----------

Go to your folder root and execute:

    curl -sS https://getcomposer.org/installer | php

If the installation work correctly, this should display:

    #!/usr/bin/env php
    All settings correct for using Composer
    Downloading...

    Composer successfully installed to: /mnt/hgfs/Documents/sandbox/vcloud/composer.phar
    Use it: php composer.phar

At this stage, you should have the following files in your project's folder:

    $ ls -l
    - composer.phar (~900 kB)


3. composer.json
----------------

Go to your folder root and execute:

    php composer.phar init

This will help you to create the composer.json interactively. Once you reach the
question _"Would you like to define your dependencies (require) interactively
[yes]?"_, **answer yes**. Then, enter the package **amercier/cli-helpers**.
Then, select _amercier/cli-helpers_ (usually **0**), and answer **1.*** as version
number.

At this stage, you should have the following files in your project's folder:

    $ ls -l
    - composer.phar (~900 kB)
    - composer.json (7 B)


4. Install packages
-------------------

Go to your folder root and execute:

    php composer.phar install

If the installation work correctly, this should display something like:

    Loading composer repositories with package information
    Initializing PEAR repository http://pear.php.net
    Updating dependencies (including require-dev)
      - Installing amercier/cli-helpers (1.0.0 8f2e517)
        Cloning 8f2e517dd3e5c858d7729148bac526414d1444e3
    
      - Installing pear-pear.php.net/xml_util (1.2.1)
        Downloading: 100%
      - Installing pear-pear.php.net/console_getopt (1.3.1)
        Downloading: 100%
      - Installing pear-pear.php.net/structures_graph (1.0.4)
        Downloading: 100%
      - Installing pear-pear.php.net/archive_tar (1.3.11)
        Downloading: 100%
      - Installing pear-pear.php.net/pear (1.9.4)
        Downloading: 100%
      - Installing pear-pear.php.net/net_url2 (2.0.0)
        Downloading: 100%
      - Installing pear-pear.php.net/http_request2 (2.1.1)
        Downloading: 100%
    Writing lock file
    Generating autoload files

At this stage, you should have the following files in your project's folder:

    $ ls -l
    - composer.phar (~900 kB)
    - composer.json (7 B)
    - vendor
      - autoload.php (~182 B)
      - composer
        - autoload_classmap.php (~150 B)
        - autoload_namespaces.php (~150 B)
        - autoload_real.php (~1 kB)
        - ClassLoader.php (~7 kB)
      - amercier
        - cli-helpers
          - ...
