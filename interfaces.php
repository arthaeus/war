<?php



namespace War\Interfaces;

use \stdClass as stdClass;

/**
 * Written specifically for the card game war.  The abstract concepts should be applicable to any game that is played
 * with a standard deck of playing cards (not card games like uno or old maid)
 */

interface ICardCollection
{
  /**
   * The IDeck is in the empty state
   */
  const STATE_EMPTY = "EMPTY";

  /**
   * The IDeck is not empty, but not full
   */
  const STATE_NOT_EMPTY = "NOT_EMPTY";

  /**
   * Full deck of ICards
   */
  const STATE_FULL = "FULL";


  /**
   * Adds one ICard to the ICardCollection
   */
  public function setICard( ICard $ICard );

  /**
   * Pops one ICard from the ICardCollection
   */
  public function getICard();

  /**
   * Set all ICards for this collection.  Current collection will be overwritten.
   */
  public function setICards( $ICards );

  /**
   * Append ICards to the already existing ICards array
   */
  public function addICards( $ICards );

  /**
   * Get all ICards from this collection
   */
  public function getICards();

}

/**
 * An IDeck is made up of ICards.  The IDeck will always have a property called ICards which will be a container for ICards
 */
interface IDeck extends ICardCollection
{

  /**
   * An IDeck of cards needs to be responsible for providing functionality for building a deck of cards.  
   * The IDeck may be standard playing cards, old maid cards, uno cards, etc.  Each is different, and each must be built differently
   * The buildDeck function (for the war IDeck) will be a facade in front of helper functions that will ultimately build an IDeck of ICards
   */
  public function buildIDeck();

  /**
   * The IDeck will be set to its default state.  The IDeck will contain all ICards, and they will not be
   * shuffled.
   */
  public function resetIDeck();

  /**
   * After this function is called, the container of ICards will be in a random order
   */
  public function shuffleIDeck( stdClass $options = null );

}

/**
 *
 * The ITurn is responsible for invoking the rules of the IGame.  The ITurn will return whatever the IGame is expecting it to return.  In war, after a turn
 * is played, the ITurn will return the array of ICards that were played during this turn, and also who is the winner.
 *
 * In card games, the players take turns.  Different games will implement a turn differently
 * Working under the assumption that a game is comprised of turns.  
 * Working under the assumption that a turn will have player(s)
 * Working under the assumption that in a card game, a turn has cards.  (When a player plays the turn, they make the cards that they play a part of the turn).
 *
 */
interface ITurn
{

  /**
   * States to tell whether a turn is still going on, or if the turn is over.
   */
  const STATE_TURN_ACTIVE = "TURN_ACTIVE";
  const STATE_TURN_OVER   = "TURN_OVER";


  public function setIGame( IGame $IGame );
  public function getIGame();

  /**
   * the play function will take the cards from the turn into consideration, and invoke the rules of a particular game.  
   * 
   * Also:
   *
   * Different card games have different things that happen upon winning a turn.  For war, the goal is
   * to get all of the cards.  In other games, you probably don't want to end up with all of the cards.  The ITurn
   * should return whatever it needs to return to the IGame, and the IGame is responsible for interfacing with the 
   * IPlayers.  The abstract ITurn class will have an abstract method called win() 
   */
  public function play();

}

/**
 * Interface for a player.  Will provide template for generic player behavior
 */
interface IPlayer
{

  /**
   * This function will pop and return a card from the player's hand
   *
   * The ITurn will use this function to interact with the IPlayer.
   */
  public function getICard();

  /**
   * Will add one single ICard to the players hand
   */
  public function setICard( ICard $ICard );

  /**
   * Will add multiple ICards to the players hand
   */
  public function setICards( $ICards = array() );

  public function setName( $name );

  public function getName();

}

/**
 * This will be the driver for the program
 *
 * Working under the assumption that if a game exists, and it is being played, it will contain player(s)
 * Working under the assumption that most card games have turns and these turns involve players.
 */
interface IGame
{

  /**
   * States to tell whether a game is still going on, or if the game is over.
   */
  const STATE_GAME_ACTIVE = "GAME_ACTIVE";
  const STATE_GAME_OVER   = "GAME_OVER";

  /**
   * A game consists of an IPlayer.  Here are functions pertaining to the IPlayer
   */
  public function addIPlayer( IPlayer $IPlayer );
  public function removeIPlayer( IPlayer $IPlayer );

  /**
   * Returns a player object named $name
   */
  public function getIPlayerByName( $name );

  /**
   * Will return all IPlayers
   */
  public function getIPlayers();

  /**
   * A game consists of an IDeck (deck of cards).  Here are functions pertaining to the IDeck
   */
  public function getIDeck();
  public function setIDeck( IDeck $IDeck );

  /**
   * Deal the ICards to the IPlayers
   */
  public function dealICards();

  /**
   * Play a turn
   */
  public function playTurn( ITurn $ITurn );

}

interface ICard
{


  /**
   * Returns the value of this ICard
   */
  public function getValue();

}

?>
