<?php
/*
 ========================================================================
 |  Copyright 2007 - Andrei Dumitrache. All rights reserved.
 | 	 http://www.hogsmeade-village.com
 |
 |   This file is part of Xpression News (X-News) v2.0.0
 |
 |   This program is Freeware.
 |   Please read the license agreement to find out how you can modify this web script.
 |
 ======================================================================== 
*/

 require("config.inc.php");
 require("functions.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Elemovements News :: Emoticons</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href=<?php print '"'.$script['paths']['url'].'inc/styles.css"'; ?> rel="stylesheet" type="text/css">
</head>

<body bgcolor="#4e4e4e">


<?php
 require("skin.php");

 $form=$_GET['form'];
 $input=$_GET['input'];

 print "

   <script language=\"javascript\" type=\"text/javascript\">
   <!--
   function InsertEmoticon(text) {
    opener.document.forms['".$form."'].".$input.".value+=text;
	opener.document.forms['".$form."'].".$input.".focus();
   }
   //-->
   </script>

 ";


?>

<div align="center"><table width="80%" border="1" cellspacing="0" cellpadding="0">
  <tr>
  <th class="cat">Emoticons</th>
  </tr>
  <tr>
    <td>
    <?php print GenerateEmoticonList('popup',''); ?>
    </td>
  </tr>
  <tr><th class="cat" align="center"><a href="#" OnClick="window.close();"><span class="SpanClass2"><font color="#FFFFFF">[Close this window]</font></span></a></th></tr>
</table></div>

</body>

</html>