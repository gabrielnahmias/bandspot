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

function SyncPostNews($author,$headline,$icon,$cat,$shortstory,$fullstory,$active="1",$activationdate="",$moddate="",$modauthor="",$date="",$gid)
{

 $now=ArchiveNameStamp($activationdate);
 require("config.inc.php");
 require_once("functions.php");
 $filename=$script['paths']['file']."news/news-".$now.".php";
 $headline=FormatSimpleString($headline);
 $icon=FormatSimpleString($icon);
 $shortstory=FormatXstring($shortstory);
 $fullstory=FormatXstring($fullstory);
 $pubdate=$date;
 if (file_exists($filename))
 {
   $id=$gid;
   $handle = fopen($filename, 'a');
   $line='|'.$id.'|'.$pubdate.'|'.$cat.'|'.$author.'|'.$headline.'|'.$shortstory.'|'.$fullstory.'|'.$icon.'|'.$active.'|'.$activationdate.'|'.$moddate.'|'.$modauthor.'|<';
   fwrite($handle,$line."\n");
   fclose($handle);
 }else
 {
   $handle = fopen($filename, 'w');
   $id=1;
   $id=$gid;
   $line='|'.$id.'|'.$pubdate.'|'.$cat.'|'.$author.'|'.$headline.'|'.$shortstory.'|'.$fullstory.'|'.$icon.'|'.$active.'|'.$activationdate.'|'.$moddate.'|'.$modauthor.'|<';
   fwrite($handle,"<?php die(\"Access denied\");?>\n");
   fwrite($handle,$line."\n");
   fclose($handle);
 }
 $ret['arch']= $now;
 $ret['id']  = $id;
 return $ret;
}

function SyncPostNews2($author,$date,$headline,$icon,$cat,$shortstory,$fullstory,$active="1",$activationdate="",$moddate="",$modauthor="",$uid)
{
 return SyncPostNews($author,$headline,$icon,$cat,$shortstory,$fullstory,$active,$activationdate,$moddate,$modauthor,$date,$uid);
}


function SyncPostComment($id,$newsarch,$newsid,$author,$email,$comment,$ip="",$timestamp="")
{
 $error='';

 $comment=stripslashes($comment);
 $author=stripslashes($author);
 $email=stripslashes($email);

 require("config.inc.php");
 require_once("functions.php");
 $comment=FormatXString($comment);
 $author=FormatSimpleString($author);
 $email=FormatSimpleString($email);


 if (file_exists($script['paths']['file']."news/comments-".$newsarch.".php"))
 {
   $handle = fopen($script['paths']['file']."news/comments-".$newsarch.".php", 'a');
   $line="|".$id."|".$newsid."|".$ip."|".$timestamp."|".$author."|".$email."|".$comment."|<";
   fwrite($handle,"\n");
   fwrite($handle,$line);
   fclose($handle);
 }else
 {
   $handle = fopen($script['paths']['file']."news/comments-".$newsarch.".php", 'w');
   $id=1;
   $line="|".$id."|".$newsid."|".$ip."|".$timestamp."|".$author."|".$email."|".$comment."|<";
   fwrite($handle,"<?php die(\"Access denied\");?>\n");
   fwrite($handle,$line);
   fclose($handle);
 }
}

function CuteListNews($newsfile)
{
     require_once("functions.php");
     $newslines = file($newsfile);
     foreach ($newslines as $i=>$newsline)
     {
      $parts=explode('|',$newsline);
      $articles[$i]['timestamp']  = $parts[0];
      $articles[$i]['author']     = $parts[1];
      $articles[$i]['title']      = $parts[2];
      $articles[$i]['shortstory'] = $parts[3];
      $articles[$i]['fullstory']  = $parts[4];
      $articles[$i]['avatar']     = $parts[5];
      $articles[$i]['category']   = $parts[6];
      $now=ArchiveNameStamp($articles[$i]['timestamp']);
      $articles[$i]['archive']    = $now;
      //      PostNews2($article['author'],$article['timestamp'],$article['title'],$article['avatar'],$article['category'],$article['shortstory'],$article['fullstory'],"1",$article['timestamp']);
     }
     return $articles;
}

function CuteListComments($commentsfile)
{
     $comlines = file($commentsfile);
     $tc=0;
     $comments='';
     foreach ($comlines as $i=>$comline)
     {
      $content = substr($comline,strpos($comline,'>|')+2);
      $parts   = explode('|',$content);
      $cn=0;
      unset($com);
      $artdate  = substr($comline,0,strpos($comline,'|'));
      $archname = ArchiveNameStamp($artdate);
      for ($j=0;$j<count($parts)-6;$j=$j+6)
      {
         $com[$cn]['timestamp']  =  $parts[$j];
         $com[$cn]['author']     =  $parts[$j+1];
         $com[$cn]['email']      =  $parts[$j+2];
         $com[$cn]['ip']         =  $parts[$j+3];
         $com[$cn]['comment']    =  $parts[$j+4];
         $com[$cn]['articledate'] = $artdate;
         $com[$cn]['archive']     = $archname;
         $cn++;
      }
      if (isset($com))
      {
        $comments[$tc]['comments']    = $com;
        $comments[$tc]['articledate'] = $artdate;
        $comments[$tc]['archive']     = $archname;

        $tc++;
      }
     }
     return $comments;
}

function AssignNewsComments($arrdest,$newsfile)
{
 require_once("functions.php");
 if (!isset($newsfile['comments'])) return $arrdest;
 $comments  =  CuteListComments($newsfile['comments']);
 if (empty($comments)) return $arrdest;
 foreach ($arrdest as $nai=>$news)
 {
  foreach ($comments as $cit=>$comitem)
  {
   if ($comitem['articledate']==$news['timestamp'])
   {
    $arrdest[$nai]['comments']=$comments[$cit]['comments'];

  //  print_r($comitem);
    unset($comitem['comments']);
    break;
   }
  }
 }
 return $arrdest;
}

function SyncCuteNews($pathto,$what)
{
 require("config.inc.php");
 require_once("functions.php");
 $msg='';

 if ($what['cat']=='YES')
 {
  $catfile=$pathto."data/category.db.php";
  if (!file_exists($catfile) || !is_readable($catfile)){$msg.='$__err_synccat<br />';}else
  {
   $catlines = file($catfile);
   foreach ($catlines as $i=>$catline)
   {
    $parts = explode('|',$catline);
    if (count($parts)>1)
    {
     $catarr[$parts[0]]=AddCat2($parts[1],'',EncodeTime(''));
    }
   }
  }
 }

 if ($what['news']=='YES')
 {
    $c=0;
    if (file_exists($pathto."data/news.txt"))
    {
     $newsfiles[$c]['news']=$pathto."data/news.txt";
     if (file_exists($pathto."data/comments.txt"))$newsfiles[$c]['comments']=$pathto."data/comments.txt";
     $c++;
    }
    if (file_exists($pathto."data/postponed_news.txt")){ $newsfiles[$c]['news']=$pathto."data/postponed_news.txt";$c++;}

    if ($dh = opendir($pathto."data/archives"))
    {
      while (($file = readdir($dh)) !== false)
      {
       $np=explode('.',$file);
       if (@$np[1]=="news" && @$np[2]=="arch")
       {
         $newsfiles[$c]['news']=$pathto."data/archives/".$file;
         if (file_exists($pathto."data/archives/".$np[0].".comments.arch"))$newsfiles[$c]['comments']=$pathto."data/archives/".$np[0].".comments.arch";
         $c++;
       }
      }
      closedir($dh);
    }
    foreach ($newsfiles as $i=>$newsfile)
    {
     $newsarr=CuteListNews($newsfile['news']);
     if ($what['comments']=='YES') $newsarr=AssignNewsComments($newsarr,$newsfile);
     foreach ($newsarr as $nai=>$news)
     {
     $nid=@$newsid[$news['archive']];
     if (empty($nid)){$nid=1;$newsid[$news['archive']]=2;}else {$newsid[$news['archive']]=$newsid[$news['archive']]+1;}
     if (!isset($uts[$news['timestamp']]))
     {
      $cat_id=@$catarr[$news['category']];
      if (empty($cat_id)) $cat_id=$news['category'];
      $ret=SyncPostNews2($news['author'],$news['timestamp'],$news['title'],$news['avatar'],$cat_id,$news['shortstory'],$news['fullstory'],"1",$news['timestamp'],"","",$nid);
      $uts[$news['timestamp']]=1;
      if (isset($news['comments']) && !empty($news['comments']))
      {
       foreach ($news['comments'] as $citer=>$cpost)
       {
        $cmid=@$commentsid[$news['archive']];

        if (empty($cmid)){$cmid=CommentsGetMaxId($news['archive'])+1;$commentsid[$news['archive']]=2;}else
        {$commentsid[$news['archive']]=$commentsid[$news['archive']]+1;}
        SyncPostComment($cmid,$news['archive'],$ret['id'],$cpost['author'],$cpost['email'],$cpost['comment'],$cpost['ip'],$cpost['timestamp']);
       }
      }
      }
     }
    }
   if ($script['rss']['enabled']=="YES"){RebuildRss();}
 }


 if ($what['users']=='YES')
 {
  $userfile=$pathto."data/users.db.php";
  if (!file_exists($userfile) || !is_readable($userfile)){$msg.='$__err_syncuserdb<br />';}else
  {
    $userlines = file($pathto."data/users.db.php");
    foreach ($userlines as $i=>$userline)
    {
        $parts=explode("|",$userline);
       if (count($parts)>2)
       {
        AddUser($parts[4],$parts[2],$parts[3],"1","",$parts[5],$parts[8],$parts[0]);
       }
    }
  }
 }

 //print_r($catarr);

 if ($msg=='')
 {
  return '$__msg_synccomplete';
 }else
 {
  return $msg;
 }

}

/*$what['news']='YES';
$what['users']='YES';
$what['news']='YES';
$what['cat']='YES';
SyncCuteNews("D:/WebServer/cutenews/",$what);*/

function SyncXnews100($pathto,$what)
{
 require("config.inc.php");
 $msg='';
 if (!file_exists($pathto."news/"))
 {
  $msg.='$__err_syncnews<br />';
 }else
 {
 if ($dh = opendir($pathto."news/")) {
   while (($file = readdir($dh)) !== false) {
     if ($file!='.' and $file!='..')
      if (substr($file,0,5)=='news-')
      {
       if ($what['news']=='YES')
       {
         copy($pathto."news/$file",$script['paths']['file']."news/".$file);
       }
      }
      if (substr($file,0,8)=='comments')
      {
       if ($what['comments']=='YES')
       {
         copy($pathto."news/$file",$script['paths']['file']."news/".$file);
       }
      }

   }
   closedir($dh);
 }
 }
 if ($what['cat']=='YES')
 {
 if (!file_exists($pathto."catdb.php")){$msg.='$__err_synccat<br />';}else
 {
   copy($pathto."catdb.php",$script['paths']['file']."catdb.php");
   copy($pathto."catindex.txt",$script['paths']['file']."catindex.txt");
 }
 }

 if ($what['ranks']=='YES')
 {
 if (!file_exists($pathto."ranks.php")){$msg.='$__err_syncrank<br />';}else
 {
   @copy($pathto."ranks.php",$script['paths']['file']."ranks.php");
   @copy($pathto."rankindex.txt",$script['paths']['file']."rankindex.txt");
 }
 }
 if ($what['users']=='YES')
 {
 if (!file_exists($pathto."userdb.php")){$msg.='$__err_syncuserdb<br />';}else
 {
   copy($pathto."userdb.php",$script['paths']['file']."userdb.php");
 }
 }
 if ($msg=='')
 {
  return '$__msg_synccomplete';
 }else
 {
  return $msg;
 }
}

function SyncXnews101($pathto,$what)
{
 require("config.inc.php");
 $msg='';
 if (!file_exists($pathto."news/"))
 {
  $msg.='$__err_syncnews<br />';
 }else
 {
 if ($dh = opendir($pathto."news/")) {
   while (($file = readdir($dh)) !== false) {
     if ($file!='.' and $file!='..')
      if (substr($file,0,5)=='news-')
      {
       if ($what['news']=='YES')
       {
         copy($pathto."news/$file",$script['paths']['file']."news/".$file);
       }
      }
      if (substr($file,0,8)=='comments')
      {
       if ($what['comments']=='YES')
       {
         copy($pathto."news/$file",$script['paths']['file']."news/".$file);
       }
      }

   }
   closedir($dh);
 }
 }
 if ($what['cat']=='YES')
 {
 if (!file_exists($pathto."catdb.php")){$msg.='$__err_synccat<br />';}else
 {
   copy($pathto."catdb.php",$script['paths']['file']."catdb.php");
   copy($pathto."catindex.txt",$script['paths']['file']."catindex.txt");
 }
 }

 if ($what['ranks']=='YES')
 {
 if (!file_exists($pathto."ranks.php")){$msg.='$__err_syncrank<br />';}else
 {
   @copy($pathto."ranks.php",$script['paths']['file']."ranks.php");
   @copy($pathto."rankindex.txt",$script['paths']['file']."rankindex.txt");
 }
 }
 if ($what['users']=='YES')
 {
 if (!file_exists($pathto."userdb.php")){$msg.='$__err_syncuserdb<br />';}else
 {
   copy($pathto."userdb.php",$script['paths']['file']."userdb.php");
 }
 }
 if ($what['license']=='YES')
 {
  if (!file_exists($pathto."license.dat")){$msg.='$__err_synclicense<br />';}else
  {
    copy($pathto."license.dat",$script['paths']['file']."license.dat");
  }
 }
 if ($what['pictures']=="YES")
 {
  if (!file_exists($pathto."images")){$msg.='$__err_syncpictures<br />';}else
  {
   if ($dh = opendir($pathto."images/"))
    {
     while (($file = readdir($dh)) !== false)
     {
      if ($file!='.' and $file!='..')
      {
          copy($pathto."images/$file",$script['paths']['file']."images/".$file);
      }
     }
     closedir($dh);
    }
   }
 }

 if ($what['icons']=="YES")
 {
  if (!file_exists($pathto."icons")){$msg.='$__err_syncicons<br />';}else
  {
   if ($dh = opendir($pathto."icons/"))
    {
     while (($file = readdir($dh)) !== false)
     {
      if ($file!='.' and $file!='..')
      {
          copy($pathto."icons/$file",$script['paths']['file']."icons/".$file);
      }
     }
     closedir($dh);
    }
   }
  }

 if ($msg=='')
 {
  return '$__msg_synccomplete';
 }else
 {
  return $msg;
 }
}

function xSynchronize($syncfrom,$pathto,$what)
{
 switch ($syncfrom)
 {
  case "xnews1-0-0": return SyncXnews100($pathto,$what);
  case "xnews1-0-1": return SyncXnews101($pathto,$what);
  case "cutenews": return SyncCuteNews($pathto,$what);
 }
}

?>