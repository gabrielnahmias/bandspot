<?php

$strConfig = "";

if ( basename( getcwd() ) != "el" )
	$strConfig = "../";

require_once $strConfig."inc/config.php";
//print "$strConfig/inc/config.php";

//error_reporting(0);
if (!isset($xnews_prev_inc)){
	include "config.inc.php";
	if (!isset($incFiles['skin'])) include "skin.php";
	if (!isset($incFiles['functions'])) include "functions.php";

	$xnews_prev_inc = 1;
}


$action=@$_GET['xnewsaction'];

$currentsection = 1;

if (!isset($xsection)) $xsection = 0;

if (!empty($_GET['xsection'])) if ($xsection!=$_GET['xsection'])
{
 $action = '';
 $currentsection = 0;
}
$deftemplate=GetDefaultTemplate();
$xout='';
if (!empty($_COOKIE['xnews-apskin'])){$template=@$_COOKIE['xnews-apskin'];}

$template=@SafeFileRequest($template);
if (empty($template)){$template=$deftemplate;}
$xwrapper=file_get_contents($script['paths']['file']."templates/$template/content-wrapper.html");
 

if (empty($action))
{

  if (!isset($authorlist)){if ($currentsection) $authorlist=@$_GET['xnews-authorlist']; else $authorlist = '';}
  if (!isset($catlist)){if ($currentsection) $catlist=@$_GET['xnews-catlist']; else $catlist = '';}
  $newsfiles=GetArchList($script['news']['sort']);

  $num=0;
  $newstpl=file_get_contents($script['paths']['file']."templates/$template/news-article.html");
  $news_wrapper=file_get_contents($script['paths']['file']."templates/$template/news-wrapper.html");
  if (count($newsfiles)>0)
  {
   if (!isset($qrange) || empty($qrange))
   {
    // no limits are set. The script will display the first $script['news']['showfirst'] news in the order selected
    // in the control panel
    foreach ($newsfiles as $i=>$line)
    {
      $news=GetNews($line,$catlist,$authorlist,$script['news']['show_first'],$script['news']['sort'],"",0);
      if (!empty($news))
      {
      foreach ($news as $j=>$article)
       {
		 $xout.=SkinNewsArticle($article,$newstpl,$xsection);
		 $num++;
		 if (IsAdPosition($num,'active')) $xout.=PrintAd($template,'active');
		 if ($num==$script['news']['show_first'])
		 {
		  break;
		 }
       }
      }
      if ($num==$script['news']['show_first'])
      {
        break;
      }
    }
   }
  }
  $news_wrapper=str_replace("{NEWS}",$xout,$news_wrapper);
  $xout=$news_wrapper;

} else if ($action=='getcomments')
 {

   if (empty($_GET['range']))$range='1-25';else $range = $_GET['range'];
   $newsid=$_GET['newsid'];
   $newsarch=$_GET['newsarch'];
   $comments=GetComments($newsarch,$newsid,$range,$xsection);
   $article=FullNews($newsarch,$newsid,'');
   $wrapper=SkinCommentsPage($article,$comments,$template,$_SERVER['SCRIPT_NAME'],$range);
   $content=str_replace("{notice}",'',$wrapper);
   $xout.=$content;
   unset($content);

 }
 else if ($action=='delcomment')
 {
   $validlogin  = ValidateLogin($_COOKIE['xusername'],$_COOKIE['xpassword']);
   $newsid    = $_GET['newsid'];
   $newsarch  = $_GET['newsarch'];
   $commentid = $_GET['commentid'];

   if ($validlogin==1)
   {
      $caneditown   =  CheckPerms($_COOKIE['xusername'],'editowncomments');
      $caneditother =  CheckPerms($_COOKIE['xusername'],'editothercomments');
      $cauth        = GetCommentAuthor($newsarch,$newsid,$commentid);
      if ($_COOKIE['xusername']==$cauth) $canedit = $caneditown; else $canedit = $caneditother;
      if ($canedit == 1)
      {
       DeleteComment($newsarch,$commentid);
       $msg = LanguageField('msg_commentdeleted','');
      }
   }else
   {
    $msg = LanguageField('msg_requireadmin','');
   }
   $wrapper=SkinMessage($msg,$template);
   $wrapper=str_replace("{LINK}",$_SERVER['SCRIPT_NAME']."?xnewsaction=getcomments&amp;newsarch=".$newsarch."&amp;newsid=".$newsid,$wrapper);
   $xout.=$wrapper;
 }
 else if ($action=='addcomment')
 {
   if ($script['comments']['enabled']!="ON")
   {
    $wrapper=SkinMessage('$__msg_commentsdisabled',$template);
    $xout.=$wrapper;
   }else
   {
	$author		=	$_POST['author'];
	$email		=	$_POST['email'];
	$comment	=	$_POST['comment'];
	$newsid		=	$_GET['newsid'];
	$newsarch	=	$_GET['newsarch'];
   if (empty($author) or empty($comment) or !VerifyCaptcha() or !VerifyCommentLength())
   {
	
     $notice='';
     if (empty($author)){$notice='$__msg_forgotname';}
     if (empty($comment)){if ($notice==''){$notice='$__msg_forgotcomment';}else {$notice.='<BR />$__msg_forgotcomment';}}
     if (!VerifyCaptcha()){if ($notice==''){$notice='$__msg_wrongcaptcha';}else {$notice.='<BR />$__msg_wrongcaptcha';}}
     if (!VerifyCommentLength()){if ($notice==''){$notice='$__msg_longcomment';}else {$notice.='<BR />$__msg_longcomment';}}
     $comments=GetComments($newsarch,$newsid,@$range);
     $article=FullNews($newsarch,$newsid,'');
     $wrapper=SkinCommentsPage($article,$comments,$template,$_SERVER['SCRIPT_NAME'],'1-25',$xsection);
     $content=str_replace("{notice}",$notice,$wrapper);
     $xout.=$content;
   }
  else {
     $post=1;
     if (IsUser($author))
     {
       if ($author!=$_COOKIE['xusername']){$post=0;}
        else
        {
         if (ValidateLogin($author,$_COOKIE['xpassword'])==1)
         {
          $post=1;
         }
        else
        {
         $post=0;
        }
       }
     }
    if ($post==1)
    {
		
     PostComment($newsarch,$newsid,$author,$email,$comment);
     $wrapper=SkinMessage(LanguageField('msg_commentposted',''),$template);
     $wrapper=str_replace("{LINK}",$_SERVER['SCRIPT_NAME']."?xnewsaction=getcomments&amp;newsarch=".$newsarch."&amp;newsid=".$newsid,$wrapper);
     $xout.=$wrapper;
    } else
    {
     $xout.= SkinMessage(LanguageField('msg_requirelogin',''),$template);
     $xout=str_replace('{REGNICK}',$author,$xout);
    }
   }
  }
 }
else if ($action=='fullnews')
{
  $newsid=$_GET['newsid'];
  $newsarch=$_GET['newsarch'];
  $article=FullNews($newsarch,$newsid,'');
  
  if ( !isset($article['short_story'] ) )
  	  print TEXT_NO_STORY;
  else {
	  $tpl=file_get_contents($script['paths']['file']."templates/$template/full-news-article.html");
	  $c=SkinFullNewsArticle($article,$tpl,$xsection);
	  $xout.=$c;
  }
  
}

if ($script['rss']['enabled']=="YES")
{
$feedlinkcode='<a href="'.$script['paths']['url'].'news.xml"><img src="'.$script['paths']['url'].'inc/img/rss.gif" border="0" alt="Google" /></a>';
$feedlinkcode.='<a href="http://fusion.google.com/add?feedurl='.$script['paths']['url'].'news.xml"><img src="'.$script['paths']['url'].'inc/img/rss-google.gif" border="0" alt="XNews" /></a>';
$feedlinkcode.='<a href="http://e.my.yahoo.com/config/cstore?.opt=content&amp;amp;.url='.$script['paths']['url'].'news.xml"><img src="'.$script['paths']['url'].'inc/img/rss-yahoo.gif" border="0" alt="Yahoo"/></a>';
$xout=str_replace("{FEEDLINKS}",$feedlinkcode,$xout);
}
$xout=str_replace("{FEEDLINKS}","",$xout);
$xwrapper=str_replace("{CONTENT}",$xout,$xwrapper);
$xwrapper=str_replace("{NAVLINKS}","",$xwrapper);
$xout=$xwrapper;
OutputWrapper($xout,1);
unset($content,$wrapper,$action,$xout);

?>
                       
<script language="javascript" type="text/javascript">

$("span.pagination").remove();

</script>