<?php

namespace War\Card;

abstract class abstractCard
{

  /**
   * The numeric value of a card
   */
  protected $value = null;

  public function __construct()
  {
    return $this;
  }

  /**
   * returns the value of this card
   */
  public function getValue() { return $this->value; }

}

?>
