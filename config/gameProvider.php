<?php

namespace War\Game;

require_once("../vendor/autoload.php");
require_once( "../interfaces.php" );
require_once( "../player/warIPlayer.php" );
require_once( "../deck/IDeckFactory.php" );
require_once( "../turn/warITurn.php" );
require_once( "../config/config.php" );

use \stdClass               as stdClass;
use War\Interfaces\IPlayer;
use War\Interfaces\IGame;
use War\Interfaces\IDeck;
use War\Interfaces\ITurn;
use War\Card\NoICardException;
use War\Deck\IDeckFactory;
use War\Player\warIPlayer;
use War\Turn\normalWarITurn;
use War\Config\config;


use \Pimple\Container;
use \Pimple\ServiceProviderInterface;

class gameProvider implements \Pimple\ServiceProviderInterface
{
    public static $availablePlayers = array(
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

    public static $config;


    public function register(Container $pimple)
    {

        self::$config = new config();
        self::$config->buildConfig();

        $pimple['warIPlayer'] = $pimple->factory(function ($c) {

            /** 
             * Create the IPlayers that will play this game of war.  Add then to the IGame
             */
            $pIndex = rand( 0 , count(self::$availablePlayers)-1);
            $IPlayer = new warIPlayer();
            $player0Name = self::$availablePlayers[$pIndex];
            $IPlayer->setName( $player0Name );
            unset(self::$availablePlayers[$pIndex]);
            self::$availablePlayers = array_values( self::$availablePlayers );
            return $IPlayer;

        });

        $pimple['normalITurn'] = $pimple->factory(function ($c) {
            return new normalWarITurn();
        });

        $pimple['IDeck'] = $pimple->factory(function ($c) {
            /** 
             * Create the IDeck of ICards for this game.  Just a standard deck
             * Shuffle and deal the ICards
             */

            $IDeckSettings = self::$config->getSetting( 'IDeck' );
            $IDeckType     = $IDeckSettings->IDeckClass->value;

            $deckOfCards = IDeckFactory::getInstance( $IDeckType );
            $deckOfCards->buildIDeck();
            return $deckOfCards;
        });
    }
}
