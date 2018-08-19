<?php

namespace voku\ParallelHelper\Parallel;

use voku\ParallelHelper\Thread\Runnable;

/**
 * Simple implementation for thread
 */
class SimpleWorker implements Runnable
{
  /**
   * @var callable
   */
  private $callable;

  /**
   * @var array
   */
  private $args = [];

  /**
   * @param callable $callable
   * @param array    $args
   */
  public function __construct(callable $callable, array $args = [])
  {
    $this->callable = $callable;
    $this->args = $args;
  }

  /**
   * {@inheritdoc}
   */
  public function run()
  {
    $return = call_user_func_array($this->callable, $this->args);

    return $return;
  }
}
