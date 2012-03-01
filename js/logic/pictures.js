var $Body = $("#pictures .inner");
var $Title = $("#pictures .title");

var oCBOpts = {
	
	rel: "gallery",
	
	width: 300
	
}

// The next bit is for making the non-JavaScript site work with pictures.  This adds
// a switch for the JS-enabled one to exclude the header and container for the loaded
// content.

$(".album-container a").each(function() {
	
   var sHREF = $(this).attr("href"); 
   
   $(this).attr("href", sHREF + "&nb");
   
} );

if (bI) {
	
	$("#gallery a").colorbox(oCBOpts);
	
	addEvent("orientationchange", function() {
		
		switch(window.orientation) {
			
			case 0:
				
				oCBOpts.width = 300;
				
				$("#gallery a").colorbox(oCBOpts)
				
				break;
			
			case -90:
			case 90:
				
				oCBOpts.width = 460;
				
				$("#gallery a").colorbox(oCBOpts)
				
				break;
			
		}
	
	} );
	
}

$("#pictures .album-container a").click( function(event) {
	
	var strFBLink = $(this).attr("fb-href");
	
	var strURL = $(this).attr("href");
	
	var strName = getURLVars(strURL)['name'];
	
	$Body.slideUp(100);
	
	$.get(
		
		strURL,
		
		function(strData) {
			
			$Body.html(strData).slideDown(1000, "easeOutBounce");
			
			$Title.html( oPHP.vars.titles[strCurrent].replace(" ", oPHP.const.TEXT_DIVIDER + strName + ' ') );
			
			$("a.fblink").attr("href", strFBLink).glow();
			
		}
		
	);
	
	track(strURL);
	
	event.preventDefault();
	
} );

$("#pictures a.back").click( function(event) {
	
	// Do the same thing for this link to allow for the non-JS site.
	
	$(this).attr("href", $(this).attr("href") + "&nb" );
	
	var strURL = $(this).attr("href");
	
	$Body.slideUp(100);
	
	$.get(
		
		strURL,
		
		{ nb: true },
		
		function(strData) {
			
			$Title.html( oPHP.vars.titles[strCurrent] );
			
			$Body.html(strData).slideDown(1000, "easeOutBounce");
			
			$("a.fblink").glow();
			
		}
		
	);
	
	track(strURL);
	
	event.preventDefault();
	
} );