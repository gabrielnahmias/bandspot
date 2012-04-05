<?php

/*
 ========================================================================
 |  Copyright 2007 - Andrei Dumitrache. All rights reserved.
 | 	 http://www.hogsmeade-village.com
 |
 |   This file is part of Elemovements News (X-News) v2.0.0
 |
 |   This program is Freeware.
 |   Please read the license agreement to find out how you can modify this web script.
 |
 ========================================================================
*/

$incFiles['skin'] =1;


function ShowActionPage($actpage)
{
  require("config.inc.php");
  $wrapper=file_get_contents($script['paths']['file']."inc/wrapper.html");
  $page=file_get_contents($script['paths']['file']."inc/".$actpage.".html");
  $wrapper=str_replace('{PAGE}',$page,$wrapper);
  $wrapper=ApplyLanguage($wrapper,"_cp");
  return $wrapper;
}

function ShowActionPage2($actpage)
{
    require_once("functions.php");
    $wrapper=ShowActionPage($actpage);
    $nick=$_COOKIE['xusername'];
    $userinfo=GetUserInfo($nick);
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">[$__logout]</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $wrapper=ApplyLanguage($wrapper,"_cp");
    return $wrapper;
}

function ShowMessage($title,$message)
{
   require_once("functions.php");
   $wrapper=ShowActionPage2("message");
   $wrapper=str_replace('{INFO_TITLE}',$title,$wrapper);
   $wrapper=str_replace('{INFO_BODY}',$message,$wrapper);
   $wrapper=ApplyLanguage($wrapper,"_cp");
   return $wrapper;
}

function ShowDlgPage($action,$vars,$postvec)
{
  require("config.inc.php");
  $nick=$_COOKIE['xusername'];
  $userinfo=GetUserInfo($nick);
  $wrapper=file_get_contents($script['paths']['file']."inc/wrapper.html");
  $page=file_get_contents($script['paths']['file']."inc/dlg.html");
  $wrapper=str_replace('{PAGE}',$page,$wrapper);
  $wrapper=str_replace('{FORM_ACTION}',$action,$wrapper);
  $vars=explode(',',$vars);
  $fields='';
  foreach ($vars as $i=>$v)
  {
    $a=$postvec[$v];
    if (is_array($a)==true)
    {
    foreach ($a as $j=>$sv)
    {
     $fields.='<input type="hidden" name="'.$v.'[]" value="'.$sv.'">';

    }}else
    {
     $fields.='<input type="hidden" name="'.$v.'" value="'.$a.'">';
    }
  }
  $wrapper=str_replace("{FIELDS}",$fields,$wrapper);
      $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','Logged in as: ',$wrapper);
      $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
      $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
      $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">[Log out]</a>',$wrapper);
      $wrapper=ApplyLanguage($wrapper,"_cp");
  return $wrapper;
}

function ShowInputDlgPage($header,$action,$lbl,$def)
{
  require("config.inc.php");
  $wrapper=file_get_contents($script['paths']['file']."inc/wrapper.html");
  $page=file_get_contents($script['paths']['file']."inc/inputdlg.html");
    $nick=$_COOKIE['xusername'];
    $userinfo=GetUserInfo($nick);
  $wrapper=str_replace('{PAGE}',$page,$wrapper);
  $wrapper=str_replace('{FORM_ACTION}',$action,$wrapper);
  $wrapper=str_replace('{HEADER}',$header,$wrapper);
  $wrapper=str_replace('{LABEL}',$lbl,$wrapper);
  $wrapper=str_replace('{DEF}',$def,$wrapper);
  $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','Logged in as: ',$wrapper);
  $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">[Log out]</a>',$wrapper);
  $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
  $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
  $wrapper=ApplyLanguage($wrapper,"_cp");
  return $wrapper;
}



function SkinNewsArticle($article,$tpl,$xsection = 0)
{
 require("config.inc.php");
 require_once("functions.php");
 if (empty($tpl)){$tpl=file_get_contents($script['paths']['file']."templates/".GetDefaultTemplate()."/news-article.html");}

        $article['activationdate']=DecodeTime($article['activationdate']);
        $c=str_replace('{title}',$article['title'],$tpl);
                $c=ApplyLanguage($c,'');
        $c=str_replace('{article}',$article['short_story'],$c);
        $c=str_replace('{date}',$article['activationdate'],$c);		// THIS IS WHERE THE {DATE} TAG IS BEING REPLACED!!!

        $icon=$article['icon'];
        if ($icon=='')
        {
         $icon=GetCatIcon($article['cat']);
        }

        if ($icon=='')
        {
         $c=str_replace('{icon}','',$c);
        }else
        {
         $c=str_replace('{icon}','<img src="'.$icon.'" align="left">',$c);
        }

        $authorline='';
        $uinfo=GetUserInfo($article['author']);
        if (!empty($uinfo['email']))
        {
         $authorline='<a href="mailto:'.$uinfo['email'].'">'.$uinfo['name'].'</a>';
        } else {
         $authorline=$uinfo['name'];
        }
        $c=str_replace('{author}',$authorline,$c);
        if ($script['comments']['enabled']=="ON")
        {
         $comlink = $_SERVER['SCRIPT_NAME'].'?xnewsaction=getcomments&amp;newsarch='.$article['archive'].'&amp;newsid='.$article['id'];
         if ($xsection!=0) $comlink.="&amp;xsection={$xsection}";
         $c=str_replace('{com-link}','<a href="'.$comlink.'">',$c);
         $c=str_replace('{/com-link}','</a>',$c);
        }else
        {
         $c1=substr($c,0,strpos($c,'{com-link}'));
         $c2=substr($c,strpos($c,'{/com-link}')+11);
         $c=$c1.$c2;
        }
		$sPrefix = "http://{$_SERVER['HTTP_HOST']}";
		$sPrefix = trim($sPrefix, "/");
        $c=str_replace('{num-com}',NumComments($article['archive'],$article['id']),$c);
		$linkfull = "news/{YEAR}/{MONTH}/{ID}";//$_SERVER['SCRIPT_NAME'].'?xnewsaction=fullnews&amp;newsarch='.$article['archive'].'&amp;newsid='.$article['id'];
		$linkfull=str_replace('{YEAR}',substr( $article['archive'] , -4 ),$linkfull);
		$linkfull=str_replace('{MONTH}',substr( $article['archive'] , 0 , 2 ),$linkfull);
		$linkfull=str_replace('{ID}',$article['id'],$linkfull);
  		if ($article['full_story']!=''){
			$c=str_replace('{url}',"$sPrefix/$linkfull",$c);		// EXTRA SLASH POSSIBLE SOURCE
         if ($xsection!=0) $linkfull.= "&amp;xsection={$xsection}";
         $c=str_replace('{full_news_link}','<a href="'.$linkfull.'">',$c);
         $c=str_replace('{/full_news_link}','</a>',$c);
        }else
        {
         $c1=substr($c,0,strpos($c,'{full_news_link}'));
         $c2=substr($c,strpos($c,'{/full_news_link}')+17);
         $c=$c1.$c2;
        }
		$c=str_replace('{url}',"$sPrefix/$linkfull",$c);
        $c=str_replace('{newsarch}',$article['archive'],$c);
        $c=str_replace('{newsid}',$article['id'],$c);
        $c=str_replace('{cat-name}',GetCatName($article['cat']),$c);
        $c=str_replace('{cat-id}',$article['cat'],$c);
        $c=str_replace('{self}',$_SERVER['SCRIPT_NAME'],$c);
        
		return $c;
}

function SkinRandomAd($tpl='')
{
  require_once("functions.php");
  require("config.inc.php");
  if (empty($tpl) || $tpl==''){$tpl=@file_get_contents($script['paths']['file']."templates/".GetDefaultTemplate()."/ad.html");} else
  {
   $tpl=@file_get_contents($script['paths']['file']."templates/".$tpl."/ad.html");
  }
  if ($tpl=="") return '';
  $ads=ListAds();
  $id=rand(0,count($ads)-1);
  $ad=$ads[$id];
  $tpl=str_replace("{AD_CODE}",$ad['code'],$tpl);
  $tpl=str_replace("{AD_NAME}",$ad['name'],$tpl);
  return $tpl;
}

function SkinDefinedAd($id,$tpl='')
{
  require_once("functions.php");
  require("config.inc.php");
  if (empty($tpl) || $tpl==''){$tpl=@file_get_contents($script['paths']['file']."templates/".GetDefaultTemplate()."/ad.html");} else
  {
   $tpl=@file_get_contents($script['paths']['file']."templates/".$tpl."/ad.html");
  }
  if ($tpl=="") return '';
  $ad=@GetAd($id);
  if (empty($ad)) return '';
  $tpl=str_replace("{AD_CODE}",$ad['code'],$tpl);
  $tpl=str_replace("{AD_NAME}",$ad['name'],$tpl);
  return $tpl;
}



//print SkinRandomAd();

function SkinFullNewsArticle($article,$tpl,$xsection=0)
{
        $article['activationdate']=DecodeTime($article['activationdate']);
        $c=str_replace('{title}',$article['title'],$tpl);
        $c=ApplyLanguage($c,'');
        if ($article['full_story']=='')
        $c=str_replace('{article}',$article['short_story'],$c);
        else
         $c=str_replace('{article}',$article['full_story'],$c);
        $c=str_replace('{date}',$article['activationdate'],$c);
        if (empty($icon))
         $icon=GetCatIcon($article['cat']);

        if ($icon=='')
        {
         $c=str_replace('{icon}','',$c);
        }else
        {
         $c=str_replace('{icon}','<img src="'.$icon.'" align="left">',$c);
        }
        $authorline='';
        $uinfo=GetUserInfo($article['author']);
        if (!empty($uinfo['email']))
        {
         $authorline='<a href="mailto:'.$uinfo['email'].'">'.$uinfo['name'].'</a>';
        } else {
         $authorline=$uinfo['name'];
        }
		
		$linkfull = "news/{YEAR}/{MONTH}/{ID}";
		$linkfull=str_replace('{YEAR}',substr( $article['archive'] , -4 ),$linkfull);
		$linkfull=str_replace('{MONTH}',substr( $article['archive'] , 0 , 2 ),$linkfull);
		$linkfull=str_replace('{ID}',$article['id'],$linkfull);
		
		$c=str_replace('{url}',"$sPrefix/$linkfull",$c);
        $c=str_replace('{author}',$authorline,$c);
        $c=str_replace('{newsarch}',$article['archive'],$c);
        $c=str_replace('{newsid}',$article['id'],$c);
        $c=str_replace('{cat-name}',GetCatName($article['cat']),$c);
        $c=str_replace('{cat-id}',$article['cat'],$c);
        $c=str_replace('{self}',$_SERVER['SCRIPT_NAME'],$c);
        $comlink = $_SERVER['SCRIPT_NAME'].'?xnewsaction=getcomments&amp;newsarch='.$article['archive'].'&amp;newsid='.$article['id'];
        if ($xsection!=0) $comlink.= "&amp;xsection={$xsection}";
        $c=str_replace('{com-link}','<a href="'.$comlink.'">',$c);
        $c=str_replace('{/com-link}','</a>',$c);
        $c=str_replace('{num-com}',NumComments($article['archive'],$article['id']),$c);
   return $c;

}

function GenerateEmoticonList($c,$limit,$id=0)
{
  require_once("functions.php");
  require("config.inc.php");
  $emos=ListEmoticons();
  if ($limit=="")
  {
   $limit=count($emos);
  }
  $tbl='';
  $c1=0;
  foreach ($emos as $i=>$emo)
  {
    $emname=substr($emo,0,strpos($emo,'.'));
    if ($i>=$limit){break;}
    $emourl=$script['paths']['url']."smillies/".$emo;
    if ($c=="no-link")
    {
    $tbl.='<img src="'.$emourl.'" border="0">';
    }else if ($c=="popup")
    {
      $tbl.='<a href="javascript:opener.InsertBBCode(\''.$id.'\',\'[::'.$emname.']\');"><img src="'.$emourl.'" border="0" alt="'.$emname.'" /></a> ';
    }else if ($c=="live")
    {
      $tbl.='<img src="'.$emourl.'" border="0" id="'.$emname.'" onclick="InsertLiveEmoticon(\''.$emname.'\',\''.$id.'\',this);" alt="'.$emname.'" />';
    }else if ($c=="livepopup")
    {
      $tbl.='<img src="'.$emourl.'" border="0" id="'.$emname.'" onclick="opener.InsertLiveEmoticon(\''.$emname.'\',\''.$id.'\',this);" alt="'.$emname.'" />';
    }
    else
    {
    $tbl.='<a href="javascript:InsertBBCode(\''.$id.'\',\'[::'.$emname.']\');"><img src="'.$emourl.'" border="0" alt="'.$emname.'" /></a>';
    }
  }
  return $tbl;
}


function SkinCommentsPage($article,$comments,$template,$thispage,$range,$xsection=0){
	global $script;
	if (empty($range))
		$range='1-25';
	require_once("functions.php");
	$newsarch			=	validArchive($article['archive']);
	$newsid				=	intval($article['id']);
	$wrapper			=	file_get_contents($script['paths']['file']."templates/$template/comments-wrapper.html");
	$addform			=	file_get_contents($script['paths']['file']."templates/$template/add-comment.html");
	$commenttpl			=	file_get_contents($script['paths']['file']."templates/$template/comment.html");
	$newsarticletpl		= 	file_get_contents($script['paths']['file']."templates/$template/news-article.html");
	
	$fullnewsarticletpl	= file_get_contents($script['paths']['file']."templates/$template/full-news-article.html");
	if ($script['comments']['captcha']!="ON"){
		$addform1=substr($addform,0,strpos($addform,'<!--BEGIN CAPTCHA//-->'));
		$addform2=substr($addform,strpos($addform,'<!--END CAPTCHA//-->')+20);
		$addform=$addform1.$addform2;
	}else{                       
		$addform=str_replace('{CAPTCHAIMAGE}','<img src="'.$script['paths']['url'].'captcha.php" alt="Verification Code" border="0" />',$addform);
		$addform=str_replace('{MD5CAPTCHA}',"",$addform);
	}

	$author		= '';
	$comment	= '';
	$email		= '';
	if (isset($_POST['author']))
		$author=$_POST['author'];
	if (isset($_POST['comment']))
		$comment=$_POST['comment'];
	if (isset($_POST['email']))
		$email=$_POST['email'];

	$addform=str_replace('{FORM_AUTHOR}',$author,$addform);
	$addform=str_replace('{FORM_COMMENT}',$comment,$addform);
	$addform=str_replace('{FORM_EMAIL}',$email,$addform);

	$wrapper=str_replace('{full_story}',SkinFullNewsArticle($article,$fullnewsarticletpl),$wrapper);
	$wrapper=str_replace('{short_story}',SkinNewsArticle($article,$newsarticletpl),$wrapper);
	
	$comcode='';
	$validlogin  = ValidateLogin($_COOKIE['xusername'],$_COOKIE['xpassword']);
	if ($validlogin){
		$caneditown   =  CheckPerms($_COOKIE['xusername'],'editowncomments');
		$caneditother =  CheckPerms($_COOKIE['xusername'],'editothercomments');
	}else{
		$caneditown   = 0;
		$caneditother = 0;
	}
	if (!empty($comments))
		foreach ($comments as $item){
			$temp=$commenttpl;
			$temp=str_replace('{author}',$item['author'],$temp);
			$item['date']=DecodeTime($item['date']);
			$temp=str_replace('{date}',$item['date'],$temp);
			$temp=str_replace('{comment}',$item['comment'],$temp);
			$canedit=0;
			if ($validlogin){
				if ($item['author']==$_COOKIE['xusername']) $canedit = $caneditown;
				if ($item['author']!=$_COOKIE['xusername']) $canedit = $caneditother;
			} else
			$canedit=0;

			if (!$canedit) 
				$temp	= SubstractHTMLCode('<!--BEGIN_ADMIN_CMD//-->(*)<!--END_ADMIN_CMD//-->',$temp);
			else
				$temp  	= str_replace('{delcomment-link}',$thispage.'?xnewsaction=delcomment&amp;newsarch='.$newsarch.'&amp;newsid='.$newsid.'&amp;commentid='.$item['id'],$temp);
   
			$comcode.=$temp;
		}
   
	$wrapper	=	str_replace('{comments}',$comcode,$wrapper);
	$formlink	=	$thispage.'?xnewsaction=addcomment&amp;newsarch='.$newsarch.'&amp;newsid='.$newsid;
	if ($xsection!=0) 
		$formlink.="&amp;xsection={$xsection}";
	$wrapper=str_replace('{add_comment_form}',$addform,$wrapper);
	$wrapper=str_replace('{form_action}',$formlink,$wrapper);
	$wrapper=str_replace('{script_url}',$script['paths']['url'],$wrapper);
	$wrapper=str_replace('{template}',$template,$wrapper);
	$wrapper=str_replace('{HTML_ON}',$script['comments']['html'],$wrapper);
	$wrapper=str_replace('{BBCODE_ON}',$script['comments']['bbcode'],$wrapper);
	$len='';
	if ($script['comments']['messagelength']=="0"){$len='unlimited';}else{$len=$script['comments']['messagelength'];}
	$wrapper=str_replace('{MSG_LENGTH}',$len,$wrapper);

	$pagination=GeneratePaginationLinks($newsarch,$newsid,25,$range,$thispage);
	$wrapper=str_replace('{pagination}',$pagination,$wrapper);
	$wrapper=str_replace('{emoticons}',GenerateEmoticonList("",15,'comment'),$wrapper);


	if ($validlogin){
		$part1=substr($wrapper,0,strpos($wrapper,"<!--NICKFIELD//-->"));
		$part2=substr($wrapper,strpos($wrapper,'<!--ENDNICKFIELD//-->')+21);
		$wrapper=$part1.'<input type="hidden" name="author" value="'.$_COOKIE['xusername'].'">'.$_COOKIE['xusername'].$part2;
		$wrapper=str_replace("{loginout}",'<a href="#" onclick="window.open(\''.$script['paths']['url'].'session.php?xnewsaction=logout\', \'remote\', \'width=640,height=250\');return false;">$__logout</a>',$wrapper);
	}else
	     $wrapper=str_replace("{loginout}",'<a href="#" onclick="window.open(\''.$script['paths']['url'].'session.php?xnewsaction=login\', \'remote\', \'width=640,height=250\');return false;">$__login</a>',$wrapper);
	return $wrapper;
}

function SkinLoginPage($template,$postvar,$error,$action,$custom=''){
	global $script;
	require("config.inc.php");
	$wrapper	=	file_get_contents($script['paths']['file']."templates/$template/login.html");
	if (!$error)
		$wrapper=str_replace('{message}',$custom,$wrapper);
	else
		$wrapper=str_replace('{message}','$__msg_loginwrong',$wrapper);
   
	$wrapper=str_replace('{message}',$message,$wrapper);
	$wrapper=str_replace('{script_url}',$script['paths']['url'],$wrapper);
	$wrapper=str_replace('{template}',$template,$wrapper);
	$wrapper=str_replace('{form-action}',$action,$wrapper);
	if (empty($postvar))
		$wrapper=str_replace('{form-fields}','',$wrapper);
	else{
		$fields='';
		foreach (array_keys($postvar) as $i=>$key)
			if ($key!='xuser' && $key!='xpass')
				$fields.='<input type="hidden" name="'.$key.'" value="'.$postvar[$key].'">';
		$wrapper=str_replace('{form-fields}',$fields,$wrapper);
	}
	return $wrapper;
}

function SkinMessage($message,$template){
	global $script;
	$wrapper=file_get_contents($script['paths']['file']."templates/$template/message.html");
	$wrapper=str_replace('{message}',$message,$wrapper);
	$wrapper=str_replace('{script_url}',$script['paths']['url'],$wrapper);
	$wrapper=str_replace('{template}',$template,$wrapper);
	return $wrapper;
}

function ApplyLanguage($input,$mode){
	global $script;
	$language_file	=	$script['paths']['file']."language/".$script['system']['language']."/language".$mode.".php";
	include($language_file);
	$lang_keys		=	array_keys($lng);
	foreach ($lang_keys as $lang_key){
			$rep='$__'.$lang_key;
			$dat=urldecode($lng[$lang_key]);
			$input=str_replace($rep,$dat,$input);
	}
	$language_file=$script['paths']['file']."language/english/language".$mode.".php";
	if (file_exists($language_file)){
		include($language_file);
		$lang_keys=array_keys($lng);
		foreach ($lang_keys as $lang_key){
			$rep='$__'.$lang_key;
			$dat=urldecode($lng[$lang_key]);
			$input=str_replace($rep,$dat,$input);
		}

	}
	return $input;
}

function LanguageField($field,$mode='_cp'){
	global $script;
	$language_file=$script['paths']['file']."language/".$script['system']['language']."/language$mode.php";
	include($language_file);
	return $lng[$field];
}


function GenerateLanguageMenu(){
	global $script;
	$language_root=$script['paths']['file']."language/";
	$handle=dir($language_root);
	$code='';
	while (false !== ($item = $handle->read())){
		$language_dir=$language_root.$item;
		if (is_dir($language_dir) && $item!='.' && $item!='..'){
			if ($item==$script['system']['language'])
				$code.='<option selected value="'.$item.'">'.$item.'</option>';
			else
				$code.='<option value="'.$item.'">'.$item.'</option>';
 		}	
	}
	$handle->close();
	return $code;
}

function ShowWYSIWYG($id,$name,$wrapper){
	$xwrapper=file_get_contents("rtf/richedit.html");
	$xwrapper=str_replace("{ID}",$id,$xwrapper);
	$xwrapper=str_replace('{SMILLIES}',GenerateEmoticonList('live',15,$id),$xwrapper);
	$wrapper=str_replace($name,$xwrapper,$wrapper);
	return $wrapper;
}

function ShowBBEditor($id,$tag,$name,$wrapper){
	$xwrapper=file_get_contents("bbedit/bbedit.html");
	$xwrapper=str_replace("{ID}",$id,$xwrapper);
	$xwrapper=str_replace('{SMILLIES}',GenerateEmoticonList("",15,$name),$xwrapper);
	$xwrapper=str_replace('{INPUTNAME}',$name,$xwrapper);
	$wrapper=str_replace($tag,$xwrapper,$wrapper);
	return $wrapper;
}

function GenerateAdList($active,$selected=''){
	global $script;
	require_once("functions.php");
	$ads=ListAds();
	$code='';
	$key=$active."_selad";

	if ($selected=='') $selected=$script['advertising'][$key];
	for ($i=0; $i<count($ads);$i++){
		if ($ads[$i]['id']==$selected)	
			$code.='<option value="'.$ads[$i]['id'].'" selected>'.$ads[$i]['name'].' [ '.$ads[$i]['id'].' ] </option>';
		else
			$code.='<option value="'.$ads[$i]['id'].'">'.$ads[$i]['name'].' [ '.$ads[$i]['id'].' ] </option>';
			
	}
	return $code;
}


function IsAdPosition($num,$active){
	global $script;
	if ($active=="active") $key="activenews"; else $key="archivednews";
	if($script['advertising'][$key]!="YES") return 0;
	$madpos=$script['advertising'][$active.'_adpos'];
	$madpos=explode(" ",$madpos);
	foreach ($madpos as $i=>$adpos)
		if ($adpos==$num) return 1;
	return 0;
}

function PrintAd($template,$active){
	global $script;
	$key1='md_'.$active;
	if ($script['advertising'][$key1]=="selected") return SkinDefinedAd($script['advertising'][$active.'_selad'],$template);
	else return SkinRandomAd($template);
}


?>
