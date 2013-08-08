<?php

namespace War;

require_once( "config/config.php" );

use War\Config\config;

abstract class abstractBase
{
  protected $state = null;

  public function getState() { return $this->state; }
  public function setState( $state ) { $this->state = $state; return; }
}

?>
