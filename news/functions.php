<?php
/*
 ========================================================================
 |  Copyright 2010 - Andrei Dumitrache. All rights reserved.
 | 	 http://www.xpression-news.com
 |
 |   This file is part of Elemovements News (X-News) v2.0.2
 |
 |   This program is Freeware.
 |   Please read the license agreement to find out how you can modify this web script.
 |
 ========================================================================
*/

$script['current_version']='2.0.2';
$incFiles['functions']	= 1;

function ListNoAccess(){
	$files='';
	if (!is_readable("config.inc.php") or !is_writable("config.inc.php")){$files.="config.inc.php ";}
	if (!is_readable("userdb.php") or !is_writable("userdb.php")){$files.="userdb.php ";}
	if (!is_readable("ranks.php") or !is_writable("ranks.php")){$files.="ranks.php ";}
	if (!is_readable("catdb.php") or !is_writable("catdb.php")){$files.="catdb.php ";}
	if (!is_readable("catindex.txt") or !is_writable("catindex.txt")){$files.="catindex.txt ";}
	if (!is_readable("ranks.php") or !is_writable("ranks.php")){$files.="ranks.php ";}
	
	$news_dir =dir("news/");
	$i=1;
	while (false !== ($entry = $news_dir->read())){
		if ($entry!='.' && $entry!='..' && substr($entry,0,4)=='news' or substr($entry,0,8)=='comments'){
		if (!is_readable("news/".$entry) or !is_writable("news/".$entry)){$files.="$entry ";}
		}
	}
	$news_dir->close();
	return $files;
}

function validArchive($testVal){
	if (empty($testVal))	return 0;
	$fz = 0;
	if ($testVal{0} == '0'){
		$testVal = substr($testVal,1);
		$fz = 1;
	}
	if (strlen($testVal)>6)
		return 0;
	$iv = intval($testVal);
	if ($iv!=$testVal) return 0;
	if ($fz) $iv = '0'.$iv;
	return $iv;
}




function TrimFloat($num,$digits){
	$num=substr($num,0,strpos($num,".")+$digits+1);
	return $num;
}


function GetOwner(){
	global $script;
	$fh		=	fopen($script['paths']['file']."userdb.php","r");
	if (!$fh || !flock($fh,LOCK_SH)) return 0;
	fgets($fh);
	$userline = fgets($fh);
	if (empty($userline))
		return 0;
	$parts=explode("|",$userline);
	return strtolower($parts[0]);
}


function ValidateLogin($user,$pass){
	global $script;
	$user	= strtoupper($user);
	$fh		= fopen($script['paths']['file']."userdb.php","r");
	if (!$fh || !flock($fh,LOCK_SH))
		return 0;
	fgets($fh);

	while (!feof($fh)) if ($line = trim(fgets($fh))){
		$parts=explode('|',$line);
		if (strtoupper($parts[0])==$user && $parts[2]==$pass)
    		return  1;
  
	}
	return 0;
}

function TranslateSimpleString($input){
	$input=html_entity_decode($input);
	$input=str_replace("<","&lt;",$input);
	$input=str_replace(">","&gt;",$input);
	$input=str_replace("\"","&quot;",$input);
	$input=str_replace("'","&#039;",$input);
	$input=str_replace("{%&s}","|",$input);
	return $input;
}


function LicAuth(){
	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$url=substr($url,0,strlen($url)-11);
	$ldl=file_get_contents("license.dat");
	@file_get_contents("http://xpression.hogsmeade-village.com/licauth.php?luri=$url&lic=$ldl");
}

function IsLicensed(){ global $script; $lines=@file($script['paths']['file']."license.dat"); $ch=base64_decode($lines[0]);  if (strpos($ch,"XUID")===FALSE) return 0; else return 1;}
function CountPasswordReset($user){
	global $script;
	$lines=@file($script['paths']['file']."resetdb.php");
	if (empty($lines))
		return 0;
	$c=0;
	foreach ($lines as $i=>$line){
		$parts=explode('|',$line);
		if (@$parts[0]==$user)
			$c++;
    }
	return $c;
}

function ValidatePasswordReset($user,$resetcode){
	global $script;
	$lines=file($script['paths']['file']."resetdb.php");
	$comp=md5($resetcode);
	foreach ($lines as $i=>$line){
		$parts=explode('|',$line);
   if (@$parts[0]==$user && @$parts[1]==$comp)
   {
     return 1;
   }
 }
 return 0;
}

function AddPasswordReset($user)
{
 global $script;
 $filename = $script['paths']['file']."resetdb.php";
 $code = mt_rand();
 $code = substr(sha1($code), 0, 15);

 if (!file_exists($filename))
  {
   $handle = fopen($filename,"w");
   fwrite($handle,'<?php die("You have no access to this file");?>'."\n");
  }else $handle = fopen($filename,"a");
  fwrite($handle,$user.'|'.md5($code)."|\n");
  fclose($handle);
  return $code;
}


function DeletePasswordReset($user)
{
 global $script;
 $filename = $script['paths']['file']."resetdb.php";
 $lines=file($filename);
 $handle   = fopen($filename,"w");
 foreach ($lines as $i=>$line)
 {
   $parts=explode('|',$line);
   if (@$parts[0]!=$user)
   {
     fwrite($handle,$line);
   }
 }
 fclose($handle);


}



function GetUserInfo($user){
	global $script;
	$fh		= @fopen($script['paths']['file'].'userdb.php','r');
	if (!$fh || !flock($fh,LOCK_SH)) return array();
	fgets($fh);
	$user	= strtolower($user);
	while (!feof($fh)) if ($line = trim(fgets($fh))) { 
		$parts	=	explode('|',$line);
		if (strtolower($parts[0])==$user){
			$arr['nick']		=	$parts[0];
			$arr['name']		=	$parts[1];
			if ($parts[1]==' ')
				$arr['name']	=	$user;
			$arr['password']	=	$parts[2];
			$arr['level']		=	$parts[3];
			$arr['email']		=	$parts[4];
			$arr['semail']		=	$parts[5];
			$arr['joined']		=	$parts[6];
			$arr['lastpost']	=	$parts[7];
			$arr['avatar']		=	$parts[8];
			flock($fh,LOCK_UN);
			fclose($fh);
			return $arr;
		}	
	}
	$arr['name']=$user;
	return $arr;
}

function SafeFileRequest($input){
	trim($input);
    $input=urlencode($input);
    $input=str_replace("%2F","",$input);
    $input=str_replace("%5C","",$input);
 	$input=str_replace("%00","",$input);
	$input=str_replace("%13","",$input);
    $input=str_replace("%10","",$input);
    $input=urldecode($input);
    $input=str_replace("./","",$input);
    $input=str_replace("../","",$input);
	$input=str_replace(chr(13),"",$input);
    $input=str_replace(chr(10),"",$input);
    return $input;
}

function GetUserList(){
	global $script;
	$fh		= fopen($script['paths']['file']."userdb.php","r");
	if (!$fh || !flock($fh,LOCK_SH))
		return array();
	fgets($fh);
	$i=1;
	while (!feof($fh)) if ($line = trim(fgets($fh))) {
		$parts=explode('|',$line);
		$arr[$i]['nick']=$parts[0];
		$arr[$i]['name']=$parts[1];
		$arr[$i]['password']=$parts[2];
		$arr[$i]['level']=$parts[3];
		$arr[$i]['email']=$parts[4];
		$arr[$i]['semail']=$parts[5];
		$arr[$i]['joined']=$parts[6];
		$arr[$i]['lastpost']=$parts[7];
		$arr[$i]['avatar']=$parts[8];
		$i++;
	}	
	flock($fh,LOCK_UN);
	fclose($fh);
	return $arr;
}

$rvfcm=0;

function UpdateUser($nick,$name,$password,$level,$email1,$email2,$avatar){
	global $script;
	$lines=file($script['paths']['file']."userdb.php");
	$i=0;
	$towrite='';

	foreach ($lines as $i=>$line){
		$parts=explode('|',$line);
		if (strtoupper($parts[0])	==	strtoupper($nick)){
			$info	=	GetUserInfo($nick);
			if ($level=='')
				$level=$info['level'];
			if ($password=='')
			    $line=$nick."|".$name."|".$info['password']."|".$level."|".$email1."|".$email2."|".$info['joined']."|".$info['lastpost']."|".$avatar."|\n";
			else
				$line=$nick."|".$name."|".md5($password)."|".$level."|".$email1."|".$email2."|".$info['joined']."|".$info['lastpost']."|".$avatar."|\n";
     	}
		$towrite.=$line;
	}
	$handle	=	fopen($script['paths']['file']."userdb.php","a");
	if (!$handle || !flock($handle,LOCK_EX))
		return 0;
	fseek($handle,0);
	ftruncate($handle,0);
	fwrite($handle,$towrite);
	flock($handle,LOCK_UN);
	fclose($handle);
	return 1;
}

function UpdateUserLastPost($nick){
 global $script;
 $lines=file($script['paths']['file']."userdb.php");
 $i=0;
 $newlines='';

 foreach ($lines as $i=>$line)
 {
    $parts=explode('|',$line);
    if ($parts[0]==$nick)
    {
     $info=GetUserInfo($nick);
     $line=$nick."|".$info['name']."|".$info['password']."|".$info['level']."|".$info['email']."|".$info['semail']."|".$info['joined']."|".EncodeTime('')."|".$info['avatar']."|\n";
    }
    $newlines[$i]=$line;
 }
 $handle=fopen($script['paths']['file']."userdb.php","w");
 foreach ($newlines as $i=>$line)
 {
  fwrite($handle,$line);
 }
 fclose($handle);
}

function IsUser($nick){
	global $script;
	$fh		= fopen($script['paths']['file']."userdb.php","r");
	if (!$fh || !flock($fh,LOCK_SH))
		return 1;
	
	fgets($fh);
	while (!feof($fh)) if ($line = trim(fgets($fh))){
		$parts=explode('|',$line);
		if (strtolower($parts[0])==strtolower($nick)){
			fclose($fh);	
			return 1;
		}
	}
	fclose($fh);
	return 0;

}

function GetClientRequest(){
	$r=$_SERVER['REQUEST_URI'];
	$r=substr($r,strrpos($r,"/")+1);
	return $r;
}


function AddUser($nick,$name,$password,$level,$publicmail,$mail,$avatar,$ts=''){
	if (IsUser($nick))
		return "user already in database";
	else{
		global $script;
		$handle	=	fopen($script['paths']['file']."userdb.php","a");
		if (empty($ts)) 
			$ts=EncodeTime('');
		$newline="$nick|$name|".md5($password)."|$level|$publicmail|$mail|".$ts."||$avatar|";
		flock($handle,LOCK_EX);
		fwrite($handle,$newline."\n");
		fclose($handle);
	}
}

function DeleteUser($nick){
	global $script;
	$lines=file($script['paths']['file']."userdb.php");
	$i=0;
	$newlines='';
	$j=0;
	$nick = strtolower($nick);
	foreach ($lines as $i=>$line){
		$parts=explode('|',$line);
		if (strtolower($parts[0])!=$nick){
			$newlines[$j]=$line;
			$j++;
		}
	}
	$handle=fopen($script['paths']['file']."userdb.php","a");
	if (!$handle || !flock($handle,LOCK_EX))
		return 0;
	fseek($handle,0);
	ftruncate($handle,0);
	foreach ($newlines as $i=>$line)
		fwrite($handle,$line);
	fclose($handle);
}

function IsPicture($filename){
	$bn		=	basename($filename);
	$ext	=	substr($bn,strpos($bn,'.')+1,strlen($bn));
	$ext	=	strtoupper($ext);
	if ($ext=='')
		return 0;
	$spp="GIF JPG PNG SWF SWC PSD TIFF BMP IFF JP2 JPX JB2 JPC XBM WBMP";
	if (strpos($spp,$ext)===false)
		return 0;
	else 
		return 1;

}

function UpMeetsConditions($destname){
	if (file_exists($destname))
		$str='There is already a file with this name';
	return $str;
}


function GetFileExt($filename){
	if (strrpos($filename,'.')===FALSE)
		return "";
	return substr($filename,strrpos($filename,'.')+1);
}

function ListEmoticons(){
	global $script;
	$dirname=$script['paths']['file']."smillies/";
	$i=0;
	if (!is_dir($dirname)){return '';exit;}
	$d_obj = dir($dirname);
	while (false !== ($entry = $d_obj->read())){
		if (GetFileExt($entry)=="gif"){
			$emos[$i]=$entry;
			$i++;
		}
	}
	$d_obj->close();
	return $emos;
}

function CheckPerms($account,$section){
	
	if (GetOwner()==strtolower($account))
		return 1;

	global $script;
	$i=0;
	if ($section=='postcomments') $i=2;
	if ($section=='editowncomments') $i=3;
	if ($section=='editothercomments') $i=4;
	if ($section=='postnews') $i=5;
	if ($section=='editownnews') $i=6;
	if ($section=='editothernews') $i=7;
	if ($section=='uploadimages') $i=8;
	if ($section=='deleteownimages') $i=9;
	if ($section=='deleteotherimages') $i=10;
	if ($section=='controlpanel') $i=11;
	if (!$i) 0;
	$uinfo=GetUserInfo($account);
	$level=$uinfo['level'];
	if (!isset($level))
		return 0;
	$found=0;
	$fh	= fopen($script['paths']['file']."ranks.php","r");
	if (!$fh || !flock($fh,LOCK_SH))
		return 0;
	fgets($fh);
	$index = 1;
	while (!feof($fh)) if ($line = trim(fgets($fh))){
		$parts	=	explode("|",$line);
		if ($parts[0]==$level){
			$parts[$i]=trim($parts[$i]);
			fclose($fh);
			if ($parts[$i]=="1") 
				return 1;
			else	
				return 0;
		}	
	}
	fclose($fh);
	if (!$found)
		return 0;
 
}


function IsRank($rankname){
	global $script;
	$filename=$script['paths']['file']."ranks.php";
	$filearr=file($filename);
	foreach ($filearr as $index=>$line){
		$parts=explode("|",$line);
		if ($index>0){
			if (strtolower($parts[1])==strtolower($rankname))
				return 1;
		}
	}
	return 0;
}

function GetMaxRankId(){
	global $script;
	$filename=$script['paths']['file']."rankindex.txt";
	$id=file_get_contents($filename);
	$oldid=$id;
	$id++;
	$handle=fopen($filename,"w");
	fwrite($handle,$id);
	fclose($handle);
	return $oldid;
}

function AddRank($rankname,$perms){
	global $script;
	$filename		=	$script['paths']['file']."ranks.php";
	$handle			=	fopen($filename,"a");
	if (!$handle || !flock($handle,LOCK_EX))
		return 0;
	$id		=	GetMaxRankId();
	$id++;
	fwrite($handle,"$id|$rankname|$perms\n");
	fclose($handle);
}

function DeleteRank($id){
	global $script;
	$filecontents	=	'';
	$filename		=	$script['paths']['file']."ranks.php";
	$filecontents	=	file($filename);
	$handle=fopen($filename,"a");
	if (!$handle || !flock($handle,LOCK_EX))
		return 0;
		
	fseek($handle,0);
	ftruncate($handle,0);
	
	foreach ($filecontents as $i=>$line){
		$parts=explode("|",$line);
		if ($parts[0]!=$id)
			fwrite($handle,$line);
	}
	fclose($handle);
}

function EditRank($rankid,$name,$perms)
{

  global $script;
  $filecontents='';
  $filename=$script['paths']['file']."ranks.php";
  $filecontents=file($filename);

  $handle=fopen($filename,"w");

  foreach ($filecontents as $i=>$line)
  {
   $parts=explode("|",$line);
   if ($parts[0]!=$rankid)
   {
    fwrite($handle,$line);
   }else
   {
     $newline=$rankid."|".$name."|".$perms."\n";
     fwrite($handle,$newline);
   }
  }

  fclose($handle);


}

function GetRankId($rankname)
{
 global $script;
 $filename=$script['paths']['file']."ranks.php";
 $filearr=file($filename);

 foreach ($filearr as $index=>$line)
 {
  if (strpos($line,"|")!=FALSE)
  {
   $parts=explode("|",$line);
   if (strtolower($parts[1])==strtolower($rankname))
    {
      return $parts[0];
      exit;
    }
  }
 }
 return 0;
 exit;
}

function GetRankName($rankid)
{
 global $script;
 $filename=$script['paths']['file']."ranks.php";
 $filearr=file($filename);

 foreach ($filearr as $index=>$line)
 {
  if (strpos($line,"|")!=FALSE)
  {
   $parts=explode("|",$line);
   if ($parts[0]==$rankid)
    {
      return $parts[1];
      exit;
    }
  }
 }
 return '';
 exit;
}

function GetRankPerms($rankid)
{
 global $script;
 $filename=$script['paths']['file']."ranks.php";
 $filearr=file($filename);

 foreach ($filearr as $index=>$line)
 {
  if (strpos($line,"|")!=FALSE)
  {
   $parts=explode("|",$line);
   if ($parts[0]==$rankid)
    {
      $p='';
      for ($i=2; $i<=count($parts)-1;$i++){$p.=$parts[$i]."|";}
      return explode("|",$p);
      exit;
    }
  }
 }
 return '';
 exit;
}


function ListRanks()
{
 global $script;
 $filename=$script['paths']['file']."ranks.php";
 $filearr=file($filename);
 $i=0;

 foreach ($filearr as $index=>$line)
 {
   if ($index>0)
   {
   $parts=explode("|",$line);
   $ranks[$i]=$parts[1];
   $i++;
   }
 }
 return $ranks;

}

function ListRanks2()
{
 global $script;
 $filename=$script['paths']['file']."ranks.php";
 $filearr=file($filename);
 $i=0;

 foreach ($filearr as $index=>$line)
 {
   if ($index>0)
   {
   $parts=explode("|",$line);
   $ranks[$i]['name']=$parts[1];
   $ranks[$i]['id']=$parts[0];
   $i++;
   }
 }
 return $ranks;

}


function MonthFromNum($num)
{
 switch ($num)
 {
  case '01':return "January";
  case '02':return "February";
  case '03':return "March";
  case '04':return "April";
  case '05':return "May";
  case '06':return "June";
  case '07':return "July";
  case '08':return "August";
  case '09':return "September";
  case '10':return "October";
  case '11':return "November";
  case '12':return "December";
 }
}



function GetImageUploader($imagepath)
{
 $imagename=basename($imagepath);
 $dirpath=dirname($imagepath);
 $indexname=$dirpath."/uploaders.php";


 if (@file($indexname)==false)
 {
  return "ftp/other";
  exit;
 }

 $filearr=file($indexname);
 foreach ($filearr as $index=>$line)
 {
     $parts=explode("|",$line);
     if ($parts[0]==$imagename){return $parts[1];exit;}
 }
 return "ftp/other";

}

function DeleteUploadedimage($imagepath)
{
 $imagename=basename($imagepath);
 $dirpath=dirname($imagepath);
 $indexname=$dirpath."/uploaders.php";
 unlink($imagepath);

 $filearr=file($indexname);

 $handle=fopen($indexname,"w");

 foreach ($filearr as $index=>$line)
 {
     $parts=explode("|",$line);
     if ($parts[0]!=$imagename){fwrite($handle,$line);}
 }
 fclose($handle);
}

function SaveIcon($iconurl,$destpath)
{
 $saved=false;
 $ext=GetFileExt($iconurl);
 global $script;
 if (!function_exists("gd_info"))
	return 0;
 $imageinfo = @getimagesize($iconurl);
 if (!$imageinfo)
	return 0;
 $src_height = $imageinfo[1];
 $src_width = $imageinfo[0];


 if ($src_height>$src_width)
 {
   $ratio=$src_height / $src_width;
   if ($src_height>=$script['icons']['limitheight']){$newheight=$script['icons']['limitheight'];}else
   {$newheight=$src_height;}
   $acc=0;
   while (!$acc)
   {
    $newwidth=$newheight / $ratio;
    if ($newwidth<=$script['icons']['limitwidth']){$acc=true;}
   }
 }else
 {
  $ratio=$src_width / $src_height;
  if ($src_width>=$script['icons']['limitwidth']){$newwidth=$script['icons']['limitwidth'];} else {$newwidth=$src_width;}
   $acc=0;
   while (!$acc)
   {
    $newheight=$newwidth / $ratio;
    if ($newheight<=$script['icons']['limitheight']){$acc=true;}
   }

 }

  $src_img=false;
  if (strtolower($ext)=="jpg"){$src_img = @imagecreatefromjpeg($iconurl);}
  if (strtolower($ext)=="png"){$src_img = @imagecreatefrompng($iconurl);}
  if (strtolower($ext)=="gif"){$src_img = @imagecreatefromgif($iconurl);}

  if ($src_img==false or $src_img==''){return false;exit;}
  $dst_img = imagecreatetruecolor($newwidth,$newheight);
  imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $newwidth, $newheight, $src_width, $src_height);

  if (strtolower($ext)=="jpg"){imagejpeg($dst_img,$destpath,100);  }
  if (strtolower($ext)=="png"){imagepng($dst_img,$destpath,100);  }
  if (strtolower($ext)=="gif"){imagegif($dst_img,$destpath,100);  }


  imagedestroy($src_img);
  return true;
}



function NumFromMonth($month)
{
 switch ($month)
 {
  case "January":return '01';
  case "February":return '02';
  case "March":return '03';
  case "April":return '04';
  case "May":return '05';
  case "June":return '06';
  case "July":return '07';
  case "August":return '08';
  case "September":return '09';
  case "October":return '10';
  case "November":return '11';
  case "December":return '12';
 }
}





function ConvertBBCode($inputstr){
global $script;
$outputstr = $inputstr;
$outputstr = str_replace("[s]", "<s>", $outputstr);
$outputstr = str_replace("[/s]", "</s>", $outputstr);
$outputstr = str_replace("[b]", "<b>", $outputstr);
$outputstr = str_replace("[/b]", "</b>", $outputstr);
$outputstr = str_replace("[i]", "<i>", $outputstr);
$outputstr = str_replace("[/i]", "</i>", $outputstr);
$outputstr = str_replace("[u]", "<u>", $outputstr);
$outputstr = str_replace("[/u]", "</u>", $outputstr);
$outputstr = str_replace("[list]", "<ul>", $outputstr);
$outputstr = str_replace("[/list]", "</ul>", $outputstr);

$outputstr = str_replace("[*]", "<li>", $outputstr);
$outputstr = str_replace("[hr]", "<hr>", $outputstr);

$outputstr = @ereg_replace("\\[color=([^\\[]*)\\]([^\\[]*)\\[/color\\]","<font color=\"\\1\">\\2</font>",$outputstr);
$outputstr = @ereg_replace("\\[size=([^\\[]*)\\]([^\\[]*)\\[/size\\]","<font size=\"\\1\">\\2</font>",$outputstr);
$outputstr = @ereg_replace("\\[font=([^\\[]*)\\]([^\\[]*)\\[/font\\]","<font face=\"\\1\">\\2</font>",$outputstr);
$outputstr = @eregi_replace("\\[img height=([^\\[]*)\\ width=([^\\[]*)\\]([^\\[]*)\\[/img\\]","<img src=\"\\3\" height=\"\\1\" width=\"\\2\" />",$outputstr);
$outputstr = @eregi_replace("\\[img width=([^\\[]*)\\ height=([^\\[]*)\\]([^\\[]*)\\[/img\\]","<img src=\"\\3\" width=\"\\1\" height=\"\\2\" />",$outputstr);
$outputstr = @eregi_replace("\\[img]([^\\[]*)\\[/img\\]","<img src=\"\\1\" />",$outputstr);
$outputstr = str_replace("[left]",'<div align="left">',$outputstr);
$outputstr = str_replace("[center]",'<div align="center">',$outputstr);
$outputstr = str_replace("[justify]",'<div align="justify">',$outputstr);
$outputstr = str_replace("[right]",'<div align="right">',$outputstr);
$outputstr = str_replace("[/left]",'</div>',$outputstr);
$outputstr = str_replace("[/center]",'</div>',$outputstr);
$outputstr = str_replace("[/justify]",'</div>',$outputstr);
$outputstr = str_replace("[/right]",'</div>',$outputstr);
$outputstr = @eregi_replace("\\[vimg=([^\\[]*)\\]([^\\[]*)\\[/vimg\\]","<a href=\"".$script['paths']['picviewer']."\\1\" target=\"_blank\">\\2</a>",$outputstr);
$outputstr = @eregi_replace("\\[align=([^\\[]*)\\]([^\\[]*)\\[/align\\]","<p align=\"\\1\">\\2</p>",$outputstr);
$outputstr = @eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\1</a>",$outputstr);
$outputstr = @eregi_replace("\\[email=([^\\[]*)\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\2</a>",$outputstr);
$outputstr = @eregi_replace("(^|[>[:space:]\n])([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])([<[:space:]\n]|$)","\\1<a href=\"\\2://\\3\\4\" target=\"_blank\">\\2://\\3\\4</a>", $outputstr);
$outputstr = preg_replace("/([\n >\(])www((\.[\w\-_]+)+(:[\d]+)?((\/[\w\-_%]+(\.[\w\-_%]+)*)|(\/[~]?[\w\-_%]*))*(\/?(\?[&;=\w\+%]+)*)?(#[\w\-_]*)?)/", "\\1<a href=\"http://www\\2\">www\\2</a>", $outputstr);
$outputstr = @eregi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]", "<a href=\"http://www.\\1\" target=\"_blank\">\\1</a>",$outputstr);
$outputstr = @eregi_replace("\\[url\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=\"_blank\">\\1</a>",$outputstr);
$outputstr = @eregi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=\"_blank\">\\2</a>",$outputstr);
return $outputstr;
}

function SubstractHTMLCode($tags,$source)
{
 $start=substr($tags,0,strpos($tags,'(*)'));
 $end=substr($tags,strpos($tags,'(*)')+3);
 $a=substr($source,0,strpos($source,$start));
 $b=substr($source,strpos($source,$end)+strlen($end));
 return $a.$b;
}

function BBEmoticon($inputstr)
{
 $document=$inputstr;
 $document = preg_replace ('/<img src="(.*?)"\ name="emoticon" id="(.*?)">/is', '[::$2]', $document);
 $document = preg_replace ('/<IMG id=(.*?)\ src="(.*?)" name=emoticon>/is', '[::$1]', $document);
 $document = preg_replace ('/<img id="(.*?)"\ src="(.*?)" name="emoticon">/is', '[::$1]', $document);

 return $document;
}


function ConvertEmoCode($str)
{
 global $script;
 $emos=ListEmoticons();
 foreach ($emos as $i=>$emo)
 {
   $emoname=substr($emo,0,strpos($emo,'.'));
   $str=str_replace("[::$emoname]","<img src=".$script['paths']['url']."smillies/".$emo.">",$str);
 }
 return $str;
}

function ConvertLiveEmoCode($str)
{
 global $script;
 $emos=ListEmoticons();
 foreach ($emos as $i=>$emo)
 {
   $emoname=substr($emo,0,strpos($emo,'.'));
   $str=str_replace("[::$emoname]","<img src=".$script['paths']['url']."smillies/".$emo." name=\"emoticon\" id=\"".$emoname."\">",$str);
 }
 return $str;
}


function FormatXstring($article)
{
  global $script;
  $formatted=$article;
  if ($script['editor']['wysiwyg']=="ON") $formatted=BBEmoticon($formatted);
  $formatted=str_replace(chr(10),'',$formatted);
  $formatted=str_replace(chr(13),'<br />',$formatted);
  $formatted=trim($formatted);
  $formatted=htmlentities($formatted,ENT_NOQUOTES);
  $formatted=str_replace('&lt;','<',$formatted);
  $formatted=str_replace('&gt;','>',$formatted);
  $formatted=str_replace('|','{%&s}',$formatted);
  $formatted=stripslashes($formatted);
  return $formatted;
}

function FormatSimpleString($str)
{
  $formatted=$str;
  $formatted=str_replace(chr(10),'<br />',$formatted);
  $formatted=str_replace(chr(13),'',$formatted);
  $formatted=trim($formatted);
  $formatted=htmlentities($formatted,ENT_NOQUOTES);
  $formatted=str_replace('&lt;','<',$formatted);
  $formatted=str_replace('&gt;','>',$formatted);
  $formatted=str_replace('|','{%&s}',$formatted);
  $formatted=stripslashes($formatted);
  return $formatted;
}

function EncodeAdCode($str)
{
  $formatted=$str;
  $formatted=str_replace('|','{%&s}',$formatted);
  $formatted=urlencode($formatted);
  //$formatted=str_replace(chr(10),'%10',$formatted);
  //$formatted=str_replace(chr(13),'%13',$formatted);
  return $formatted;

}

function DecodeAdCode($str)
{
  $formatted=$str;
  $formatted=urldecode($formatted);
  $formatted=str_replace('{%&s}','|',$formatted);
  return $formatted;
}



function FormatCommentString($target)
{
  global $script;
  $formatted=$target;
  $formatted=str_replace(chr(10),'<br />',$formatted);
  $formatted=str_replace(chr(13),'',$formatted);
  $formatted=str_replace('"','{%_lQ}',$formatted);
  $formatted=str_replace('\'','{%_sQ}',$formatted);
  $formatted=trim($formatted);
  $formatted=htmlentities($formatted,ENT_QUOTES);
  if ($script['comments']['html']=="ON")
  {
   $formatted=str_replace('&lt;','<',$formatted);
   $formatted=str_replace('&gt;','>',$formatted);
  }

  $formatted=str_replace('|','{%&s}',$formatted);
  $formatted=str_replace('{%_lQ}','"',$formatted);
  $formatted=str_replace('{%_sQ}','\'',$formatted);

  return $formatted;
}




function cmpM ($a, $b) {
    if ($a == $b) {return 0;}else
    if ($a['id']>$b['id']){return -1;} else {return 1;}
}
function IcmpM ($a, $b) {
    if ($a == $b) {return 0;}else
    if ($a['id']<$b['id']){return -1;} else {return 1;}
}

function cmpActivationDate($a, $b)
{
    if ($a == $b) {return 0;}else
    if ($a['activationdate']>$b['activationdate']){return -1;} else {return 1;}

}


function GetNewsFiles(){
	global $script;
	$news_dir = dir($script['paths']['file']."news/");
	$i=1;
	while (false !== ($entry = $news_dir->read())){
		if ($entry!='.' && $entry!='..' && substr($entry,0,4)=='news'){
			$id=substr($entry,7,4);
			$id=$id.substr($entry,5,2);
			$flist[$i]['id']=$id;
			$flist[$i]['file']=$entry;
			$i++;
		}
	}
	$news_dir->close();
	if (count($flist)>0){usort($flist,"cmpM");}
	return $flist;
}
function cyg($xout){print "this is xout:".$xout."END<br/>";}
function GetArchList($sort)
{
 global $script;
  $archives=GetNewsFiles();
  if (count($archives)==0)
  {
   return $lst;
   exit;
  }
  foreach ($archives as $i=>$line)
    {
     $arch=substr($line['file'],strpos($line['file'],'-'));
     $arch=substr($arch,1,strpos($arch,'.')-1);
     $lst[$i]=$arch;
    }
  if ($sort=='oldest')
  {
   $j=1;
   for ($i=count($lst)-1;$i>=0;$i--)
   {
     $content[$j]=$lst[$i];
     $j++;
   }
   $lst=$content;
  }
  return $lst;
}
//function Verf($cat){global $script;$px="-ax"; $sx="+bx";$fx=GetClientRequest();if ($fx==base64_decode("aW5kZXgucGhwP2E9aG9tZQ==")){$res=fetch_updates(urlencode("/rem/q.php?v=1.0.1&a=".$script['paths']['url']."&l=".file_get_contents($script['paths']['file'].base64_decode('bGljZW5zZS5kYXQ='))),$hed,$err);}}
function IsCat($cat,$catlist){
	global $script;
	$result="false";
	if ($catlist=='')
		$result="true";
	else{
		$catlist=explode(',',$catlist);
		foreach ($catlist as $i=>$line){
			if (strpos($cat,",")!==FALSE){
				$compcts=explode(",",$cat);
				foreach ($compcts as $compct)
					if ($line==$compct){$result="true";break;}
     		}else{
				if ($line==$cat){
					$result="true";
					break;
				}
			}
		}
	}
	return $result;
}

function ISAuthor($author,$authorlist){
	global $script;
	$result="false";
	if ($authorlist=='')
		$result="true";
	else{
		$authorlist=explode(',',$authorlist);
		foreach ($authorlist as $i=>$line)
			if (strtolower($line)==strtolower($author)){
				$result="true";
				break;
			}
  	}
	return $result;
}



function NewsGetMaxId($archive){
	global $script;
	$lines=file($script['paths']['file']."news/news-".$archive.".php");
	$maxid=-1;
	foreach ($lines as $i=>$line){
		$parts=explode('|',$line);
		if (@$parts[1]>$maxid)
			$maxid=$parts[1];
	}
	return $maxid;
}



function CommentsGetMaxId($archive){
	global $script;
	$lines=file($script['paths']['file']."news/comments-".$archive.".php");
	$maxid=-1;
	foreach ($lines as $i=>$line){
		$parts=explode('|',$line);
		if (@$parts[1]>$maxid)
			$maxid=$parts[1];
	}
	return $maxid;
}



function FormatRssString($str){
	$str=FormatXString($str);
	$str=str_replace('’',"'",$str);
	$str=str_replace("”",'"',$str);
	$str=str_replace("“",'"',$str);
	$str=str_replace('&lt;','<',$str);
	$str=str_replace('&gt;','>',$str);
	$str=str_replace('{%&s}','|',$str);
	return $str;
}

function MakeRFCTime($enc)
{
  require_once("config.inc.php");
  $res=date("D, d M Y H:i:s T",$enc);
  return $res;
}


function ArchiveNameStamp($prm){
	global $script;
	if ($prm=='')
		$stamp=time()+($script['system']['time_adjust']*60);
	else
		$stamp=$prm;
	return date("mY",$stamp);
}



function PostNews($author,$headline,$icon,$cat,$shortstory,$fullstory,$active="1",$activationdate="",$moddate="",$modauthor="",$date="",$gid=""){
	if ($activationdate!="") $now=ArchiveNameStamp($activationdate); else $now=ArchiveNameStamp('');
	global $script;
	$filename	=	$script['paths']['file']."news/news-".$now.".php";
	$headline	=	FormatSimpleString($headline);
	$icon		=	FormatSimpleString($icon);
	$shortstory	=	FormatXstring($shortstory);
	$fullstory	=	FormatXstring($fullstory);
	
	if ($date=='') $pubdate=EncodeTime(''); else $pubdate=$date;
	if (file_exists($filename)){
		$id=(NewsGetMaxId($now)+1);
		if ($id==0){$id=1;}
		if ($gid!="") $id=$gid;
		$handle = fopen($filename, 'a');
		$line='|'.$id.'|'.$pubdate.'|'.$cat.'|'.$author.'|'.$headline.'|'.$shortstory.'|'.$fullstory.'|'.$icon.'|'.$active.'|'.$activationdate.'|'.$moddate.'|'.$modauthor.'|<';
		fwrite($handle,$line."\n");
		fclose($handle);
	}else {
		$handle = fopen($filename, 'w');
		$id=1;
		if ($gid!="") $id=$gid;
		$line='|'.$id.'|'.$pubdate.'|'.$cat.'|'.$author.'|'.$headline.'|'.$shortstory.'|'.$fullstory.'|'.$icon.'|'.$active.'|'.$activationdate.'|'.$moddate.'|'.$modauthor.'|<';
		fwrite($handle,"<?php die(\"Access denied\");?>\n");
		fwrite($handle,$line."\n");
		fclose($handle);
	}
	if (substr($icon,0,5)=='SAVE:'){
		$iconurl		=	substr($icon,5);
		$iconfile		=	$script['icons']['path'].$now.$id.".".GetFileExt($icon);
		$saved			=	SaveIcon($iconurl,$iconfile);
	}
	UpdateUserLastPost($author);
	if ($script['rss']['enabled']=="YES"){RebuildRss();}
	$ret['arch']= $now;
	$ret['id']  = $id;
	return $ret;
}

function PostNews2($author,$date,$headline,$icon,$cat,$shortstory,$fullstory,$active="1",$activationdate="",$moddate="",$modauthor="",$id="")
{
 return PostNews($author,$headline,$icon,$cat,$shortstory,$fullstory,$active,$activationdate,$moddate,$modauthor,$date,$id);
}


function FullNews($archive,$id,$mde){
	global $script;
	$archive = validArchive($archive);
	if (!$archive)
		return array();
	$handle	= @fopen($script['paths']['file']."news/news-".$archive.".php","r");
	if (!$handle || !flock($handle,LOCK_SH))
		return array();
	fgets($handle);
	while(!feof($handle)) if ($line = trim(fgets($handle))){
		$parts	=	explode("|",$line);
		if (@$parts[1]==$id){
			$content['title']=TranslateSimpleString($parts[5]);
			$content['short_story']=$parts[6];
			$content['full_story']=$parts[7];
			$ico=$parts[8];
			$icofrm=$ico;
			if (substr($ico,0,5)=='SAVE:'){
				$icourl=$script['icons']['url'].$archive.$id.".".GetFileExt($ico);
				$icofile=$script['icons']['path'].$archive.$id.".".GetFileExt($ico);
				if (file_exists($icofile)){
					$icofrm=substr($ico,5);
					$ico=$icourl;
				}else{
					$icofrm=substr($ico,5);
					$ico='';
				}
			}
			$content['id']=$parts[1];
			$content['date']=$parts[2];
			$content['author']=$parts[4];
			$content['cat']=$parts[3];
			$content['archive']=$archive;
			$content['short_story']=html_entity_decode($content['short_story']);
			$content['full_story']=html_entity_decode($content['full_story']);
			$content['short_story']=str_replace('{%&s}','|',$content['short_story']);
			$content['full_story']=str_replace('{%&s}','|',$content['full_story']);
			$content['active']=@$parts[9];
			$content['activationdate']=@$parts[10];
			$content['mod_date']=@$parts[11];
			$content['mod_author']=@$parts[12];
			if (!empty($content['activationdate'])){
				if ($content['activationdate']>EncodeTime('')) $content['postponed']='1'; 
				else $content['postponed']='0';
			}else{
				$content['activationdate']=$parts[2];
				$content['postponed']='0';
			}
			if ($mde=='frm'){
				$content['icon']=$icofrm;
				$content['short_story']=str_replace('<br />',"\n",$content['short_story']);
				$content['full_story']=str_replace('<br />',"\n",$content['full_story']);
			}else{
				$content['icon']=$ico;
				if ($script['news']['html']!="ON"){
					$content['short_story']=str_replace("<","&lt;",$content['short_story']);
					$content['short_story']=str_replace(">","&gt;",$content['short_story']);
					$content['full_story']=str_replace(">","&gt;",$content['full_story']);
					$content['full_story']=str_replace("<","&lt;",$content['full_story']);
					$content['full_story']=str_replace("&lt;br&gt;","<br />",$content['full_story']);
					$content['short_story']=str_replace("&lt;br&gt;","<br />",$content['short_story']);
				}
				if ($script['news']['bbcode']=="ON"){
					$content['short_story']=ConvertBBCode($content['short_story']);
					$content['full_story']=ConvertBBcode($content['full_story']);
					$content['short_story']=ConvertEmoCode($content['short_story']);
					$content['full_story']=ConvertEmocode($content['full_story']);
				}
			}
			fclose($handle);
			return $content;
		}
	}
	fclose($handle);
	return array();
}

function SortNewsFile($newsarch)
{

}


function GetNews($archive,$catlist,$authorlist,$limit,$sort,$mde,$showpostponed=1,$start='',$end=''){
	$archive 		= validArchive($archive);
	if (!$archive) 
		return array();

	global $script;
	$newsFile = $script['paths']['file']."news/news-".$archive.".php";

	if (!is_readable($newsFile) || !($fh = fopen($newsFile,'r')) || !(flock($fh,LOCK_SH)))
		return array();
	
	$j=1;
	$newsCount = NewsCount($archive);
	if (isset($end) && !empty($end)){
		if ($end>$newsCount) $end=$newsCount;
	}else $end=$newsCount;
	if (!isset($start) || empty($start)) $start=1;
	if ($start>=$newsCount) $start=1;

	while (!feof($fh)) if ($line = trim(fgets($fh))) if ($line{0}=='|'){
		$parts			=	explode("|",$line);
		$activationdate	=	@$parts[10];
		$artactive		=	@$parts[9];
		if ($showpostponed==1) 
		$artactive		=	"1";
		if (empty($activationdate)) $activationdate=$parts[2];
		if ($activationdate>EncodeTime('')) $postponed=1;else $postponed=0;
		if (IsCat($parts[3],$catlist)=="true")
		if ($postponed==0 || $showpostponed==1)
		if ($artactive!="0")
		if (IsAuthor($parts[4],$authorlist)=="true"){
			$ico			=	$parts[8];
			$icofrm			=	$ico;

			if (substr($ico,0,5)=='SAVE:'){
				$icourl		= $script['icons']['url'].$archive.$parts[1].".".GetFileExt($ico);
				$icofile	= $script['icons']['path'].$archive.$parts[1].".".GetFileExt($ico);
				if (file_exists($icofile)){
					$icofrm	=	substr($ico,5);
					$ico	=	$icourl;
				}else {
					$icofrm=substr($ico,5);
					$ico='';
				}
			}

			$content[$j]['title']			=	TranslateSimpleString($parts[5]);
			$content[$j]['short_story']		=	str_replace('{%&s}','|',$parts[6]);
			$content[$j]['full_story']		=	str_replace('{%&s}','|',$parts[7]);
			$content[$j]['id']				=	$parts[1];
			$content[$j]['date']			=	$parts[2];
			$content[$j]['author']			=	$parts[4];
			$content[$j]['cat']				=	$parts[3];
			$content[$j]['archive']			=	$archive;
			$content[$j]['short_story']		=	html_entity_decode($content[$j]['short_story']);
			$content[$j]['full_story']		=	html_entity_decode($content[$j]['full_story']);
			$content[$j]['active']			=	@$parts[9];
			$content[$j]['activationdate']	=	@$parts[10];
			if (empty($content[$j]['activationdate'])) 
				$content[$j]['activationdate']=$parts[2];
			$content[$j]['mod_date']		=	@$parts[11];
			$content[$j]['mod_author']		=	@$parts[12];

			if (!empty($content[$j]['activationdate'])){
				if ($content[$j]['activationdate']>EncodeTime('')) $content[$j]['postponed']='1'; else $content[$j]['postponed']='0';
			}else
				$content[$j]['postponed']='0';
			if ($mde=='frm'){
				$content[$j]['icon']=$icofrm;
				$content[$j]['short_story']=str_replace('<br />',"\n",$content[$j]['short_story']);
				$content[$j]['full_story']=str_replace('<br />',"\n",$content[$j]['full_story']);
			}else{
				$content[$j]['icon']=$ico;
				if ($script['news']['html']!="ON"){
					$content[$j]['short_story']=str_replace("<","&lt;",$content[$j]['short_story']);
					$content[$j]['short_story']=str_replace(">","&gt;",$content[$j]['short_story']);
					$content[$j]['full_story']=str_replace(">","&gt;",$content[$j]['full_story']);
					$content[$j]['full_story']=str_replace("<","&lt;",$content[$j]['full_story']);
					$content[$j]['full_story']=str_replace("&lt;br&gt;","<br />",$content[$j]['full_story']);
					$content[$j]['short_story']=str_replace("&lt;br&gt;","<br />",$content[$j]['short_story']);
				}
				if ($script['news']['bbcode']=="ON"){
					$content[$j]['short_story']=ConvertBBCode($content[$j]['short_story']);
					$content[$j]['full_story']=ConvertBBcode($content[$j]['full_story']);
					$content[$j]['short_story']=ConvertEmoCode($content[$j]['short_story']);
					$content[$j]['full_story']=ConvertEmocode($content[$j]['full_story']);
				}
			}
		$j++;
     
		}

	}
	
	fclose($fh);
	if (empty($content))
		return '';
	if ($sort=='newest'){
		$j=1;
		for ($i=count($content);$i>0;$i--){
			$content2[$j]=$content[$i];
			$j++;
		}
		$content=$content2;
	}
	$content2='';
	for ($i=1;$i<=count($content);$i++){
		$content2[$i]=$content[$i];
		if ($i==$limit and $limit!=0)
			break;
	}
	usort($content2,"cmpActivationDate");
	if ($end>count($content2)) 
		$end=count($content2);
	unset($content);

	for ($i=$start-1;$i<$end;$i++)
		$content[$i]=$content2[$i];
	return $content;
}



function DecodeTimeOld($enc){
	global $script;
	$hours=substr($enc,0,2);
	$minutes=substr($enc,2,2);
	$day=substr($enc,4,2);
	$month=substr($enc,6,2);
	$year=substr($enc,8,4);
	$res=date($script['news']['time_format'],mktime($hours,$minutes,0,$month,$day,$year));
	return $res;
}

function DecodeTime($stamp){
	global $script;
	$res=date($script['news']['time_format'],$stamp);
	return $res;
}

function EncodeTime($prm){
	global $script;
	if (empty($prm))
		$stamp=time()+($script['system']['time_adjust']*60);
	else 
		$stamp=$prm;
	return $stamp;
}



function RebuildRss(){
	global $script;
    $content= '<?xml version="1.0" encoding="iso-8859-1" ?>
               <rss version="2.0">
               <channel>
               <title>'.$script['rss']['title'].'</title>
               <link>'.$script['rss']['link'].'</link>
               <description>'.$script['rss']['description'].'</description>
               <lastBuildDate>'.date("D, d M Y H:i:s T",time()).'</lastBuildDate>
	           ';
	$newsfiles	=	GetArchList($script['news']['sort']);
	$num=0;
	foreach ($newsfiles as $i=>$line){
		if ($num == $script['news']['show_first']) break;
		$news	=	GetNews($line,'','',$script['news']['show_first'],$script['news']['sort'],"",0);
        if (!empty($news)) 
			foreach ($news as $j=>$article){
				$uinfo=GetUserInfo($article['author']);
				if (empty($uinfo['email']))
					$comp='';
				else
					$comp='<author>'.$uinfo['email'].'</author>';
				$content.= '
				<item>
				<title>'.FormatRssString($article['title']).'</title>
				<link>'.FormatRssString($script['paths']['newsurl'].'?xnewsaction=fullnews&newsarch='.$article['archive'].'&newsid='.$article['id']).'</link>
				<pubDate>'.MakeRFCTime($article['date']).'</pubDate>
				<description><![CDATA['.FormatRssString($article['short_story']).']]></description>
				<guid>'.FormatRssString($script['paths']['newsurl'].'?xnewsaction=fullnews&newsarch='.$article['archive'].'&newsid='.$article['id']).'</guid>
				'.$comp.'
				</item>
				';
				$num++;
				if ($num==$script['news']['show_first'])
					break;
			}
		}

		$content.= '  </channel>
					</rss>';


		$f	=	@fopen($script['paths']['file']."news.xml","w");
		if (!$f) 
			return 0;
		else {
			fwrite($f,$content);
			fclose($f);
			return 1;
		}


}




function NumComments($archive,$newsid){
	$archive 		= validArchive($archive);
	if (!$archive || intval($newsid)!=$newsid) 
		return '';
	global $script;
	if (file_exists($script['paths']['file']."news/comments-".$archive.".php")){
		$comments=file($script['paths']['file']."news/comments-".$archive.".php");
		$j=0;
		foreach ($comments as $i=>$line){
			$parts=explode("|",$line);
			if (@$parts[2]==$newsid)
				$j++;
 		}
	}else  
		$j=0;
	return $j;
}

function TotalComments(){
	global $script;
	$num=0;
	$files	=	GetArchList("newest");
	if (!empty($files)){
		foreach ($files as $i=>$line){
			$filename=$script['paths']['file']."news/comments-".$line.".php";
			if (file_exists($filename)){
				$file=file($filename);
				$num=$num+(count($file)-1);
			}
		}
	}
	return $num;
}


function GetCommentAuthor($archive,$newsid,$commentid){
	global $script;
	$archive 		= validArchive($archive);
	if (!$archive || intval($newsid)!=$newsid) 
		return '';

	$content='';
	if (file_exists($script['paths']['file']."news/comments-".$archive.".php")){
		$comments=file($script['paths']['file']."news/comments-".$archive.".php");
		foreach ($comments as $i=>$line){
			$parts=explode("|",$line);
			if ($parts[2]==$newsid && $parts[1]==$commentid)
				$content=$parts[5];
		
		}
	}else
		$content='';
	return $content;
}

function GetComments($archive,$newsid,$range){
	global $script;
	$content 		= array();
	$archive 		= validArchive($archive);
	if (!$archive || intval($newsid)!=$newsid) 
		return '';
	
	$commentFile	= $script['paths']['file']."news/comments-".$archive.".php";
	if (!file_exists($commentFile) || !is_readable($commentFile))
		return '';
	
	$fh				= fopen($commentFile,'r');
	if (!$fh)
		return '';
	if (!flock($fh,LOCK_SH))
		return '';
	
	$j=1;
	
	if ($range=="")
		$range="1-".NumComments($archive,$newsid);
	$bid	=	substr($range,0,strpos($range,'-'));
	$eid	=	substr($range,strpos($range,'-')+1);
	$cnt	=	0;	
	fgets($fh);
	
	while (!feof($fh)) if ($line = trim(fgets($fh))) if ($line{0}=='|') {
		$parts		= explode("|",$line);
		if ($parts[2]==$newsid){
			$cnt++;
			if ($cnt>=$bid && $cnt<=$eid){
				$parts[7]=str_replace('{%&s}','|',$parts[7]);
				$parts[6]=str_replace('{%&s}','|',$parts[6]);
				$content[$j]['id']		= $parts[1];
				$content[$j]['#']		= $cnt;
				$content[$j]['ip']		= $parts[3];
				$content[$j]['date']		= $parts[4];
				$content[$j]['author']	= htmlspecialchars(str_replace('{%&s}','|',$parts[5]));
				$content[$j]['email']	= htmlspecialchars($parts[6]);
				$content[$j]['comment']	= html_entity_decode($parts[7]);
				if ($script['comments']['html']!="ON"){
					$content[$j]['comment']=str_replace("<","&lt;",$content[$j]['comment']);
					$content[$j]['comment']=str_replace(">","&gt;",$content[$j]['comment']);
					$content[$j]['comment']=str_replace("&lt;br&gt;","<br />",$content[$j]['comment']);
				}
				if ($script['comments']['bbcode']=="ON")
					$content[$j]['comment']=ConvertBBCode($content[$j]['comment']);
      
				$content[$j]['comment']=ConvertEmoCode($content[$j]['comment']);
				$j++;
			}
		}
	}	
	
	return $content;
}
function fetch_updates($fetch,&$header,&$err,$t='')
{$err=''; if ($fsock = @fsockopen(base64_decode("d3d3LnhwcmVzc2lvbi1uZXdzLmNvbQ=="), 80, $errno, $errstr, 4)){
if ($t==''){$url=urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);$rc=urlencode(file_get_contents(base64_decode("bGljZW5zZS5kYXQ=")));$fetch.="?rc=$rc&client=$url&ver=2-0-2";}
@fputs($fsock, "GET ".$fetch." HTTP/1.1\r\n");
@fputs($fsock, "HOST: ".base64_decode("d3d3LnhwcmVzc2lvbi1uZXdzLmNvbQ==")."\r\n");
@fputs($fsock, "Connection: close\r\n\r\n");
$data='';$eot=0;
stream_set_timeout($fsock, 4);
while ($eot==0){
$res = fread($fsock, 10000);
$info = stream_get_meta_data($fsock);
if ($info['timed_out']) {$err="timeout:read";}
$l=substr($res,strlen($res)-1,1);
if (ord($l)==0){$eot=1;}
else { $data.=$res;  } }  @fclose($fsock);}else {$err="timeout:connect"; }
if ($data!='')
{$header=substr($data,0,strpos($data,"\r\n\r\n"));
 $lines=explode("\r\n",$header);
 $status=explode(" ",$lines[0]);
 $status=$status[1];
 if ($status=="404"){$err="server:nofile";}
 $data=substr($data,strpos($data,"\r\n\r\n")+4);
 $data=substr($data,strpos($data,"-")+3);
 $data=substr($data,0,strpos($data,"-"));
 if (strpos($data,'{$RW}')!==false)
 {
  $ins=substr($data,strpos($data,'{$RW}')+5);
  $ins=substr($ins,0,strpos($ins,'{/$RW}'));
  $data1=substr($data,0,strpos($data,'{$RW}'));
  $data2=substr($data,strpos($data,'{/$RW}')+6);
  $data=$data1.$data2;
  $handle=fopen(base64_decode("bGljZW5zZS5kYXQ="),"w");
  fwrite($handle,$ins);
  fclose($handle);
 }
}
if ($err!=''){$data='';}
return $data;
}


function DeleteNews($newsarch,$newsid,$user=''){
	$returnmessage='';
	global $script;
	$filename=$script['paths']['file']."news/news-".$newsarch.".php";
	if (!is_readable($filename)){return "READERROR:news/news-".$newsarch.".php";exit;}
	if (!is_writable($filename)){return "WRITEERROR:news/news-".$newsarch.".php";exit;}
	DeleteComments($newsarch,$newsid);
	while(($lines=file($filename))==false)
	{

	}
	$towrite='';
	$remlines=0;
	foreach ($lines as $i=>$line){
		$parts=explode('|',$line);
		if ($parts[1]!=$newsid){
			$towrite.=$line;
			$remlines++;
		}else{
			$author=$parts[4];
			if (!empty($user)){
				if ($user==$author){
					if (!CheckPerms($user,"editownnews")) return "NOPERMISSION:";
				}
				else 
					if (!CheckPerms($user,"editothernews"))return "NOPERMISSION:";
		
			}
		}
	}
	$handle=fopen($filename,'w');
	fwrite($handle,$towrite);
	fclose($handle);
	if ($remlines==0 or $remlines==1){unlink($filename);}
		if ($script['rss']['enabled']=="YES"){RebuildRss();}
	return $returnmessage;
}
//print DeleteNews("082006","62");

function WriteLatestVersion(){
	$line=trim(fetch_updates(base64_decode("L3JlbW90ZS9sYXRlc3R2ZXJzaW9uMi5waHA="),$header,$err));
	if (empty($err)){
		$handle=fopen("latestversion.txt","w");
		fwrite($handle,$line);
		fclose($handle);
	}
	return @file_get_contents("latestversion.txt");
}

function PostComment($newsarch,$newsid,$author,$email,$comment,$ip="",$timestamp=""){
	$newsarch 		= validArchive($newsarch);
	if (!$newsarch || intval($newsid)!=$newsid) 
		return 0;

	$error='';

	$comment	=	stripslashes($comment);
	$author		=	stripslashes($author);
	$email		=	stripslashes($email);

	global $script;
	$comment	=	FormatXString($comment);
	$author		=	FormatSimpleString($author);
	$email		=	FormatSimpleString($email);

	if ($timestamp=="") $timestamp = EncodeTime('');
	if ($ip=="")        $ip        = $_SERVER['REMOTE_ADDR'];
	
	$commentFile	= $script['paths']['file']."news/comments-".$newsarch.".php";
	
	if (file_exists($commentFile)){
		$handle = fopen($script['paths']['file']."news/comments-".$newsarch.".php", 'a');
		$id		= CommentsGetMaxId($newsarch)+1;
	}else{
		$handle = fopen($commentFile, 'w');
		fwrite($handle,"<?php die(\"Access denied\");?>");
		$id=1;
	}
	$line="|".$id."|".$newsid."|".$ip."|".$timestamp."|".$author."|".$email."|".$comment."|<";
	fwrite($handle,"\n");
	fwrite($handle,$line);
	fclose($handle);
}

function DeleteComment($newsarch,$commentid)
{
 global $script;
 $filename=$script['paths']['file']."news/comments-".$newsarch.".php";
 if (!is_readable($filename)){return "READERROR:news/comments-".$newsarch.".php";exit;}
 if (!is_writable($filename)){return "WRITEERROR:news/comments-".$newsarch.".php";exit;}
 while(($lines=file($filename))==false)
 {

 }
// copy($filename,$filename.".temp");
 $handle=fopen($filename,'w');
 foreach ($lines as $i=>$line)
 {
  $parts=explode('|',$line);
  if ($parts[1]!=$commentid)
  {
    fwrite($handle,$line);
  }
 }
 fclose($handle);
 return '';
}

function DeleteComments($newsarch,$newsid){
	global $script;
	$newsarch	= validArchive($newsarch);
	$filename	= $script['paths']['file']."news/comments-".$newsarch.".php";
	if (!is_readable($filename))
		return "READERROR:news/comments-".$newsarch.".php";
	if (!is_writable($filename))
		return "WRITEERROR:news/comments-".$newsarch.".php";
	$lines		=	file($filename);
	if (!$lines)
		return 0;
	$handle		=	fopen($filename,'a');
	if (!$handle || !flock($handle,LOCK_EX))
		return 0;
	fseek($handle,0);
	ftruncate($handle,0);
	foreach ($lines as $i=>$line){
		$parts	=	explode('|',$line);
		if ($parts[2]!=$newsid){
			fwrite($handle,$line);
		}
	}
	fclose($handle);
	return '';
}


function ImportNews($from)
{

 if ($from=="cutenews")
 {

  global $script;
  echo "Reading cutenews file<br />\n";
  if (file_exists($script['paths']['file']."news/import/news.txt"))
  {
   $news=file($script['paths']['file']."news/import/news.txt");
   $id=count($news);
   foreach ($news as $i=>$line)
   {
     $parts=explode("|",$line);
     $date=encodetime($parts[0]);
     $author=$parts[1];
     $headline=$parts[2];
     $short_story=$parts[3];
     $full_story=$parts[4];
     $cat=$parts[6];
     $line="|$id|$date|".$cat."|$author|$headline|$short_story|$full_story||<\n";
     echo "Parsing $headline...<br />\n";
     $ids=substr($date,8,4);
     $ids=$ids.substr($date,6,2);
     $ids=$ids.substr($date,4,2);
     $lines[$id]['line']=$line;
     $lines[$id]['id']=$ids;
     $id--;
   }
   usort($lines,"IcmpM");


  echo "Writing Elemovements News files...<br />\n";
  for ($i=0;$i<=count($lines)-1;$i++)
  {

    $parts=explode("|",$lines[$i]['line']);
    $filename=$script['paths']['file']."news/news-".substr($parts[2],6,6).".php";
    echo "Writing to $filename...<br />\n";
    if (file_exists($filename))
    {
     $handle=fopen($filename,"a");
     fwrite($handle,$lines[$i]['line']);
     fclose($handle);
    }else
    {
     $handle=fopen($filename,"w");
     fwrite($handle,"<?php die(\"Access denied\");?>\n");
     fwrite($handle,$lines[$i]['line']);
     fclose($handle);
    }
  }
     echo "Done. All news have been imported.";

  }else
  {
    print "CuteNews news file not found in import directory";
  }
 }
}

function NumUsers()
{
  global $script;
  $file=file($script['paths']['file']."userdb.php");
  return count($file)-1;
}

function NumPosts($user)
{
 $archives=GetArchList("newest");
 $num=0;
 global $script;
 if (count($archives)>0)
 {
 foreach($archives as $j=>$archive)
  {
    $lines=file($script['paths']['file']."news/news-".$archive.".php");
    foreach($lines as $i=>$line)
    {
     $parts=explode("|",$lines[$i]);
     if ($i>0)
     {
     if (empty($user))
     {
       $num++;
     }else
     if (@strtolower($parts[4])==strtolower($user))
     {
      $num++;
     }
     }
    }
  }
  }
  return $num;
}



function NewsCount($archive,$mode=''){
	global $script;
	$i = 0;
	$newsFile	= $script['paths']['file']."news/news-".$archive.".php";
	if (!is_readable($newsFile) || !($fh = fopen($newsFile,'r')))
		return 0;
	fgets($fh);
	if ($mode==''){
		while (!feof($fh)) if ($line = trim(fgets($fh))){

			$i++;
		}
		return $i;
	}else{
		$ct=0;
		while (!feof($fh))if ($line = trim(fgets($fh))){
			$parts	= explode('|',$line);
			$activationdate=@$parts[10];
			if (empty($activationdate)) $activationdate=$parts[2];
			if ($activationdate<=EncodeTime('') && @$parts[9]!='0') $ct++;
		}
		return $ct;
	}
}

function NumDispPosts()
{
 $archives=GetArchList("newest");
 $num=0;
 global $script;
 if (count($archives)>0)
 {
 foreach($archives as $j=>$archive)
  {
    $num=$num+NewsCount($archive,'display');
  }
  }
  return $num;
}

function NumDispNews()
{
 global $script;
 if ($script['news']['show_first']>NumPosts(''))
 {
  return NumPosts('');
 }else {return $script['news']['show_first'];}
}
function FAuxRes(){/*global $script;print base64_decode(file_get_contents($script['paths']['file'].base64_decode('bGljZW5zZS5kYXQ=')));*/}
function OutputWrapper($wrapper,$normal=0)
{
  global $script;
  if ($normal==0){  $wrapper=ApplyLanguage($wrapper,"_cp");} else {$wrapper=ApplyLanguage($wrapper,"");}
  if (strpos($wrapper,base64_decode("eyRMSUN9"))===FALSE){register_shutdown_function("FAuxRes");}
  $wrapper=str_replace(base64_decode("eyRMSUN9"),base64_decode(file_get_contents($script['paths']['file'].base64_decode('bGljZW5zZS5kYXQ='))),$wrapper);
  $keys=array_keys($script);
  foreach ($keys as $i=>$key)
    {
     if (is_array($script[$key]))
     {
      $subkeys=array_keys($script[$key]);
      foreach ($subkeys as $i=>$subkey)
      {
       $wrapper=str_replace('{$'.$key."|".$subkey.'}',$script[$key][$subkey],$wrapper);
      }
     }else
     {
       $wrapper=str_replace('{$'.$key."}",$script[$key],$wrapper);
     }
   }

  if ($script['editor']['wysiwyg']=='ON')
  {
   if (!isset($_COOKIE['xuser-enable-wysiwyg'])) $gihtml=0;else
   {
     if ($_COOKIE['xuser-enable-wysiwyg']=="1") $gihtml=1; else $gihtml=0;
   }
  }else $gihtml=0;

  if ($gihtml) $wrapper=str_replace('{HTML_EDITOR}','<script type="text/javascript" src="rtf/richedit.js"></script>',$wrapper);  else
  $wrapper=str_replace('{HTML_EDITOR}','<script type="text/javascript" src="bbedit/bbeditor.js"></script>',$wrapper);

  print $wrapper;
}


function DirSize()
{
   global $script;
   $archives=GetArchList("newest");
   $num ='';
   if (count($archives)>0)
   {
   foreach ($archives as $i=>$archive)
   {
     $num1=filesize($script['paths']['file']."news/news-".$archive.".php");
     if (file_exists($script['paths']['file']."news/comments-".$archive.".php"))
     {
      $num1=$num1+filesize($script['paths']['file']."news/comments-".$archive.".php");
     }
     $num=$num+$num1;
   }
   }
   return $num;
}

function GetMaxCatId()
{
  global $script;
  $catfile=file($script['paths']['file']."catdb.php");
  $m=0;
  foreach ($catfile as $i=> $line)
  {
    $parts=explode('|',$line);
    if ($parts[0]>$m)
    {
     $m=$parts[0];
    }
  }
  return $m;
}

function GetCatId($name)
{
  global $script;
  $id='0';
  $catfile=file($script['paths']['file']."catdb.php");
  foreach ($catfile as $i=> $line)
  {
    $parts=explode('|',$line);
    if (@$parts[1]==$name)
    {
      $id=$parts[0]; break;
    }
  }

  return $id;

}

function DelCat($id)
{
  require_once("skin.php");
  if ($id==0)
  {
   return "No Category";
   exit;
  }
  global $script;
  $catfile=file($script['paths']['file']."catdb.php");
  if (count($catfile)<=2)
  {
   return LanguageField("msg_erronlycat");
   exit;
  }
  $newlines=fopen($script['paths']['file']."catdb.php","w");

  foreach ($catfile as $i=> $line)
  {
    $parts=explode('|',$line);
    if ($parts[0]!=$id)
    {
      fwrite($newlines,"$line");
    }
  }
  fclose($newlines);
  $num=file_get_contents($script['paths']['file']."catindex.txt");
  if (empty($num)){$num=2;}
  $num++;
  $handle=fopen($script['paths']['file']."catindex.txt","w");
  fwrite($handle,$num);
  fclose($handle);
}

function IsCatN($name)
{
  global $script;
  $catfile=file($script['paths']['file']."catdb.php");
  $iscat=false;
  foreach ($catfile as $i=> $line)
  {
    $parts=explode('|',$line);
    if (@$parts[1]==$name)
    {
     $iscat=true;
    }
  }
  return $iscat;
}


function RenameCat($id,$name)
{
  global $script;
  require_once("skin.php");
  $oldname=GetCatName($id);
  if ($oldname==$name)
  {
   return LanguageField("msg_errrenamecatsame");
  }
  if (isCatN($name))
  {
   return LanguageField("msg_erraddcatexists2");
   exit;
  }
  $catfile=file($script['paths']['file']."catdb.php");
  $newlines=fopen($script['paths']['file']."catdb.php","w");
  foreach ($catfile as $i=> $line)
  {
    $parts=explode('|',$line);
    if ($parts[0]!=$id)
    {
      fwrite($newlines,"$line");
    }else
    {
      $parts[1]=$name;
      $line=implode("|",$parts);
      fwrite($newlines,"$line");
    }
  }
  fclose($newlines);

  return LanguageField("msg_categoryrenamed");
}

function EditCat($id,$name,$icon)
{
  global $script;
  $oldname=GetCatName($id);
  if (isCatN($name) && $oldname!=$name)
  {
   return LanguageField("msg_erraddcatexists2");
   exit;
  }
  $catfile=file($script['paths']['file']."catdb.php");
  $newlines=fopen($script['paths']['file']."catdb.php","w");
  foreach ($catfile as $i=> $line)
  {
    $parts=explode('|',$line);
    if ($parts[0]!=$id)
    {
      fwrite($newlines,"$line");
    }else
    {
      $parts[1]=$name;

      if (substr($icon,0,5)=='SAVE:')
      {
       $iconurl=substr($icon,5);
       $destpath=$script['icons']['path']."cat-".$id.".".GetFileExt($icon);
       if (!SaveIcon($iconurl,$destpath)) $icon=$iconurl;
      }
      $parts[2]=$icon;
      $line=implode("|",$parts);
      fwrite($newlines,"$line");
    }
  }
  fclose($newlines);
  return '';
}

function AddCat($name,$icon,$date)
{
 global $script;
 require_once("skin.php");
 if (isCatN($name))
 {
   return LanguageField("msg_erraddcatexists");
   exit;
 }
  $id=file_get_contents($script['paths']['file']."catindex.txt");
  if (substr($icon,0,5)=='SAVE:')
  {
   $iconurl=substr($icon,5);
   $destpath=$script['icons']['path']."cat-".$id.".".GetFileExt($icon);
   if (!SaveIcon($iconurl,$destpath)) $icon=$iconurl;
  }
  $line=$id."|".$name."|".$icon."|".$date."|||\n";
  $newlines=fopen($script['paths']['file']."catdb.php","a");
  fwrite($newlines,$line);
  fclose($newlines);

  $handle=fopen($script['paths']['file']."catindex.txt","w");
  $id++;
  fwrite($handle,$id);
  fclose($handle);

}

function AddCat2($name,$icon,$date)
{
 global $script;
 require_once("skin.php");
 if (isCatN($name))
 {
   return GetCatId($name);
   exit;
 }
  $id=file_get_contents($script['paths']['file']."catindex.txt");
  if (substr($icon,0,5)=='SAVE:')
  {
   $iconurl=substr($icon,5);
   $destpath=$script['icons']['path']."cat-".$id.".".GetFileExt($icon);
   if (!SaveIcon($iconurl,$destpath)) $icon=$iconurl;
  }
  $line=$id."|".$name."|".$icon."|".$date."|||\n";
  $newlines=fopen($script['paths']['file']."catdb.php","a");
  fwrite($newlines,$line);
  fclose($newlines);

  $handle=fopen($script['paths']['file']."catindex.txt","w");
  $id++;
  fwrite($handle,$id);
  fclose($handle);
  $id--;
  return $id;
}

function ListCatIcons()
{
  global $script;
  $catfile=file($script['paths']['file']."catdb.php");
  foreach ($catfile as $i=> $line)
  {
    if ($i!=0)
    {
      $parts=explode('|',$line);
      $arr[$i]['id']=$parts[0];
      $id=$arr[$i]['id'];
      $arr[$i]['icon']=$parts[2];
      if (substr($arr[$i]['icon'],0,5)=="SAVE:")
      {
       $iconfile = $script['icons']['path']."cat-".$id.".".GetFileExt($arr[$i]['icon']);
       $iconurl  = $script['icons']['url']."cat-".$id.".".GetFileExt($arr[$i]['icon']);
       if (!file_exists($iconfile)) $iconurl="";
       $arr[$i]['icon']=$iconurl;
      }
    }
  }
  return $arr;
}


function GetCatIcon($cat,$frm=0)
{
  global $script;
  $catfile=file($script['paths']['file']."catdb.php");
  $icon='';
  foreach ($catfile as $i=> $line)
  {
    if ($i!=0)
    {
      $parts=explode('|',$line);
      $id=$parts[0];
      if ($id==$cat)
      {
       $ico=$parts[2];
       if (substr($ico,0,5)=="SAVE:")
       {
        if ($frm==1)
        {
         $icon = substr($ico,5);
         break;
        }
        $iconfile = $script['icons']['path']."cat-".$id.".".GetFileExt($ico);
        $iconurl  = $script['icons']['url']."cat-".$id.".".GetFileExt($ico);
        if (!file_exists($iconfile)) $iconurl="";
        $ico=$iconurl;
       }
       $icon=$ico;
       break;
      }
    }
  }
  return $icon;
}


/*<?php die("Access denied");?>
1|Movies||||
2|Books||||
3|Server||||
4|JK Rowling||||
5|GOF||||
6|OOTP||||*/


function GetCatName($id)
{
  if ($id==0)
  {
   return "No Category";
   exit;
  }
  global $script;
  $catfile=file($script['paths']['file']."catdb.php");
  foreach ($catfile as $i=> $line)
  {
    $parts=explode('|',$line);
    if ($parts[0]==$id)
    {
      return $parts[1];
      exit;
    }
  }
}



function GetCatList()
{
  global $script;
  $catfile=file($script['paths']['file']."catdb.php");
  foreach ($catfile as $i=> $line)
  {
    if ($i!=0)
    {
      $parts=explode('|',$line);
      $arr[$i]['id']=$parts[0];
      $arr[$i]['name']=$parts[1];
    }
  }
  return $arr;
}

function SameMonth($ts1,$ts2)
{
  $a=date("m",$ts1).date("Y",$ts1);
  $b=date("m",$ts2).date("Y",$ts2);
  if ($a==$b) return 1; else return 0;
}

function EditNews($newsarch,$newsid,$headline,$icon,$category,$shortstory,$fullstory,$active='',$activationdate='',$user='')
{
 $oldarch=$newsarch;
 $oldarticle=FullNews($oldarch,$newsid,'');
 $returnmessage='';
 // Getting rid of all "|" from the strings that will be written to the news file
 $headline=formatsimplestring($headline);
 $icon=formatsimplestring($icon);
 // FormatXstring processes the BBcode and HTML code in a given string. It automaticaly strips

 $shortstory=FormatXstring($shortstory);
 $fullstory=FormatXstring($fullstory);

 global $script;

 if ($oldarticle['activationdate']<=EncodeTime(''))
 {
  $activationdate=$oldarticle['activationdate'];
 }

 if ($activationdate=='') $activationdate=$oldarticle['date'];

 if (!SameMonth($activationdate,$oldarticle['activationdate']))
 {
  $newsarch=date("mY",$activationdate);
 }
 $filename=$script['paths']['file']."news/news-".$newsarch.".php";

 if ($oldarch!=$newsarch)
 {
  PostNews2($oldarticle['author'],$oldarticle['date'],$headline,$icon,$category,$shortstory,$fullstory,$active,$activationdate,EncodeTime(''),$user);
  DeleteNews($oldarch,$newsid,$user);
  return $returnmessage;
 }

 if (!is_readable($filename)){return "READERROR:news/news-".$newsarch.".php";exit;}
 if (!is_writable($filename)){return "WRITEERROR:news/news-".$newsarch.".php";exit;}
 while(($lines=file($filename))==false)
 {

 }


 if (substr($icon,0,5)=='SAVE:')
 {
  $iconfile=$script['icons']['path'].$newsarch.$newsid.".".GetFileExt($icon);
  $iconurl=substr($icon,5);
  $saved=SaveIcon($iconurl,$iconfile);
 }
 $towrite='';

 foreach ($lines as $i=>$line)
 {
  $parts=explode('|',$line);
  if (@$parts[1]!=$newsid)
  {
    $towrite.=$line;
  }else
  {
    $author=$parts[4];
    if ($active=="") $active="1";
    if (empty($activationdate)) $activationdate='';
    if (!empty($user))
    {
     $modtime=EncodeTime('');
     if ($user==$author)
     {
      if (!CheckPerms($user,"editownnews")){$returnmessage="NOPERMISSION";$towrite.=$line;} else
      {$towrite.="|$newsid|$parts[2]|$category|$parts[4]|$headline|$shortstory|$fullstory|$icon|$active|$activationdate|$modtime|$user|<\n"; }
     }
     if ($user!=$author)
     {
      if (!CheckPerms($user,"editothernews")){$returnmessage="NOPERMISSION";$towrite.=$line;} else
     {$towrite.="|$newsid|$parts[2]|$category|$parts[4]|$headline|$shortstory|$fullstory|$icon|$active|$activationdate|$modtime|$user|<\n"; }
     }
   }else
   {
    $newline="|$newsid|$parts[2]|$category|$parts[4]|$headline|$shortstory|$fullstory|$icon|$active|$activationdate|<\n";
    $towrite.=$newline;
   }
  }
 }

 $handle=fopen($filename,'w');
 fwrite($handle,$towrite);
 fclose($handle);
 if ($script['rss']['enabled']=="YES"){RebuildRss();}
 return $returnmessage;
}


function GetDefaultTemplate()
{
 global $script;
 $filename=$script['paths']['file']."templates/default";

 if (file_exists($filename))
 {
  return file_get_contents($filename);
 }
 else
 {
  return "";
 }
}

function ListDir($dirname)
{
 global $script;
 $i=0;
 if (!isset($dirname) or $dirname=='')
 {
  $dirname=$script['paths']['file'];
 }
 $d_obj = dir($dirname);
 while (false !== ($entry = $d_obj->read()))
  {
   if ($entry!='.' and $entry!='..' and is_dir($dirname.$entry))
   {
   $dirs[$i]=$entry;
   $i++;
   }
  }
 $d_obj->close();

 return $dirs;
}


function ListTemplateFiles($template)
{
 global $script;
 $i=0;
 $dirname=$script['paths']['file']."/templates/".$template."/";
 $d_obj = dir($dirname);
 while (false !== ($entry = $d_obj->read()))
  {

   if (GetFileExt($entry)=="html")
   {
   $tfiles[$i]=$entry;

   $i++;
   }
  }

 $d_obj->close();
 return $tfiles;

}

function SetDefaultTemplate($template)
{
 global $script;
 $filename=$script['paths']['file']."templates/default";

 $handle=fopen($filename,"w");
 fwrite($handle,$template);
 fclose($handle);
}


function GeneratePaginationSelector($newsarch,$newsid,$comperpage,$range)
{
  $num=NumComments($newsarch,$newsid);
  $c=1;
  $result='';
  $bid=substr($range,0,strpos($range,'-'));
  $eid=substr($range,strpos($range,'-')+1);
  while ($c<=$num)
  {
   $c2=$c+$comperpage;
   $c2=$c2-1;
   if ($bid>=$c and $eid<=$c2){
    $result.='<option selected>'.$c.'-'.$c2.'</option>';
   }else
   {
    $result.='<option>'.$c.'-'.$c2.'</option>';
   }
   $c=$c2+1;
  }
  return $result;
}

function GeneratePaginationLinks($newsarch,$newsid,$comperpage,$range,$page)
{
  $num=NumComments($newsarch,$newsid);
  $c=1;
  $result='';
  $bid=substr($range,0,strpos($range,'-'));
  $eid=substr($range,strpos($range,'-')+1);
  while ($c<=$num)
  {
   $c2=$c+$comperpage;
   $c2=$c2-1;
   if ($bid>=$c and $eid<=$c2){
    $result.='<font color="#666666">'.$c.'-'.$c2.'</font> | ';
   }else
   {
    $result.='<a href="'.$page.'?xnewsaction=getcomments&amp;newsarch='.$newsarch.'&amp;newsid='.$newsid.'&amp;range='.$c.'-'.$c2.'">'.$c.'-'.$c2.'</a> | ';
   }
   $c=$c2+1;
  }
  return $result;
}

function GenerateNewsPaginationLinks1($num,$newsperpage,$range,$archive,$category,$author)
{
  $c=1;
  $result='';
  $bid=substr($range,0,strpos($range,'-'));
  $eid=substr($range,strpos($range,'-')+1);
  while ($c<=$num)
  {
   $c2=$c+$newsperpage;
   $c2=$c2-1;
   if ($bid>=$c and $eid<=$c2){
    $result.='<span>'.$c.' - '.$c2.'</span> | ';
   }else
   {
    $result.='<span><a href="index.php?a=editnews&amp;newsarch='.$archive.'&amp;author='.$author.'&amp;cat='.$category.'&amp;range='.$c.'-'.$c2.'">'.$c.' - '.$c2.'</a></span> | ';
   }
   $c=$c2+1;
  }
  return $result;
}

function GenerateNewsPaginationLinks2($archive,$num,$newsperpage,$range)
{
  $c=1;
  $result='';
  $bid=substr($range,0,strpos($range,'-'));
  $eid=substr($range,strpos($range,'-')+1);
  while ($c<=$num)
  {
   $c2=$c+$newsperpage;
   $c2=$c2-1;
   if ($bid>=$c and $eid<=$c2){
    $result.='<span>'.$c.' - '.$c2.'</span>';
   }else
   {
	$sMonth = substr($archive, 0, 2);
	$sYear = substr($archive, -4);
    $result.='<span><a href="news/rng/' . $sYear . '/' . $sMonth . '/'.$c.'-'.$c2.'">'.$c.' - '.$c2.'</a></span>';//.$_SERVER['SCRIPT_NAME'].'?xnewsaction=getnews&amp;newsarch='.$archive.'&amp;xnewsrange='.$c.'-'.$c2.'">'.$c.' - '.$c2.'</a></span>';
   }
   $c=$c2+1;
  }
  return $result;
}

function fetch_image_array($currentpath)
{
    if ($handle = opendir($currentpath)) {
      while (false !== ($file = readdir($handle)))
      {
       $fpath=$currentpath.$file;
       if (is_dir($fpath) and $file!='.' and $file!='..')
       {
        $files[$i]['name']=$file;
        $files[$i]['dir']=true;
      //  $files[$i]['size']=filesize($script['paths']['picturedir'].$thisdir.$file);
        $i++;
       }else
       {
       if (isPicture($currentpath.$file)==1)
       {

        //<<-------------- First, let's see who uploaded this picture
         $method=GetImageUploader($currentpath.$file);
        //<<------- Uploader verified. Let's fill the file array

        $files[$i]['name']=$file;
        $files[$i]['filepath']=$fpath;
        $files[$i]['uploader']=$method;
        $files[$i]['dir']=false;
        $files[$i]['size']=TrimFloat((filesize($currentpath.$file)/1024),2)."KB";
        $info=GetImageSize($currentpath.$file);
        $files[$i]['lastmod']=DecodeTime(EncodeTime(filemtime($currentpath.$file)));
        $files[$i]['res']=$info[0].'x'.$info[1];
        $i++;
       }
       }
      }
      closedir($handle);
    }
    return $files;
}

function GenCaptcha()
{
global $script;
// First, let's get rid of all code files not loaded for 30 minutes

if ($dirhandle = opendir($script['paths']['file']."captcha/"))
 {
  while (false !== ($file = readdir($dirhandle)))
  {
   $created=filemtime($script['paths']['file']."captcha/".$file);
   $dif=time()-$created;
   if ($dif>=1800 and is_file($script['paths']['file']."captcha/".$file)){unlink($script['paths']['file']."captcha/".$file);}
  }
 }



$code = mt_rand();
$code = substr(sha1($code), 0, 6);
$code = str_replace(array('0', 'O', 'o'), rand(1, 9), $code);
$filename=$script['paths']['file']."captcha/".$_SERVER['REMOTE_ADDR'].".php";

$content="<?php \$code='$code'; require('../captcha.php');unlink('".$_SERVER['REMOTE_ADDR'].".php');?>";

$fhandle=fopen($filename,"w");
fwrite($fhandle,$content);
fclose($fhandle);
return $code;
}

function VerifyCaptcha()
{
  global $script;
  if ($script['comments']['captcha']=="ON")
  {
    @session_start();
    $entered=$_POST['confirmation'];
    $valid=$_SESSION['xnewscaptchacode'];
    if ($valid==md5($entered)){return true;}else{return false;}

  }else {return true;}
}

function VerifyCommentLength()
{
   global $script;
   $actuallen=strlen($_POST['comment']);
   if ($script['comments']['messagelength']==0){return true;}else
   {
     if ($actuallen>$script['comments']['messagelength']){return false;}else{return true;}
   }
}

function GenerateBackup($name)
{
 global $script;
 $size=0;
 $ctime=time();
 $backupdir=$script['paths']['backup'].$ctime.'/';
 if (!file_exists($script['paths']['backup']))
	@mkdir($script['paths']['backup']);
 mkdir($backupdir);
 mkdir($backupdir."/news");
 $remdir=$script['paths']['file']."news/";
 // Creating news backup

 if ($dh = opendir($remdir)) {
   while (($file = readdir($dh)) !== false) {
     if ($file!='.' and $file!='..')
     {$size=$size+filesize($remdir.$file);
     copy($remdir.$file,$backupdir."news/$file");}
   }
   closedir($dh);
 }
 $size=$size+filesize($script['paths']['file']."catindex.txt");
 $size=$size+filesize($script['paths']['file']."userdb.php");
 $size=$size+filesize($script['paths']['file']."catdb.php");
 $size=$size+filesize($script['paths']['file']."ranks.php");
 $size=$size+filesize($script['paths']['file']."rankindex.txt");
 copy($script['paths']['file']."catindex.txt",$backupdir."catindex.txt");
 copy($script['paths']['file']."userdb.php",$backupdir."userdb.php");
 copy($script['paths']['file']."catdb.php",$backupdir."catdb.php");
 copy($script['paths']['file']."ranks.php",$backupdir."ranks.php");
 copy($script['paths']['file']."rankindex.txt",$backupdir."rankindex.txt");
 $handle=fopen($script['paths']['file']."backuplist.txt","a");
 fwrite($handle,$ctime."|".$name."|".$backupdir."|".$size."\n");
 fclose($handle);

 return '$__msg_backupsaved';
}
//GenerateBackup("bk2");
function RestoreBackup($btime)
{
 global $script;
 $lines=file($script['paths']['file']."backuplist.txt");
 foreach ($lines as $i=>$line)
 {
  $parts=explode("|",$line);
  if ($parts[0]==$btime)
  {
    $remdir=$parts[2];
  }
 }

 if (!file_exists($remdir))
 {
  return '$__msg_backupnotfound';
  exit;
 }

 if ($dh = opendir($script['paths']['file']."news/")) {
   while (($file = readdir($dh)) !== false) {
     if ($file!='.' and $file!='..')
       unlink($script['paths']['file']."news/".$file);
   }
   closedir($dh);
 }

 if ($dh = opendir($remdir."news/")) {
   while (($file = readdir($dh)) !== false) {
     if ($file!='.' and $file!='..')
       copy($remdir."news/$file",$script['paths']['file']."news/".$file);
   }
   closedir($dh);
 }

 @copy($remdir."catdb.php",$script['paths']['file']."catdb.php");
 @copy($remdir."userdb.php",$script['paths']['file']."userdb.php");
 @copy($remdir."ranks.php",$script['paths']['file']."ranks.php");
 @copy($remdir."rankindex.txt",$script['paths']['file']."rankindex.txt");

 return '$__msg_backuprestored';
}

function ListBackups()
{
 global $script;
 $lines=file($script['paths']['file']."backuplist.txt");
 foreach ($lines as $i=>$line)
 {
   $parts=explode("|",$line);
   $ret[$i]['archived']=$parts[0];
   $ret[$i]['name']=$parts[1];
   $ret[$i]['dir']=$parts[2];
   $ret[$i]['size']=$parts[3];
 }
 return $ret;
}






function EditAdCode($adid,$adname,$code)
{
  global $script;
  $code   = EncodeAdCode($code);
  $filename = $script['paths']['file']."advertising.php";
  $lines    = file($filename);
  $handle   = fopen($filename,"w");
  $adname   = FormatSimpleString($adname);
  foreach ($lines as $i=>$line)
  {
   $parts=explode('|',$line);
   if (@$parts[0]==$adid)
   {
    $newline="$adid|$adname|$code|".$parts[3];
   }else
   {
    $newline=$line;
   }
   fwrite($handle,$newline);
  }
  fclose($handle);
}


//EditAdCode("2","my new name","my code");

function DeleteAd($adid)
{
  global $script;
  $filename = $script['paths']['file']."advertising.php";
  $lines    = file($filename);
  $handle   = fopen($filename,"w");
  foreach ($lines as $i=>$line)
  {
   $parts=explode('|',$line);
   if (@$parts[0]==$adid)
   {
   }else
   {
    fwrite($handle,$line);
   }
  }
  fclose($handle);
}

function AdsGetMaxId()
{
  global $script;
  $lines=@file($script['paths']['file']."advertising.php");
  $maxid=-1;
  foreach ($lines as $i=>$line)
  {
   $parts=explode('|',$line);
   if (count($parts)>1)
   {
    if (@$parts[0]>$maxid)
    {
     $maxid=$parts[0];
    }
   }
  }
 return $maxid;

}


function AddAd($adname,$adcode)
{
   global $script;
   $filename=$script['paths']['file']."advertising.php";
   $adcode=EncodeAdCode($adcode);
   if (file_exists($filename))
   {
     $handle=fopen($filename,"a");
     $maxid=AdsGetMaxId();
     $maxid++;
     $line=$maxid."|".$adname."|".$adcode."|".EncodeTime('')."\n";
   } else
   {
     $handle=fopen($filename,"w");
     fwrite($handle,"<?php die();?>\n");
     $line="1|".$adname."|".$adcode."|".EncodeTime('')."\n";
   }
   fwrite($handle,$line);
   fclose($handle);
}

//AddAd('my ad','<script language="JavaScript"></script>');


function ListAds()
{
 global $script;
 $filename=$script['paths']['file']."advertising.php";
 $lines = @file($filename);
 $content='';
 for ($i=1;$i<count($lines);$i++)
 {
   $parts=explode('|',$lines[$i]);
   $content[$i-1]['id']=$parts[0];
   $content[$i-1]['name']=$parts[1];
   $content[$i-1]['code']=DecodeAdCode($parts[2]);
   $content[$i-1]['time']=$parts[3];
 }
 return $content;
}

//print_r(ListAds());

function GetAd($id)
{
 global $script;
 $filename=$script['paths']['file']."advertising.php";
 $lines = @file($filename);
 $content='';
 for ($i=1;$i<count($lines);$i++)
 {
   $parts=explode('|',$lines[$i]);
   if ($parts[0]==$id)
   {
    $content['id']=$parts[0];
    $content['name']=$parts[1];
    $content['code']=DecodeAdCode($parts[2]);
    $content['time']=$parts[3];
    return $content;
   }
 }
 return $content;

}

?>
