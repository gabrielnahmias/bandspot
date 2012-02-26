<?php
 require "config.inc.php";
 require "functions.php";
 require "skin.php";
 
 $template=GetDefaultTemplate(); 
 $xwrapper=file_get_contents("inc/richedit1.html");
 $xwrapper=str_replace("{ID}","1",$xwrapper);

 $xwrapper=str_replace('{SMILLIES1}',GenerateEmoticonList('live',15,1),$xwrapper);
 print $xwrapper;
?>