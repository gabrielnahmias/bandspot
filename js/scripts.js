var $Archive = $("div#archive-popout");

var oPHP = json("inc/json.php");

var bGlown = false;
var bI = ( navigator.platform.indexOf("iPhone") != -1 );
var bIE = $.browser.msie;
var bLoaded = false;
var bFirstParse = true;

// Make strCurrent come from PHP? oPHP.vars.sCurrent...?
var strCurrent = ( ( extractPage() == "" ) ? "home" : extractPage() );
var strPG = "pg";
var strPrefix = "/?" + strPG + "=";

var oNB = { nb: "true" };
var oNHF = { nhf: "true" };

var oArchive = {
	
	bOpen: false,
	
	strClosed: "0px",
	
	strOpen: "-115px",
	
	isOpen: function() {
		
		return this.bOpen;
		
	},
	
	toggle: function() {
		
		var strRight = $Archive.css("right");
		
		$Archive.removeClass("sticky");
		
		if (!this.bOpen) {
			
			$Archive.css("visibility", "visible").animate(
				
				{ right: this.strOpen },
				
				500,
				
				"easeOutBack",
				
				function() {
					
					// $("a.archive").css("cssText", "border-color: #66B0D4 !important; color: #66B0D4 !important;");
					
					$Archive.css("padding-left", "6px");
					$Archive.css("z-index", 0);
					
				}
			
			);
			
			scrollRight();
			
			this.bOpen = true;
			
		} else {
			
			// $("a.archive").css("cssText", "");
			
			$Archive.css("visibility", "hidden").css("padding-left", "20px");
			$Archive.css("right", this.strClosed);
			$Archive.css("z-index", -1);
			
			this.bOpen = false;
			
		}
	}
	
}

// Once you have an iPhone again >:(, it would be a good idea to eliminate the Facebook-related errors.

window.fbAsyncInit = function() {
	
	FB.init( {
		
		appId      : oPHP.const.ID_FB_APP,
		status     : true, 
		cookie     : true,
		xfbml      : true,
		oauth      : true,
		
	} );
  
}

String.prototype.trim = function() {
	
	return this.replace(/^\s+|\s+$/g, '');
	
}

function addEvent(strType, func) {
	
	if (window.addEventListener)
		window.addEventListener(strType, func, false); 
	else if (window.attachEvent)
		window.attachEvent("on" + strType, func);

}

function adminLinks() {
	
	if ( (strCurrent == "home" || strCurrent == "archive") && !$(".links .admin").length ) {
		
		var sID;
		
		FB.getLoginStatus( function() {
			
			sID = response.authResponse.userID;
			
			if ( oPHP.const.ID_FB_ADMINS.indexOf(sID) != -1 ) {
				
				// One of the Facebook admins is using the page, so display some links for them.
				
				var $Links = $('<span class="admin"> · <a href="news/" target="_blank">Login</a> · <a href="https://www.google.com/analytics/web/?pli=1#report/visitors-overview/a27838426w53292149p54112166/" target="_blank"><img class="analytics" src="img/graph.png" title="Access Google Analytics" /></a></span>');
				
				// For some reason the beginning · doesn't get registered when encapsulated in a jQuery object... weird.
				
				$(".box-1 .title .links").append($Links);
				
				if (!bGlown) {
					
					$Links.glow();
					
					bGlown = true;
					
				}
				
			}
			
		} );
		
	}
}

function adjustClassHeight(strClass, intAdjust) {
	
	if (intAdjust === undefined) intAdjust = 0;
	
	var tmp_list = document.getElementsByClassName(strClass);
	
	if (tmp_list.length != 0) {
		
		var tmp_max = getElemHeight( tmp_list[0] );
		
		for(var i = 0; i < tmp_list.length; i++)
			tmp_max = tmp_max < getElemHeight(tmp_list[i]) ? tmp_max = getElemHeight(tmp_list[i]) : tmp_max;
		
		for(var i = 0; i < tmp_list.length; i++)
			tmp_list[i].style.height = (tmp_max + intAdjust) + 'px';
		
	}
	
}

function extractPage() {
	
	// This is pretty ugly...
	
	// return window.location.search.replace(/(.+)=(.+)&(.+)/, '$2');
	
	var strFirst = window.location.pathname.replace("/", "").split("&")[0];
	
	if (strFirst == "index.php")
		return window.location.search.split("&")[0].replace("?pg=", "");
	else
		return strFirst;
		
}

function fbInfo() {
	
	// Figure out how to make this work even if someone logs into the comments box.
	
	var $El;
	
	// MAKE THESE ANIMATION TECHNIQUES AN EXTENSION SO IT'S NOT SO REDUNDANT.
	
	$(".fb-load").animate( {
		
		opacity: 1
		
	} );
	
	if ( fbLoggedIn() ) {
		
		var sURL;
		
		// Change the logout button's text.
		
		$(".fb-login-button .fb_button_text").text("Log Out");
		
		FB.api('/me', function(response) {
			
			var sName = response.first_name;
			
			$El = $("#fb-name");
			
			sURL = response.link;
			
			$El.html("Yo, <em><strong>" + sName + "</strong>!</em>").css("padding", "12px 15px 0 5px");
			
		} );
		
		FB.api('/me/picture', function(response) {
			
			$El = $("#fb-picture");
			
			$El.one("load", function() {
				
				$(".fb-load").animate( {
					
					opacity: 0
					
				} );
				
			} ).attr("src", response);
			
			$El.css("width", "30px");
			$El.wrap('<a href="' + sURL + '" target="_blank"></a>');
			
			adminLinks();
			
		} );
		
	} else {
		
		$El = $("#fb-picture");
		
		$("#fb-name").text("").css("padding", "0");
		
		$El.one("load", function() {
				
			console.log('Image replacement with spacer fired.  Ceasing load animation.')
			
			$(".fb-load").animate( {
				
				opacity: 0
				
			} );
			
		} ).attr("src", "img/spacer.png")
		   .css("width", "0");
		
		$(".links .admin").remove();
		
	}
	
	if ( navigator.userAgent.indexOf("WebKit") != -1 ) {
		
		// Why is this necessary for Chrome, etc...?
		
		$(".fb-load").animate( {
			
			opacity: 0
			
		} );
		
	}
	
}

function fbLoggedIn() {
	
	var bLoggedIn = false;
	
	FB.getLoginStatus( function(response) {
		
		if (response.status === 'connected') {
			
			// var uid = response.authResponse.userID;
			// var accessToken = response.authResponse.accessToken;
			
			bLoggedIn = true;
			
		}/* else if (response.status === 'not_authorized') {
			
			console.log('not authorized');
			
		}*/ else
			
			bLoggedIn = false;
		
	} );
	
	return bLoggedIn;
	
}

function getCookie(strName) {
	
	var i, strCName, y, arrCookies = document.cookie.split(";");
	
	for (i = 0; i < arrCookies.length; i++) {
		
		strCName = arrCookies[i].substr(0, arrCookies[i].indexOf("=") );
		strValue = arrCookies[i].substr( arrCookies[i].indexOf("=") + 1 );
		
		strCName = strCName.replace(/^\s+|\s+$/g, "");
		
		if (strCName == strName)
			return unescape(strValue);
			
	}
	
}

function getElemHeight(D) {
	
	return Math.max( Math.max(D.scrollHeight), Math.max(D.offsetHeight), Math.max(D.clientHeight) );
	
}

function getURLVars(strVars) {
	
	var arrVars = {};
	
	var objVar;
	
	if (strVars == null)
		objVar = window.location.href;
	else
		objVar = strVars;
	
	var parts = objVar.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
		
		arrVars[key] = value;
		
	} );
	
	return arrVars;
	
}

function json(sURL) {
	
	var sJSON = null;
	
	$.ajax( {
		
		'async': false,
		'global': false,
		'url': sURL,
		'dataType': "text",
		
		'success': function (data) {
			
			sJSON = data;
			
		}
		
	} );
	
	// Might as well use jQuery to parse the JSON so that older
	// browsers will work, too.
	
	return $.parseJSON(sJSON);

};

function loadContent(strURL, bPush, bReplace) {
	
	var $Box = $("#loaded");
	
	strCurrent = strURL.split("&")[0];
	
	// Closes any picture open in Colorbox.
	
	if (strCurrent != "pictures" && bI)
		$.colorbox.close();
	
	if (!bIE) {
		
		if (bPush) {
			
			var objState = { page: strCurrent };
			
			if (bReplace)
				history.replaceState(objState, "", "");
			else
				history.pushState(objState, "", strURL);
			
		} else {}
		
	}
	
	if (!bReplace) {
		
		// Load the page.
		
		$Box.slideUp(100);
		
		$.get(
			
			"index.php" + strPrefix + strURL,
			
			oNHF,
			
			function(strData) {
				
				document.title = oPHP.const.NAME + oPHP.const.TEXT_DIVIDER + ucwords(strCurrent);
				
				$Box.html(strData).slideDown(1000, "easeOutBounce");
				
			}
			
		);
		
		// Reload the Facebook widgets for the current page.
		
		if (!bI) {
			
			var oComments = $(".fb-comments").get(0);
			var oLike = $(".fb-like").get(0);
			
			oComments.innerHTML = '<div class="fb-comments" data-href="http://' + window.location.host + '/' + strCurrent + '" data-num-posts="' + oPHP.const.FB_COMMENTS_NUM + '" data-width="' + oPHP.const.FB_COMMENTS_WIDTH + '"></div>';
			oLike.innerHTML = '<div class="fb-like" data-href="http://' + window.location.host + '/' + (strCurrent == "home" ? "" : strCurrent ) + '" data-layout="' + oPHP.const.FB_LIKE_LAYOUT + '" data-send="' + oPHP.const.FB_LIKE_SEND + '" data-width="' + oPHP.const.FB_LIKE_WIDTH + '" data-show-faces="' + oPHP.const.FB_LIKE_FACES + '" data-font="' + oPHP.const.FB_LIKE_FONT + '"></div>';
			
			FB.XFBML.parse(oComments);
			FB.XFBML.parse(oLike);
			
		}
		
	}
	
}

function resizeTitles() {
	
	$("div#home .inner > span.title").each( function() {
		
		var iLength = $(this).text().length;
		
		if ( iLength > 33 && iLength < 43 )
			$(this).css('font-size', '13px');
		else if ( iLength >= 43 )
			$(this).css('font-size', '10px');
		
	} );

}

function scrollRight() {
	
	$Win = $(window);
	
	strLeft = $Win.width() + "px";
	strTop = window.scrollY + "px"
	
	$('html,body').stop().animate( {
	
		  scrollTop: strTop,
		  
		  scrollLeft: strLeft
	
	} )
	
}

function setCookie(strName, value, intDays) {
	
	var objDate = new Date();
	
	objDate.setDate( objDate.getDate() + intDays );
	
	var strValue = escape(value) + ( (intDays == null) ? "" : "; expires=" + objDate.toUTCString() );
	
	document.cookie = strName + "=" + strValue;
	
}

function sleep(iMs) {
	
	iMs += new Date().getTime();
	
	while (new Date() < iMs) {}
	
}

function toggleBox(objElement, bInstant) {
	
	var $Body,
		$Element = $(objElement),
		$Master;
	
	if ( $Element.is("a") )
		$Master = $Element.parent().parent().parent();
	else if ( $Element.is("div.box-1") )
		$Master = $Element;
		
	$Body = $Master.find('.inner');
	
	var $Image = $Master.find('.title img');
	
	var strID = $Master.attr("id");
	
	if ( $Body.is(":visible") ) {
		
		if (bInstant)
			$Body.hide()
		else
			$Body.slideUp();
		
		setCookie(strID, "min", 1000);
		
		$Image.attr("src", "img/restore.png");
		
	} else {
		
		if (bInstant)
			$Body.show()
		else
			$Body.slideDown();
		
		setCookie(strID, "max", 1000);
		
		$Image.attr("src", "img/minimize.png");
		
	}
	
}

function track(strURL) {
	
	_gaq.push( ['_trackPageview', "/" + strURL] );
	
}

function ucwords(str) {
	
	return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
		
        return $1.toUpperCase();
		
    } );
	
}

( function ($) {
	
	$.fn.glow = function (iTime) {
		
		return this.each(function () {
			
			jQuery(this).animate( {textShadow: "#ffffff 0 0 10px "} , ( (iTime) ? iTime : 1000 ) )
		   				.animate( {textShadow: "#000000 0 0 0"} , 500 )
			
		} );
		
	};
	
} )(jQuery);

( function() {
	
	var $Warning = $("div.dialog.warning");
	
	var sndYeah = new buzz.sound("etc/yeah.wav");
	
	if (!bI) {
		
		$( function() {
			
			window.f = new flux.slider("#slider", {
				
				controls: true,
				
				pagination: false
				
			} );
		
		} );
		
	}
	
	window.onload = function() {
		
		fbInfo();
		
		// I'm trying to tell Facebook they have a problem somewhere in the interactivity of their widgets.
		// The URL of the bug report is http://developers.facebook.com/bugs/260258890721446 but this girl
		// seems to think I'm wrong.  The next bit is a bit of debugging for this.
		
		FB.Event.subscribe('auth.authResponseChange', function(response) {
			
			//fbInfo();
			
			var sPreposition;
			
			console.log("Event fired.  Response object:");
			
			console.log(response);
			
			if (response.status == "connected")
				sPreposition = "into";
			else
				sPreposition = "out of";
			
			console.info("You are currently logged " + sPreposition + " Facebook for the site.");
			
		} );
		
	}
	
	$(document).ready( function() {
		
		( function() {
			
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			
			po.src = 'https://apis.google.com/js/plusone.js';
			
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			
		} )();
		
		window.onpopstate = function(event) {
			
			var objState = event.state;
			
			if (objState && objState.page)
				loadContent(objState.page, ( (!bLoaded) ? true : false ) );
			
			if (!bLoaded)
				bLoaded = true;
			
		}
		
		if (bI)
			window.scrollTo(0, 1);
		
		$.each( $("div.box-1"), function() {
			
			var strID = $(this).attr("id");
			
			if ( getCookie(strID) == "min" )
				toggleBox(this, true);
			
		} );
		
		if (!bI) {
			
			$Archive.waypoint( function(event, direction) {
				
				var bConditions = (direction === "down") && ( oArchive.isOpen() ) && ( $(window).width() >= 1110 );
				
				$(this).toggleClass("sticky", bConditions);
				
			} );
			
		}
		
		if ( !getCookie("gotcha") ) {
			
			$Warning.dialog( {
				
				autoOpen: false,
				
				buttons: {
					
					"Gotcha" : function () {
						
						$(this).dialog("close");
						
					}
					
				},
				
				close: function() {
					
					setCookie("gotcha", 1, 1000);
					
				},
				
				dialogClass: "ui-error",
				
				resizable: false,
				
				title: "Warning"
				
			} );
			
		}
		
		if (!bI) {
			
			if ( !flux.browser.supportsTransitions && !getCookie("gotcha") )
				$Warning.dialog("open");
			
		}
		
		$("a").hover( function() {
			
			// Kind of a janky CSS fix.
			
			$(this).has("img").css("border", "none");
		
		} );
		
		if (!Modernizr.csstransitions) {
			
			// Fall back to jQuery's animate() if CSS transitions are not supported.
			
			$(".icon").hover( function() {
				
				$(this).animate( {
					
					opacity: 1
			
				} )
			
			} ).mouseleave( function() {
				
				$(this).animate( {
					
					opacity: 0.7
					
				} )
			
			} );
			
		}
		
		$("#logo").click( function(e) {
			
			sndYeah.play();
			
			sleep(2000);
			
		} );
			
		$("#nav a").click( function(event) {
			
			var strHREF = $(this).attr("href");
			var strPage = strHREF.replace(strPrefix, "");
			
			if (strCurrent != strPage) {
				
				// As long as we're not currently on the page we just clicked on...
				
				strCurrent = strPage;
				
				if ( oArchive.isOpen() )
					oArchive.toggle();
				
				loadContent(strHREF, true);
				
				track(strPage);
				
			}
			
			event.preventDefault();
			
		} );
		
		$(document).ajaxStart( function() {
            
			$(".load").animate( {
				
				opacity: 1
				
			} );
			
        } );
		
		$(document).ajaxStop( function() {
            
			$(".load").animate( {
				
				opacity: 0
				
			} );
			
        } );
		
	} );
	
}() );

if (!bI) {
	
	var objOpts = {
		
		slideshowGroup: 'gallery',
		wrapperClassName: 'dark',
		dimmingOpacity: 0.8,
		align: 'center',
		transitions: ['expand', 'crossfade'],
		fadeInOut: true,
		wrapperClassName: 'borderless floating-caption',
		marginLeft: 100,
		marginBottom: 80,
		numberPosition: 'caption'
		
	};
	
	hs.addSlideshow( {
		
		//slideshowGroup: 'group1',
		
		interval: 5000,
		
		repeat: false,
		
		useControls: true,
		
		overlayOptions: {
			className: 'text-controls',
			position: 'bottom center',
			relativeTo: 'viewport',
			offsetY: -60
		},
		
		thumbstrip: {
			position: 'bottom center',
			mode: 'horizontal',
			relativeTo: 'viewport'
		}
		
	} );
	
	//hs.captionEval = 'this.a.title';
	hs.maxHeight = $(window).height() - 300;
	hs.showCredits = false;
	hs.transitions = ['expand', 'crossfade'];
	
	hs.extend(hs.Expander.prototype, {
			
		fix: function(on) {
			var sign = on ? -1 : 1,
				stl = this.wrapper.style;
	
			if (!on) hs.getPageSize(); // recalculate scroll positions
			
			
			hs.setStyles (this.wrapper, {
				position: on ? 'fixed' : 'absolute',
				zoom: 1, // IE7 hasLayout bug,
				left: (parseInt(stl.left) + sign * hs.page.scrollLeft) +'px',
				top: (parseInt(stl.top) + sign * hs.page.scrollTop) +'px'
			});
	
			if (this.outline) {
				stl = this.outline.table.style;
				hs.setStyles (this.outline.table, {
					position: on ? 'fixed' : 'absolute',
					zoom: 1, // IE7 hasLayout bug,
					left: (parseInt(stl.left) + sign * hs.page.scrollLeft) +'px',
					top: (parseInt(stl.top) + sign * hs.page.scrollTop) +'px'
				});
	
			}
			this.fixed = on; // flag for use on dragging
		},
		
		onAfterExpand: function() {
			this.fix(true); // fix the popup to viewport coordinates
		},
	
		onBeforeClose: function() {
			this.fix(false); // unfix to get the animation right
		}
		
	} );

}