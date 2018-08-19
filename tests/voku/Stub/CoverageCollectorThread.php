<?php

class CoverageCollectorThread extends \voku\ParallelHelper\Parallel\SharedThread
{

  public function start()
  {
    $this->fork();

    $this->waitChild();
  }

} 