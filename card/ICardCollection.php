<?php

namespace War\Card;

require_once( "../interfaces.php" );
require_once( "../base.php" );

use War\abstractBase;
use War\Interfaces\ICardCollection as cardCollectionInterface;
use War\Interfaces\ICard as ICard;


class ICardCollection extends abstractBase implements cardCollectionInterface
{

  protected $ICards = array();

  /**
   * Get all ICards from this collection
   */
  public function getICards()
  {
    return $this->ICards;
  }

  /**
   * Append ICards to the already existing ICards array
   */
  public function addICards( $ICards )
  {
    $this->ICards = array_merge( $this->ICards , $ICards );
  }

  /**
   * Set all ICards for this collection.  Current collection will be overwritten.
   */
  public function setICards( $ICards )
  {
    $this->ICards = $ICards;
    return true;
  }

  /** 
   * Adds one ICard to the ICardCollection
   */
  public function setICard( ICard $ICard )
  {
    $this->ICards[] = $ICard;
    return true;
  }

  /** 
   * Pops one ICard from the ICardCollection
   */
  public function getICard()
  {
    /**
     * Shift off one card, and return it.
     */
    $returnCard = array_shift( $this->ICards ); 
  
    if( !$returnCard )
    {
      throw new NoICardsException( "Tried to pop, but deck is empty \n" );
    }
  
    if( count( $this->ICards ) == 0 )
    {
      $this->setState( self::STATE_EMPTY );
    }
    else
    {
      $this->setState( self::STATE_NOT_EMPTY );
    } 

    return $returnCard;
  }

}

class NoICardsException extends \Exception
{}

?>
