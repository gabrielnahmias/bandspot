<?php

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

function wordWithEnding($sWord, $intNumber) {
	
	$sEnding = '';
	
	if ($intNumber != 1)
		$sEnding = 's';
	
	return "$sWord$sEnding";
	
}