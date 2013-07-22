<?php

namespace War\Card;

require_once( "../interfaces.php" );
require_once( "abstractCard.php" );
use War\Card\abstractCard as abstractCard;
use War\Interfaces\ICard    as ICard;


class standardICard extends abstractCard implements ICard
{

  /**
   * The suit of a card
   */
  protected $suit  = null;

  /**
   * The title for a card. (2 = 2, 11 = Jack, 13 = King, 7 = 7, etc)
   */
  protected $title = null;

  public function __construct( $suit , $value , $title )
  {
    $this->suit  = $suit;
    $this->value = $value;
    $this->title = $title;
    return $this;
  }

  /**
   * returns the value of this card
   */
  public function getSuit() { return $this->suit; }

  /**
   * Get the title
   */
  public function getTitle() { return $this->title; }

}

?>
