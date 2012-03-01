<?php

$oSmarty->assign("sCurrent", strtok( strtok( $_SERVER['REQUEST_URI'] , "?") , "&") );

?>