<?php

use voku\ParallelHelper\Parallel\SimpleWorker;

class SimpleWorkerTest extends PHPUnit_Framework_TestCase
{

  public function testWorker()
  {
    $worker = new SimpleWorker(
        function () {
          return 10;
        }
    );

    self::assertEquals(10, $worker->run());
  }

  public function testArguments()
  {
    $worker = new SimpleWorker(
        function ($a, $b) {
          self::assertEquals('a', $a);
          self::assertEquals('b', $b);

          return 10;
        }, ['a', 'b']
    );

    self::assertEquals(10, $worker->run());
  }

} 