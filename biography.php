<?php

$sPage = "Band $sPage";

$aDir = scandir(DIR_BAND);

$aNewBios = array();
$aPhotos = array();

foreach ($aDir as $sFile) {
    
    if ( isExt($sFile, "jpg") ) {
        
        // For some reason, is_dir() returns false for the "profiles"
        // directory.
        
        $sName = ucwords( basename($sFile, ".jpg") );
        
        $aPhotos[$sName] = DIR_BAND . "/$sFile";
        
    }
    
}

foreach ($aBios as $sFirst => $sValue) {
	
	$aInfo = explode("|", $sValue);
	
	$sLast = $aInfo[0];
	$sFBID = $aInfo[1];
	$sBio = $aInfo[2];
	
	$sName = "$sFirst $sLast";
	
	$aNewBios[$sName] = $sBio;
	
}

$oSmarty->assign("aBios", $aNewBios);
$oSmarty->assign("aPhotos", $aPhotos);

$oSmarty->assign("sPage", $sPage);

?>