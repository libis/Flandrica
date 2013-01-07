<?php

	if($_GET['type'] == 'tentoon'){
		/**
		* Create the parent feed
		*/
		$feed = new Zend_Feed_Writer_Feed;
		$feed->setTitle('Tentoonstellingen');
		$feed->setLink(abs_uri('home'));
		$feed->setDescription("De meest recente tentoonstellingen");
		$feed->setDateModified(time());

		/**
		 * Add one or more entries. Note that entries must
		 * be manually added once created.
		 */
		$entry = $feed->createEntry();

		//get exhibits
		$exhibits = exhibit_builder_recent_exhibits(10);
		$recents = $exhibits;

		foreach($recents as $recent){
			$entry = $feed->createEntry();
			//check if Item or Exhibit
				$entry->setTitle($recent->title);
				$entry->setDescription('Tentoonstelling');
				if($recent->description)
				$entry->setContent($recent->description);
				$entry->setLink(abs_uri(exhibit_builder_exhibit_uri($recent)));
				$entry->setDateModified(time());
				$entry->setDateCreated(time());

			$feed->addEntry($entry);
		}
	}

	if($_GET['type'] == 'object'){
		/**
		* Create the parent feed
		*/
		$feed = new Zend_Feed_Writer_Feed;
		$feed->setTitle('Objecten');
		$feed->setLink(abs_uri('home'));
		$feed->setDescription("De meest recente objecten");
		$feed->setDateModified(time());

		/**
		 * Add one or more entries. Note that entries must
		 * be manually added once created.
		 */
		$entry = $feed->createEntry();

		//get items and exhibits
		$items = get_items(array("recent"=>"true"),5);//TODO:welk type?

		$recents = $items;

		foreach($recents as $recent){
			$entry = $feed->createEntry();
			//check if Item or Exhibit
				$entry->setTitle(item('Dublin Core','Title',array(),$recent));
				$entry->setDescription('Afbeelding');
				if(item('Dublin Core','Description',array(),$recent))
				$entry->setContent(item('Dublin Core','Description',array(),$recent));
				$entry->setLink(xml_escape(abs_item_uri($recent)));
				$entry->setDateModified(time());
				$entry->setDateCreated(time());

			$feed->addEntry($entry);

		}
	}

	if($_GET['type'] ==""){

		/**
	    * Create the parent feed
	    */
	    $feed = new Zend_Feed_Writer_Feed;
	    $feed->setTitle('Laatste nieuws');
	    $feed->setLink(abs_uri('home'));
	    $feed->setDescription("De meest recente items en tentoonstellingen");
	    $feed->setDateModified(time());

	    /**
	    * Add one or more entries. Note that entries must
	    * be manually added once created.
	    */
	    $entry = $feed->createEntry();


	    //get items and exhibits
	    $items = get_items(array("recent"=>"true"),5);//TODO:welk type?
	    $exhibits = exhibit_builder_recent_exhibits(5);
	    $recents = array();

	    //get items and exhibits in one array
	    for($i=0;$i<=10;$i++):
	    	if($items[$i])
	    		$recents[]=$items[$i];
	    	if($exhibits[$i])
	    		$recents[]=$exhibits[$i];
	    endfor;
	    //var_dump($recents);


	    foreach($recents as $recent){
	    	$entry = $feed->createEntry();
	    	//check if Item or Exhibit
	    	if(get_class($recent)=="Item"){
	    		$entry->setTitle(item('Dublin Core','Title',array(),$recent));
	    		$entry->setDescription('Afbeelding');
	    		if(item('Dublin Core','Description',array(),$recent))
	    			$entry->setContent(item('Dublin Core','Description',array(),$recent));
	    		$entry->setLink(xml_escape(abs_item_uri($recent)));
	    		$entry->setDateModified(time());
	    		$entry->setDateCreated(time());
	    	}else{
	    		$entry->setTitle($recent->title);
	    		$entry->setDescription('Tentoonstelling');
	    		if($recent->description)
	    			$entry->setContent($recent->description);
	    		$entry->setLink(abs_uri(exhibit_builder_exhibit_uri($recent)));
	    		$entry->setDateModified(time());
	    		$entry->setDateCreated(time());
	    	}

	    	$feed->addEntry($entry);

	    }
	}
    /**
    * Render the resulting feed to Atom 1.0 and assign to $out.
    * You can substitute "atom" with "rss" to generate an RSS 2.0 feed.
    */
   echo $feed->export('rss');?>

