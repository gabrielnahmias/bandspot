<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= NAME . TEXT_DIVIDER . ( !isset( $aOG['title'] ) ? $sPage : $aOG['title'] ) ?></title>

<base href="http://<?=$_SERVER['HTTP_HOST']."/".urlPath()?>" />

<link href="img/f.ico" rel="shortcut icon" />

<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css" />

<link href="<?=TEXT_MIN_F?>styles.css,960.css,reset.css,text.css&b=<?=$sPath?>css" rel="stylesheet" type="text/css" />
<link href="<?=TEXT_MIN_F?><?= $sPath . ( ($bI) ? "cb/colorbox" : "hs/highslide" ) ?>.css" rel="stylesheet" type="text/css" />
<?php if ($bI): css_add(); ?>
<link rel="apple-touch-icon-precomposed" href="img/touch.png"/>
<?php endif; ?>

<meta name="description" content="<?=$aMeta[$pg]?>" />
<meta name="keywords" content="<?= implode(",", $aKeywords) . ",$pg" ?>" />
<meta name="author" content="Gabriel Nahmias" />
<meta name="robots" content="index, follow" />

<?php if ($bI): ?>
<meta name="viewport" content = "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<?php endif; ?>
<meta property="og:site_name"			 content="<?=NAME?>" />
<meta property="fb:admins"				 content="<?=ID_FB_ADMINS?>" />
<meta property="fb:app_id"				 content="<?=ID_FB_APP?>" />
<meta property="og:url"					 content="<?= ( ( !isset($aOG['url'] ) ) ? DOMAIN . "/$pg" : $aOG['url'] ) ?>" />
<meta property="og:title"				 content="<?= ( ( !isset($aOG['title'] ) ) ? NAME . TEXT_DIVIDER . $sPage : $aOG['title'] ) ?>" />
<meta property="og:type"				 content="<?=$aOG['type']?>" />
<meta property="og:image"				 content="<?= DOMAIN . "/" . DIR_IMG . "/" . $aOG['picture'] ?>" />
<meta property="og:description"			 content="<?= ( ( !isset( $aOG['description'] ) ) ? $aMeta[$pg] : $aOG['description'] ) ?>" />
<?php if ( isset( $_GET['xnewsaction'] ) ): ?>
<meta property="article:published_time"  content="<?=$aOG['published']?>">
<meta property="article:modified_time"   content="<?=$aOG['modified']?>">
<meta property="article:expiration_time" content="<?=$aOG['expires']?>">
<meta property="article:author"          content="<?=$aOG['author']?>">
<meta property="article:section"         content="music">
<!-- <meta property="article:tag"             content=""> -->
<?php endif; ?>

<!--[if lte IE 8]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" type="text/javascript"></script>

<script language="javascript" src="<?= TEXT_MIN_F . $sJS ?>buzz.js,<?=$sJS?>easing.js,<?php if (!$bI): ?><?=$sJS?>flux.js,<?=$sJS?>wp.js<?php else: ?><?=$sJS?>orientation.js<?php endif; ?>,<?=$sPath?><?=( ($bI) ? "cb/colorbox" : "hs/highslide" ) ?>.js,<?=$sJS?>modernizr.js,<?=$sJS?>scripts.js,<?=$sJS?>textshadow.js<?=( ($bWK && !$bI) ? "," . $sJS . "zepto.js" : "" )?>,<?=$sJS?>fb.js,<?=$sJS?>ga.js,<?=$sPath . DIR_JS_LOGIC?>/news.js" type="text/javascript"></script>

</head>

<body<?=( ($bI) ? " onorientationchange=\"updateOrientation();\" onload=\"updateOrientation();\"" : "" )?>>
	
    <div class="wrapper">
        <?php if (!$bI): ?>
        
        <div id="fb-root"></div>
        
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
                    
                    <a href="home"><div id="logo"></div></a>
                
                </div>
                
                <div class="clear"></div>
                
                <div class="toolbar">
                    
                    <?php if (!$bI): ?>
                    
                    <img id="fb-picture" src="img/spacer.png" />
                    
                    <div id="fb-name"></div>
                    
                    <div class="fb-login-button" autologoutlink="true" data-scope="<?=FB_PERMS?>" data-show-faces="false" data-width="200" data-max-rows="1" onlogin="fbInfo()"></div>
                    
                    <div class="fb-load">
                    	
                    	<img src="img/fb-load.gif" />
                    	
                    </div>
                    
                    <?php endif; ?>
                    
                    <div class="tagline">Debut Album Coming Soon!</div>
                    
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
                            
                            <?php if ($pg != "ar"): ?>
                            
							<div class="box-2" id="archive-popout">
                                    
                                <div class="inner">
                                    
                                    <?=TEXT_NO_JS?>
                                    
                                </div>
                                    
                            </div>
                            
                            <?php endif; ?>
                            
							<div id="loaded">
                            	
