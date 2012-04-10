<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="<?=$pg?>" xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"
      xmlns:fb="https://www.facebook.com/2008/fbml"> 
<head prefix="og: http://ogp.me/ns# elemovements: 
                  http://ogp.me/ns/apps/elemovements#">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= NAME . TEXT_DIVIDER . ( !isset( $aOG['title'] ) ? $sPage : $aOG['title'] ) ?></title>

<?php if ( !localhost() ): ?>
<base href="http://<?=$_SERVER['HTTP_HOST'].urlPath(true)?>" />
<?php endif;?>

<link href="img/f.ico" rel="shortcut icon" />

<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css" />

<link href="<?=TEXT_MIN_F?>styles.css,960.css,reset.css,text.css&b=<?=$sPath?>css" rel="stylesheet" type="text/css" />
<link href="<?=TEXT_MIN_F?><?= $sPath . ( ($bI) ? "cb/colorbox" : "hs/highslide" ) ?>.css" rel="stylesheet" type="text/css" />
<?php css_add("css", $sPathPlain); if ($bI): ?>
<link rel="apple-touch-icon-precomposed" href="img/touch.png"/>
<?php endif;

// I'm not sure if I want to load separate CSS... it sounds like a good idea but a bit of a hassle.
// We shall see if I feel like separating that 1500 behemoth of a stylesheet into different files.

// Special CSS loading time.  Don't forget to add this functionality to loadContent (dynamically load the file).
// Also, remember you need to do the same for JavaScript anyway!

if ( file_exists($sCSSFile) )
	print '<link href="' . TEXT_MIN_F . urlPath() . $sCSSFile . '" rel="stylesheet" type="text/css" />';

?>

<meta name="description" content="<?= ( ( !isset( $aOG['description'] ) ) ? $aMeta[$pg] : $aOG['description'] ) ?>" />
<meta name="keywords" content="<?= implode(",", $aKeywords) . ",$pg" ?>" />
<meta name="author" content="Gabriel Nahmias" />
<meta name="robots" content="index, follow" />

<?php if ($bI): ?>
<meta name="viewport" content = "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<?php endif; ?>
<meta property="og:site_name"			 content="<?=NAME?>" />
<meta property="fb:admins"				 content="<?=ID_FB_ADMINS?>" />
<meta property="fb:app_id"				 content="<?=ID_FB_APP?>" />
<meta property="og:url"					 content="<?= ( ( !isset( $aOG['url'] ) ) ? DOMAIN . "/$pg" : $aOG['url'] ) ?>" />
<meta property="og:title"				 content="<?= ( ( !isset( $aOG['title'] ) ) ? NAME . TEXT_DIVIDER . $sPage : $aOG['title'] ) ?>" />
<meta property="og:type"				 content="<?=$aOG['type']?>" />
<meta property="og:image"				 content="<?= DOMAIN . "/" . DIR_IMG . "/" . $aOG['picture'] ?>" />
<meta property="og:description"			 content="<?= ( ( !isset( $aOG['description'] ) ) ? $aMeta[$pg] : $aOG['description'] ) ?>" />
<?php if ( isset( $_GET['xnewsaction'] ) ): ?>
<meta property="article:published_time"  content="<?=$aOG['published']?>">
<meta property="article:modified_time"   content="<?=( !isset( $aOG['modified'] ) || empty( $aOG['modified'] ) ) ? $aOG['published'] : $aOG['modified'] ?>">
<meta property="article:expiration_time" content="<?=$aOG['expires']?>">
<meta property="article:author"          content="<?=$aOG['author']?>">
<meta property="article:section"         content="music">
<!-- <meta property="article:tag"             content=""> -->
<?php endif; ?>

<script language="javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script language="javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" type="text/javascript"></script>

<!--[if (gte IE 5.5)&(lte IE 6)]>
<script language="javascript" src="<?=DIR_JS?>/png.js" type="text/javascript"></script>
<![endif]-->

<!--[if lte IE 8]>
<script language="javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->

<?php if ($bI):

/*	WHAT IN THE NAME OF GOD ALWAYS CAUSES SOMETHING TO GO WRONG WITH THE JAVASCRIPT ON THE SITE????
	EITHER IT'S THE DESKTOP SITE WITH A THOUSAND ERRORS OR THE EVER-IMPOSSIBLE-TO-TRACE-EVEN-ONE-ERROR
	IPHONE VERSION.	I'll simply have to take time out to eliminate one by one which file causes it like
	every other time!
 */

?>

<script language="javascript" src="cb/colorbox.js" type="text/javascript"></script>
<script language="javascript" src="js/buzz.js" type="text/javascript"></script>
<script language="javascript" src="js/effects.js" type="text/javascript"></script>
<script language="javascript" src="js/orientation.js" type="text/javascript"></script>
<script language="javascript" src="js/modernizr.js" type="text/javascript"></script>
<script language="javascript" src="js/scripts.js" type="text/javascript"></script>

<?php else: ?>

<script language="javascript" src="<?=TEXT_MIN_F?><?=( ($bI) ? "cb/colorbox" : "hs/highslide" ) ?>.js<?=( !empty($sPathPlain) ? "&b=$sPathPlain" : "" )?>" type="text/javascript"></script>

<script language="javascript" src="<?= TEXT_MIN_F ?>buzz.js,paper.js,effects.js,<?php if (!$bI): ?>flux.js,<?php else: ?>orientation.js<?php endif; ?>modernizr.js,scripts.js<?=( ($bWK && !$bI) ?( ",zepto.js") : "" )?>&b=<?=$sJS?>" type="text/javascript"></script>

<script language="javascript" src="<?=TEXT_MIN_F . DIR_JS_LOGIC?>/news.js<?=( !empty($sPathPlain) ? "&b=$sPathPlain" : "" )?>" type="text/javascript"></script>

<!-- <script type="text/javascript" src="https://apis.google.com/js/plusone.js">{"parsetags": "explicit"}</script> -->

<?php endif; ?>

<script language="javascript" type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f770f490e3607ff"></script>

</head>

<body<?=( ($bI) ? " onorientationchange=\"updateOrientation();\" onload=\"updateOrientation();\"" : "" )?>>
	
	<div class="wrapper">
        <?php if (!$bI): ?>
        
        <div id="fb-root"></div>
        
        <script language="javascript" src="<?=TEXT_MIN_F?>fb.js&b=<?=$sJS?>" type="text/javascript"></script>
        
        <div class="warning dialog">
            
            <?=( ($oBr->Name == "MSIE") ? "You're using Internet Explorer?  This page will not look as good as it could.<br />" . nbsp(3) : "" )?>
            
            The image slider on this page requires a browser that supports CSS3 transitions in order to display its cool effects.
            You should try the latest version of <a href="http://www.firefox.com" target="_blank" title="Mozilla Firefox">Firefox</a> or
            <a href="https://www.google.com/chrome/" target="_blank" title="Google Chrome">Chrome</a>.
            
        </div>
        <?php endif;?>
        
        <div id="container-wrapper" class="container_16">
            
            <div id="container">
            	
                <div id="header">
                    
                    <a href="home"><div id="logo"><span class="fade-in hover"></span></div></a>
                
                </div>
                
                <div class="clear"></div>
                
                <div class="toolbar">
                    
                    <?php if (!$bI): ?>
                    
                    <img id="fb-picture" src="img/spacer.png" />
                    
                    <div id="fb-name"></div>
                    
                    <div class="fb-login-button" autologoutlink="true" data-scope="<?=FB_PERMS?>" data-show-faces="false" data-width="200" data-max-rows="1" onlogin=""></div>
                    
                    <div class="fb-load">
                    	
                    	<img src="img/fb-load.gif" />
                    	
                    </div>
                    
                    <?php endif; ?>
                    
                    <div class="tagline">Debut Album Coming Soon!</div>
                                        
                    <div class="box-3" id="social-popout">
                        
                        <div class="inner">
                            
                            <?=TEXT_NO_JS?>
                            
                            <img alt="Share!" src="img/share.png" />
                            
                            <div class="buttons center">
                                
                                <div id="fb-like">
                                    
                                    <?=CODE_LIKE?>
                                    
                                </div>
                                
                                <div id="plus-one">
                                        
                                    <?=CODE_PLUS?>
                                    
                                </div>
                                
                                <div id="tweet">
                                        
                                    <?=CODE_TWTR?>
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>
                    
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
							print '<a href="' . $aParts[1] . ( ( isset($d) ) ? "&d" : "" ) . '">' . strtoupper( $aParts[0] ) . '<div class="fade-in hover">' . strtoupper( $aParts[0] ) . '</div></a>' . "\r\n" . tabs(5);
						
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
                            
							<div class="box-2" id="archive-popout">
                                    
                                <div class="inner">
                                    
                                    <?=TEXT_NO_JS?>
                                    
                                </div>
                                    
                            </div>
                            
							<div id="loaded">
                            	
