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

?>

<html>

<head>
  <title>Elemovements News - View Permissions</title>
<link href="inc/styles.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#4E647E">
<?php

require("functions.php");
$rankid=$_GET['rank'];
$p=GetRankPerms($rankid);
$p=str_replace("1","YES",$p);
$p=str_replace("0","NO",$p);



?>
<div align="center">
  <table width="80%" border="0" cellspacing="1" cellpadding="0" class="tableborder">
    <tr>
      <th colspan="2" class="cat">Viewing Permissions for <?php print GetRankName($rankid);?> (rank)</th>
    </tr>
    <tr>
      <td width="40%" class="row1"> <div align="right">&nbsp;Can Post Comments:</div></td>
      <td width="60%" class="row2"><?php print $p[0];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Edit Own Comments:</div></td>
      <td class="row2"><?php print $p[1]; ?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Edit Other Comments:</div></td>
      <td class="row2"><?php print $p[2];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Post News:</div></td>
      <td class="row2"><?php print $p[3];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Edit Own News:</div></td>
      <td class="row2"><?php print $p[4];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Edit Other News:</div></td>
      <td class="row2"><?php print $p[5];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Upload Images:</div></td>
      <td class="row2"><?php print $p[6];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Delete Own Images:</div></td>
      <td class="row2"><?php print $p[7];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Delete Other Images:</div></td>
      <td class="row2"><?php print $p[8];?></td>
    </tr>
    <tr>
      <td class="row1"><div align="right">Can Administrate:</div></td>
      <td class="row2"><?php print $p[9];?></td>
    </tr>
  </table>
</div>
</body>

</html>