<?php

namespace War\Deck;
require_once( "../config/config.php" );

use War\Config\config;

abstract class IDeckFactory
{
    public static function getInstance($IDeckType)
    {

        $c = new config();
        $c->buildConfig();
        $basePath = $c->getSetting('global')
            ->basePath
            ->value;

        /**
         * Namespaced deck class name
         */

        $IDeckClassName = '\\War\\Deck\\' . $IDeckType . 'IDeck';

        $IDeckFileName = $basePath . "deck/" . $IDeckType . 'IDeck.php';


        if (is_file( $IDeckFileName ) )
        {
            require_once( $IDeckFileName );
            return new $IDeckClassName();
        }
        else
        {
            throw new \Exception('Deck not found');
        }
    }
}

?>
