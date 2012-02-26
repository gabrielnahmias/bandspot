<?php

import_request_variables("g");

if ( isset($f) ) {
	
	$sDir = dirname($f);
	$sFile = basename($f);
	
	header('Content-Disposition: attachment; filename="' . $sFile. '"');
	
	readfile($f);
	
}

?>