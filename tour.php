<?php

require('rss/rss_fetch.inc');

$aShows = array();

$oRSS = fetch_rss(URL_TOUR_RSS);
	
$aRSS = (array)$oRSS;

$aRSS = @array_filter($aRSS, "strlen");

foreach ($oRSS->items as $aShow) {
	
	$intDate = strtotime( $aShow['title'] );
	
	strtok($aShow['description'], "-");
	
	$sAddress = $aShow['address'];
	$sCity = str_replace(" US", "", $aShow['loc'] );
	$sDate = date(DATE_FMT, $intDate);
	$sDescription = ( strtok("") == "-" || strtok("") == " " ) ? "" : strtok("");
	$sPrice = ( $aShow['show_price'] == "-" ) ? "" : $aShow['show_price'];
	$sURL = $aShow['venue_website'];
	$sVenue = $aShow['venue'];
	
	$sMapURL = "http://maps.google.com/?q=$sAddress";
	
	$aShows[] = array(
					
					"address" => $sAddress,
					"city" => $sCity,
					"date" => $sDate,
					"description" => $sDescription,
					"mapURL" => $sMapURL,
					"price" => $sPrice,
					"url" => $sURL,
					"venue" => $sVenue
				
				);
	
}

$oSmarty->assign("aShows", $aShows);

?>