<?php

$oSmarty->assign("sCurrent", strtok( strtok($smarty.server.REQUEST_URI, "?") , "&") );

?>