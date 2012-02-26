<?php

// To add an album, add a text file to the music directory with a filename in the format
// of Year-Title.txt that contains a tracklist with each track on a separate line.  Add
// the cover art to "img/covers" with the title of the album as the filename (plus the
// extension ".jpg").  You may add MP3, FLAC, or M4A audio for the albums in the "music/mp3"
// directory in a folder named the title of the album.  You may also add a ZIP archive of
// the entire album in the "music/mp3" directory with the title of the album for the
// filename (plus the extension ".zip").

$sDir = "msc";

$sIframe = "download";
$sTarget = "_blank";

// Remove "." and ".." along with some other garbage.

$aDir = array_diff( scandir($sDir), array('.', '..', 'mp3', '_notes') );

$aAlbums = array();

foreach ($aDir as $sAlbum) {
	
	$aTracks = array();
	
	if ( !is_dir($sAlbum) && isExt($sAlbum, "txt") ) {
		
		$aLines = file("$sDir/$sAlbum");
		
		$bZIP = false;
		
		$intTrack = 1;
			
		$sYear = strtok($sAlbum, "-");
		$sTitle = trim( basename( strtok(""), ".txt") );
		
		foreach ($aLines as $sTrack) {
			
			$aLinks = array(
							
							'FLAC' => "",
							
							'M4A' => "",
							
							'MP3' => ""
							
						);
			
			$sTrack = trim($sTrack);
			$sFilePrefix = "$sDir/mp3/$sTitle/" . noPunctuation($sTrack);
			
			$sFLACFile = "$sFilePrefix.flac";
			$sM4AFile = "$sFilePrefix.m4a";
			$sMP3File = "$sFilePrefix.mp3";
			$sZIPFile = "$sDir/mp3/" . noPunctuation($sTitle) . ".zip";
			
			if ( file_exists($sFLACFile) )
				$aLinks['FLAC'] = $sFLACFile;
				
			if ( file_exists($sM4AFile) )
				$aLinks['M4A'] = $sM4AFile;
				
			if ( file_exists($sMP3File) )
				$aLinks['MP3'] = $sMP3File;
				
			if ( file_exists($sZIPFile) )
				$bZIP = true;
			
			$aTracks[$sTrack] = $aLinks;
			
			$aAlbums[$sTitle] = array("year" => $sYear, "tracks" => $aTracks);
			
			if ($bZIP)
				$aAlbums[$sTitle]['ZIP'] = $sZIPFile;
			
		}
		
	}
	
}

$oSmarty->assign("aAlbums", $aAlbums);

$oSmarty->assign("sIframe", $sIframe);
$oSmarty->assign("sTarget", $sTarget);

?>