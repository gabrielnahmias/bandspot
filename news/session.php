<?php

$action=$_GET['xnewsaction'];
require "skin.php";
require "config.inc.php";
require "functions.php";

$xout='
<html>

<head>
  <title>Elemovements News</title>

<script language="JavaScript">
function GoBack()
{
 opener.location.reload(false);
 window.close();
}
</script>

</head>

';

$jslink="javascript: GoBack()";

$template=GetDefaultTemplate();

if ($action=='login')
{
  if (empty($_POST['xuser']) || empty($_POST['xpass']))
  {
   $wrapper=SkinLoginPage($template,$_POST,0,$_SERVER['SCRIPT_NAME'].'?xnewsaction=login');
   $xout.=$wrapper;
  }else
  {
    $xuser=$_POST['xuser'];
    $xpass=md5($_POST['xpass']);
    if (ValidateLogin($xuser,$xpass)==1)
    {
     $xout.=SkinMessage(LanguageField('msg_loginok',''),$template);
     setcookie('xusername',$xuser,NULL,'/');
     setcookie('xpassword',$xpass,NULL,'/');
     $xout=str_replace('{LINK}',$jslink,$xout);
    }else
    {
     $xout.=SkinLoginPage($template,$_POST,1,$_SERVER['SCRIPT_NAME'].'?xnewsaction=login');
    }
  }
}
else if ($action=='logout')
{
  setcookie('xusername','',NULL,'/');
  setcookie('xpassword','',NULL,'/');
  $xout.=SkinMessage(LanguageField('msg_loggedout',''),$template);
  $xout=str_replace('{LINK}',$jslink,$xout);
}
$xout.='

</body>

</html>
';
OutputWrapper($xout,1);
?>