<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=NAME?> - <?=$sPage?></title>

<link href="img/f.ico" rel="shortcut icon" />

<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css" />

<link href="<?= TEXT_MIN_F . ( ($bI) ? "cb/colorbox" : "hs/highslide" ) ?>.css" rel="stylesheet" type="text/css" />
<link href="min/?g=css" rel="stylesheet" type="text/css" />
<?php if ($bI): css_add(); ?>
<link rel="apple-touch-icon-precomposed" href="img/touch.png"/>
<?php endif; ?> 

<meta name="description" content="<?=$aMeta[$pg]?>" />
<meta name="keywords" content="band,music,jam,memphis,oxford,ms,mississippi,memphis,tn,tennessee,live,perforamance,concert,tour schedule,player,info,information,elemovements,elements,movements,<?=$pg?>" />
<meta name="author" content="Gabriel Nahmias" />
<meta name="robots" content="index, follow" />

<?php if ($bI): ?>
<meta name="viewport" content = "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<?php endif; ?>
<meta property="og:title" content="<?=NAME?> - <?=$sPage?>"/>
<meta property="og:type" content="band"/>
<meta property="og:url" content="http://el.x10.mx/<?=$pg?>"/>
<meta property="og:image" content="http://el.x10.mx/img/graphicon.png"/>
<meta property="og:site_name" content="<?=NAME?>"/>
<meta property="fb:admins" content="<?=ID_FB_ADMINS?>"/>
<meta property="fb:app_id" content="<?=ID_FB_APP?>"/>
<meta property="og:description"
      content="<?=$aMeta[$pg]?>"/>

<!--[if lte IE 8]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" type="text/javascript"></script>

<?php if ($bI): ?>

<script language="javascript" src="js/buzz.js" type="text/javascript"></script>
<script language="javascript" src="js/scripts.js" type="text/javascript"></script>
<script language="javascript" src="js/orientation.js" type="text/javascript"></script>

<?php endif; ?>

<script language="javascript" src="<?=TEXT_MIN_F?>js/buzz.js,js/easing.js,<?php if (!$bI): ?>js/flux.js,js/waypoints.min.js<?php else: ?>js/orientation.js<?php endif; ?>,<?=( ($bI) ? "cb/colorbox" : "hs/highslide" ) ?>.js,<?=( ($bWK && !$bI) ? "js/zepto.min.js," : "" )?>js/scripts.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-27838426-1']);
_gaq.push(['_trackPageview']);

( function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
} )();

</script>

</head>

<body<?=( ($bI) ? " onorientationchange=\"updateOrientation();\" onload=\"updateOrientation();\"" : "" )?>>
	
    <div class="wrapper">
        <?php if (!$bI): ?>
        
        <div id="fb-root"></div>
        
        <script>
		
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?=ID_FB_APP?>";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
        
        </script>
        
        <div class="warning dialog">
            
            <?=( ($oBr->Name == "MSIE") ? "You're using Internet Explorer?  This page will not look as good as it could.<br />" . nbsp(3) : "" )?>
            
            The image slider on this page requires a browser that supports CSS3 transitions in order to display its cool effects.
            You should try the latest version of <a href="http://www.firefox.com" target="_blank">Firefox</a> or
            <a href="https://www.google.com/chrome/" target="_blank">Chrome</a>.
            
        </div>
        <?php endif;?>
        
        <div id="container-wrapper" class="container_16">
            
            <div id="container">
            	
                <div id="header">
                    
                    <a href="home"><div id="logo"></div></a>
                
                </div>
                
                <div class="clear"></div>
                
                <div class="tagline">
                    
                    Debut Album Coming this Winter!
                    
                </div>
                
                <div class="clear"></div>
		        
                <div id="slider">
                    
                    <?php
                    
					if (!$bI):
						
						$aDir = array_diff( scandir(DIR_SLIDER), array('.', '..', 'mp3', '_notes') );
						
						sort($aDir, SORT_NUMERIC);
						
						foreach ($aDir as $sSlide) {
							
							if ( isExt($sSlide, "jpg") )
								print "<img src=\"" . DIR_SLIDER . "/$sSlide\" />\r\n" . tabs(5);
							
						}
					
					else:
					
                    ?>
                    
                    <img src="<?=DIR_IMG?>/ibanner.jpg" />
                    
                    <?php endif; ?>
                    
                </div>
                
                <div class="clear"></div>
                
                <div class="bar" id="nav">
                		
                    <?php
					
					foreach ($aLinks as $sLink) {
						
						$aParts = explode("|", trim($sLink) );
						
						// Part 3 of the string denotes if it's only meant for iPhone users to see.
						
						if ( ( $aParts[2] == "1" && $bI ) || $aParts[2] == "0" )
							print '<a href="' . $aParts[1] . ( ( isset($d) ) ? "&d" : "" ) . '">' . strtoupper( $aParts[0] ) . '</a>' . "\r\n" . tabs(5);
						
					}
                    
					?>
						
                    <div class="load"><?php if (!$bI) { ?>LOADING <?php } ?><img src="./img/load.gif" /></div>
                	
                </div>
                
                <div class="clear"></div>
                
                <div id="page-wrapper">
                    
                    <div id="page">
                        <?php if (!$bI): ?>
                        
                        <div class="grid_5" id="sidebar"><?php
                           
                           box("player");
                           box("schedule");
                           box("social");
                           
                           ?> 
                           
                        </div>
                        <?php endif; ?>
                        
                        <div class="grid_11" id="content">
                            
                            <?php if ($pg != "archive"): ?>
                            
							<div class="box-2" id="archive-popout">
                                    
                                <div class="inner">
                                    
                                    <?php
                                    
									if ( isset( $_GET['xnewsaction'] ) ) {
										
										$xnewsactionCopy = $_GET['xnewsaction'];
										
										unset( $_GET['xnewsaction'] );
										
									}
									
									include "archive.php";
									
									if ( isset( $_GET['xnewsaction'] ) ) {
										
										$_GET['xnewsaction'] = $xnewsactionCopy;
										
									}
									
									?>
                                    
                                </div>
                                    
                            </div>
                            
                            <?php endif; ?>
                            
							<div id="loaded">
                                
