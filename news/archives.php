<?php

define("LINK", "index.php?pg=archive&");

error_reporting(0);
if (!isset($xnews_prev_inc)){
	include "config.inc.php";
	if (!isset($incFiles['skin'])) include "skin.php";
	if (!isset($incFiles['functions'])) include "functions.php";

	$xnews_prev_inc = 1;
}


$xout='';
$action=@$_GET['xnewsaction'];
$deftemplate=GetDefaultTemplate();
if (!empty($_COOKIE['xnews-apskin'])){$template=@$_COOKIE['xnews-apskin'];}
if (empty($template)){$template=$deftemplate;}
$template=SafeFileRequest($template);
$xwrapper=file_get_contents($script['paths']['file']."templates/$template/content-wrapper.html");
function GetGroupedArchList()
{
 include_once("config.inc.php");
 $archives=GetArchList(@$script['news']['sort']);
 $lasty='';
 $a=0;
 $b=0;
 if (count($archives)>0)
 {
 foreach ($archives as $i=>$archive)
 {
   $a++;
   $month=substr($archive,0,2);
   $year=substr($archive,2,4);
   if ($lasty!=$year)
   {
    $lasty=$year;
    $a=1;
   }
   $res[$year][$a]['month']=$month;
   $res[$year][$a]['year']=$year;
   $res[$year][$a]['newscount']=NewsCount($month.$year,'display');
 }
 }
 return $res;
}


if (empty($action))
{

  if (!isset($authorlist)){$authorlist=@$_GET['xnews-authorlist'];}
  if (!isset($catlist)){$catlist=@$_GET['xnews-catlist'];}
  $archmonth=file_get_contents($script['paths']['file']."templates/$template/archives-month.html");
  $archyear=file_get_contents($script['paths']['file']."templates/$template/archives-year.html");
  $wrapper=file_get_contents($script['paths']['file']."templates/$template/archives-wrapper.html");
  $content='';
  $arr=GetGroupedArchList();

  if (count($arr)>0)
  {
  foreach ($arr as $i=>$item)
  {
   $block='';
   $displayedmonths=0;
   foreach ($item as $j=>$subitem)
   {
    if ($subitem['newscount']>0)
    {
     $hlink=/*$_SERVER['SCRIPT_NAME']*/LINK.'xnewsaction=getnews&amp;newsarch='.$subitem['month'].$subitem['year'];
     if (isset($_GET['xnews-catlist'])){$hlink.='&amp;xnews-catlist='.$_GET['xnews-catlist'];}
     $mtpl=str_replace("{ARCH}",MonthfromNum($subitem['month']),$archmonth);
     $mtpl=str_replace("{ARCHLINK}",$hlink,$mtpl);
     $block.=str_replace("{ARCHNUM}",$subitem['newscount'],$mtpl);
     $displayedmonths++;
    }
   }
   if ($displayedmonths>0)
   {
    $ytpl=str_replace("{MONTHS}",$block,$archyear);
    $content.=str_replace("{YEAR}",$item[1]['year'],$ytpl);
   }
  }
  } else {$content='';}
  $wrapper=str_replace("{ARCHCONT}",$content,$wrapper);
  $xout.=$wrapper;

}
else if ($action=='getnews')
{
 $newsarch=$_GET['newsarch'];
 $newsarch=SafeFileRequest($newsarch);
 $newstpl= file_get_contents($script['paths']['file']."templates/$template/news-article.html");
 if (isset($_GET['xnewsrange'])) $xnewsrange=$_GET['xnewsrange'];
 if (empty($xnewsrange) || !isset($xnewsrange))
 {
  $xstart='';$xend='';
 } else
 {
  $xstart = substr($xnewsrange,0,strpos($xnewsrange,'-'));
  $xend   = substr($xnewsrange,strpos($xnewsrange,'-')+1);
 }
 if (empty($catlist)) $catlist='';
 if (empty($authorlist)) $authorlist='';
 $news=GetNews($newsarch,$catlist,$authorlist,0,$script['news']['sort'],"",0,$xstart,$xend);

 foreach ($news as $i=>$article)
 {
   $xout.=SkinNewsArticle($article,$newstpl);
   if (IsAdPosition(($i+1),'archive')) $xout.=PrintAd($template,'archive');
 }
  $news_wrapper=file_get_contents($script['paths']['file']."templates/$template/news-wrapper.html");
  $news_wrapper=str_replace("{NEWS}",$xout,$news_wrapper);
  $xout=$news_wrapper;
  if (!isset($newsperpage)) $newsperpage=$script['news']['show_first'];
  if (isset($xnewspagination))
  {
   if ($xnewspagination=="1")
   {
    $xout=str_replace('{NAVLINKS}',GenerateNewsPaginationLinks2($newsarch,NewsCount($newsarch,'display'),$newsperpage,$xnewsrange),$xout);
   }else
   {
    $xout=str_replace('{NAVLINKS}','',$xout);
   }
  } else
  {
   $xout=str_replace('{NAVLINKS}','',$xout);
  }
}

else if ($action=='fullnews')
{
  $newsid=$_GET['newsid'];
  $newsarch=$_GET['newsarch'];
  $newsarch=SafeFileRequest($newsarch);
  $newsid=SafeFileRequest($newsid);
  $article=FullNews($newsarch,$newsid,'');
  
  if ( !isset($article['short_story'] ) )
  	  print TEXT_NO_STORY;
  else {
	  $tpl=file_get_contents($script['paths']['file']."templates/$template/full-news-article.html");
	  $c=SkinFullNewsArticle($article,$tpl);
	  $xout.=$c;
  }
  
}

else if ($action=='getcomments')
 {
   $range=$_GET['range'];
   if ($range==''){$range='1-25';}
   $newsid=$_GET['newsid'];
   $newsarch=$_GET['newsarch'];
   $newsarch=SafeFileRequest($newsarch);
   $newsid=SafeFileRequest($newsid);
   $comments=GetComments($newsarch,$newsid,$range);
   $article=FullNews($newsarch,$newsid,'');
   $wrapper=SkinCommentsPage($article,$comments,$template,/*$_SERVER['SCRIPT_NAME']*/LINK,$range);
   $content=str_replace("{notice}",'',$wrapper);
   $xout.=$content;
   unset($content);
   unset($c);

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
   $wrapper=str_replace("{LINK}",/*$_SERVER['SCRIPT_NAME']*/LINK."xnewsaction=getcomments&amp;newsarch=".$newsarch."&amp;newsid=".$newsid,$wrapper);
   $xout.=$wrapper;
 }
 else if ($action=='addcomment')
 {   if ($script['comments']['enabled']!="ON")
   {
    $wrapper=SkinMessage('$__msg_commentsdisabled',$template);
    $xout.=$wrapper;
   }else
   {
  // echo "hey!";
   $author=$_POST['author'];
   $email=$_POST['email'];
   $comment=$_POST['comment'];
   $newsid=$_GET['newsid'];
   $newsarch=$_GET['newsarch'];
   if (empty($author) or empty($comment) or !VerifyCaptcha() or !VerifyCommentLength())
   {
     $notice='';
     if (empty($author)){$notice='$__msg_forgotname';}
     if (empty($comment)){if ($notice==''){$notice='$__msg_forgotcomment';}else {$notice.='<BR />$__msg_forgotcomment';}}
     if (!VerifyCaptcha()){if ($notice==''){$notice='$__msg_wrongcaptcha';}else {$notice.='<BR />$__msg_wrongcaptcha';}}
     if (!VerifyCommentLength()){if ($notice==''){$notice='$__msg_longcomment';}else {$notice.='<BR />$__msg_longcomment';}}
     $comments=GetComments($newsarch,$newsid,$range);
     $article=FullNews($newsarch,$newsid,'');
     $wrapper=SkinCommentsPage($article,$comments,$template,/*$_SERVER['SCRIPT_NAME']*/LINK,'1-25');
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
     $wrapper=str_replace("{LINK}",/*$_SERVER['SCRIPT_NAME']*/LINK."xnewsaction=getcomments&amp;newsarch=".$newsarch."&amp;newsid=".$newsid,$wrapper);
     $xout.=$wrapper;
    } else
    {
     $xout.= SkinMessage(LanguageField('msg_requirelogin',''),$template);
     $xout=str_replace('{REGNICK}',$author,$xout);
    }
    }
  }
 }
if ($script['rss']['enabled']=="YES")
{
$feedlinkcode='<a href="'.$script['paths']['url'].'news.xml"><img src="'.$script['paths']['url'].'inc/img/rss.gif" border="0" alt="Google" /></a>';
$feedlinkcode.='<a href="http://fusion.google.com/add?feedurl='.$script['paths']['url'].'news.xml"><img src="'.$script['paths']['url'].'inc/img/rss-google.gif" border="0" alt="XNews" /></a>';
$feedlinkcode.='<a href="http://e.my.yahoo.com/config/cstore?.opt=content&amp;.url='.$script['paths']['url'].'news.xml"><img src="'.$script['paths']['url'].'inc/img/rss-yahoo.gif" border="0" alt="Yahoo"/></a>';
$xout=str_replace("{FEEDLINKS}",$feedlinkcode,$xout);
}
$xout=str_replace("{FEEDLINKS}","",$xout);
$xwrapper=str_replace("{CONTENT}",$xout,$xwrapper);
$xout=$xwrapper;
OutputWrapper($xout,1);
unset($content,$wrapper,$action,$xout);

?>