Parallel.php - Simple multitasking library
==========================================

[![Build Status](https://travis-ci.org/voku/PHParallel.svg)](https://travis-ci.org/voku/PHParallel)
[![Coverage Status](https://coveralls.io/repos/github/voku/PHParallel/badge.svg?branch=master)](https://coveralls.io/github/voku/PHParallel?branch=master)

* [Requirements](#requirements)
* [Usage](#usage)
* [Installation](#installation)
* [License](#license)
* [Author](#author)

Requirements
------------

* UNIX
* CGI or CLI version of PHP5.5+
* Compiled with **--enable-pcntl**

Usage
-----

### Run multiple tasks asynchronously

``` php
<?php

use voku\ParallelHelper\Parallel\Parallel;

require __DIR__ . "/vendor/autoload.php";

$parallel = new Parallel();
$parallel->run([
    function () {
        echo "task#1 start\n";
        sleep(2);
        echo "task#1 end\n";
    },
    function () {
        echo "task#2 start\n";
        sleep(3);
        echo "task#2 end\n";
    }
]);

echo "Done\n";
```

will outputs like this:

```
task#1 start
task#2 start
task#1 end
task#2 end
Done
```

#### Get an array containing all the results of each job

##### Notes:

1. Internally unix socket is used to receive a value from child process.
2. The result must be serializable.

``` php
<?php

use voku\ParallelHelper\Parallel\Parallel;

require __DIR__ . "/vendor/autoload.php";

$parallel = new Parallel();
$values = $parallel->values([
    function () {
        return 'item';
    },
    'foo' => function () {
        // return value must be serializable
        return new \DateTime('2013-01-01');
    }
]);

var_dump($values);
```

will output like this:

```
array(2) {
  [0] =>
  string(4) "item"
  'foo' =>
  class DateTime#6 (3) {
    public $date =>
    string(19) "2013-01-01 00:00:00"
    public $timezone_type =>
    int(3)
    public $timezone =>
    string(10) "Asia/Tokyo"
  }
}
```

### Process multiple values asynchronously

``` php
<?php

use voku\ParallelHelper\Parallel\Parallel;

require __DIR__ . "/vendor/autoload.php";

$parallel = new Parallel();
$parallel->each(['a', 'b'], function ($str) {
    echo "start task with '$str'\n";
    sleep(3);
    echo "finish task with '$str'\n";
});
```

#### Get an array containing all the results of each job

##### Notes:

1. Internally unix socket is used to receive a value from child process.
2. The result must be serializable.

``` php
<?php

use voku\ParallelHelper\Parallel\Parallel;

require __DIR__ . "/vendor/autoload.php";

$parallel = new Parallel();
$values = $parallel->map([1, 2, 3], function ($value) {
    return $value * 2;
});

var_dump($values);
```

will output like this:

```
array(3) {
  [0] =>
  int(2)
  [1] =>
  int(4)
  [2] =>
  int(6)
}
```

### Java like `Thread` and `Runnable`

``` php
<?php

use voku\ParallelHelper\Thread\Runnable;
use voku\ParallelHelper\Thread\Thread;

require __DIR__ . "/vendor/autoload.php";

class Job implements Runnable
{
    public function run()
    {
        // do your job
    }
}

class AnotherJob extends Thread
{
    public function run()
    {
        // do your another job
    }
}

$thread1 = new Thread(new Job());
$thread2 = new AnotherJob();

$thread1->start();
$thread2->start();

$thread1->wait();
$thread2->wait();
```



Installation
------------

Update or create composer.json.

``` json
{
    "require": {
        "voku/phparallel": "0.1.1"
    }
}
```

License
-------

The MIT License

Author
------

Kazuyuki Hayashi (@voku\ParallelHelper)
