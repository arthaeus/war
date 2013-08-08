<?php

namespace War\Deck;

class IDeckFactory
{
    public static function getInstance($IDeckType)
    {

        /**
         * Namespaced deck class name
         */

        $IDeckClassName = '\\War\Deck\\' . $IDeckType . 'IDeck';
        
        $IDeckFileName = $IDeckType . 'IDeck.php';


        if (is_file( $IDeckFileName ) )
        {
            require_once( $IDeckFileName );
            return new $IDeckClassName();
        }
        else
        {
            throw new Exception('Car not found');
        }
    }
}
