<?php

namespace War;

abstract class abstractBase
{
  protected $state = null;

  public function getState() { return $this->state; }
  public function setState( $state ) { $this->state = $state; return; }
}

?>
