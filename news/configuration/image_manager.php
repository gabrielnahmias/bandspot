<?php

function WriteConfiguration()
{
   $handle=fopen("configuration/image_manager.inc.php","w");
   fwrite($handle,"<?php\n");
   fwrite($handle,"//This file has been automatically generated by X-News v1.0.1\n\n");
   fwrite($handle,"\$script['paths']['picturedir']=\"".htmlspecialchars($_POST['paths#picturedir'])."\";\n");
   fwrite($handle,"\$script['paths']['pictureurl']=\"".htmlspecialchars($_POST['paths#pictureurl'])."\";\n");
   fwrite($handle,"\$script['paths']['picviewer']=\"".htmlspecialchars($_POST['paths#picviewer'])."\";\n");
   fwrite($handle,"?>");
   fclose($handle);
}
function ReadConfiguration($wrapper)
{
    return $wrapper;
}
?>