<?php
 require("../functions.php");
 require("../skin.php");
 require("../config.inc.php");
 if (ValidateLogin($_COOKIE['xusername'],$_COOKIE['xpassword'])==0)
 {
    header('Location: '.$script['paths']['url'].'index.php?a=login');
    exit;
 }
 $wrapper=file_get_contents("emoticons.html");
 $wrapper=str_replace("{CALLER_ID}",$_GET['callerid'],$wrapper);
 $wrapper=str_replace('{EMOTICONS}',GenerateEmoticonList('livepopup','',$_GET['callerid']),$wrapper);   
 OutputWrapper($wrapper);
?>
