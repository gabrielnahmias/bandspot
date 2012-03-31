<?php

require_once "config.php";

// This simply outputs some JSON so that all the pages can have JavaScript
// equivelants of all of the declared PHP constants & variables.  The output file
// gets read in by a function called by our main script and parsed appropriately.

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

// Get all the user-defined constants.

$aConstants = get_defined_constants(true);
$aConstants = array( "const" => $aConstants['user'] );

// We only need a few variables.

$aVars = array( "vars" => array("bios" => $aBios, "iphone" => $bI, "titles" => $aTitles) );

// Merge all this together.

$aData = array_merge($aConstants, $aVars);

// Output.

print json_encode($aData);

?>