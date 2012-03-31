<?php

function array_key_exists_r($needle, $haystack) {
	
	$result = array_key_exists($needle, $haystack);
	
	if ($result)
		return $result;
	
	foreach ($haystack as $v) {
		
		if ( is_array($v) || is_object($v) )
			$result = array_key_exists_r($needle, $v);
	
		if ($result)
			return $result;
		
	}
	
	return $result;
	
}

function box($sName) {
	
	global $aTitles, $oSmarty;
	
	$sFile = "$sName.php";
	
	if ( file_exists($sFile) )
		include $sFile;
	
?>


							<div class="box-1" id="<?=$sName?>">
								
								<div class="title"><?=$aTitles["$sName-box"]?></div>
								
								<div class="body">
									
									<div class="inner">
										
										<?=$oSmarty->display("$sName-box.tpl")?>
										
									</div>
									
								</div>
							
							</div>
                            
<?php
	
}

function isExt($sFile, $sExt) {
	
	return ( substr($sFile, ( strrpos($sFile, ".") + 1 ) ) == $sExt );
	
}

function nbsp($intNumber) {
	
	$sText = "";
	
	for($i = 0; $i < $intNumber; $i++)
		$sText .= "&nbsp;";
		
	return $sText;
	
}

function noPunctuation($sString) {
	
	return preg_replace("/[^a-zA-Z 0-9]+/", "", $sString);
	
}

function tabs($intNumber) {
	
	$sText = "";
	
	for($i = 0; $i < $intNumber; $i++)
		$sText .= "\t";
		
	return $sText;
	
}

function urlPath() {
	
	if ( !isset($sSelf) )
		$sSelf = $_SERVER['PHP_SELF'];
	
	$aFilename = explode("/", $sSelf);
	
	array_pop($aFilename);
	
	$aFilename = array_filter($aFilename);
	
	unset( $aFilename[ array_search("index.php", $aFilename) ] );
	
	$aFilename = array_values($aFilename);
	
	$sFilename2 = "";
	
	for( $i = 0; $i < ( count($aFilename) ); ++$i )
		$sFilename2 .= $aFilename[$i]. '/';
	
	return $sFilename2;
	
}

function wordWithEnding($sWord, $intNumber) {
	
	$sEnding = '';
	
	if ($intNumber != 1)
		$sEnding = 's';
	
	return "$sWord$sEnding";
	
}