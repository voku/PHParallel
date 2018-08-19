<?php

require __DIR__ . '/../Stub/Task.php';
require __DIR__ . '/../Stub/ChildThread.php';

class ThreadTest extends \PHPUnit_Framework_TestCase
{

  public function testEnvironment()
  {
    $parallel = new \voku\ParallelHelper\Parallel\Parallel();

    self::assertTrue($parallel->isSupported());
  }

  public function testRunnable()
  {
    $task = new Task();
    $thread = new \voku\ParallelHelper\Thread\Thread($task);
    $thread->start();
    $thread->wait();
    self::assertGreaterThan(0, $thread->getPid());
  }

  public function testSubclass()
  {
    $task = new ChildThread();
    $thread = new \voku\ParallelHelper\Thread\Thread($task);
    $thread->start();
    $thread->wait();
    self::assertGreaterThan(0, $thread->getPid());
  }

}
