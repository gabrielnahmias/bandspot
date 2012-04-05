<?php

$xnewspagination = "1";
$newsperpage = 2;
$xnewsrange = "1-2";

require_once "inc/config.php";

include "news/archives.php";

// We HAVE to do something about the next tag because $sPath is always gonna be empty for this page because
// it's being loaded by JavaScript and doesn't have access to the same values as it normally would if
// included with the rest of the stuff.  We should make this a conditional in "index.php."

?>

<script language="javascript" src="<?=TEXT_MIN_F . ( ( localhost() ) ? "sites/el/" : "" ) . DIR_JS_LOGIC?>/home.js" type="text/javascript"></script></script>