<?php

/*
 ========================================================================
 |  Copyright 2010 - Andrei Dumitrache. All rights reserved.
 | 	 http://www.dumitrache.net
 |
 |   This file is part of Elemovements News (X-News) v2.0.2
 |
 |   This program is Freeware.
 |   Please read the license agreement to find out how you can modify this web script.
 |
 ========================================================================
*/

error_reporting(0);

function GetOwnerSafe()
{
  $lines=@file("userdb.php");
  $userline=@$lines[1];
  $parts=explode("|",$userline);
  return $parts[0];
}

if (GetOwnerSafe()=='')
{
  header("Location: install.php");
  exit;
}
require("config.inc.php");
require("functions.php");
require("skin.php");


$action=@$_GET['a'];

if (file_exists("install.php"))
{
 print "You must delete the file install.php to be able to log in";
 exit;
}

if ($action=="login")
{
  setcookie('xusername','',NULL,'/');
  setcookie('xpassword','',NULL,'/');
  $page=file_get_contents($script['paths']['file']."inc/login.html");
  $wrapper=file_get_contents($script['paths']['file']."inc/wrapper.html");
  $wrapper=str_replace('{LABEL_LOGOUT}','',$wrapper);
  $wrapper=str_replace('{PAGE}',$page,$wrapper);
  $wrapper=str_replace('{LABEL_LOGGED_IN_AS}',' ',$wrapper);
  $wrapper=str_replace('{USER_NICK}',' ',$wrapper);
  OutputWrapper($wrapper);
  exit;
}

if ($action=="sendpass")
{
  setcookie('xusername','',NULL,'/');
  setcookie('xpassword','',NULL,'/');
  $page=file_get_contents($script['paths']['file']."inc/sendpassword.html");
  $wrapper=file_get_contents($script['paths']['file']."inc/wrapper.html");
  $wrapper=str_replace('{LABEL_LOGOUT}','',$wrapper);
  $wrapper=str_replace('{PAGE}',$page,$wrapper);
  $wrapper=str_replace('{LABEL_LOGGED_IN_AS}',' ',$wrapper);
  $wrapper=str_replace('{USER_NICK}',' ',$wrapper);
  OutputWrapper($wrapper);
  exit;

}

if ($action=="dosendpass")
{
 $username = $_POST['username'];
 $email    = $_POST['email'];
 if ($email=='') $email='@';
 $info     = GetUserInfo($username);
 if (@$info['semail']==$email)
 {
  if (CountPasswordReset($username)>0)  DeletePasswordReset($username);
  $cd        = AddPasswordReset($username);
  $resetlink = $script['paths']['url']."index.php?a=resetpass&amp;user=".$username."&amp;authcode=".$cd;
  $wrapper   = ShowMessage(LanguageField("page_resetpassword"),LanguageField("msg_resetlinksent"));
  $mailm     = LanguageField("email_resetlink");
  $mailm     = str_replace('{ACCOUNTNAME}',$username,$mailm);
  $mailm     = str_replace('{LINK}',$resetlink,$mailm);
  mail($email,LanguageField("email_sresetlink"),$mailm);
 }
 else $wrapper=ShowMessage(LanguageField("page_resetpassword"),LanguageField("msg_resetnoemail"));
 OutputWrapper($wrapper);
 exit;
}

if ($action=="resetpass")
{
  if (ValidatePasswordReset($_GET['user'],$_GET['authcode'])==1)
  {
    $nfo     =   GetUserInfo($_GET['user']);
    setcookie('xusername',$_GET['user'],NULL,'/');
    setcookie('xpassword',$nfo['password'],NULL,'/');
    DeletePasswordReset($_GET['user']);
    $wrapper=ShowActionPage2("resetpass");
  }else
  {
    $wrapper=ShowMessage(LanguageField("page_resetpassword"),LanguageField("msg_resetinvalid"));
  }
  OutputWrapper($wrapper);
  exit;
}

if ($action=="checklogin")
{

  if (ValidateLogin($_POST['username'],md5($_POST['password']))==0)
  {
    header('Location: '.$script['paths']['url'].'index.php?a=login');
    die();
  }else
  {
    setcookie('xusername',$_POST['username'],NULL,'/');
    setcookie('xpassword',md5($_POST['password']),NULL,'/');
    header("Location: ".$script['paths']['url']."index.php?a=home");
    exit;
  }
}


if (ValidateLogin($_COOKIE['xusername'],$_COOKIE['xpassword'])==0)
{
    header('Location: '.$script['paths']['url'].'index.php?a=login');
    exit;
}

if (empty($action))
{
    header('Location: '.$script['paths']['url'].'index.php?a=home');
    exit;
}

$nick=$_COOKIE['xusername'];
if ($script['editor']['wysiwyg']=='ON')
{
 if (!isset($_COOKIE['xuser-enable-wysiwyg'])) $gtml=0;else
 {
   if ($_COOKIE['xuser-enable-wysiwyg']=="1") $ghtml=1; else $ghtml=0;
 }
}else $ghtml=0;



if ($action=="nopermissions")
{
    $wrapper=ShowMessage(LanguageField("hmsg_forbidden"),LanguageField("msg_forbidden"));
    OutputWrapper($wrapper);
    exit;
}

if ($action=="doresetpass")
{
  $info = GetUserInfo($nick);
  UpdateUser($nick,$info['name'],$_POST['password'],$info['level'],$info['email'],$info['semail'],$info['avatar']);
  $wrapper=ShowMessage(LanguageField("page_resetpassword"),LanguageField("msg_resetdone"));
  OutputWrapper($wrapper);
  exit;
}

if ($action=="home")
{

  $userinfo=GetUSerInfo($nick);
  $wrapper=ShowActionPage2("home");
  $sizeinkb=dirsize()/1024;
  $sizeinkb=round($sizeinkb,2);
  $wrapper=str_replace('$__vimsg_welcome',LanguageField("msg_welcome"),$wrapper);
  $wrapper=str_replace('{USER_POSTS}',numposts($nick),$wrapper);
  $wrapper=str_replace('{S_ALLNEWS}',numposts(''),$wrapper);
  $wrapper=str_replace('{S_DISPNEWS}',NumDispPosts(),$wrapper);
  $wrapper=str_replace('{S_SIZE}',$sizeinkb." KB",$wrapper);
  $wrapper=str_replace('{S_USERS}',NumUsers(),$wrapper);
  $wrapper=str_replace('{S_DISPCOMMENTS}',totalcomments(),$wrapper);
  $wrapper=str_replace('{S_TIME}',date("H:i:s - d/m/Y"),$wrapper);
  $wrapper=str_replace('{A_TIME}',date("H:i:s - d/m/Y",time()+($script['system']['time_adjust']*60)),$wrapper);
  $wrapper=str_replace('{S_ARCHIVES}',(count(getarchlist("newest"))),$wrapper);
  $wrapper=str_replace('{S_VER}',$script['current_version'],$wrapper);
  $wrapper=str_replace('{S_LVER}',WriteLatestVersion(),$wrapper);

  if (IsLicensed()==0) $reclic='$__l_recfreelicense'; else $reclic='$__l_recprolicense';

  $wrapper=str_replace('{REC_LICENSE}',$reclic,$wrapper);

  $noaccess=ListNoAccess();
  if ($noaccess!=''){$wrapper=str_replace('{MODMESSAGE}','<span class="ErrorClass1"><b>$__msg_notfullaccess</b><BR>'.$noaccess.'</span>',$wrapper);} else
  {$wrapper=str_replace('{MODMESSAGE}','<span class="SpanClass1">$__msg_fullaccess</span>',$wrapper);}
  if (empty($userinfo['lastpost']))
  {
  $wrapper=str_replace('$__msg_yourlastpost','',$wrapper);
  }else
  {
  $wrapper=str_replace('$__msg_yourlastpost',LanguageField("msg_yourlastpost"),$wrapper);
  }
  if (!empty($userinfo['lastpost']))
  {
  $wrapper=str_replace("{USER_LASTPOST}",DecodeTime($userinfo['lastpost']),$wrapper);
  }else
  {
  $wrapper=str_replace("{USER_LASTPOST}","",$wrapper);
  }
  $wrapper=str_replace("{USER_NAME}",$userinfo['name'],$wrapper);

  $fx="index.php?a=home";
  OutputWrapper($wrapper);


}

if ($action=='addnews')
{

  $nick=$_COOKIE['xusername'];
  if (!CheckPerms($nick,'postnews')){header("Location: index.php?a=nopermissions");}
  $userinfo=GetUSerInfo($nick);
  $wrapper=ShowActionPage2("posting");
  $wrapper=str_replace('{HEADER}','$__page_addarticles',$wrapper);
  $wrapper=str_replace('{NEWS_HEADLINE}','',$wrapper);
  $wrapper=str_replace('{NEWS_ICON}','',$wrapper);
  $wrapper=str_replace('{EDIT_INFO}','',$wrapper);

  if ($ghtml)
  {
   $wrapper=ShowWYSIWYG('1','{SHORTSTORY_EDITOR}',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}','',$wrapper);
   $wrapper=ShowWYSIWYG('2','{FULLSTORY_EDITOR}',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}','',$wrapper);
   $wrapper=str_replace('{FORM_ONSUBMIT}','UpdateTextBox(\'1\');UpdateTextBox(\'2\');',$wrapper);
   $wrapper=str_replace('{CHANGE_EDITOR}','<a href="#" onClick="window.open(\'index.php?a=cheditor&amp;e=bbcode\', \'\', \'HEIGHT=100,resizable=yes,scrollbars=no,WIDTH=100\');return false;" class="SpanClass1">[ $__l_activatebbeditor ]</a>',$wrapper);

  }else
  {
   $wrapper=ShowBBEditor('1','{SHORTSTORY_EDITOR}','shortstory',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}','',$wrapper);

   $wrapper=ShowBBEditor('2','{FULLSTORY_EDITOR}','fullstory',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}','',$wrapper);
   $wrapper=str_replace('{FORM_ONSUBMIT}',"OnNewsSubmit(1);OnNewsSubmit(2);if ('$action' == 'addnews') { SocialPost(); return false; }",$wrapper);
   if ($script['editor']['wysiwyg']=='ON')
   {
    $wrapper=str_replace('{CHANGE_EDITOR}','<a href="#" onClick="window.open(\'index.php?a=cheditor&amp;e=wysiwyg\', \'\', \'HEIGHT=100,resizable=yes,scrollbars=no,WIDTH=100\');return false;" class="SpanClass1">[ $__l_activatewysiwyg ]</a>',$wrapper);
   } else
   {
    $wrapper=SubstractHTMLCode('<!--BEGIN_CHEDIT//-->(*)<!--END_CHEDIT//-->',$wrapper);
   }
  }
  $wrapper=str_replace('{FORM_ACTION}','index.php?a=doaddnews',$wrapper);

  if ($script['icons']['save']=="ON")
  {
  $wrapper=str_replace('{ICONSAVE}','<input type="checkbox" name="saveicon" value="SAVE:" checked> <span class="SpanClass1">$__l_saveicon</span>',$wrapper);
  }else
  {
   $wrapper=str_replace("{ICONSAVE}","",$wrapper);
  }
  $wrapper=str_replace('{PREVIEW}','',$wrapper);
  $wrapper=str_replace('{ACT_DISPLAY}','none',$wrapper);
  $wrapper=str_replace('{ACT_YEAR}',date("Y",EncodeTime('')),$wrapper);
  $wrapper=str_replace('{ACT_MONTH}',date("m",EncodeTime('')),$wrapper);
  $wrapper=str_replace('{ACT_DAY}',date("d",EncodeTime('')),$wrapper);
  $wrapper=str_replace('{ACT_HOUR}',date("H",EncodeTime('')),$wrapper);
  $wrapper=str_replace('{ACT_MINUTE}',date("i",EncodeTime('')),$wrapper);
  $wrapper=str_replace('{ACT_SECOND}',date("s",EncodeTime('')),$wrapper);
  $wrapper=str_replace('{CHECKED_ACTIVE}','checked',$wrapper);
  $wrapper=str_replace('{CHECKED_INACTIVE}','',$wrapper);
  $wrapper=str_replace('{NEWS_ARCH}',$newsarch,$wrapper);
  $wrapper=str_replace('{NEWS_ID}',$newsid,$wrapper);
  
  $sArch =  date("Y", EncodeTime('') ) . "/" . date("m", EncodeTime('') );
  $sFile = "news/news-" . date("m", EncodeTime('') ) . date("Y", EncodeTime('') ) . ".php";
  $sID = count( file($sFile) ) + 1;
  
  $wrapper=str_replace('{VARS}',"/news/$sArch/$sID",$wrapper); // This is for the Facebook link.
  
  $catlist=GetCatList();
  $catcode='';
  foreach ($catlist as $i=>$cat)
  {
    $catcode.="<option>".$cat['name']."</option>";
  }

  $wrapper=str_replace('{CATLIST}',$catcode,$wrapper);
  OutputWrapper($wrapper);
}

if ($action=='doeditnews')
{
 $newsid=$_POST['newsid'];
 $newsarch=$_POST['newsarch'];
 if (empty($newsid) or empty($newsarch))
 {
  header("Location: index.php?a=editnews");
 }

 $userinfo=GetUserInfo($nick);
 $headline=stripslashes($_POST['headline']);
 $icon=$_POST['icon'];
 $cat=$_POST['cat'];
 if ($ghtml==1)
 {
  $shortstory=stripslashes($_POST['textbox1']);
  $fullstory=stripslashes($_POST['textbox2']);
 }else
 {
  $shortstory=stripslashes($_POST['shortstory']);
  $fullstory=stripslashes($_POST['fullstory']);
 }
  if ($fullstory=='<br>' || $fullstory=='<br />') $fullstory='';
 if ($script['icons']['save']=="ON")
 {
   $saveicon=$_POST['saveicon'];
 }
 if (!isset($saveicon)){$saveicon='';}

  $active=$_POST['active'];

  if (@$_POST['postpone']=="1")
  {
   $activationdate=mktime($_POST['ahour'],$_POST['aminute'],$_POST['asecond'],$_POST['amonth'],$_POST['aday'],$_POST['ayear']);
  }else $activationdate="";

  if ($activationdate!="" && $activationdate<EncodeTime(''))
  {
   $wrapper=ShowMessage(LanguageField("hmsg_error"),LanguageField("msg_invalidactdate"));
  }else
  {
   EditNews($newsarch,$newsid,$headline,$saveicon.$icon,GetCatid($cat),$shortstory,$fullstory,$active,$activationdate,$nick);
   $wrapper=ShowMessage(LanguageField("hmsg_edited"),LanguageField("msg_newsedited"));
  }

 OutputWrapper($wrapper);
}



if ($action=='editnewsfrm')
{
 $newsid=$_GET['newsid'];
 $newsarch=$_GET['newsarch'];
 if (empty($newsid))
 {
  header("Location: index.php?a=editnews");
 }
 if (empty($newsarch))
 {
  header("Location: index.php?a=editnews");
 }
  $userinfo=GetUSerInfo($nick);
  if ($ghtml) $article=FullNews($newsarch,$newsid,'');else $article=FullNews($newsarch,$newsid,'frm');

  if ($nick==$article['author'])
  {
    if (!CheckPerms($nick,"editownnews")){header("Location: index.php?a=nopermissions");}
  }
  else
  {
    if (!CheckPerms($nick,"editothernews")){header("Location: index.php?a=nopermissions");}
  }
  $edit_info=file_get_contents($script['paths']['file']."inc/edit_info.html");
  $wrapper=ShowActionPage2("posting");
  $wrapper=str_replace('{NEWS_ARCH}',$newsarch,$wrapper);
  $wrapper=str_replace('{NEWS_ID}',$newsid,$wrapper);
  $wrapper=str_replace('{EDIT_INFO}',$edit_info,$wrapper);
  $wrapper=ApplyLanguage($wrapper,"_cp");
  if (isset($article['mod_date']) && !empty($article['mod_date']) && isset($article['mod_author']))
  {
   $wrapper=str_replace('{NEWS_MODDATE}',DecodeTime($article['mod_date']),$wrapper);
   $wrapper=str_replace('{NEWS_MODAUTHOR}',$article['mod_author'],$wrapper);

  }else
  {
  $wrapper=SubstractHTMLCode('<!--BEGIN_LASTEDIT//-->(*)<!--END_LASTEDIT//-->',$wrapper);
  }
  $wrapper=str_replace('{NEWS_AUTHOR}',$article['author'],$wrapper);
  $wrapper=str_replace('{NEWS_DATE}',DecodeTime($article['date']),$wrapper);
  $wrapper=str_replace('{FORM_ACTION}','index.php?a=doeditnews',$wrapper);
  $wrapper=str_replace('{NEWS_HEADLINE}',$article['title'],$wrapper);
  $wrapper=str_replace('{NEWS_ICON}',$article['icon'],$wrapper);


  if ($ghtml)
  {
   $wrapper=ShowWYSIWYG('1','{SHORTSTORY_EDITOR}',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}',ConvertLiveEmoCode($article['short_story']),$wrapper);
   $wrapper=ShowWYSIWYG('2','{FULLSTORY_EDITOR}',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}',ConvertLiveEmoCode($article['full_story']),$wrapper);
   $wrapper=str_replace('{FORM_ONSUBMIT}','UpdateTextBox(\'1\');UpdateTextBox(\'2\');',$wrapper);
   $wrapper=str_replace('{CHANGE_EDITOR}','<a href="#" onClick="window.open(\'index.php?a=cheditor&amp;e=bbcode\', \'\', \'HEIGHT=100,resizable=yes,scrollbars=no,WIDTH=100\');return false;" class="SpanClass1">[ $__l_activatebbeditor ]</a>',$wrapper);
  }else
  {

   $wrapper=ShowBBEditor('1','{SHORTSTORY_EDITOR}','shortstory',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}',$article['short_story'],$wrapper);
   $wrapper=ShowBBEditor('2','{FULLSTORY_EDITOR}','fullstory',$wrapper);
   $wrapper=str_replace('{INPUTTEXT}',$article['full_story'],$wrapper);
   $wrapper=str_replace('{FORM_ONSUBMIT}',"OnNewsSubmit(1);OnNewsSubmit(2);if ('$action' == 'addnews') { SocialPost(); return false; }",$wrapper);
   if ($script['editor']['wysiwyg']=='ON')
   {
    $wrapper=str_replace('{CHANGE_EDITOR}','<a href="#" onClick="window.open(\'index.php?a=cheditor&amp;e=wysiwyg\', \'\', \'HEIGHT=100,resizable=yes,scrollbars=no,WIDTH=100\');return false;" class="SpanClass1">[ $__l_activatewysiwyg ]</a>',$wrapper);
   } else
   {
    $wrapper=SubstractHTMLCode('<!--BEGIN_CHEDIT//-->(*)<!--END_CHEDIT//-->',$wrapper);
   }
  }

  $wrapper=str_replace('{HEADER}','$__page_editarticles',$wrapper);
  $wrapper=str_replace('{PREVIEW}','',$wrapper);
  if ($script['icons']['save']=="ON")
  {
  $wrapper=str_replace('{ICONSAVE}','<input type="checkbox" name="saveicon" value="SAVE:" checked> <span class="SpanClass1">$__l_saveicon</span>',$wrapper);
  }else
  {
   $wrapper=str_replace("{ICONSAVE}","",$wrapper);
  }
  $catlist=GetCatList();
  $catcode='';
  foreach ($catlist as $i=>$cat)
  {
    if ($cat['id']==$article['cat'])
    {
      $catcode.="<option selected>".$cat['name']."</option>";
    } else {
      $catcode.="<option>".$cat['name']."</option>";
     }
  }
  $wrapper=str_replace('{CATLIST}',$catcode,$wrapper);

  if ($article['activationdate']>EncodeTime(''))
  {
   $wrapper=str_replace('{ACT_DISPLAY}','',$wrapper);
   $wrapper=str_replace('{CHECK_POSTPONED}','checked',$wrapper);
   $wrapper=str_replace('{ACT_YEAR}',date("Y",$article['activationdate']),$wrapper);
   $wrapper=str_replace('{ACT_MONTH}',date("m",$article['activationdate']),$wrapper);
   $wrapper=str_replace('{ACT_DAY}',date("d",$article['activationdate']),$wrapper);
   $wrapper=str_replace('{ACT_HOUR}',date("H",$article['activationdate']),$wrapper);
   $wrapper=str_replace('{ACT_MINUTE}',date("i",$article['activationdate']),$wrapper);
   $wrapper=str_replace('{ACT_SECOND}',date("s",$article['activationdate']),$wrapper);
  }
  else
  {
   $wrapper=str_replace('{CHECK_POSTPONED}','',$wrapper);
   $wrapper=str_replace('{ACT_DISPLAY}','none',$wrapper);
   $wrapper=str_replace('{ACT_YEAR}',date("Y",EncodeTime('')),$wrapper);
   $wrapper=str_replace('{ACT_MONTH}',date("m",EncodeTime('')),$wrapper);
   $wrapper=str_replace('{ACT_DAY}',date("d",EncodeTime('')),$wrapper);
   $wrapper=str_replace('{ACT_HOUR}',date("H",EncodeTime('')),$wrapper);
   $wrapper=str_replace('{ACT_MINUTE}',date("i",EncodeTime('')),$wrapper);
   $wrapper=str_replace('{ACT_SECOND}',date("s",EncodeTime('')),$wrapper);
   $wrapper=SubstractHTMLCode('<!--{BEGIN_POSTPONE_GR}//-->(*)<!--{END_POSTPONE_GR}//-->',$wrapper);
  }
  if ($article['active']=="0")
  {
   $wrapper=str_replace('{CHECKED_INACTIVE}','checked',$wrapper);
   $wrapper=str_replace('{CHECKED_ACTIVE}','',$wrapper);
  }
  else
  {
   $wrapper=str_replace('{CHECKED_INACTIVE}','',$wrapper);
   $wrapper=str_replace('{CHECKED_ACTIVE}','checked',$wrapper);
  }

  OutputWrapper($wrapper);
}



if ($action=='cheditor')
{
   $wrapper='
	<html>
	<head></head>
	<body>
		<script language="JavaScript">
			opener.location.reload(false);
			window.close();
		</script>
	</body>
	</html>
  ';

  if ($ghtml==1 && $_GET['e']=="bbcode")
  {
   setcookie('xuser-enable-wysiwyg','0',time()+60*60*24*30);
   print $wrapper;
  }
  else if ($ghtml==0 && $_GET['e']=="wysiwyg")
  {
   setcookie('xuser-enable-wysiwyg','1',time()+60*60*24*30);
   print $wrapper;
  }
}

if ($action=='doaddnews')
{

 $nick=$_COOKIE['xusername'];
 if (!CheckPerms($nick,'postnews'))
 {
  header("Location: index.php?a=nopermissions");
  exit;
 }
 $act=@$_POST['act'];


 $headline=stripslashes($_POST['headline']);
 $icon=stripslashes($_POST['icon']);
 $cat=stripslashes($_POST['cat']);
 if ($ghtml==1)
 {
  $shortstory=stripslashes($_POST['textbox1']);
  $fullstory=stripslashes($_POST['textbox2']);
 }else
 {
  $shortstory=stripslashes($_POST['shortstory']);
  $fullstory=stripslashes($_POST['fullstory']);
 }
 $userinfo=GetUserInfo($nick);
 if ($script['icons']['save']=="ON")
 {
   $saveicon=$_POST['saveicon'];
 }
 if (!isset($saveicon) || empty($saveicon)){$saveicon='';}
 if ($fullstory=='<br>' || $fullstory=='<br />') $fullstory='';
 if (empty($headline) || empty($shortstory) || $shortstory=="<br>")
 {
  $wrapper=ShowMessage(LanguageField("hmsg_error"),LanguageField("msg_errnewspost1"));
 }
 else
 {
  $active=$_POST['active'];
  if (@$_POST['postpone']=="1")
  {
   $activationdate=mktime($_POST['ahour'],$_POST['aminute'],$_POST['asecond'],$_POST['amonth'],$_POST['aday'],$_POST['ayear']);
  }else $activationdate="";

  if ($activationdate!="" && $activationdate<EncodeTime(''))
  {
   $wrapper=ShowMessage(LanguageField("hmsg_error"),LanguageField("msg_invalidactdate"));
  }else
  {
   PostNews($nick,$headline,$saveicon.$icon,GetCatID($cat),$shortstory,$fullstory,$active,$activationdate) ;
   $wrapper=ShowMessage(LanguageField("hmsg_posted"),LanguageField("msg_newsposted"));
  }
 }
  OutputWrapper($wrapper);
}

if ($action=='editnews')
{
  $nick=$_COOKIE['xusername'];
  $userinfo=GetUSerInfo($nick);
  if (@$_POST['action']==LanguageField('l_search','_cp'))
  {
     $selcat=$_POST['cat'];
     $selauthor=$_POST['author'];
     $selarch=$_POST['newsarch'];
  }else
  {
    // $selarch='';$selauthor='';$selcat='';
     $selcat=@$_GET['cat'];
     $selauthor=@$_GET['author'];
     $selarch=@$_GET['newsarch'];
     $range=@$_GET['range'];

  }
  if (empty($range) || !isset($range))
  {
    $range='1-'.$script['news']['newsperpage'];
  }
  $wrapper=showactionpage2("editnews");
  $wrapper=str_replace('{FORM_ACTION}','index.php?a=editnews',$wrapper);
  $wrapper=str_replace('{FORM_ACTION_DEL}','index.php?a=deletenews',$wrapper);
  $archlist=GetArchList("newest");
  $archcode='';

  if (count($archlist)>0)
  {
   $selected=0;
   foreach ($archlist as $i=>$item)
   {
     $archstr=MonthFromNum(substr($item,0,2))."-".substr($item,2,4);
     if ($archstr==$selarch)
     {
      $archcode.="<option selected>".$archstr."</option>";
     }
     else
     {
      if (empty($selarch))
      {
       if ($selected==0)
       {
        $ctime=mktime(0,0,0,substr($item,0,2),1,substr($item,2,4));
        if ($ctime<=EncodeTime(''))
        {
         $archcode.="<option selected>".$archstr."</option>";
         $farch=$item;
         $selected=1;
        }else
        {
         $archcode.="<option>".$archstr."</option>";
        }
       }else
       {
        $archcode.="<option>".$archstr."</option>";
       }
      }
     else $archcode.="<option>".$archstr."</option>";
   }
  }
 }
   else {$archcode='';}


  $catlist=GetCatList();
  $catcode='';
  if (count($catlist)>0)
  {
  foreach ($catlist as $i=>$cat)
  {
    if ($cat['name']==$selcat)
    {
      $catcode.="<option selected>".$cat['name']."</option>";
    } else {
      $catcode.="<option>".$cat['name']."</option>";
     }
  }
  } else {$catcode='';}
  $wrapper=str_replace('{CATLIST}',$catcode,$wrapper);
  $wrapper=str_replace('{ARCHLIST}',$archcode,$wrapper);
  if (!empty($selarch))
  {
   $a=substr($selarch,0,strpos($selarch,'-'));
   $b=substr($selarch,strpos($selarch,'-')+1);
   $a=numfrommonth($a);
   $newsarch=$a.$b;
  } else
  {
   if (!empty($farch)) $newsarch=$farch;else $newsarch=$archlist[0];
  }
  if (empty($selcat) or $selcat=="Any"){$catid=0;}else{$catid=GetCatId($selcat);}
  if (empty($selauthor)){$selauthor='';}
  if (empty($catid)){$catid=0;}

  $a=substr($range,0,strpos($range,'-'));
  $b=substr($range,strpos($range,'-')+1);
  $newsarr=GetNews($newsarch,$catid,$selauthor,0,"newest","frm",1,$a,$b);
  $arrlen=count($newsarr);
  $newsrow=file_get_contents($script['paths']['file']."inc/editnews-list.html");
  $rows='';
  if (count($newsarr)>0)
  {
  foreach ($newsarr as $i=>$news)
  {
   $row=$newsrow;

   if ($news['author']==$nick)
   {
    if (!CheckPerms($nick,'editownnews'))
     {$checkcode=false;}
     else{$checkbox=true;}
   }else
   {
   if (!CheckPerms($nick,'editothernews'))
   {$checkbox=false;}
   else {$checkbox=true;}
   }
   if ($checkbox==true){$checkbox='<input type="checkbox" name="sel[]" value="{NEWS_ID}">';}
   else {$checkbox='';}
   $stats='';
    if ($news['active']=='0') $stats='<span class="redlight">[$__l_asinactive]</span>'; else $stats='<span class="greenlight">[$__l_asactive]</span>';

   if ($news['postponed']=="1") $stats.='<br /><span class="yellowlight">[$__l_aspostponed]</span>';
   if (!empty($news['activationdate'])) $row=str_replace('{ACTIVATIONDATE}',DecodeTime($news['activationdate']),$row); else
          $row=str_replace('{ACTIVATIONDATE}',DecodeTime($news['date']),$row);
   $row=str_replace('{STATUS}',$stats,$row);
   $row=str_replace('{DELCHECK}',$checkbox,$row);
   $row=str_replace('{NEWS_ID}',$news['id'],$row);
   $row=str_replace('{NEWS_ARCH}',$newsarch,$row);
   $row=str_replace('{HEADLINE}',$news['title'],$row);
   $row=str_replace('{AUTHOR}',$news['author'],$row);
   $row=str_replace('{DATE}',Decodetime($news['date']),$row);
   $row=str_replace('{CATEGORY}',GetCatName($news['cat']),$row);
   $row=str_replace('{COMMENTS}',NumComments($newsarch,$news['id']),$row);
   $rows.=$row;
  }
  }else
  {
   $rows='';
  }
  $wrapper=str_replace('{LIST_NEWS}',$rows,$wrapper);
  $wrapper=str_replace('{SEL_ARCH}',$newsarch,$wrapper);
  $wrapper=str_replace('{NEWSARCH}',$newsarch,$wrapper);
  $wrapper=str_replace('{NAVLINKS}',GenerateNewsPaginationLinks1(NewsCount($newsarch),$script['news']['newsperpage'],$range,$selarch,$selcat,$selauthor),$wrapper);

  OutputWrapper($wrapper);
}

if ($action=="deletenews")
{

  $nick=$_COOKIE['xusername'];
  $userinfo=GetUserInfo($nick);
  $wrapper=ShowDlgPage("index.php?a=dodeletenews","newsarch,sel",$_POST);
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
  $wrapper=str_replace("{Q_TITLE}",LanguageField("hmsg_?deletenews"),$wrapper);
  $wrapper=str_replace("{Q_MESSAGE}",LanguageField("msg_?deletenews"),$wrapper);
  $lst='';
  $newsarch=$_POST['newsarch'];
  foreach ($_POST['sel'] as $i=>$item)
  {
   $article=FullNews($newsarch,$item,'');
   $lst.="<b>".$article['title']."</b><br>";
  }
  $wrapper=str_replace("{Q_LIST}",$lst,$wrapper);
  OutputWrapper($wrapper);
}

if ($action=="dodeletenews")
{
   $nick=$_COOKIE['xusername'];
   $act=$_POST['Submit'];
   $not=false;
   if ($act==LanguageField("l_yes"))
   {
    $newsarch=$_POST['newsarch'];
    $errcode='';
    foreach ($_POST['sel'] as $i=>$sel)
    {
       $message=DeleteNews($newsarch,$sel);
       $a=substr($message,0,strpos($message,':'));
       $b=substr($message,strpos($message,':')+1);
       if ($a=='READERROR'){$errcode.='<BR>'.LanguageField("msg_readerror").$b;}
       if ($a=='WRITEERROR'){$errcode.='<BR>'.LanguageField("msg_writeerror").$b;}
       if ($a=='NOPERMISSION'){$errcode.='<BR>'.LanguageField("msg_moderror").$b;}
    }
    $wrapper=showactionpage2("message");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $wrapper=str_replace('{INFO_TITLE}',LanguageField("hmsg_deleted"),$wrapper);
    if ($errcode==''){
     $wrapper=str_replace('{INFO_BODY}',LanguageField("msg_newsdeleted"),$wrapper);
    }else
    {
     $wrapper=str_replace('{INFO_BODY}',LanguageFieled("msg_errnewsdelete")."<span class=\"ErrorClass1\">".$errcode."</span>",$wrapper);
    }
     OutputWrapper($wrapper);
   }else
   {
    header("Location: index.php?a=editnews");
   }

}

if ($action=="profile")
{
   $nick=$_COOKIE['xusername'];
   $userinfo=GetUserInfo($nick);
   $subaction=$_GET['action'];
   if ($subaction=='edit')
   {
    $wrapper=showactionpage2("profile");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $wrapper=str_replace('{FORM_EXTRA_FIELDS}','{VLEVEL}',$wrapper);
    $rnick=$nick;
    $ruserinfo=GetUserInfo($rnick);
    $wrapper=str_replace('{VNAME}',$ruserinfo['name'],$wrapper);
    $wrapper=str_replace('{VAVATAR}',$ruserinfo['avatar'],$wrapper);
    $wrapper=str_replace('{VEMAIL2}',$ruserinfo['email'],$wrapper);
    $wrapper=str_replace('{VEMAIL1}',$ruserinfo['semail'],$wrapper);
    $wrapper=str_replace('{VJOINED}',decodetime($ruserinfo['joined']),$wrapper);
    if ($script['editor']['wysiwyg']!='ON')
      $wrapper=SubstractHTMLCode('<!--BEGIN_WYSIWYG//-->(*)<!--END_WYSIWYG//-->',$wrapper);else
    {
      if ($ghtml==1) $wrapper=str_replace('{VUSEWYSIWYG}','checked',$wrapper); else $wrapper=str_replace('{VUSEWYSIWYG}','',$wrapper);
    }
    if (GetOwner()==$rnick)
    {
      $rank="Owner";
    }else
    {
      $rank=GetRankName($ruserinfo['level']);
    }
    $wrapper=str_replace('{VLEVEL}',$rank,$wrapper);
    $wrapper=str_replace('{FORM_ACTION}','index.php?a=profile&amp;action=doedit',$wrapper);
    $wrapper=str_replace('{VNICK}',$rnick,$wrapper);

    OutputWrapper($wrapper);
   }
   if ($subaction=='doedit')
   {
    $rnick=$nick;
    $rname=$_POST['name'];
    $rpassword=$_POST['password'];
    $secretmail=$_POST['email1'];
    $publicmail=$_POST['email2'];
    $ravatar=$_POST['avatar'];
    UpdateUser($rnick,$rname,$rpassword,"",$publicmail,$secretmail,$ravatar);
    if ($_POST['usewysiwyg']=="1")
    {
     setcookie('xuser-enable-wysiwyg','1',time()+60*60*24*30);
    }else setcookie('xuser-enable-wysiwyg','0',time()+60*60*24*30);
    $wrapper=showactionpage2("message");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $wrapper=str_replace('{INFO_TITLE}',LanguageField("hmsg_updated"),$wrapper);
    $wrapper=str_replace('{INFO_BODY}',LanguageField("msg_profileupdated"),$wrapper);
    OutputWrapper($wrapper);
   }
}


if ($action=="options")
{
  $nick		=	$_COOKIE['xusername'];
  $userinfo	=	GetUserInfo($nick);
  if (!CheckPerms($nick,'controlpanel')){
 
	header("Location: index.php?a=nopermissions");
    die();
  }
  if (empty($_GET['page']))
  {
    $wrapper=showactionpage2("options");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    OutputWrapper($wrapper);
    die();
  }


  if ($_GET['page']=="advertising")
  {
   $wrapper=showactionpage2("advertising");
   $subcontent='';

   if (!isset($_GET['action']) || $_GET['action']=='setup')
   {
    $subcontent=file_get_contents($script['paths']['file']."inc/adv_gen.html");
    require("configuration/advertising.php");
    $subcontent=ReadConfiguration($subcontent);
    $subcontent=str_replace("system|language",GenerateLanguageMenu(),$subcontent);
    $keys=array_keys($script);
    foreach ($keys as $i=>$key)
    {
     if (is_array($script[$key]))
     {
      $subkeys=array_keys($script[$key]);
      foreach ($subkeys as $i=>$subkey)
      {
       $subcontent=str_replace($key."|".$subkey,$script[$key][$subkey],$subcontent);
      }
     }else
     {
       $subcontent=str_replace($key."|",$script[$key],$subcontent);
     }
    }
    $subcontent=str_replace('{ACTIVE_ADLIST}',GenerateAdList('active'),$subcontent);
    $subcontent=str_replace('{ARCHIVE_ADLIST}',GenerateAdList('archive'),$subcontent);
    $subcontent=str_replace('check_ON','checked',$subcontent);
    $subcontent=str_replace('check_YES','checked',$subcontent);
    $subcontent=str_replace('check_NO','',$subcontent);
    $subcontent=str_replace('check_OFF','',$subcontent);
    $subcontent=str_replace('check_','',$subcontent);
   }
   else if ($_GET['action']=='dosetup')
   {
    require("configuration/advertising.php");
    WriteConfiguration();
    header("Location: index.php?a=options&page=advertising");
   }
   else if ($_GET['action']=='edit')
   {
    $subcontent=file_get_contents($script['paths']['file']."inc/adv_edit.html");
    $adlist=ListAds();
    $code='';
    $rowtpl=file_get_contents($script['paths']['file']."inc/adv_list.html");
    for ($i=0;$i<count($adlist);$i++)
    {
     $tpl=$rowtpl;
     $tpl=str_replace('{AD_ID}',$adlist[$i]['id'],$tpl);
     $tpl=str_replace('{AD_NAME}',$adlist[$i]['name'],$tpl);
     $tpl=str_replace('{AD_DATE}',DecodeTime($adlist[$i]['time']),$tpl);
     $code.=$tpl;
    }
    $subcontent=str_replace('{ADLIST}',$code,$subcontent);
   } else if ($_GET['action']=='editfrm')
   {
    $subcontent=file_get_contents($script['paths']['file']."inc/adv_editfrm.html");
    $ad=GetAd($_GET['id']);
    $subcontent=str_replace("{FORM_TITLE}",'$__L_adunitedit [{AD_NAME}]',$subcontent);
    $subcontent=str_replace("{FORM_ACTION}","doedit",$subcontent);
    $subcontent=str_replace("{AD_ID}",$_GET['id'],$subcontent);
    $subcontent=str_replace("{AD_NAME}",$ad['name'],$subcontent);
    $subcontent=str_replace("{AD_CODE}",$ad['code'],$subcontent);
   } else if ($_GET['action']=='doedit')
   {
      EditAdCode($_GET['id'],$_POST['adname'],$_POST['adcode']);
      header("Location: index.php?a=options&page=advertising&action=edit");
   }else if ($_GET['action']=='add')
   {
    $subcontent=file_get_contents($script['paths']['file']."inc/adv_editfrm.html");
    $subcontent=str_replace("{FORM_ACTION}","doadd",$subcontent);
    $subcontent=str_replace("{FORM_TITLE}",'$__L_adunitcreate',$subcontent);
    $subcontent=str_replace("{AD_ID}",'',$subcontent);
    $subcontent=str_replace("{AD_NAME}",'',$subcontent);
    $subcontent=str_replace("{AD_CODE}",'',$subcontent);
   }else if ($_GET['action']=='doadd')
   {
      AddAd($_POST['adname'],$_POST['adcode']);
      header("Location: index.php?a=options&page=advertising&action=edit");
   }
   else if ($_GET['action']=='deletead')
   {
      DeleteAd($_GET['id']);
      header("Location: index.php?a=options&page=advertising&action=edit");
   }
   $wrapper=str_replace("{CONTENT}",$subcontent,$wrapper);
   OutputWrapper($wrapper);
  }

//<<___________________________| User called the integration wizard |________________________________>>


  if ($_GET['page']=="integration")
  {
   $act=$_GET['action'];
   $nick=$_COOKIE['xusername'];
   $wrapper=showactionpage2("integration");
   if (empty($act))
   {
    $wizard=file_get_contents($script['paths']['file']."inc/int_stage1.html");
   }
   else if ($act=="maketarget")
   {
    $target=$_POST['target'];
    $wizard=file_get_contents($script['paths']['file']."inc/int_stage2.html");
    if ($target=="activenews")
    {
     $wizard=SubstractHTMLCode('<!--{BEGIN_ARCH}//-->(*)<!--{END_ARCH}//-->',$wizard);
    }
    $catlist=GetCatList();
    $catcode='';
    foreach ($catlist as $i=>$cat)
    {
     $catcode.="<option value=\"".$cat['id']."\">".$cat['name']."</option>";
    }

    $users=GetUserList();
    foreach ($users as $i=>$user)
    {
     $authorcode.="<option value=\"".$user['name']."\">".$user['name']."</option>";
    }

    $tpldir=$script['paths']['file']."templates/";
    $tpls=ListDir($tpldir);
    $tlist='';
    $defaulttpl=GetDefaultTemplate();
    $wizard=str_replace("{DEFTEMPLATE}",$defaulttpl,$wizard);
    foreach ($tpls as $i=>$tpl)
    {
          $temp='<option value="'.$tpl.'">'.$tpl.'</option>';
          $tlist.=$temp;
    }

    $wizard=str_replace('{AUTHORLIST}',$authorcode,$wizard);
    $wizard=str_replace('{CATLIST}',$catcode,$wizard);
    $wizard=str_replace('{TEMPLATE_LIST}',$tlist,$wizard);
    $wizard=str_replace('{TARGET}',$target,$wizard);
   }
   else if ($act=="generatecode")
   {
    $wizard=file_get_contents($script['paths']['file']."inc/int_completed.html");
    $target=$_POST['target'];
    $authors=$_POST['selauthors'];
    $categories=$_POST['selcats'];

    $catcode=''; $authorcode='';
    foreach ($_POST['selauthors'] as $i=>$author)
    {
     if ($author=='')
     {
      $authorcode='';
      break;
     }
     $authorcode.=$author.",";
    }

    foreach ($_POST['selcats'] as $i=>$cat)
    {
     if ($cat=='')
     {
      $catcode='';
      break;
     }
     $catcode.=$cat.",";
    }


    if (!empty($authorcode)) $authorcode=substr($authorcode,0,strlen($authorcode)-1);
    if (!empty($catcode)) $catcode=substr($catcode,0,strlen($catcode)-1);
    $generatedcode="<?php\n";
    if ($authorcode!='') $generatedcode.="\$authorlist=\"".$authorcode."\";\n";
    if ($catcode!='') $generatedcode.="\$catlist=\"".$catcode."\";\n";
    if ($_POST['seltemplate']!='default') $generatedcode.="\$template=\"".$_POST['seltemplate']."\";\n";
    if ($_POST['selpagination']=='1')
    {
        $generatedcode.="\$xnewspagination=\"1\";\n";
        $generatedcode.="\$newsperpage=".$_POST['selrange'].";\n";
        $generatedcode.="\$xnewsrange=\"1-".$_POST['selrange']."\";\n";
    }
    if ($target=="activenews")
    {
     $generatedcode.="include \"".$script['paths']['file']."news.php\";\n";
    } else
    {
     $generatedcode.="include \"".$script['paths']['file']."archives.php\";\n";
    }
    $generatedcode.="?>";
    $wizard=str_replace("{GENERATEDCODE}",$generatedcode,$wizard);
    $wizard=str_replace("{TARGET}",$target,$wizard);
   }
   $wrapper=str_replace('{WIZARD}',$wizard,$wrapper);
   OutputWrapper($wrapper);
  }



//<<___________________________| User called the template manager |________________________________>>

  if ($_GET['page']=='templates')
  {
   $action=$_GET['action'];
   if (!isset($action))
   {
    $wrapper=showactionpage2("templates");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $content_file=file_get_contents($script['paths']['file']."inc/template-manager.html");
    $tplrow=file_get_contents($script['paths']['file']."inc/template-manager-row.html");

    $tpldir=$script['paths']['file']."templates/";
    $tpls=ListDir($tpldir);
    $i=1;
    $tlist='';
    $content='';
    foreach ($tpls as $i=>$tpl)
    {
          $temp=str_replace('{TPL_ID}',$i,$tplrow);
          if (GetDefaultTemplate()==$tpl){$temp=str_replace('{TPL_DEF}',LanguageField('l_yes'),$temp);}else {$temp=str_replace('{TPL_DEF}','No',$temp);}
          $temp=str_replace('{TPL_NAME}',$tpl,$temp);
          $tlist.=$temp;
    }
    $content=str_replace('{TPL_LIST}',$tlist,$content_file);
    $wrapper=str_replace("{CONTENT}",$content,$wrapper);
    OutputWrapper($wrapper);
    die();
   }

   if ($action=="setdefault")
   {
       $name=$_GET['name'];
       $wrapper=showactionpage2("message");
       $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
       $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
       $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
       $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
       $wrapper=str_replace('{INFO_TITLE}',LanguageField("hmsg_deftplset"),$wrapper);
       $wrapper=str_replace('{INFO_BODY}',LanguageField("msg_deftplset"),$wrapper);
       $wrapper=str_replace('{SETTPL}',$name,$wrapper);
       SetDefaultTemplate($name);
       OutputWrapper($wrapper);
       die();
   }
   if ($action=="edit")
   {
    $name=$_GET['name'];
    $wrapper=showactionpage2("templates");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $content=file_get_contents($script['paths']['file']."inc/template-editor.html");
    $wrapper=str_replace("{CONTENT}",$content,$wrapper);
    $tpldir=$script['paths']['file']."templates/".$name."/";
    $tfiles=ListTemplateFiles($name);

    foreach ($tfiles as $i=>$tfile)
    {
     $nm=substr($tfile,0,strpos($tfile,'.'));
     $nm=strtoupper($nm);
     $objcontent=file_get_contents($tpldir.$tfile);
     $objcontent=str_replace('{$LIC}','{$LICENSE}',$objcontent);
     $objcontent=str_replace("<","&lt;",$objcontent);
     $objcontent=str_replace(">","&gt;",$objcontent);
     $wrapper=str_replace("{TPL_".$nm."}",$objcontent,$wrapper);
    }

    $wrapper=str_replace("{ACTION_URL}","index.php?a=options&amp;page=templates&amp;action=doedit&amp;name=$name",$wrapper);
    $wrapper=str_replace('$__page_editingtemplate',LanguageField('page_editingtemplate'),$wrapper);
    $wrapper=str_replace('{TPL_NAME}',$name,$wrapper);
    OutputWrapper($wrapper);
    die();
   }

   if ($action="doedit")
   {
    $name=$_GET['name'];
    $submit=$_POST['submit'];

   if ($submit==LanguageField("l_savechanges"))
   {
    $postlines=$_POST;
    $keys=array_keys($postlines);
    foreach ($keys as $i=>$line)
    {
      $line=stripslashes($line);
      if (strtoupper(substr($line,0,4))=='TPL_')
      {
       $dirname=$script['paths']['file']."templates/".$name."/";
       $filename=substr($line,4).".html";
       //<<-------------------- Let's make a backup shall we? Just in case ----------------------------
       if (!is_dir($dirname."old/"))
       {
        mkdir($dirname."old/");
       }
       copy($dirname.$filename,$dirname."old/".$filename);
       //<<--------------------- Ok. The old files should now be in the "old/" directory--

       $towrite=stripslashes($postlines[$line]);
       $towrite=str_replace('{$LICENSE}','{$LIC}',$towrite);

       $filename=$script['paths']['file']."templates/".$name."/".$filename;
       $handle=fopen($filename,"w");
       fwrite($handle,$towrite);
       fclose($handle);
      }
    }

       $wrapper=showactionpage2("message");
       $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
       $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
       $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
       $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
       $wrapper=str_replace('{INFO_TITLE}',LanguageField("hmsg_edited"),$wrapper);
       $wrapper=str_replace('{INFO_BODY}',LanguageField("msg_templateedited"),$wrapper);
       OutputWrapper($wrapper);
       die();
   }
   else
   {
     header("Location: index.php?a=options&page=templates");
   }

  }
  }
  if ($_GET['page']=='system')
  {
    $wrapper=ShowActionPage2("sysconfig");
    $tab=@$_GET['t'];
    if (isset($tab))
    {
     $subpage=file_get_contents($script['paths']['file']."inc/cfg_".$tab.".html");
     $wrapper=str_replace("{TAB}",$subpage,$wrapper);
    }else
    {
     $tab="script_settings";
     $subpage=file_get_contents($script['paths']['file']."inc/cfg_".$tab.".html");
     $wrapper=str_replace("{TAB}",$subpage,$wrapper);

    }


    require("configuration/$tab.php");
    $wrapper=ReadConfiguration($wrapper);
    $wrapper=str_replace("system|language",GenerateLanguageMenu(),$wrapper);
    $keys=array_keys($script);
    foreach ($keys as $i=>$key)
    {
     if (is_array($script[$key]))
     {
      $subkeys=array_keys($script[$key]);
      foreach ($subkeys as $i=>$subkey)
      {
       $wrapper=str_replace($key."|".$subkey,$script[$key][$subkey],$wrapper);
      }
     }else
     {
       $wrapper=str_replace($key."|",$script[$key],$wrapper);
     }
    }


    $wrapper=str_replace('check_ON','checked',$wrapper);
    $wrapper=str_replace('check_YES','checked',$wrapper);
    $wrapper=str_replace('check_NO','',$wrapper);
    $wrapper=str_replace('check_OFF','',$wrapper);
    $wrapper=str_replace('check_','',$wrapper);
    OutputWrapper($wrapper);
    exit;

  }


  if ($_GET['page']=='updatesysconfig')
  {

     // Here we are getting the configuration tab to be re-written.
     $t=$_GET['t'];
     require("configuration/$t.php");
     WriteConfiguration();
     $wrapper=ShowActionPage2("sysconfig");
     $wrapper=str_replace("{TAB}",LanguageField("msg_updatedconfig"),$wrapper);
     if ($t=="rss_engine"){ RebuildRss();}
     OutputWrapper($wrapper);
    exit;
  }
  if ($_GET['page']=='profiles')
  {
   $subaction=$_GET['action'];
   $nick=$_COOKIE['xusername'];
   $userinfo=GetUserInfo($nick);
   if ($subaction=='edit')
   {
    $wrapper=showactionpage2("profile");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $tpl=file_get_contents($script['paths']['file']."inc/profile-extrafields.html");
    $wrapper=str_replace('{FORM_EXTRA_FIELDS}',$tpl,$wrapper);
    $rnick=$_GET['nick'];
    $ruserinfo=GetUserInfo($rnick);
    $wrapper=str_replace('{VNAME}',$ruserinfo['name'],$wrapper);
    $wrapper=str_replace('{VAVATAR}',$ruserinfo['avatar'],$wrapper);
    $wrapper=str_replace('{VEMAIL1}',$ruserinfo['semail'],$wrapper);
    $wrapper=str_replace('{VEMAIL2}',$ruserinfo['email'],$wrapper);
    $wrapper=str_replace('{VJOINED}',decodetime($ruserinfo['joined']),$wrapper);
    $wrapper=SubstractHTMLCode('<!--BEGIN_WYSIWYG//-->(*)<!--END_WYSIWYG//-->',$wrapper);
    if (GetOwner()==$rnick)
    {
     $rank="Owner";
    }
    else
    {
     $rank=GetRankName($ruserinfo['level']);
    }
    $wrapper=str_replace('{VLEVEL}',$rank,$wrapper);
    $wrapper=str_replace('{FORM_ACTION}','index.php?a=options&amp;page=profiles&amp;action=doedit&amp;nick='.$rnick,$wrapper);
    $wrapper=str_replace('{VNICK}',$rnick,$wrapper);

    $ranktpl='';
    $ranks=ListRanks();
    foreach ($ranks as $i=>$rank){ if (GetRankName($ruserinfo['level'])==$rank){$ranktpl.="<option selected>".$rank."</option>";} else {$ranktpl.="<option>".$rank."</option>";} }
    $wrapper=str_replace('{USER_RANKS}',$ranktpl,$wrapper);
    OutputWrapper($wrapper);
   }
   if ($subaction=='doedit')
   {
    $rnick=$_GET['nick'];
    $rname=$_POST['name'];
    $rpassword=$_POST['password'];
    $secretmail=$_POST['email1'];
    $publicmail=$_POST['email2'];
    $ravatar=$_POST['avatar'];
    $rlevel=$_POST['level'];
    $rlevel=GetRankId($rlevel);
    UpdateUser($rnick,$rname,$rpassword,$rlevel,$publicmail,$secretmail,$ravatar);
    header("Location: index.php?a=options&page=profiles");
	die();
   }

   if ($subaction=="delete")
   {

    $delnick=$_GET['nick'];

    $wrapper=ShowDlgPage("index.php?a=options&amp;page=profiles&amp;action=dodelete&amp;nick=".$delnick,"nick",$_GET);
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    $wrapper=str_replace("{Q_TITLE}",LanguageField("hmsg_delete"),$wrapper);
    $wrapper=str_replace("{Q_LIST}","",$wrapper);
    $wrapper=str_replace("{Q_MESSAGE}",LanguageField("msg_?deleteuser"),$wrapper);
    $wrapper=str_replace("{DELACC}",$delnick,$wrapper);
    OutputWrapper($wrapper);

   }
   if ($subaction=="dodelete")
   {
    if ($_POST['Submit']==LanguageField("l_yes"))
    {
      if (strtolower($_GET['nick'])==GetOwner()){
		$wrapper = Showmessage(LanguageField("hmsg_error"),LanguageField("msg_errownerdelete"));
		OutputWrapper($wrapper);
		die();
	}
      DeleteUser($_GET['nick']);
      $wrapper=showactionpage2("message");
      $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
      $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
      $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
      $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
      $wrapper=str_replace('{INFO_TITLE}','User Deleted',$wrapper);
      $wrapper=str_replace('{INFO_BODY}',LanguageField("msg_userdeleted"),$wrapper);
    $wrapper=str_replace("{DELACC}",$_GET['nick'],$wrapper);
      OutputWrapper($wrapper);
    }
    else {header("Location: index.php?a=options&page=profiles");};
   }
   if ($subaction=="add")
   {
    $nick=$_COOKIE['xusername'];
    $userinfo=GetUserInfo($nick);
    $account=$_POST['account'];
    $name=$_POST['displayname'];
    $password=$_POST['password'];
    $level=GetRankId($_POST['accesslevel']);
    $publicemail=$_POST['publicemail'];
    $email=$_POST['email'];
    $avatar=$_POST['avatar'];

    $wrapper=showactionpage2("message");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);
    if (empty($account))
    {
      $wrapper=str_replace('{INFO_TITLE}','Error',$wrapper);
      $wrapper=str_replace('{INFO_BODY}',"The 'Account Name' field must be completed.<br> <a href=\"index.php?a=options&amp;page=profiles\">Go Back</a>",$wrapper);
    } else
    if (isuser($account)==true)
    {
      $wrapper=str_replace('{INFO_TITLE}','Error',$wrapper);
      $wrapper=str_replace('{INFO_BODY}',$account." is already in the database.<br> <a href=\"index.php?a=options&amp;page=profiles\">Go Back</a>",$wrapper);
    }
    else
    {
      if (strlen($password)<5)
      {
       $wrapper=str_replace('{INFO_TITLE}','Error',$wrapper);
       $wrapper=str_replace('{INFO_BODY}',$account."'s password must have at least 5 characters in length.<br> <a href=\"index.php?a=options&amp;page=profiles\">Go Back</a>",$wrapper);
      }
      else
      {
        AddUser($account,$name,$password,$level,$publicemail,$email,$avatar);
        $wrapper=str_replace('{INFO_TITLE}','User Added',$wrapper);
        $msg=LanguageField("msg_useradded")."<BR><BR>";
        $msg=str_replace("{ADDEDACC}",$account,$msg);
        if (CheckPerms($account,"controlpanel")==true)
        {
         $msg.=LanguageField("msg_warninguserpriv");
         $msg=str_replace("{ADDEDACC}",$account,$msg);
        }
        $msg.='<a href="index.php?a=options&amp;page=profiles">Go Back</a>';
        $wrapper=str_replace('{INFO_BODY}',$msg,$wrapper);
      }
    }
    OutputWrapper($wrapper);
   }
   if (empty($subaction))
   {
    $wrapper=ShowActionPage2("profilemngr");
    $tpl=file_get_contents($script['paths']['file']."inc/profilemngr-uline.html");
    $ctmp='';

    $users=GetUserList();
    foreach ($users as $i=>$user)
    {
      $a=$tpl;
      $a=str_replace('{USER_NAME}',$user['name'],$a);
      $a=str_replace('{USER_NICK}',$user['nick'],$a);
      $a=str_replace('{USER_LEVEL}',GetRankName($user['level']),$a);
      $a=str_replace('{USER_ART}',numposts($user['nick']),$a);
      $ctmp.=$a;

    }
    $wrapper=str_replace('{USERLIST}',$ctmp,$wrapper);
    $ranktpl='';
    $ranks=ListRanks();
    foreach ($ranks as $i=>$rank){$ranktpl.="<option>".$rank."</option>"; }
    $wrapper=str_replace('{RANKLIST}',$ranktpl,$wrapper);
    OutputWrapper($wrapper);
   }
  }

  if ($_GET['page']=='catmanager')
  {
    $subaction=@$_GET['action'];
    $catname=@$_POST['catname'];
    $wrapper=showactionpage2("catmanager");
    if ($subaction=='dodelete')
    {
    $act=$_POST['Submit'];
    if ($act==LanguageField("l_yes"))
    {
     $inf=LanguageField("hmsg_deleted");
     foreach ($_POST['sel'] as $i=>$item)
     {
      $s=DelCat($item);
      if ($s!=''){$inf=$s;};

     }
     $wrapper=str_replace('{INFO}',$inf,$wrapper);
    }
    }
    if (!empty($catname) && $subaction=="doadd")
    {
    $app=@$_POST['saveicon'];
    $ico=$app.$_POST['icon'];
    $wrapper=str_replace('{INFO}',AddCat($catname,$ico,encodetime('')),$wrapper);

    }else
    {

    }
    if ($subaction=='delete')
    {
      $wrapper=ShowDlgPage("index.php?a=options&amp;page=catmanager&amp;action=dodelete","sel",$_POST);
      $wrapper=str_replace("{Q_TITLE}",LanguageField("hmsg_delete"),$wrapper);
      $wrapper=str_replace("{Q_MESSAGE}",LanguageField("msg_?deletecategory"),$wrapper);
      $lst='';
      foreach ($_POST['sel'] as $i=>$item)
      {
       $lst.="<b>".GetCatName($item)."</b><br>";
      }
      $wrapper=str_replace("{Q_LIST}",$lst,$wrapper);
      OutputWrapper($wrapper);
     exit;
    }
    if ($subaction=="edit")
    {
      $wrapper=showactionpage2("cateditor");
      $icon=GetCatIcon($_GET['id'],1);
      $name=GetCatName($_GET['id']);
      $wrapper=str_replace("{CATNAME}",$name,$wrapper);
      $wrapper=str_replace("{CATICON}",$icon,$wrapper);
      $wrapper=str_replace("{ID}",$_GET['id'],$wrapper);
      if ($script['icons']['save']=="ON")
      {
       $wrapper=str_replace('{ICONSAVE}','<input type="checkbox" name="saveicon" value="SAVE:" checked> <span class="SpanClass1">$__l_saveicon</span>',$wrapper);
      }else
      {
       $wrapper=str_replace("{ICONSAVE}","",$wrapper);
      }
       $icon=GetCatIcon($_GET['id']);
       if ($icon!='') $wrapper=str_replace('{ICONPREVIEW}','<img src="'.$icon.'">',$wrapper); else $wrapper=str_replace('{ICONPREVIEW}','',$wrapper);
       OutputWrapper($wrapper);
      exit;
    }
    if ($subaction=="doedit")
    {
      $id=$_GET['id'];
      $ico=@$_POST['saveicon'];
      $ico=$ico.$_POST['caticon'];
      $msg=EditCat($_GET['id'],$_POST['catname'],$ico);
      $wrapper=str_replace('{INFO}',$msg,$wrapper);
     // header("Location: index.php?a=options&amp;page=catmanager");
    }
    $catlist=GetCatList();
    $tpl=file_get_contents($script['paths']['file']."inc/catmanager-list.html");
    $code='';
    foreach ($catlist as $i=>$cat)
    {
     $codeline=str_replace("{ID}",$cat['id'],$tpl);
     $codeline=str_replace("{NAME}",$cat['name'],$codeline);
     $code.=$codeline;
    }
     $wrapper=str_replace('{INFO}','',$wrapper);
    $wrapper=str_replace("{CATLIST}",$code,$wrapper);
    if ($script['icons']['save']=="ON")
    {
     $wrapper=str_replace('{ICONSAVE}','<input type="checkbox" name="saveicon" value="SAVE:" checked> <span class="SpanClass1">$__l_saveicon</span>',$wrapper);
    }else
    {
     $wrapper=str_replace("{ICONSAVE}","",$wrapper);
    }
    OutputWrapper($wrapper);
  }

//<<-------------------- The synchroinzer ------------------------------------------------
// First implemented on the 28th of October, 2006
// Stage: BETA
//
// This wizard is supposed to help users import news, users and comments from an early
// version of X-News.

if ($_GET['page']=='synchronizer')
{
    $nick=$_COOKIE['xusername'];
    $userinfo=GetUserInfo($nick);
    $wrapper=ShowActionPage2("synchronizer");
  if (!isset($_GET['action']))
  {
    $tab=file_get_contents("inc/sync_select.html");
    $wrapper=str_replace("{TAB}",$tab,$wrapper);
    OutputWrapper($wrapper);
  }else if ($_GET['action']=="maketarget")
  {
   if ($_POST['syncfrom']=='xnews1-0-0')
   {
    $tab=file_get_contents("inc/sync_xnews1-0-0.html");
    $wrapper=str_replace("{TAB}",$tab,$wrapper);
    OutputWrapper($wrapper);
   }
   else if ($_POST['syncfrom']=='xnews1-0-1')
   {
    $tab=file_get_contents("inc/sync_xnews1-0-1.html");
    $wrapper=str_replace("{TAB}",$tab,$wrapper);
    OutputWrapper($wrapper);
   }
   else if ($_POST['syncfrom']=='cutenews')
   {
    $tab=file_get_contents("inc/sync_cutenews.html");
    $wrapper=str_replace("{TAB}",$tab,$wrapper);
    OutputWrapper($wrapper);
   }
  }else if ($_GET['action']=='sync')
  {
    $syncfrom=$_POST['syncfrom'];
    $pathto=$_POST['oldxpath'];
    $what['news']=@$_POST['syncnews'];
    $what['comments']=@$_POST['synccomments'];
    $what['cat']=@$_POST['synccat'];
    $what['ranks']=@$_POST['syncranks'];
    $what['users']=@$_POST['syncuserdb'];
    $what['license']=@$_POST['synclicense'];
    $what['icons']=@$_POST['syncicons'];
    $what['pictures']=@$_POST['syncpictures'];


    require_once("sync.inc.php");
    $wrapper=Showmessage('$__L_synchronizer',xSynchronize($syncfrom,$pathto,$what));
    $wrapper=str_replace("{SYNCDIR}",$pathto,$wrapper);
    OutputWrapper($wrapper);

  }
}

if ($_GET['page']=="backups")
{
 if (!isset($_GET['action']))
 {
    $nick=$_COOKIE['xusername'];
    $userinfo=GetUserInfo($nick);
    $wrapper=ShowActionPage2("backups");
    $backupline=file_get_contents("inc/backup_list.html");
    $backups=ListBackups();
    $data='';
    for ($i=(count($backups)-1);$i>=0;$i--)
    {
      $backup=$backups[$i];
      $tmp=$backupline;
      $tmp=str_replace("{NAME}",$backup['name'],$tmp);
      $tmp=str_replace("{LOCATION}",$backup['dir'],$tmp);
      $tmp=str_replace("{SIZE}",$backup['size'],$tmp);
      $tmp=str_replace("{STAMP}",$backup['archived'],$tmp);
      $tmp=str_replace("{ARCHIVED}",date("j M Y - H:i:s",$backup['archived']),$tmp);
      $data.=$tmp;
    }

   $wrapper=str_replace("{BACKUP_LINES}",$data,$wrapper);
   OutputWrapper($wrapper);
 }else if ($_GET['action']=='restore')
 {
   $target=$_GET['target'];
   $wrapper=ShowMessage('$__L_backuprestored',RestoreBackup($target));
   $wrapper=str_replace("{BACKUP_TIME}",date("j M Y - H:i:s",$target),$wrapper);
   OutputWrapper($wrapper);
 } else if ($_GET['action']=='make')
 {
   $target=$_POST['backupname'];
   $wrapper=ShowMessage('$__L_backuprestored',GenerateBackup($target));
   OutputWrapper($wrapper);
 }
}

//<<-------------------- This is the rank manager ---------------------------------------

if ($_GET['page']=='rankmanager')
{
  if (!isset($_GET['action']))
  {
    $notice=$_GET['notice'];
    if ($notice=="deleted")
    {
      $notice=LanguageField("msg_ranksdeleted");
    }
    $nick=$_COOKIE['xusername'];
    $userinfo=GetUserInfo($nick);
    $wrapper=ShowActionPage2("rankmanager");
    $wrapper=str_replace('{INFO}',$notice,$wrapper);
    $ranklist=ListRanks2();
    $listtpl=file_get_contents($script['paths']['file']."inc/rankmanager-list.html");
    $formtpl=file_get_contents($script['paths']['file']."inc/rankmanager-form.html");

    $wrapper=str_replace('{EDITRANK}',$formtpl,$wrapper);
    $wrapper=str_replace('{FORM_ACTION}','index.php?a=options&amp;page=rankmanager&amp;action=new',$wrapper);
    $wrapper=str_replace('{FORM_TITLE}',languagefield('L_addrank2'),$wrapper);
    $code='';
    foreach ($ranklist as $i=>$rank)
    {
     $temp=$listtpl;
     $temp=str_replace('{EDIT}','<a href="index.php?a=options&amp;page=rankmanager&amp;action=edit&amp;id={ID}" class="ListItem">Edit</a>',$temp);
     $temp=str_replace('{DELETE}','<input name="sel[]" type="checkbox" id="sel" value="{ID}">',$temp);
     $temp=str_replace('{NAME}',$rank['name'],$temp);
     $temp=str_replace('{ID}',$rank['id'],$temp);
     $code.=$temp;
    }
    $wrapper=str_replace('{RANKLIST}',$code,$wrapper);
    OutputWrapper($wrapper);
    die();
   }
  if($_GET['action']=='delete')
  {
    foreach ($_POST['sel'] as $i=>$rank)
    {
      DeleteRank($rank);
    }
    header("Location: index.php?a=options&page=rankmanager&notice=deleted");
  }
  if ($_GET['action']=='new')
  {
    $perms='';
    $keys=array_keys($_POST);
    $rankname=$_POST['rankname'];
    if (ISRank($rankname))
    {
    header("Location: index.php?a=options&page=rankmanager&notice=".LanguageField("msg_rankexists"));
    exit;
    }
    foreach ($keys as $i=>$key)
    {
     if (substr($key,0,3)=='can')
     {
      if (strtoupper($_POST[$key])==strtoupper('YES')){$perms.="1|";}else{$perms.="0|";}
     }
    }

    AddRank($rankname,$perms);
    header("Location: index.php?a=options&page=rankmanager&notice=".LanguageField("msg_rankadded"));
  }


}

//<<-------------------- This is the image manager implemented in the administration panel

$nick=$_COOKIE['xusername'];
$error='';
    $thisdir=@$_GET['thisdir'];
    if (empty($thisdir)){$thisdir=@$_POST['thisdir'];}
    $thisdir=str_replace("../","",$thisdir);
    $thisdir=str_replace("./","",$thisdir);
    if (substr($thisdir,strlen($thisdir)-1,1)!='/' && $thisdir!=''){$thisdir.='/';}
if ($_GET['page']=='upimages')
  {
    $nick=$_COOKIE['xusername'];
    $action=$_GET['action'];

   if ($action=="dodelete")
   {
    $act=$_POST['Submit'];
    if ($act==LanguageField("l_yes"))
    {
      foreach ($_POST['sel'] as $i=>$sel)
      {
       deleteuploadedimage($sel);
      }

       $wrapper=ShowMessage(LanguageField("hmsg_deleted"),LanguageField("msg_picsdeleted"));
       OutputWrapper($wrapper);
       die();

    }else
    {
      header("Location:index.php?a=options&page=upimages");
    }

   }
   if ($action=="delete")
    {
    $wrapper=ShowDlgPage("index.php?a=options&amp;page=upimages&amp;action=dodelete","sel",$_POST);
    $wrapper=str_replace("{Q_TITLE}",LanguageField("hmsg_delete"),$wrapper);
    $wrapper=str_replace("{Q_MESSAGE}",LanguageField("msg_?deletepictures"),$wrapper);
    $lst='';
    foreach ($_POST['sel'] as $i=>$item)
    {
     $lst.="<b>".$item."</b><br>";
    }
    $wrapper=str_replace("{Q_LIST}",$lst,$wrapper);
    OutputWrapper($wrapper);
     die();
   }
    if ($action=="upimage")
    {
    if (CheckPerms($nick,"uploadimages")==true)
     {
      $uploadfile = $script['paths']['picturedir']. $thisdir.'/'.$_FILES['userfile']['name'];
      if (upMeetsConditions($uploadfile)=='')
      {
       if (copy($_FILES['userfile']['tmp_name'], $uploadfile)) {
          $indexfile=$script['paths']['picturedir']. $thisdir.'/uploaders.php';
          if (!file_exists($indexfile)){ $handle=fopen($indexfile,"w"); } else {$handle=fopen($indexfile,"a");}
          fwrite($handle,$_FILES['userfile']['name']."|".$nick."|\n");
          fclose($handle);
       }  else {
         $error="Your file could not be uploaded. Make sure you have writing permissions for this folder";
         print_r($_FILES);
       }
     }
     else {
      $error=upMeetsConditions($uploadfile);
     }
    } else {$error="You don't have the permission to upload images";}
    }
    $wrapper=showactionpage2("manage_upimages");

    $uploadform=file_get_contents($script['paths']['file']."inc/images-uploadform.html");
    $uploadedfiles=file_get_contents($script['paths']['file']."inc/images-uploaded-files.html");

    $wrapper=str_replace('{UPLOADFORM}',$uploadform,$wrapper);
    $wrapper=str_replace('{UPLOADEDFILES}',$uploadedfiles,$wrapper);

    $wrapper=str_replace("{THIS_DIR}",$thisdir,$wrapper);
    $wrapper=str_replace("{PIC_FOLDER_PATH}",$script['paths']['picturedir'],$wrapper);
    $wrapper=str_replace('{VIEW_FORM_ACTION}',"index.php?a=options&amp;page=upimages",$wrapper);
    $wrapper=str_replace('{FORM_ACTION}',"index.php?a=options&amp;page=upimages&amp;action=delete",$wrapper);
    $wrapper=str_replace('{UPLOAD_FORM_ACTION}',"index.php?a=options&amp;page=upimages&amp;action=upimage&amp;thisdir=".$thisdir,$wrapper);

    $currentpath=$script['paths']['picturedir'].$thisdir;

    $files=fetch_image_array($currentpath);

    $listtpl=file_get_contents($script['paths']['file']."inc/images-uploaded-files-row.html");
    $dirlistcode='';
    $flistcode='';
    if (count($files)>0)
    {

    foreach ($files as $i=>$line)
    {
     if ($line['dir']==false)
     {
      if ($line['uploader']==$nick)
      {
       if (CheckPerms($nick,"deleteownimages")==true)
       {
         $checkcode="<input type=\"checkbox\" name=\"sel[]\" value=\"{FILEPATH}\">";
       } else {$checkcode='';}
      }else
      {
       if (CheckPerms($nick,"deleteotherimages")==true)
       {
         $checkcode="<input type=\"checkbox\" name=\"sel[]\" value=\"{FILEPATH}\">";
       } else {$checkcode="";}
      }
      $code=str_replace("{ICON}","inc/img/icon_picture.gif",$listtpl);
      $code=str_replace("{CHECKBOX}",$checkcode,$code);
      $code=str_replace("{FILEPATH}",$line['filepath'],$code);
      $code=str_replace("{FILENAME}",$line['name'],$code);
      $code=str_replace("{RESOLUTION}",$line['res'],$code);
      $code=str_replace("{FILESIZE}",$line['size'],$code);
      $code=str_replace("{LASTMOD}",$line['lastmod'],$code);
            $code=str_replace("{ACT}","",$code);
      $code=str_replace("{UPLOADEDBY}",$line['uploader'],$code);
      $link=$script['paths']['pictureurl'].$thisdir.$line['name'];
      $code=str_replace("{ITEMLINK}",$link,$code);
      $flistcode.=$code;
     }else
     {
      $code=str_replace("{ICON}","inc/img/icon_folder.gif",$listtpl);
      $code=str_replace("{FILENAME}",$line['name'],$code);
      $code=str_replace("{RESOLUTION}","",$code);
      $code=str_replace("{FILESIZE}","",$code);
      $code=str_replace("{LASTMOD}","",$code);
      $code=str_replace("{UPLOADEDBY}","",$code);
      $code=str_replace("{ITEMLINK}",'index.php?a=options&amp;page=upimages&amp;thisdir='.$thisdir.$line['name'],$code);
      $code=str_replace("{CHECKBOX}","",$code);
            $code=str_replace("{ACT}","",$code);
      $dirlistcode.=$code;
     }
    }
    $wrapper=str_replace("{FILE_LIST}",$dirlistcode.$flistcode,$wrapper);
    } else { $wrapper=str_replace("{FILE_LIST}",'',$wrapper);   }
    $wrapper=str_replace("{ERROR}",$error,$wrapper);
      $wrapper=str_replace('{ONCLICK}','',$wrapper);
    OutputWrapper($wrapper);


  }


}

if ($action=="modcp")
{

    $nick=$_COOKIE['xusername'];
    $userinfo=GetUserInfo($nick);
    $newsarch=$_GET['newsarch'];
    $newsid=$_GET['newsid'];
    $range=$_POST['range'];
    if ($range==""){$range=$_GET['range'];}

    if ($range==''){
      $range="1-50";
    }

    $act=$_GET['action'];

//<--------------------------- Some comments are about to be deleted. Is the user sure of this action? ----------------------

    if ($act=="delete")
    {

      $wrapper=ShowDlgPage("index.php?a=modcp&amp;newsarch=".$newsarch."&amp;newsid=".$newsid."&amp;action=dodelete","sel",$_POST);
      $wrapper=str_replace("{Q_TITLE}","Delete Comments",$wrapper);
      $lst='';
      $newsarch=$_POST['newsarch'];
      $c=0;
      foreach ($_POST['sel'] as $i=>$item)
      {
        $lst.="<b>".$item."</b><br>";
        $c++;
      }
      $wrapper=str_replace("{Q_MESSAGE}","You are about to delete $c comments. Are you sure?",$wrapper);
      $wrapper=str_replace("{Q_LIST}","ID's to be deleted:<BR>".$lst,$wrapper);
      OutputWrapper($wrapper);
      die();
    }

    if ($act=="dodelete")
    {
     $act2=$_POST['Submit'];
     if ($act2==LanguageField("l_yes"))
     {
      foreach ($_POST['sel'] as $i=>$item)
      {
        DeleteComment($newsarch,$item);
      }
     }
      header("Location: index.php?a=modcp&newsarch=".$newsarch."&newsid=".$newsid);
    }

//<<------------------------- No action specified so we are just listing the comments

$bid		=	substr($range,0,strpos($range,'-'));
$eid		=	substr($range,strpos($range,'-')+1);

    $comments=GetComments($newsarch,$newsid,$range);

    $article=FullNews($newsarch,$newsid,"");

    $uinfo=GetUserInfo($article['author']);
    $timestamp=$article['date'];

    $dateline=date("F, j Y",$timestamp);
    $timeline=date("H:i:s",$timestamp);

    $fileline="news/news-".date("mY",$timestamp).".php";
    $wrapper=showactionpage2("modcp");
    $wrapper=str_replace('{LABEL_LOGGED_IN_AS}','$__loggedinas',$wrapper);
    $wrapper=str_replace('{LABEL_LOGOUT}','<a href="index.php?a=login">$__logout</a>',$wrapper);
    $wrapper=str_replace('{USER_NICK}',$nick,$wrapper);
    $wrapper=str_replace('{USER_NAME}',$userinfo['name'],$wrapper);

    $wrapper=str_replace('{ARTICLE_HEADLINE}',$article['title'],$wrapper);
    $wrapper=str_replace('{ARTICLE_SHORT_STORY}',$article['short_story'],$wrapper);
    $wrapper=str_replace('{ARTICLE_FULL_STORY}',$article['full_story'],$wrapper);
    $wrapper=str_replace('{ARTICLE_SHORT_STORY}',$article['short_story'],$wrapper);
    $wrapper=str_replace('{ARTICLE_AUTHOR}',$article['author'],$wrapper);
    $wrapper=str_replace('{ARTICLE_AUTHOR_NAME}',$uinfo['name'],$wrapper);
    $wrapper=str_replace('{ARTICLE_DATE}',$dateline,$wrapper);
    $wrapper=str_replace('{ARTICLE_TIME}',$timeline,$wrapper);
    $wrapper=str_replace('{ARTICLE_FILE}',$fileline,$wrapper);
    $wrapper=str_replace('{COMMENTS_NUM}',NumComments($newsarch,$newsid),$wrapper);
    $comment_tpl=file_get_contents($script['paths']['file']."inc/modcp-comment.html");
    $comment_list='';
    if (!empty($comments)){
    foreach ($comments as $i=>$comment)
    {
     $temp=str_replace("{COMMENT_AUTHOR}",$comment['author'],$comment_tpl);
     $temp=str_replace("{COMMENT_IP}",$comment['ip'],$temp);
     $temp=str_replace("{COMMENT_TIME}",DecodeTIme($comment['date']),$temp);
     $temp=str_replace("{COMMENT_BODY}",$comment['comment'],$temp);
     $temp=str_replace("{COMMENT_ID}",$comment['id'],$temp);
     $temp=str_replace("{COMMENT_#}",$comment['#'],$temp);
     $comment_list.=$temp;
    }
    $wrapper=str_replace('{COMMENTS_LIST}',$comment_list,$wrapper);
    } else
    {
    $wrapper=str_replace('{COMMENTS_LIST}','<span class="SpanClass1">No comments recorded yet</span>',$wrapper);
    }
    $wrapper=str_replace('{ARTICLE_ARCH}',$newsarch,$wrapper);
    $wrapper=str_replace('{ARTICLE_ID}',$article['id'],$wrapper);
    $wrapper=str_replace('{COMMENTS_BID}',$bid,$wrapper);
    $wrapper=str_replace('{COMMENTS_EID}',$eid,$wrapper);

    $wrapper=str_replace('{PAGINATION_MENU}',GeneratePaginationSelector($newsarch,$newsid,50,$range),$wrapper);

    $wrapper=str_replace('{RANGE}',$range,$wrapper);
    OutputWrapper($wrapper);
    die();
}

if ($action=="imagemanager")
{
  $nick=$_COOKIE['xusername'];
  $thisdir=$_GET['thisdir'];
  if (empty($thisdir)){$thisdir=$_POST['thisdir'];}
    $thisdir=str_replace("../","",$thisdir);
    $thisdir=str_replace("./","",$thisdir);
    if (substr($thisdir,strlen($thisdir)-1,1)!='/' && $thisdir!=''){$thisdir.='/';}
  $subaction=$_GET['action'];
  $wrapper=file_get_contents($script['paths']['file']."inc/popup-image-manager.html");
  $uptpl=file_get_contents($script['paths']['file']."inc/images-uploadform.html");
  $filestpl=file_get_contents($script['paths']['file']."inc/images-uploaded-files.html");
  $rowtpl=file_get_contents($script['paths']['file']."inc/images-uploaded-files-row.html");
  $message='';

  if ($subaction=="delete")
  {
   $images=$_POST['sel'];
   foreach ($images as $i=>$image)
   {
    $uploader=GetImageUploader($image);
    if ($uploader==$nick)
    {
     if (CheckPerms($nick,"deleteownimages")==false)
     {
       $message="You are not allowed to perform this operation";
     }else
     {
       DeleteUploadedImage($image);
     }
    }else
    {
     if (CheckPerms($nick,"deleteotherimages")==false)
     {
       $message="You are not allowed to perform this operation";
     }else
     {
       DeleteUploadedImage($image);
     }
    }
   }
  }

    if ($subaction=="upimage")
    {
    $error='';
    if (CheckPerms($nick,"uploadimages")==true)
     {
      $uploadfile = $script['paths']['picturedir']. $thisdir.'/'.$_FILES['userfile']['name'];
      if (upMeetsConditions($uploadfile)=='')
      {
       if (copy($_FILES['userfile']['tmp_name'], $uploadfile)) {
          $indexfile=$script['paths']['picturedir']. $thisdir.'/uploaders.php';
          if (!file_exists($indexfile)){ $handle=fopen($indexfile,"w"); } else {$handle=fopen($indexfile,"a");}
          fwrite($handle,$_FILES['userfile']['name']."|".$nick."|\n");
          fclose($handle);
       }  else {
         $error="Your file could not be uploaded. Make sure you have writing permissions in this folder";
         print_r($_FILES);
       }
     }
     else {
      $error=upMeetsConditions($uploadfile);
     }
    } else {$error="You don't permission to upload images";}
      if ($error==''){$message="Your file has been uploaded";}else {$message=$error;}
    }



  if ($subaction==''){$message="";}

  $wrapper=str_replace("{MESSAGE}",$message,$wrapper);
  $wrapper=str_replace("{UPLOADFORM}",$uptpl,$wrapper);
  $wrapper=str_replace("{UPLOADEDIMAGES}",$filestpl,$wrapper);
  $wrapper=str_replace("{THIS_DIR}",$thisdir,$wrapper);
  $wrapper=str_replace("{PIC_FOLDER_PATH}",$script['paths']['picturedir'],$wrapper);
  $wrapper=str_replace('{VIEW_FORM_ACTION}',"index.php?a=imagemanager&amp;page=upimages",$wrapper);
  $wrapper=str_replace('{FORM_ACTION}',"index.php?a=imagemanager&amp;action=delete",$wrapper);
  $wrapper=str_replace('{UPLOAD_FORM_ACTION}',"index.php?a=imagemanager&amp;action=upimage&amp;targetinput=".$_GET['targetinput']."&amp;targetform=".$_GET['targetform']."&amp;thisdir=".$thisdir,$wrapper);
  $wrapper=str_replace('{ONCLICK}','onclick="return confirm(\''.LanguageField("msg_?deletepictures").'\');"',$wrapper);
  $wrapper=str_replace("{TARGET_FORM}",$_GET['targetform'],$wrapper);
  $wrapper=str_replace("{TARGET_INPUT}",$_GET['targetinput'],$wrapper);
  $cpath=$script['paths']['picturedir'].$thisdir;

  $images=fetch_image_array($cpath);

  if (count($images)==0)
  {
  $wrapper=str_replace("{FILE_LIST}","",$wrapper);
  } else
  {



    $dirlistcode='';
    $flistcode='';
    foreach ($images as $i=>$line)
    {
     if ($line['dir']==false)
     {
      if ($line['uploader']==$nick)
      {
       if (CheckPerms($nick,"deleteownimages")==true)
       {
         $checkcode="<input type=\"checkbox\" name=\"sel[]\" value=\"{FILEPATH}\">";
       } else {$checkcode='';}
      }else
      {
       if (CheckPerms($nick,"deleteotherimages")==true)
       {
         $checkcode="<input type=\"checkbox\" name=\"sel[]\" value=\"{FILEPATH}\">";
       } else {$checkcode="";}
      }
      $code=str_replace("{ICON}","inc/img/icon_picture.gif",$rowtpl);
      $code=str_replace("{CHECKBOX}",$checkcode,$code);
      $code=str_replace("{FILEPATH}",$line['filepath'],$code);
      $code=str_replace("{FILENAME}",$line['name'],$code);
      $code=str_replace("{RESOLUTION}",$line['res'],$code);
      $code=str_replace("{FILESIZE}",$line['size'],$code);
      $code=str_replace("{LASTMOD}",$line['lastmod'],$code);
      $code=str_replace("{UPLOADEDBY}",$line['uploader'],$code);
      $link=$script['paths']['pictureurl'].$thisdir.$line['name'];
      $addlink='<a href="javascript:InsertPicture(\''.$link.'\');">Insert</a>';
      $code=str_replace("{ACT}",$addlink,$code);
      $code=str_replace("{ITEMLINK}",$link,$code);
      $flistcode.=$code;
     }else
     {
      $code=str_replace("{ICON}","inc/img/icon_folder.gif",$rowtpl);
      $code=str_replace("{FILENAME}",$line['name'],$code);
      $code=str_replace("{RESOLUTION}","",$code);
      $code=str_replace("{FILESIZE}","",$code);
      $code=str_replace("{LASTMOD}","",$code);
      $code=str_replace("{UPLOADEDBY}","",$code);
      $code=str_replace("{ITEMLINK}",'index.php?a=imagemanager&amp;thisdir='.$thisdir.$line['name'],$code);
      $code=str_replace("{CHECKBOX}","",$code);
      $code=str_replace("{ACT}","",$code);
      $dirlistcode.=$code;
     }
    }
    $wrapper=str_replace("{FILE_LIST}",$dirlistcode.$flistcode,$wrapper);




  }

  OutputWrapper($wrapper);
  die();
}


require("modules.php");

?>
