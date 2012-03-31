<?php

require "inc/common.php";

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
$sJSFile = $sJS . "logic/$pg.js";
$sTPLFile = DIR_TEMPLATES . "/$pg.tpl";

if ( !file_exists($sFile) && !file_exists($sTPLFile) ) {
	
	// If neither the view nor the controller is present, go 404.
	
	$pg = "404";
	
	$sFile = "$pg.php";
	
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
										
										if ( file_exists( str_replace( urlPath() , "", $sJSFile ) ) )
											print "<script language=\"javascript\" src=\"" . TEXT_MIN_F . "$sJSFile\" type=\"text/javascript\"></script>";
										
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