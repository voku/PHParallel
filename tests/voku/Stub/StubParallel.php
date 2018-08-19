<?php

use voku\ParallelHelper\Parallel\Server;
use voku\ParallelHelper\Parallel\SimpleWorker;

class StubParallel extends \voku\ParallelHelper\Parallel\Parallel
{

  /**
   * @param callable[] $workers
   *
   * @return array
   */
  public function values($workers)
  {
    Server::getInstance()->listen();

    $threads = array_map(
        function (callable $worker) {
          return new CoverageCollectorThread(new SimpleWorker($worker));
        }, $workers
    );

    $result = $this->fetch($threads);
    Server::getInstance()->close();

    return $result;
  }

} 