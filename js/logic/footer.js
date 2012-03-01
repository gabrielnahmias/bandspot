loadContent(strCurrent, true, true);

var $Archive = $("#archive-popout");

( function() {

	// Unfortunately, this has to put down here because it needs to run
	// on both the archive and the news sections and it would be annoying
	// and redundant to call it every time those two are loaded.
	
	// TODO: move this to scripts.js
	
	var $Body = $("#home .inner");
	var $Title = $("#home > .title");
	
	// var strArchiveURL = "index.php?pg=archive&xnewsaction=getnews&newsarch=" + getURLVars()["newsarch"];

	var strBack = "";
	
	var strOld = $Body.html();
	
	strBack = '<a class="back">' + oPHP.const.TEXT_BACK + '</a>';
		
	function displayArchive(event, me) {
		
		$Body = $("#home .inner");
		$Title = $("#home > .title");
		
		var strURL = $(me).attr("href");
		
		$("#home .inner").slideUp(100);
		
		$.get(
			
			strURL,
			
			oNB,
			
			function(strData) {
				
				$Title.html(oPHP.const.TEXT_ARCHIVE_TITLE);
				
				$Body.html(strData + ( ( window.location.pathname.replace("/", "") == "archive" ) ? strBack : "" ) ).slideDown(1000, "easeOutBounce");		// This had strData + strBack... I don't know why.
				
			}
			
		);
		
		event.preventDefault();
		
	}
	
	if (strCurrent == "archive")
		$(document).on("click", "#home td a", function(event) { displayArchive(event, this) } );
	else
		$("#archive-popout a").click( function(event) { displayArchive(event, this) } );
	
    $(document).on("click", "#home .more a", function(event) {
		
		$Body = $("#home .inner");
		
		strOld = $Body.html();
		
    	var strURL = $(this).attr("href");
		
		$Body.slideUp(100);
        
        $.get(
            
            strURL,
            
            oNB,
            
            function(strData) {
				
                $Body.html(strData + strBack).slideDown(1000, "easeOutBounce");
                
            }
            
        );
        
        track(strURL);
        
        event.preventDefault();
        
    } );
	
	$(document).on("click", "#home a.archive", function(event) {
		
		if (strCurrent != "archive") {
			
			oArchive.toggle();
			
			event.preventDefault();
			
		}
		
	} );
	
	$(document).on("click", "#home a.back", function(event) {
		
		$Body.slideUp(100, function() {
			
			$(this).html(strOld);
			
		} ).slideDown(1000, "easeOutBounce");
		
		event.preventDefault();
		
	} );
	
	$(document).on("click", "#home a.latest", function(event) {
		
		$Body = $("#home .inner")
		$Title = $("#home > .title");
		
		$Body.slideUp(100);
		
		$.get(
			
			oPHP.const.URL_NEWS,
			
			function(strData) {
				
				$("#home > .title").html(oPHP.const.TEXT_NEWS_TITLE);
				
				$("#home .inner").html(strData).slideDown(1000, "easeOutBounce");
				
			}
			
		);
		
		track("home");
		
		event.preventDefault();
		
	} );
	
	$(document).on("click", "#home span.pagination a", function(event) {
		
		$Body = $("#home .inner")
		$Title = $("#home > .title");
		
		var strLinkText = $(this).text(),
			strURL = $(this).attr("href");
		
		$.get(
			
			strURL,
			
			oNB,
			
			function(strData) {
				
				var strDirection,
					strSeparator = " - ",
					strOpposite;
				
				// Find the span within the pagination element that does not include a link and that is the
				// current range.  Format it appropriately and parse its first value as an integer.  Also,
				// do the same for the link that has just been clicked.  Compare the two and if the current
				// is less than the clicked, come in from the right, else, from the left.  Then, it's merely
				// a matter of hiding the current content in the opposite direction, loading the new page
				// into the element, and showing it.  Creates a very unique page-flipping effect!
				
				var intCurrent = parseInt( $("span.pagination").find("span:not(':has(a)')").text().split(strSeparator)[0] );
				var intClicked = parseInt( strLinkText.split(strSeparator)[0] );
				
				if (intCurrent < intClicked) {
					
					strDirection = "right";
					strOpposite = "left";
					
				} else {
					
					strDirection = "left";
					strOpposite = "right";
					
				}
				
				$Body.hide("slide", { direction: strOpposite }, 500, function() {
					
					$(this).html(strData);
					
				} ).show("slide", { direction: strDirection }, 500);
				
			}
			
		);
		
		track(strURL);
		
		event.preventDefault();
		
	} );
	
}() );