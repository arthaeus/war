<?php

namespace War\Deck;

require_once( "abstractIDeck.php" );
require_once( "../interfaces.php" );
require_once( "../card/unoICard.php" );

use \stdClass               as stdClass;
use War\Deck\abstractIDeck;
use War\Deck\EmptyIDeckException;
use War\Interfaces\IDeck;
use War\Card\unoICard;


/**
 * Class to represent a deck of uno ICards
 */
class unoIDeck extends abstractIDeck implements IDeck
{

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


    $suits = array( "green" , "red" , "blue" , "yellow" );

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
      catch( \Exception $e )
      {}
    }
    /**
     * after all of the suited ICards are created, the buildIDeck function is responsible for building the wild cards
     * this is done here, and the cards are then added to the ICardCollection
     */

    $wildCards = $this->buildWildICards();

    $this
      ->ICardCollection
      ->addICards( $wildCards );

    $this
      ->ICardCollection
      ->setState( self::STATE_FULL );

    return true;
  }

  /**
   * At this point, all of the suited ICards have been created.  Now, make the 8 wild cards.  The wild cards have no suit and i will just give them the value
   * of 13 and 14
   * 
   * There will be 4 wild cards and 4 wild draw cards.
   */
  private function buildWildICards()
  {


    $wildCardValue = 13;
    for( $cardCount = 0 ; $cardCount < 8; $cardCount++ )
    {
      
      $wildCardSuit = "WILD";

      $wildCardTitle = "WILD";

      if( $cardCount > 3 )
      {
          $wildCardTitle = "DRAW WILD";
          $wildCardValue = 14;
      }

      $ICard = new unoICard( $wildCardSuit , $wildCardValue , $wildCardTitle );
      $ICards[] = $ICard;
    }

    return $ICards;
  }

  /**
   * in uno the deck of cards is as such:
   *
        19 Blue Cards - 0 x1 and 1 to 9 x2
        19 Green Cards - 0 x1 and 1 to 9 x2
        19 Red Cards - 0 x1 and 1 to 9 x2
        19 Yellow Cards - 0 x1 and 1 to 9 x2
        8 Draw Two cards - 2 each in Blue, Green, Red and Yellow
        8 Reverse Cards - 2 each in Blue, Green, Red and Yellow
        8 Skip Cards - 2 each in Blue, Green, Red and Yellow
        4 Wild Cards
        4 Wild Draw 4 cards 
   */
  private function buildSuit( $suit )
  {

    /**
     * Create all cards, except for the ace.
     */
    $ICards = array();
    for( $cardValue = 0; $cardValue < 13; $cardValue++ )
    {

      /**
       * the numCards variable will determine how many of these cards are created.
       */
      $numCardsToCreate = 2;

      /**
       * for the special case cards.  Special cases are: 
       * there is only one zero card per color.
       * there are 2 [draw 2,reverse,skip] cards per color
       *
       * if we are on zero, there will only be one zero card for each suit
       */
      if( ( $cardValue == 0 ) || ( $cardValue >= 10 && $cardValue <= 12 ) )
      {
        switch( $cardValue )
        {
          case "0":
            $numCardsToCreate = 1;
            $title = "zero of " . $suit;
            break;
          case "10":
            $title = "draw two of " . $suit;
            break;
          case "11":
            $title = "reverse of " . $suit;
            break;
          case "12":
            $title = "skip of " . $suit;
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
      for( $cardsCreated = 0 ; $cardsCreated < $numCardsToCreate; $cardsCreated++ )
      {
          $ICard = new unoICard( $suit , $cardValue , $title );
          $ICards[] = $ICard;
      }
    }
    /**
     * Now, add all 108 cards to the uno deck
     */
    $this
      ->ICardCollection
      ->addICards( $ICards );
    return true;
  }

}

$u = new unoIDeck();
$u->buildIDeck();
print_r( $u );

?>
