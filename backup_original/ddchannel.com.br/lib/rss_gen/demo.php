<?php
/*
 *      demo.php
 *      
 *      Copyright 2010 João Pedro Perera <contacto[at]joaopedropereira[dot]com>
 *      
 */

	include( "feed.php" );


	//Create a new Feed
	$feed = new Feed( );
  
	//Setting the channel elements
	//Helper -> http://www.rssboard.org/rss-specification
	$feed->setFeedTitle( 'Demo - RSS Generator Class' );
	$feed->setFeedLink( 'http://joaopedropereira.com/blog/rss' );
	$feed->setFeedDesc( 'This is demo of generating a RSS feed. ONLY RSS Version 2.0 Supported' );
	$feed->setFeedImage( 'Oh, my photo...', 'http://joaopedropereira.com/projects/rss_gen', 'http://s3.amazonaws.com/twitter_production/profile_images/63969619/imagemresized.jpg' );
  
	//Is possible to use setChannelElm() function for setting other optional channel elements
	$feed->setChannelElm( 'language', 'en-us' );
    
	//Create a new Item
	$item1 = new Item( );
  
	//Setting the Item elements
	//Helper -> http://www.rssboard.org/rss-specification
	$item1->setItemTitle( 'Item nº 1' );
	$item1->setItemLink( 'http://joaopedropereira.com' );
	$item1->setItemDate( time( ) );
	$item1->setItemDesc( 'Bla, bla, bla, item nº 1.' );
	$item1->setItemEnclosure( 'http://www.beardodisco.com/beatelectric/music/Loverboy12Mix.mp3', '17121349', 'audio/mpeg' );
	$item1->setItemAuthor( 'contacto@joaopedropereira.com (João Pedro Pereira)' );
	//As in Channel is possible to use setItemElm() function for setting other optional item elements

	//Create another Item
	$item2 = new Item( 'Item nº 2', 'http://twitter.com/joaoppereira', 'Bla, bla, bla, twitter of the owner of the blog of a webdeveloper' );
  
	$item2->setItemDate( time( ) );
	$item2->setItemAuthor( 'contacto@joaopedropereira.com (João Pedro Pereira)' );
	
	//Adding both created items
	$feed->addItem( $item1 );
	$feed->addItem( $item2 );
  
	//Now we're ready to generate the Feed, Awesome!
	$feed->genFeed( );
  
?>
