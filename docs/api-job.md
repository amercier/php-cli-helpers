\Cli\Helpers\Job
================

Usage
-----

On successful jobs:
```php
\Cli\Helpers\Job::run('Doing awesome stuff', function() {
    ...  // awesome stuff
});
```
```
Doing awesome stuff... OK
```

On unsuccessful jobs:
```php
\Cli\Helpers\Job::run('Fighting Chuck Norris', function() {
    ...  // throws a RoundHouseKickException('You've received a round-house kick', 'face')
});
```
```
Fighting Chuck Norris... NOK - You've received a round-house kick in the face
```
