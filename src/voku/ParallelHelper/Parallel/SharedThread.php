<?php

namespace voku\ParallelHelper\Parallel;

use voku\ParallelHelper\Thread\Thread;

/**
 * Send return value to IPC server
 */
class SharedThread extends Thread
{

  /**
   * {@inheritdoc}
   */
  public function start()
  {
    $this->fork();
    $this->waitChild();
  }

  /**
   * {@inheritdoc}
   */
  public function run()
  {
    if ($this->runnable) {
      return $this->runnable->run();
    }

    return null;
  }

  protected function waitChild()
  {
    if (!$this->pid) {
      $file = sys_get_temp_dir() . '/parallel' . posix_getppid() . '.sock';
      $address = 'unix://' . $file;
      $result = $this->run();

      $client = stream_socket_client($address);
      if ($client) {
        stream_socket_sendto($client, serialize([posix_getpid(), $result]));
        fclose($client);
      }

      posix_kill(posix_getpid(), SIGHUP);

      return;
    }
  }
}
