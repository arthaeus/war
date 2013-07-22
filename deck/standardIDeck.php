<?php

namespace War\Deck;

require_once( "abstractIDeck.php" );
require_once( "../interfaces.php" );
require_once( "../card/standardICard.php" );

use \stdClass               as stdClass;
use War\Deck\abstractIDeck;
use War\Deck\EmptyIDeckException;
use War\Interfaces\IDeck;
use War\Card\standardICard;


/**
 * Class to represent a standard deck of playing cards (hearts,spades,diamonds,clubs. 2 through ace)
 */
class standardIDeck extends abstractIDeck implements IDeck
{

  /**
   * Values for the ace card should be 1 or 14
   */
  const ACE_VALUE = 15;
  /**
   * An IDeck of cards extending abstractDeck needs to be responsible for providing functionality for building a deck of cards.  
   * The IDeck may be standard playing cards, old maid cards, uno cards, etc.  Each is different, and each must be built differently
   * The buildDeck function (for the war IDeck) will be a facade in front of helper functions that will ultimately build and return
   * an IDeck of cards
   */

  public function __construct()
  {
    abstractIDeck::__construct();
  }

  public function buildIDeck()
  {


    $suits = array( "hearts" , "diamonds" , "spades" , "clubs" );

    /**
     * For a standard deck of cards, create each of the 4 suits
     */
    foreach( $suits as $suit )
    {
      /**
       * If the ace value is not set properly, an exception will be raised
       */
      try
      {
        $this->buildSuit( $suit );
      }
      catch(InvalidAceValueException $e)
      {
        /**
         * the exception handler will create an ace card with a value of 14 if the ACE_VALUE is not properly set
         */
        $aceCard = $e->handleException();
        $this
          ->ICardCollection
          ->setICard( $aceCard );
      }
    }
    
    $this
      ->ICardCollection
      ->setState( self::STATE_FULL );

    return true;
  }

  private function buildSuit( $suit )
  {

    /**
     * Create all cards, except for the ace.
     */
    $ICards = array();
    for( $cardValue = 2; $cardValue < 14; $cardValue++ )
    {
      /**
       * give the appropriate title to the face cards
       */
      if( $cardValue > 10 && $cardValue < 14 )
      {
        switch( $cardValue )
        {
          case "11":
            $title = "jack of " . $suit;
            break;
          case "12":
            $title = "queen of " . $suit;
            break;
          case "13":
            $title = "king of " . $suit;
            break;
        }
      }
      else
      {
        /**
         * The title of a regular number card is just its number.  The title for a card worth 11 is Jack, 12 = Queen, etc
         */
        $title = $cardValue . " of $suit";
      }
      /**
       * create the card, and then insert it into the returnCards array
       */
      $ICard = new standardICard( $suit , $cardValue , $title );

      $ICards[] = $ICard;

    }

    $this
      ->ICardCollection
      ->addICards( $ICards );


    /**
     * Take care of creating the ace
     */
    if( self::ACE_VALUE != 1 && self::ACE_VALUE != 14 )
    {
      throw new InvalidAceValueException( "Ace value must be 1 or 14.  Check the const at the top of standardIDeck.php.  I will default to the value for ace as being set to 14" );
    }

    $ICard = new standardICard( $suit , self::ACE_VALUE , "ace of " . $suit );
    $this->ICardCollection->setICard( $ICard );

    return true;
  }

}

class InvalidAceValueException extends \Exception
{
  /**
   * handle the exception by setting ace to high value
   */
  public function handleException()
  {
    $traceArray = $this->getTrace();
    $suit = $traceArray[0]['args'][0];
    return new standardICard( $suit , 14 , "ace of " . $suit );
  }
}


?>
