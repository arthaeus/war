<?php

namespace War\Player;

require_once( "../interfaces.php" );
require_once( "../card/ICardCollection.php" );

use \stdClass               as stdClass;
use War\Interfaces\IPlayer;
use War\Interfaces\ICard;
use War\Card\ICardCollection;


abstract class abstractIPlayer implements IPlayer
{

  /**
   * Represents the IPlayers hand
   */
  protected $ICardCollection = null;

  protected $name = null;

  public function __construct()
  {
    $this->ICardCollection = new ICardCollection();
  }

  public function getName() { return $this->name; }
  public function setName( $name ) { $this->name = $name; return; }

  /** 
   * This function will pop and return a card from the player's hand
   *
   * The ITurn will use this function to interact with the IPlayer.
   */
  public function getICard()
  {
    return array_shift( $this->ICardCollection );
  }

  /** 
   * Will add one single ICard to the players hand
   */
  public function setICard( ICard $ICard )
  {
    $this
      ->ICardCollection
      ->setICard( $ICard );
    return true;
  }
  
  /** 
   * Will add multiple ICards to the players hand
   */
  public function setICards( $ICards = array() )
  {}

  public function getICardCollection()
  {
    return $this->ICardCollection;
  }

}

?>
