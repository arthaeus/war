<?php

namespace War\Deck;

require_once( "../base.php" );
require_once( "../interfaces.php" );
require_once( "../card/ICardCollection.php" );

use \stdClass            as stdClass;
use War\abstractBase;
use War\Interfaces\IDeck; 
use War\Interfaces\ICard;
use War\Card\ICardCollection;
use War\Card\NoICardsException;


abstract class abstractIDeck extends abstractBase implements IDeck
{

  protected $ICardCollection = null;

  public function __construct()
  {
    $this->ICardCollection = new ICardCollection();
  }

  /**
   * After this function is called, the container of ICards will be in a random order
   */
  public function shuffleIDeck( stdClass $options = null )
  {
    /**
     * To shuffle the IDeck, get the ICards from the collection, shuffle them, and then set them back
     */
    
    $ICards = $this->ICardCollection->getICards(); 
    
    shuffle( $ICards );

    $this
      ->ICardCollection
      ->setICards( $ICards );

    return true;
  }

  /**
   * The IDeck will be set to its default state.  The IDeck will contain all ICards, and they will not be
   * shuffled.
   */
  public function resetIDeck()
  {
    $this->ICardCollection = new ICardCollection();
    $this->buildIDeck();
    return true;
  }

  /**
   * Will pop and return an ICard from the IDeck.  If the IDeck is empty, return false
   */
  public function getICard( stdClass $options = null )
  {
    /**
     * Shift off one card, and return it.
     */

    try
    {
      $returnCard = $this
        ->ICardCollection
        ->getICard();
    }
    catch( NoICardsException $e )
    {

    }

    return $returnCard;
  }

  /**
   * Will add an ICard to the IDeck.
   */
  public function setICard( ICard $ICard )
  {
    $this
      ->ICardCollection
      ->setICard( $ICard );

    return true;
  }


  /** 
   * Set all ICards for this collection.  Current collection will be overwritten.
   */
  public function setICards( $ICards )
  {
    $this
      ->ICardCollection
      ->setICards( $ICards );
  }

  /** 
   * Append ICards to the already existing ICards array
   */
  public function addICards( $ICards )
  {
    $this
      ->ICardCollection
      ->addICards( $ICards );
  }

  /** 
   * Get all ICards from this collection
   */
  public function getICards()
  {
    return $this
      ->ICardCollection
      ->getICards();
  }

  /**
   * Get the full ICardCollection object
   */
  public function getICardCollection()
  {
    return $this->ICardCollection;
  }


}

?>
