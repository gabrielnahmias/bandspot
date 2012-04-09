<?php

require "inc/common.php";

// IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT IMPORTANT
// We need to disable minification if the domain specified  in config is not in the current hostname.
// It's too much of a hassle.

import_request_variables("g");

// NHF = no header or footer
// NB = no box (or header or footer)

if ( isset($nhf) )
	$bNHF = true;
else
	$bNHF = false;

if ( isset($nb) )
	$bNB = true;
else
	$bNB = false;
	
if ( !isset($pg) )
	$pg = "home";

$sFile = "$pg.php";
$sJSFile = DIR_JS_LOGIC . "/$pg.js";
$sTPLFile = DIR_TEMPLATES . "/$pg.tpl";
$sCSSFile = DIR_CSS . "/$pg.css";

$oSmarty->assign("sJS", $sJS);

if ( !file_exists($sFile) && !file_exists($sTPLFile) ) {
	
	// If neither the view nor the controller is present, go 404.
	
	header('HTTP/1.0 404 Not Found');
	
	$sTemp = "404";
	
	// You might say this is a little extreme but you never know how
	// intricate you can make a 404.  Take GitHub for example... sheesh.
	// Star Wars and everything.
	
	$sFile = str_replace($pg, $sTemp, $sFile);
	$sJSFile = str_replace($pg, $sTemp, $sJSFile);
	$sTPLFile = str_replace($pg, $sTemp, $sTPLFile);
	$sCSSFile = str_replace($pg, $sTemp, $sCSSFile);
	
	$pg = "404";
	
}

$sPage = ucwords($pg);
$sTitle = $aTitles[$pg];

if (!$bNHF && !$bNB)
	require "header.php";

if (!$bNB):

?>
							
                            <div class="box-1" id="<?=( ($pg == "archive") ? "home" : $pg )?>">
                                
                                <div class="title"><?=$sTitle?></div>
                                
                                <div class="body">
                                	
                                    <div class="inner">
                                    	
                                        <?php
                                        
										endif;
										
										// This is the main output device.  It includes the controller, then outputs the
										// view and JavaScript page logic.
										
										if ( file_exists($sFile) )
											include $sFile;
										
										if ( file_exists($sTPLFile) )
											$oSmarty->display($sTPLFile);
										
										if ( file_exists($sJSFile) )
											print "<script id=\"logic\" language=\"javascript\" src=\"" . TEXT_MIN_F . urlPath() . "/$sJSFile\" type=\"text/javascript\"></script>";
										
										if (!$bNB):
										
										?>
                                        
                                    </div>
                                    
                                </div>
                            
                            </div>
							
<?php

endif;

if (!$bNHF && !$bNB)
	require "footer.php";
	
?>