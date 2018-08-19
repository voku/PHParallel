<?php

use voku\ParallelHelper\Parallel\Parallel;

require __DIR__ . '/../Stub/CoverageCollector.php';
require __DIR__ . '/../Stub/CoverageCollectorThread.php';
require __DIR__ . '/../Stub/StubParallel.php';

class ParallelTest extends PHPUnit_Framework_TestCase
{

  public function testRun()
  {
    $parallel = new Parallel();
    $parallel->run(
        [
            function () {
            },
            function () {
            },
        ]
    );
  }

  public function testValues()
  {
    $parallel = new Parallel();
    $values = $parallel->values(
        [
            function () {
              return 'item';
            },
            'foo' => function () {
              return new \DateTime('2013-01-01');
            },
        ]
    );

    self::assertEquals('item', $values[0]);
    self::assertInstanceOf('DateTime', $values['foo']);
  }

  public function testEach()
  {
    $parallel = new Parallel();
    $parallel->each(
        [1, 2, 3], function ($value) {
    }
    );
  }

  public function testMap()
  {
    $parallel = new Parallel();
    $values = $parallel->map(
        [1, 2, 3], function ($value) {
      return $value * 2;
    }
    );

    self::assertEquals([2, 4, 6], $values);
  }

  public function testCoverageOnChildProcess()
  {
    $thread = new voku\ParallelHelper\Thread\Thread(new CoverageCollector());
    $thread->start();
    $thread->wait();
  }

  public function testCoverageOnChildProcessWithServer()
  {
    $parallel = new StubParallel();
    $values = $parallel->values(
        [
            function () {
              return 'item';
            },
            'foo' => function () {
              return new \DateTime('2013-01-01');
            },
        ]
    );

    self::assertEquals('item', $values[0]);
    self::assertInstanceOf('DateTime', $values['foo']);
  }

} 