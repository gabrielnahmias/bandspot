var $Body = $("#pictures .inner");
var $Title = $("#pictures .title");

var oCBOpts = {
	
	rel: "gallery",
	
	width: 300
	
}

// Scroller gallery:

if ( $('div.highslide-gallery').length ) {

	$( function() {
		
		var $Div = $('div.highslide-gallery'),
			$Ul = $('ul.horiz-list'),
			$UlPadding = 0;
		
		$Ul.width(9000);
		$Div.width( $Div.parent().parent().width() - 26 );
		
		var $DivWidth = $Div.width();
	 
		$Div.css( {overflow: 'hidden'} );
				
		var lastLi = $Ul.find('li:last-child');
		
		$Div.mousemove(function(e){
			
			var $UlWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + $UlPadding;	
			
			var left = (e.pageX - $Div.offset().left) * ($UlWidth-$DivWidth) / $DivWidth;
			
			$Div.scrollLeft(left);
			
		} );
		
	} );

}

// End gallery.

// The next bit is for making the non-JavaScript site work with pictures.  This adds
// a switch for the JS-enabled one to exclude the header and container for the loaded
// content.

// TODO: I need to consolidate all the various "nb" and "nhf" methods I use.  I'm thinking just adding
// it in with the href, etc. is the best idea.  Should I use JavaScript to attach the switch to the
// end of links I need them to be or is that too much work?

$(".album-container a").each(function() {
	
   var sHREF = $(this).attr("href"); 
   
   $(this).attr("href", sHREF + "&nb");
   
} );

if (oVars.bI) {
	
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
	
} else {
	
	$(window).resize( function() {
	
		hs.maxHeight = $(window).height() - 300;
		
		if ( hs.getExpander() != null ) {
			
			// If there is a Highslide window open...
			
			/*
			
			$Image = $(".highslide-image");
			
			$Image.css( {
				
				"position": "fixed",	// set this outside of the resize?
				
				"top": ( $(window).height() - ( $Image.height() / 2 ) ) + "px"
				
			} );
			
			if ( $Image.width() >= $(window).width() ) {
				
				$Image.width( $(window).width() - 20 );
				
			} else if ( $Image.height() >= $(window).height() ) {
				
				$Image.width( $(window).height() - 20 );
				
			}
			
			*/
			
		}
		
	} );
	
}

$("#pictures .album-container a").click( function(event) {
	
	var strFBLink = $(this).attr("fb-href");
	
	var strURL = $(this).attr("href");
	
	var strName = getUrlVars(strURL)['name'];
	
	$Body.slideUp(100);
	
	$.get(
		
		strURL,
		
		function(strData) {
			
			$Body.html(strData).slideDown(oVars.iSpeed, "easeOutBounce", function() {
				
				$(this).find("div.back").show("slide", { direction: "right" }, oVars.iSpeed, function() { } );
				
			} );
			
			$Title.html( oPHP.vars.titles[ extractPage("ending") ].replace(" ", oPHP.const.TEXT_DIVIDER + strName + ' ') );
			
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
	
	$Body.find("div.back").hide("slide", { direction: "right" }, function() {
		
		$Body.slideUp(100);
		
		$.get(
			
			strURL,
			
			{ nb: true },
			
			function(strData) {
				
				$Title.html( oPHP.vars.titles[oVars.sCurrent] );
				
				$Body.html(strData).slideDown(oVars.iSpeed, "easeOutBounce");
				
				$("a.fblink").glow();
				
			}
			
		);
		
	} );
	
	track(strURL);
	
	event.preventDefault();
	
} );