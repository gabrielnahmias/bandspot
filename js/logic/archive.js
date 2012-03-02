$("span.pagination span:first").css("border", "none");
$("span.pagination span:last").css("border-right", "none");

if ( $("span.pagination span").length > 2 )
	$("span.pagination span:last").css("border", "none");

// We don't have to have the home's logic file's contents here anymore because I included it in archive.php.