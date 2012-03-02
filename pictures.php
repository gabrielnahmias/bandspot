<?php

@require 'inc/facebook.php';

require_once "inc/config.php";
require_once "inc/functions.php";

$facebook = new Facebook( array(
	
	'appId'  => ID_FB_APP,
	'secret' => ID_FB_SEC,
	'cookie' => true
	
) );

isset( $_REQUEST['action'] ) ? $action = $_REQUEST['action'] : $action = "";
	
if ( empty($action) ) {
	
	$aAlbums = array();
	
	$iTotal = 0;
	
	$fql = "SELECT aid, cover_pid, description, link, location, name, size, type FROM album WHERE owner=" . ID_FB;
	
	$param = array(
	 'method'    => 'fql.query',
	 'query'     => $fql,
	 'callback'  => ''
	);
	
	$fqlResult = $facebook->api($param);
	
	foreach( $fqlResult as $keys => $values ) {
		
		$fql2 = "select src from photo where pid = '" . $values['cover_pid'] . "'";
		
		$param2 = array(
		 'method'    => 'fql.query',
		 'query'     => $fql2,
		 'callback'  => ''
		);
		
		$fqlResult2 = $facebook->api($param2);
		
		foreach( $fqlResult2 as $keys2 => $values2)
			$album_cover = $values2['src'];
		
		if ( $values['type'] != "profile" && stripos( $values['name'] , "cover photos" ) === false ) {
			
			$iTotal += $values['size'];
			
			$sTitle = "";
			
			$sURL = "pictures&action=list_pics&aid=" . $values['aid'] . "&size=" . $values['size'] . "&name=" . $values['name'];
		
			// If there's no punctuation on the description, add a period.
			
			if ( !empty( $values['description'] ) &&
				 ( substr( $values['description'] , -1) != "." &&
				 substr( $values['description'] , -1) != "?" &&
				 substr( $values['description'] , -1) != "!" ) )
				 
				 $values['description'] .= ".";
			
			if ( !empty( $values['location'] ) )
				$values['location'] = "Taken in " . $values['location'] . ".";
			
			$sTitle = trim( $values['description'] . "  " . $values['location'] );
			$sLinkProps = 'fb-href="' . $values['link'] . '" href="' . $sURL . '" title="' . $sTitle . '"';
			
			$aAlbums[] = array(
							
							"name" => $values['name'],
							"linkProps" => $sLinkProps,
							"src" => $album_cover,
							
						);
			
		}
		
	}
	
	$oSmarty->assign("aAlbums", $aAlbums);
	
	$oSmarty->assign("iPics", $iTotal);
	
}

if ($action == 'list_pics') {
	
	$aPics = array();
	
	isset( $_GET['name'] ) ? $album_name = stripslashes( $_GET['name'] ) : $album_name = "";
	
	$fql = "SELECT pid, src, src_small, src_big, caption FROM photo WHERE aid = '" . $_REQUEST['aid'] ."'  ORDER BY created DESC";
	
	$param = array(
	 'method'    => 'fql.query',
	 'query'     => $fql,
	 'callback'  => ''
	);
	
	$fqlResult = $facebook->api($param);
	
	foreach ($fqlResult as $keys => $values) {
		
		if ( empty( $values['caption'] ) )
			$caption = "";
		else
			$caption = $values['caption'];	
		
		$aPics[] = array(
					
					"caption" => $caption,
					"src" => $values['src_big'],
					"thumb" => $values['src']
					
				);
		
	}
	
	$oSmarty->assign("aPics", $aPics);
	
	$oSmarty->assign("sAlbum", $album_name);
	
}

?>