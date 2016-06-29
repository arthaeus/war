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

    public function register(Container $pimple)
    {
        $pimple['warIPlayer'] = $pimple->factory(function ($c) {

            /** 
             * Create the IPlayers that will play this game of war.  Add then to the IGame
             */
            $pIndex = rand( 0 , count(self::$availablePlayers)-1);
            $IPlayer = new warIPlayer();
            $player0Name = self::$availablePlayers[$pIndex];
            $IPlayer->setName( $player0Name );
            unset(self::$availablePlayers[$pIndex]);
            return $IPlayer;

        });
    }
}
