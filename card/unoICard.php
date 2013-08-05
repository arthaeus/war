<?php

namespace War\Card;

require_once( "../interfaces.php" );
require_once( "standardICard.php" );
use War\Card\standardICard  as abstractCard;
use War\Interfaces\ICard    as ICard;


/**
 * If we consider a "suit" to be a second differentiator of a card (the number is one, the "suit" is the other
 * we can reuse the standard class to implement a basic card with two differentiators.
 *
 * for instance, with the standard card deck you have a number differentiator (2-ace) and a suit differentiator (spade,heart,diamonds, and clubs.)
 * we could reuse this code for a deck of uno cards as well.  there are colors and numbers in uno.  Obviously, the uno and standard cards share the
 * same concept of a number differentiator.  We can consider the colors in uno to be their suit for the purpose of reuse, and we don't need to do 
 * anything to the standardICard.
 */

class unoICard extends standardICard implements ICard
{}

?>
