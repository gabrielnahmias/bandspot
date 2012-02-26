<?php include('functions.php');include("misc.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Elemovements News - The Ultimate News System</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
body {
	background-color: #e7eff9;
	background-image: url(headerbg.jpg);
	background-repeat: repeat-x;
	background-attachment: fixed;
	background-position: center top;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #003366;
}
a{
 font-size: 11px;
}
.mainframe
{
border: 1px solid #003366;
}
</style>

</head>

<body>
<div align="center">
  <table width="80%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="57" colspan="2">Your news section</td>
    </tr>
    <tr>
      <td height="100" colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1" class="mainframe">
          <tr>
            <td height="20" background="grad1.jpg"><div align="center"><font size="-1"><strong>Headlines</strong></font></div></td>
          </tr>
          <tr>
            <td height="34" bgcolor="#dde8f7"><div align="center">
                <?php print GenerateHeadlinks("example1.php",5,'<a href="{LINK}">{HEADLINE}</a><br />','{LINKS}'); ?>
              </div></td>
          </tr>
        </table> </td>
    </tr>
    <tr>
      <td width="23%" height="91" valign="top"><table width="90%" border="0" cellspacing="1" cellpadding="1" class="mainframe">
          <tr>
            <td height="20" background="grad1.jpg"><div align="center"><font size="-1"><strong>Categories</strong></font></div></td>
          </tr>
          <tr>
            <td height="34" bgcolor="#dde8f7"><div align="center">
                <?php  print Generatecatlist("example1.php",'<a href="{LINK}">{CATNAME}</a><br />','{LINKS}'); ?>
              </div></td>
          </tr>
        </table></td>
      <td width="77%" valign="top"><div align="center">
          <table width="100%" border="0" cellspacing="1" cellpadding="1" class="mainframe">
            <tr>
              <td height="20" background="grad1.jpg"><div align="center"><font size="-1"><a href="example1.php">Home</a>
                  - <a href="example2.php">Go to archives</a></font></div></td>
            </tr>
            <tr>
              <td bgcolor="#b5c8e1"><div align="center"><br />
                  <?php include("news.php"); ?>
                  <br />
                </div></td>
            </tr>
          </table>
        </div></td>
    </tr>
  </table>
</div>
</body>
</html>