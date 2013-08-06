<?php

namespace War\Turn;

require_once( "../interfaces.php" );
require_once( "../game/war.php" );
require_once( "../card/ICardCollection.php" );

use \stdClass               as stdClass;
use War\Interfaces\ITurn;
use War\Interfaces\IGame;
use War\Card\standardICard;
use War\Card\ICardCollection;
use War\Card\NoICardsException;
use War\Game\war;

class abstractITurn
{

  protected $IGame = null;

  /**
   * The cards that are in play for this turn
   */
  protected $ICardCollection = null;

  public function __construct()
  {
    $this->ICardCollection = new ICardCollection();
  }

  public function setIGame( IGame $IGame ) { $this->IGame = $IGame; return true; }
  public function getIGame() { return $this->IGame; }

  public function getICardCollection() { return $this->ICardCollection; }
 
}

/**
 * The two IPlayers must battle
 */
class battleWarITurn extends abstractITurn implements ITurn
{

  /**
   * So that we can have access to the original ITurn (which has accesss to the IGame, IPlayers)
   */
  protected $originalITurn = null;

  public function __construct( ITurn $ITurn )
  {
    $this->originalITurn = $ITurn;
    abstractITurn::__construct();
    return $this;
  }

  public function play()
  {

    /**
     * Get the IPlayers
     */

    $IPlayers = $this
      ->originalITurn
      ->getIGame()
      ->getIPlayers();


    $player0Name = $IPlayers[0]->getName();
    $player1Name = $IPlayers[1]->getName();
    echo "Starting a new *WAR TURN* THE CARD COUNTS ARE: \n$player0Name = " . count( $IPlayers[0]->getICardCollection()->getICards() ) . " \nTO \n$player1Name = " . count( $IPlayers[1]->getICardCollection()->getICards() ) . "\n\n";

    /**
     * This is the winner's pool so far
     */
    $ITurnCards = $this
      ->originalITurn
      ->getICardCollection()
      ->getICards();

      echo "THE WAR MATCHUP IS BETWEEN CARDS: \n";
      echo $ITurnCards[0]->getTitle();

      echo " \nVS \n";
      echo $ITurnCards[1]->getTitle();
      echo " \n\n";



    /**
     * Put the war cards (4 each) in the winners pool (which is this ITurn's ICardCollection)
     */

    /**
     * playerIndex will be used in the loop to determine which player is laying down their cards
     */
    $playerIndex = 0;

    /**
     * The card that will determine the winner of this war.  This will be the 4th card if the player has enough cards.
     */
    $player1Card = $player2Card = null;


    for( $warCards = 0 ; $warCards < 8; $warCards++ )
    {

      /**
       * in a war, the 4th card is turned face up.  This is when the counter is at warCards = 3 for player 1
       */
      try
      {
        $warICard = $IPlayers[$playerIndex]->getICardCollection()->getICard();
      }
      catch( NoICardsException $e )
      {
        $winnerIndex = ( $playerIndex == 0 ) ? 1 : 0;
        $loserName  = $IPlayers[$playerIndex]->getName();
        $winnerName = $IPlayers[$winnerIndex]->getName();
        echo "$loserName does not have enough cards for a proper war!  Player $winnerName has won!\n";
        exit;
      }

      if( $warCards == 3 )
      {
        /**
         * the card that player1 will play in the war
         */
        $player1Card = $warICard;

        /**
         * switch players.  player at index1 (player2) will now lay down their cards
         */
        $playerIndex = 1;
      }
      /**
       * in a war, the 4th card is turned face up.  This is when the counter is at warCards = 8 for player 2
       */
      else if( $warCards == 7 )
      {
        $player2Card = $warICard;
      }

      $this
        ->ICardCollection
        ->setICard( $warICard );
    }

    /**
     * Player 1 wins the war
     */
    if( $player1Card->getValue() > $player2Card->getValue() )
    {
      $winnerIndex = "0";
    }
    /**
     * Player 2 wins the war
     */
    else if( $player2Card->getValue() > $player1Card->getValue() )
    {
      $winnerIndex = "1";
    }
    /**
     * There will be another war.  Return the cards from this war, and war again
     */
    else
    {
      $winnerIndex = null;
      echo "ANOTHER WAR!! \n";
    }

    $winner = $warStat = new stdClass;
    $winner->winnerIndex = $winnerIndex;
    $winner->ICardCollection = $this->ICardCollection->getICards();

    /**
     * If there is a winner to the war, log it in the IGame.  The game will last until all cards are won, or until a player wins n wars
     */
    if( $winnerIndex !== null )
    { 
      $warStat->playerName = $IPlayers[$winnerIndex]->getName();
      war::addWarStat( $warStat );
    }
    return $winner;

  }
}

/**
 * Class to represent a default turn.  The turn can cascade into battles
 */
class normalWarITurn extends abstractITurn implements ITurn
{
 public function play()
  {

    /**
     * Get the IPlayers
     */

    $IPlayers = $this
      ->IGame
      ->getIPlayers();


    $player0Name = $IPlayers[0]->getName();
    $player1Name = $IPlayers[1]->getName();

    echo "Starting a new *NORMAL TURN* THE CARD COUNTS ARE: \n$player0Name = " . count( $IPlayers[0]->getICardCollection()->getICards() ) . " \nTO \n$player1Name = " . count( $IPlayers[1]->getICardCollection()->getICards() ) . "\n\n";
    
    /**
     * Get player 1 card
     */
    $player1Card = $IPlayers[0]
      ->getICardCollection()
      ->getICard();

    /**
     * Get player 2 card
     */
    $player2Card = $IPlayers[1]
      ->getICardCollection()
      ->getICard();

    echo "Matchup for NORMAL turn is \n$player0Name = " . $player1Card->getTitle() . "\n VS\n$player1Name = " . $player2Card->getTitle() . "\n\n";

    /**
     * Put the cards in the winners pool (which is this ITurn's ICardCollection)
     */
    $this
      ->ICardCollection
      ->setICard( $player1Card );

    $this
      ->ICardCollection
      ->setICard( $player2Card );


    /**
     * This is the winner's pool so far
     */
    $ITurnCards = $this
      ->ICardCollection
      ->getICards();

    /**
     * If the card values are the same, time for war
     */
    if( $player1Card->getValue() == $player2Card->getValue() )
    {
      $battleWarITurn = new battleWarITurn( $this );

      /**
       * Do the war
       */
      $battleResult = $battleWarITurn->play();

      /**
       * If there are multiple wars, save the resulting ICards into this tempWarCards variable.  
       * When the final war is complete, add tempWarCards to the winners hand
       */
      $tempWarCards = array();
      $tempWarCards = $battleResult->ICardCollection;
      $tempWarCards = array_merge( $tempWarCards , $ITurnCards );

      /**
       * The battleWarITurn will return a stdClass.  One property of this stdClass is the winner index, the other property is the cards that were
       * played in the battle
       *
       * if player[0] wins, winnerIndex will be 0, and if player[1] wins, winnerIndex will be  1
       * if the players tie, the winnerIndex will be null
       */
      while( $battleResult->winnerIndex === null )
      {
        /**
         * Do the war.  
         * Each time there is a war within a war, it is essentially another turn.  Create a new turn, and play the turn.
         * merge the results into the temp variable, and when the final war is fought, the winner will get all of the cards from
         * all of the battles.
         */
        $battleWarITurn = new battleWarITurn( $this );
        $battleResult = $battleWarITurn->play();
        $tempWarCards = array_merge( $tempWarCards , $battleResult->ICardCollection );
        echo "There will be another war! \n";
      }


      /**
       * After the wars are resolved, add the cards to the winners hand
       */
      $IPlayers[$battleResult->winnerIndex]
        ->getICardCollection()
        ->addICards( $tempWarCards );
    }
    /**
     * If player[0] wins, add the cards to their hand
     */
    else
    {
      if( $player1Card->getValue() > $player2Card->getValue() )
      {
        $IPlayers[0]
          ->getICardCollection()
          ->setICard( $ITurnCards[0] ); 
        
        $IPlayers[0]
          ->getICardCollection()
          ->setICard( $ITurnCards[1] ); 

      }
    /**
     * If player[1] wins, add the cards to their hand
     */
      else if( $player2Card->getValue() > $player1Card->getValue() )
      {
        $IPlayers[1]
          ->getICardCollection()
          ->setICard( $ITurnCards[0] ); 
        
        $IPlayers[1]
          ->getICardCollection()
          ->setICard( $ITurnCards[1] ); 
      }
    }


    /**
     * if either of the players are out of cards, return false to the IGame
     */
    if( count( $IPlayers[0]->getICardCollection()->getICards() ) == 0 || count( $IPlayers[1]->getICardCollection()->getICards() ) == 0 )
    {
      return false;
    }


    return true;
  }

}


?>
