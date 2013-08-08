<?php

namespace War\Game;
require_once( "../interfaces.php" );
require_once( "../player/warIPlayer.php" );
//require_once( "../deck/standardIDeck.php" );
//require_once( "../deck/unoIDeck.php" );
require_once( "../deck/IDeckFactory.php" );
require_once( "../turn/warITurn.php" );
require_once( "../config/config.php" );

use \stdClass               as stdClass;
use War\Interfaces\IPlayer;
use War\Interfaces\IGame;
use War\Interfaces\IDeck;
use War\Interfaces\ITurn;
use War\Card\NoICardException;
//use War\Deck\standardIDeck;
//use War\Deck\unoIDeck;
use War\Deck\IDeckFactory;
use War\Player\warIPlayer;
use War\Turn\normalWarITurn;
use War\Config\config;

class war implements IGame
{

  /**
   * warStats will hold statistics about the current game.
   */
  public static $warStats = null;
  public static $config   = null;
  
  
  private $IPlayers       = array();
  private $IDeck          = array();

  const MAX_WAR_WINS      = 100;
  const MAX_TURNS         = 10000;

  public function __construct()
  {

    /**
     * Initialize the config
     */
    self::$config = new config();
    self::$config->buildConfig();
    print_r( self::$config );
    die;
  }

  public static function addWarStat( stdClass $warStat )
  {

    if( !self::$warStats )
    {
        self::$warStats = array();
        if( !array_key_exists( self::$warStats[$warStat->playerName] , self::$warStats ) )
        {
            self::$warStats[$playerName];
        }
    }

    self::$warStats[$warStat->playerName]['wins']++;
    if( self::$warStats[$warStat->playerName]['wins'] == self::MAX_WAR_WINS )
    {
      echo "GAME IS OVER BECAUSE " . $warStat->playerName . " has won " . self::MAX_WAR_WINS . " wars. \n Here are the standings: \n";
      print_r( self::$warStats );
      exit;
    }
    return true;
  }

  /**
   * Play a turn
   */
  public function playTurn( ITurn $ITurn )
  {

  }


  public function main()
  {
    
    $availablePlayers = array(
      "frodo",
      "hank_hill",
      "mum_ra",
      "gandalf",
      "steve_urkel",
      "aragorn",
      "chris_davis",
      "samwise",
      "famous_dave"
    );

    /**
     * Create the IPlayers that will play this game of war.  Add then to the IGame
     */
    $player0 = new warIPlayer();
    $player0Name = $availablePlayers[rand( 0 , 8)];
    $player0->setName( $player0Name );
    
    $player1 = new warIPlayer();

    $player1Name = $availablePlayers[rand( 0 , 8)];

    while( $player1Name == $player0Name )
    {
      $player1Name = $availablePlayers[rand( 0 , 8)];
    }

    $player1->setName( $player1Name );

    $this->addIPlayer( $player0 );
    $this->addIPlayer( $player1 );

    /**
     * Initialize the warStats
     */
    self::$warStats[$player0Name] = null;
    self::$warStats[$player1Name] = null;
    self::$warStats[$player0Name]['wins'] = null;
    self::$warStats[$player1Name]['wins'] = null;



    /**
     * Create the IDeck of ICards for this game.  Just a standard deck
     * Shuffle and deal the ICards
     */

    //IDeckFactory::getInstance( $IDeckType );
    $deckOfCards = new unoIDeck();
    $deckOfCards->buildIDeck();
    $deckOfCards->shuffleIDeck();

    $this->setIDeck( $deckOfCards );

    /**
     * The IGame is responsible for dealing out the cards in whatever way the cards are dealt for this game
     */
    $this->dealICards();

    /**
     * The ICards have been dealt.  Play turns
     */

    $keepGoing = true;

    //how many turns have been played.  This variable will be incremented by one after each turn is played.  the incrementing will be done by the following while loop
    $turnCount = 0;
    while( $keepGoing )
    {

      echo "PLAYING TURN $turnCount out of " . self::MAX_TURNS . "\n\n";
      /**
       * create a new turn, and give the turn the information about this IGame (players, their cards, etc)
       */
      $ITurn = new normalWarITurn();
      $ITurn->setIGame( $this );

      /**
       * Play the turn
       */
      $keepGoing = $ITurn->play();
      $turnCount++;
      if( $turnCount == self::MAX_TURNS )
      {
          echo "The maximum number of turns has been met.  The game is a draw \n";
          print_r( self::$warStats );
          echo "AT THE END OF THE GAME, THE CARD COUNTS ARE: \n$player0Name = " . count( $player0->getICardCollection()->getICards() ) . " \nTO \n$player1Name = " . count( $player1->getICardCollection()->getICards() ) . "\n\n";
          exit;
      }
    }

    if( count( $player0->getICardCollection()->getICards() ) == 0 )
    {
      echo $player0->getName() . " has lost! and " . $player1->getName() . " has won! \n";
    }
    else
    {
      echo $player1->getName() . " has lost! and " . $player0->getName() . " has won! \n";
    }
  }

  /**
   * Deal the ICards to the IPlayers
   * In the game of war, alternate.. deal one to one, and then the next to the other until the deck is empty
   */
  public function dealICards()
  {

    /**
     * Get the IPlayers
     */
    $IPlayers = $this->IPlayers;

    /**
     * Get the IDeck
     */
    $IDeck    = $this
      ->IDeck
      ->getICardCollection();

    /**
     * While the IDeck is not empty, deal the ICards to the IPlayers
     */
    $count = 0;
    while( $IDeck->getState() != "EMPTY" )
    {
      try
      {
        /**
         * Get an ICard from the top of the IDeck
         */
        $ICard = $IDeck->getICard();
      }
      catch( NoICardException $e )
      {
        echo $e->getMessage();
      }

      /**
       * If the counter is even, assign the ICard to IPlayer[0], else assign to IPlayer[1]
       */
      if( $count % 2 == 0 )
      {
        $IPlayers[0]->setICard( $ICard );
      }
      else
      {
        $IPlayers[1]->setICard( $ICard );
      }

      $count++;
    }

    /**
     * Set the IPlayers hands to full state
     */
    $IPlayers[0]
      ->getICardCollection()
      ->setState( "FULL" );

    $IPlayers[1]
      ->getICardCollection()
      ->setState( "FULL" );
    return true;
  }

  /** 
   * A game consists of an IPlayer.  Here are functions pertaining to the IPlayer
   */
  public function addIPlayer( IPlayer $IPlayer )
  {
    $this->IPlayers[] = $IPlayer;
    return true;
  }

  public function removeIPlayer( IPlayer $IPlayer )
  {

  }

  /** 
   * Returns a player object named $name
   */
  public function getIPlayerByName( $name )
  {
    return $this->IPlayers[$IPlayer->getIPlayerByName()];
  }

  /** 
   * Will return all IPlayers
   */
  public function getIPlayers()
  {
    return $this->IPlayers;
  }

  /** 
   * A game consists of an IDeck (deck of cards).  Here are functions pertaining to the IDeck
   */
  public function getIDeck()
  {
    return $this->IDeck;
  }

  public function setIDeck( IDeck $IDeck )
  {
    $this->IDeck = $IDeck;
    return;
  }

}

$w = new war();
$w->main();

?>
