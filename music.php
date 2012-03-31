<?php

/*
 *	GUIDE TO EASILY CREATING A DISCOGRAPHY
 *	
 *	To add an album, add a text file to the "msc" directory with a filename in the format
 *	of Year-Title.txt that contains two beginning lines that are 1) A description and 2) Semi-
 *	colon separated links to places to buy the album (any of this can be blank), then follows a
 *	tracklist with each track on a separate line.  Add the cover art to "img/covers" with the
 *	title of the album as the filename (plus the extension ".jpg").  You may add MP3, FLAC, or
 *	M4A audio for the albums in the "music/files" directory in a folder named the title of the
 *	album.  Also, you can add a preview file by adding a WAV file with the title of the song plus
 *	-preview (Name of Song-preview.wav) in the appropriate directory.  Additionally, you may add
 *	a ZIP archive of the entire album in the "music/files" directory with the title of the album
 *	for the filename (plus the extension ".zip").
 */

$sDir = "msc";

$sIframe = "download";
$sTarget = "_blank";

// Remove "." and ".." along with some other garbage.

$aDir = array_diff( scandir($sDir), array('.', '..', 'files', '_notes') );

$aAlbums = array();

foreach ($aDir as $sAlbum) {
	
	$aTracks = array();
	
	if ( !is_dir($sAlbum) && isExt($sAlbum, "txt") ) {
		
		$aLines = file("$sDir/$sAlbum");
		
		$bDL = false;
		$bZIP = false;
		
		$intTrack = 1;
			
		$sYear = strtok($sAlbum, "-");
		$sTitle = trim( basename( strtok(""), ".txt") );
		
		// Do something with the description and buy links.
		
		$sDescription = array_shift($aLines);
		$sLinks = array_shift($aLines);
		
		$aBuyLinks = explode(";", $sLinks);
		
		$aBuy = array();
		
		foreach ($aBuyLinks as $sLink) {
			
			if ( strpos($sLink, "itunes") )
				$aBuy['iTunes'] = $sLink;
				
			if ( strpos($sLink, "cdbaby") )
				$aBuy['CD Baby'] = $sLink;
			
		}
		
		foreach ($aLines as $sTrack) {
			
			$aLinks = array(
							
							'FLAC' => "",
							
							'M4A' => "",
							
							'MP3' => ""
							
						);
			
			$sTrack = strtok( trim($sTrack), "|" );
			$sTime = strtok("|");
			$sFilePrefix = "$sDir/files/$sTitle/" . noPunctuation($sTrack);
			
			$sPreviewFile = "$sFilePrefix-preview.wav";
			
			$sFLACFile = "$sFilePrefix.flac";
			$sM4AFile = "$sFilePrefix.m4a";
			$sMP3File = "$sFilePrefix.mp3";
			$sOGGFile = "$sFilePrefix.ogg";
			$sWAVFile = "$sFilePrefix.wav";
			$sZIPFile = "$sDir/files/" . noPunctuation($sTitle) . ".zip";
			
			if ( file_exists($sFLACFile) ) {
				
				$aLinks['FLAC'] = $sFLACFile;
				
				$bDL = true;
				
			}
			
			if ( file_exists($sM4AFile) ) {
				
				$aLinks['M4A'] = $sM4AFile;
				
				$bDL = true;
				
			}
				
			if ( file_exists($sMP3File) ) {
				
				$aLinks['MP3'] = $sMP3File;
				
				$bDL = true;
				
			}
				
			if ( file_exists($sOGGFile) ) {
				
				$aLinks['OGG'] = $sOGGFile;
				
				$bDL = true;
				
			}
				
			if ( file_exists($sWAVFile) ) {
				
				$aLinks['WAV'] = $sWAVFile;
				
				$bDL = true;
				
			}
				
			if ( file_exists($sZIPFile) )
				$bZIP = true;
			
			$aTracks[$sTrack] = $aLinks;
			
			if ( !empty($sTime) )
				$aTracks[$sTrack]['length'] = $sTime;
			
			if ( file_exists($sPreviewFile) )
				$aTracks[$sTrack]['preview'] = $sPreviewFile;
			
			$aAlbums[$sTitle] = array("description" => $sDescription, "hasDLs" => $bDL, "year" => $sYear, "links" => $aBuy, "tracks" => $aTracks);
			
			if ($bZIP)
				$aAlbums[$sTitle]['ZIP'] = $sZIPFile;
			
		}
		
	}
	
}

// Make most recent album appear first.

ksort($aAlbums, SORT_NUMERIC);

$oSmarty->assign("aAlbums", $aAlbums);

$oSmarty->assign("sIframe", $sIframe);
$oSmarty->assign("sTarget", $sTarget);

?>