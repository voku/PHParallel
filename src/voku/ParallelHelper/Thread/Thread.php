<?php

namespace voku\ParallelHelper\Thread;

class Thread implements Runnable
{
  /**
   * @var int
   */
  protected $pid;

  /**
   * @var int[]
   */
  protected $processes;

  /**
   * @var Runnable
   */
  protected $runnable;

  /**
   * @var int|null <p>The maximum number of processes to run simultaneously.</p>
   */
  protected $process_limit;

  /**
   * @param Runnable $runnable
   */
  public function __construct(Runnable $runnable = null)
  {
    $this->runnable = $runnable;

    if (is_readable('/proc/cpuinfo')) {
      exec("cat /proc/cpuinfo | grep processor | wc -l", $processors);
      $this->setProcessLimit(reset($processors));
    }
  }

  /**
   * Sets how many processes at most to execute at the same time.
   *
   * A fluent interface is provided so that you can chain multiple workers
   * in one call.
   *
   * @param int|null $process_limit The limit, minimum of 1
   *
   * @see DocBlox_Parallel_Manager::addWorker() for an example
   *
   * @return self
   */
  public function setProcessLimit($process_limit)
  {
    if ($process_limit === null) {
      $this->process_limit = $process_limit;

      return $this;
    }

    if ($process_limit < 1) {
      throw new \InvalidArgumentException(
          'Number of simultaneous processes may not be less than 1'
      );
    }

    $this->process_limit = $process_limit;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function run()
  {
    if ($this->runnable) {
      $this->runnable->run();
    }

    return null;
  }

  /**
   * Start a thread
   */
  public function start()
  {
    $this->fork();

    if (!$this->pid) {
      $this->run();

      if (strpos(PHP_SAPI, 'cli') === 0) {
        exit(0);
      }

      pcntl_wexitstatus(null);
    }
  }

  /**
   * Waits on a forked child
   */
  public function wait()
  {
    if ($this->pid) {
      pcntl_waitpid($this->pid, $status);
    }
  }

  /**
   * Returns process id
   *
   * @return int
   */
  public function getPid()
  {
    return $this->pid;
  }

  /**
   * Forks the currently running process
   *
   * @throws \RuntimeException
   */
  protected function fork()
  {
    // fork the process and register the PID
    $this->pid = pcntl_fork();
    if ($this->pid < 0) {
      throw new \RuntimeException('Unable to fork child process');
    }

    if ($this->pid > 0) {
      $this->processes[] = $this->pid;
      if (count($this->processes) >= $this->getProcessLimit()) {
        pcntl_waitpid(array_shift($this->processes), $status);
      }
    }
  }

  /**
   * Returns the current limit on the amount of processes that can be
   * executed at the same time.
   *
   * @return int|null
   */
  public function getProcessLimit()
  {
    return $this->process_limit;

  }
}
