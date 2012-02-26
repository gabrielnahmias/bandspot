<?php

$imgtypes = array(
       1 => 'GIF',
       2 => 'JPG',
       3 => 'PNG',
       4 => 'SWF',
       5 => 'PSD',
       6 => 'BMP',
       7 => 'TIFF(intel byte order)',
       8 => 'TIFF(motorola byte order)',
       9 => 'JPC',
       10 => 'JP2',
       11 => 'JPX',
       12 => 'JB2',
       13 => 'SWC',
       14 => 'IFF',
       15 => 'WBMP',
       16 => 'XBM'
   );

$location=$_GET['src'];
$img='<img src="'.$location.'" border="0">';
$info=getimagesize($location);
$width=$info[0];
$height=$info[1];
$imgtype=$imgtypes[$info[2]];
$resolution=$width." by ".$height." pixels";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Elemovements News</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

.spanclass1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #ebf0f3;
	font-weight: normal;
}
</style>
</head>

<body bgcolor="#096686">
<p>&nbsp;</p>
<table width="80%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#096686" style="border: 1px solid #0b7197">
  <tr> 
    <td height="25" background="grad2.jpg" colspan="2"><div align="center"><font color="#FFFFFF" size="-1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Xpression 
        News - Picture Viewer</strong></font></div></td>
  </tr>
  <tr align="center" valign="middle"> 
    <td colspan="2"><?php print $img;?></td>
  </tr>
  <tr bgcolor="#004b74"> 
    <td width="18%">&nbsp;<span class="spanclass1">Location:</span></td>
    <td width="82%" class="spanclass1"><?php print $location;?></td>
  </tr>
  <tr bgcolor="#004b74"> 
    <td>&nbsp;<span class="spanclass1">Image type:</span></td>
    <td class="spanclass1"><?php print $imgtype;?></td>
  </tr>
  <tr bgcolor="#004b74"> 
    <td>&nbsp;<span class="spanclass1">Resolution:</span></td>
    <td class="spanclass1"><?php print $resolution;?></td>
  </tr>
</table>
</body>
</html>
