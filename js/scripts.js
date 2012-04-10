var oPHP = json( "inc/json.php");

if (!window.oVars)
	window.oVars = {};

window.oVars = {
	
	$Archive: $("div#archive-popout"),
	$Social: $("div#social-popout"),
	
	aMonths: [
		
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"
		
	],
	
	bGlown: false,
	bI: ( navigator.platform.indexOf("iPhone") != -1 ),
	bIE: $.browser.msie,
	bLoaded: false,
	bFirstParse: true,
	
	// General speed of UI specialness.
	
	iSpeed: 1000,
	
	sCurrent: extractPage(),
	
	// Ok, wtf is the logic here?  I have no idea why I am not using the PHP
	// for this... what is wrong with me?
	
	sDomain: "http://" + window.location.host,
	
	sOrigin: window.location.pathname.replace("/", ""),
	sNB: "&nb=true",
	sPG: "pg",
	sPrefix: function() { return "?" + this.sPG + "="; },
	
	oModal: {
		
		resizable: false,
		
		modal: true,
		
		buttons: {
			
			'OK': function() {
				
				$(this).dialog("close");
				
			},
			
			'Cancel': function() {
				
				$(this).dialog("close");
				
			}
			
		}
		
	},
	
	oArchive: {
		
		bOpen: false,
		
		sClosed: "0px",
		
		sOpen: "-115px",
		
		isOpen: function() {
			
			return this.bOpen;
			
		},
		
		toggle: function() {
			
			var sRight = $(oVars.$Archive.selector).css("right");
			
			$(oVars.$Archive.selector).removeClass("sticky");
			
			if (!this.bOpen) {
				
				$(oVars.$Archive.selector).css("visibility", "visible").animate(
					
					{ right: this.sOpen },
					
					500,
					
					"easeOutBack",
					
					function() {
						
						$(oVars.$Archive.selector).css("padding-left", "6px")
										 .css("z-index", 0);
						
					}
				
				);
				
				scrollRight();
				
				this.bOpen = true;
				
			} else {
				
				$(oVars.$Archive.selector).css("visibility", "hidden").css("padding-left", "20px");
				$(oVars.$Archive.selector).css("right", this.sClosed);
				$(oVars.$Archive.selector).css("z-index", -1);
				
				this.bOpen = false;
				
			}
			
		}
		
	},
	
	oNB: { nb: "true" },
	oNHF: { nhf: "true" }
	
};

String.prototype.trim = function() {
	
	return this.replace(/^\s+|\s+$/g, '');
	
}

function addBodyClass(sClass) {
	
	// I implemented these two functions because the iPhone site was getting affected by me
	// setting the body's class throughout the code (I also was using that class to determine
	// the site's orientation [landscape/portrait]).  I've decided to use the HTML element
	// for these purposes but may revert, so these stay.
	
	document.body.setAttribute("class", document.body.getAttribute("class") + " " + sClass)
	
}

function removeBodyClass(sClass) {
	
	document.body.setAttribute("class", document.body.getAttribute("class").replace(/\btesting\b/g, "") )
	
}

function addEvent(sType, func) {
	
	if (window.addEventListener)
		window.addEventListener(sType, func, false); 
	else if (window.attachEvent)
		window.attachEvent("on" + sType, func);

}

function addFriend(sID) {
	
	FB.ui( {
		
		method: 'friends',
		
		id: sID
		
	} );
	
}

var Facebook = function() {
	
	// Add all FB functions in here.  Much neater.
	
	this.a = 1;
	
}

function fbLinks() {
	
	// Expansion, ahoy!
	
	var bMenuDown = true;
	
	if ( fbLoggedIn() ) {
		
		if ( $("#home").length && !$(".links .admin").length && typeof FB !== "undefined" ) {
			
			var sID;
			
			if (FB._userStatus == "connected") {
			//FB.getLoginStatus( function(response) {
				
				sID = FB._userID;//response.authResponse.userID;
				
				if ( oPHP.const.ID_FB_ADMINS.indexOf(sID) != -1 ) {
					
					// One of the Facebook admins is using the page, so display some links for them.
					
					var $Links = $(oPHP.const.TEXT_ADMIN_LINKS);
					
					// For some reason the beginning · doesn't get registered when encapsulated in a jQuery object... weird.
					
					// Make sure this thing doesn't get added twice like it has before. 
					
					if ( !$(".links .admin").length ) {
						
						$(".box-1 .title .links").append($Links)
												 .animate( {
													 
													 width: 100
													 
												 }, oVars.iSpeed, "easeOutBounce", function() {
													
													$(this).find(".gear").fadeIn(oVars.iSpeed);
													 
												 } );
						
						$(".box-1 .title .gear").click( function() {
							
							// I would do this with slideToggle() but it just doesn't suffice.
							
							var sTitle = "";
							
							if (bMenuDown) {
								
								// We are sliding the menu up.
								
								$("#admin-menu").slideUp( (oVars.iSpeed / 2) , "" , function() {
									
									$(".menu-clicked").fadeOut();
									
								} );
								
								sTitle = oPHP.const.TEXT_SHOW_MENU;
								
								bMenuDown = false;
								
							} else {
								
								// Sliding menu down.
								
								$(".menu-clicked").show();
								
								$("#admin-menu").slideDown();
								
								sTitle = oPHP.const.TEXT_HIDE_MENU;
								
								bMenuDown = true;
								
							}
							
							$(this).attr("title", sTitle);
							
						} );
						
					}
					
					if (!oVars.bGlown) {
						
						$Links.glow();
						
						oVars.bGlown = true;
						
					}
					
				}
				
			}// );
			
		} else if ( $(".connect").length || $("#.add").length || $(".friends").length ) {
			
			// If you log in and you're on the contact or bio page, be sure to run this special function afterwards.
			
			friendButton(oPHP.const.TEXT_FRIEND, ( $("#bio").length ? oPHP.const.FB_UID : null ) );
			
		}
		
	} else {
		
		if ( $("#home").length)
		
			$(".links").find(".admin").remove().end().animate( {
				
				width: "auto"
				
			} );
			
		else if ( $("#contact").length)
			
			friendButton(oPHP.const.TEXT_FRIEND, oPHP.const.FB_UID);	// Could run without arguments?
			
	}
	
}

function adjustClassHeight(sClass, intAdjust) {
	
	if (intAdjust === undefined) intAdjust = 0;
	
	var tmp_list = document.getElementsByClassName(sClass);
	
	if (tmp_list.length != 0) {
		
		var tmp_max = getElemHeight( tmp_list[0] );
		
		for(var i = 0; i < tmp_list.length; i++)
			tmp_max = tmp_max < getElemHeight(tmp_list[i]) ? tmp_max = getElemHeight(tmp_list[i]) : tmp_max;
		
		for(var i = 0; i < tmp_list.length; i++)
			tmp_list[i].style.height = (tmp_max + intAdjust) + 'px';
		
	}
	
}

function afterLoad(sID, fFunc) {
	
	if( document.getElementById(sID) == null )
		setTimeout(arguments.callee.name + '("' + sID + '")', 300);
	else
		eval(fFunc);
	
}

function cleanUrlConvert(sURL, mType) {
	
   /*
	*  Function cleanUrlConvert
	*  
	*  Parameters:
	*-------------------------------*
	*	sURL		mType			*				   *
	*--------------------------------------------------*
	*  String		String/Integer					   *
	*--------------------------------------------------*
	*	The URL		Denotes which way to convert.  It  *
	*				can be "to," "from," or optionally *
	*				be 0 or 1 respectively.			   *
	*--------------------------------------------------*
	* Returns: A string containing the converted URL.  *
	*--------------------------------------------------*
	
	*/
	
	var sResult;
	
	var aVars = [];
	
	if (!sURL || !mType)
		return undefined;
	
	if (mType == 1 || mType == "from") {
		
		var sAction;
		
		// For the year and month combo.
		
		var sMY = "";
		
		aVars = sURL.split("/");
		
		// Get rid of "news."
		
		aVars.shift();
		
		// Remove "rng," too, if it's there.
		
		if ( aVars.indexOf("rng") != -1 )
			aVars.shift();
		
		// Prefix
		
		sResult = "index.php?pg=archive&newsarch=";
		
		// If they're there, add the year and the month together then add that to
		// the end result.
		
		if ( aVars[0] )
			sMY += aVars[0];
		
		if ( aVars[1] )
			sMY = aVars[1] + sMY;
		
		sResult += sMY;
		
		// Add ID if it's there and figure out whether to show a specific post or just the monthly list.
		
		if (aVars.length > 2) {
			
			if ( aVars[aVars.length - 1].indexOf("-") != -1 ) {
				
				sAction = "getnews";
				
				sResult += "&xnewsrange=" + aVars[2];
				
			} else {
				
				sAction = "fullnews";
				
				sResult += "&newsid=" + aVars[2];
				
			}
			
		} else
		
			sAction = "getnews";
		
		// I might want to consider either changing the order that these parameters appear because I have it
		// in another sequence below.
		
		sResult += "&xnewsaction=" + sAction;
		
	} else if (mType == 0 || mType == "to") {
		
		var sMonth, sYear;
		
		aVars = getUrlVars(sURL);
		
		sResult = "news";
		
		if ( aVars['xnewsrange'] )
			sResult += "/rng";
		
		sResult += "/";
		
		if ( aVars['newsarch'] ) {
			
			sMonth = aVars['newsarch'].substr(0, 2);
			
			sYear = aVars['newsarch'].substr(-4);
			
			sResult += sYear + "/" + sMonth + "/";
			
			if ( aVars['newsid'] )
				sResult += aVars['newsid'];
			else if ( aVars['xnewsrange'] )
				sResult += aVars['xnewsrange'];
			
		}
		
	}
	
	return sResult;
		
}

function extractPage(sPart) {
	
	// This is pretty ugly...
	
	// return window.location.search.replace(/(.+)=(.+)&(.+)/, '$2');
	
	var sFirst = (window.location.pathname.replace("/", "")).split("&")[0];
	
	var sPage = sFirst.substr( sFirst.lastIndexOf("/") );
	
	if ( sFirst.indexOf(oPHP.const.DIR_NEWS) == 0 ) {
		
		// If "news" is at the beginning of the current page, you know we're in the archive.
		
		return "archive";
		
	} else if (sPage == "" || sPage == "/") {
		
		return "home";
		
	} else {
		
		if (sPart == "ending") {
			
			var aSplit = sFirst.split("/");
			
			return aSplit[aSplit.length - 1];
			
		} else {
			
			if (sFirst == "index.php")
				return window.location.search.split("&")[0].replace("?pg=", "");
			else if (sFirst == "")
				return "home";
			else
				return sFirst;
			
		}
		
	}
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
		
		FB.api('/me', function(response) {
			
			$(".fb-login-button .fb_button_text").text("Log Out");
			
			var sName = response.first_name;
			
			$El = $("#fb-name");
			
			sURL = response.link;
			
			$El.html("Yo, <em><strong>" + sName + "</strong>!</em>").css("padding", "12px 15px 0 5px");
					
			FB.api('/me/picture', function(response) {
				
				$El = $("#fb-picture");
				
				$El.one("load", function() {
					
					$(".fb-load").animate( {
						
						opacity: 0
						
					} );
					
				} ).attr("src", response);
				
				$El.css("width", "31px")
				   .wrap('<a href="' + sURL + '" target="_blank"></a>');
				
			} );
			
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
		
	}
	
	if ( navigator.userAgent.indexOf("WebKit") != -1 ) {
		
		// Why is this necessary for Chrome, etc...?
		
		$(".fb-load").animate( {
			
			opacity: 0
			
		} );
		
	}
	
	fbLinks();
	
}

function fbLoggedIn() {
	
	var bLoggedIn = false;
	
	if (typeof FB !== "undefined") {
		
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
		
	}
	
	return bLoggedIn;
	
}

function friendButton(sEl, sID) {
	
	// TODO: make it so that it detects whether there is a friend request pending.
	
	var $El = $(sEl);
	
	var sName;
	
	if (sID == null || !sID) {
		
		// Use the existing ID if there is not one specified as an argument.
		
		sID = $El.attr("onclick").replace(/addFriend\('(.*)'\);/, '$1');
		
	}
	
	if ( fbLoggedIn() ) {
		
		FB.api(sID, function(response) {
			
			sName = response.first_name;
			
			if ( !friends(sID) )
				
				$El.attr("onclick", "addFriend('" + sID + "');")
				   .attr("title", "Add " + sName + " as a Friend on Facebook")
				   .removeClass("friends connect")
				   .addClass("add");
				
			else
				
				$El.attr("onclick", "")
				   .attr("title", "You two are already Facebook friends!")
				   .removeClass("add connect")
				   .addClass("friends");
			
		} );
		
	} else {
		
		$El.attr("onclick", "")
		   .attr("title", "Connect to Facebook for this Feature")	// Maybe more specific like "to Add this Person/Friend."
		   .removeClass("add friends")
		   .addClass("connect");
		
	}
		
}

function friends(sID) {
	
	var bFriends = false;
	
	if ( FB && fbLoggedIn() ) {
		
		$.ajax( {
			
			async: false,
			
			url: "https://graph.facebook.com/me/friends/" + sID,
			
			dataType: 'json',
			
			data: {
				
				access_token: FB.getAccessToken()
				
			},
			
			success: function(response) {
				
				if (response.data[0]) {
					
					if (response.data[0].name)
						bFriends = true;
					
				}
				
			} 
			
		} );
		
		return bFriends;
		
	}
		
	return "undefined";
	
}

// END FACEBOOK FUNCTIONS (majority?)

function fileExists(url) {
	
	if (url) {
	
	var req = this.window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
	
	if (!req)
		throw new Error('XMLHttpRequest not supported');
	
	req.open('HEAD', url, false);
	req.send(null);
	
	if (req.status == 200)
		return true;
	
	return false;
	
	}
	
}

function getCookie(sName) {
	
	var i, sCName, y, arrCookies = document.cookie.split(";");
	
	for (i = 0; i < arrCookies.length; i++) {
		
		sCName = arrCookies[i].substr(0, arrCookies[i].indexOf("=") );
		sValue = arrCookies[i].substr( arrCookies[i].indexOf("=") + 1 );
		
		sCName = sCName.replace(/^\s+|\s+$/g, "");
		
		if (sCName == sName)
			return unescape(sValue);
			
	}
	
}

function getElemHeight(D) {
	
	return Math.max( Math.max(D.scrollHeight), Math.max(D.offsetHeight), Math.max(D.clientHeight) );
	
}

function getUrlVars(sVars) {
	
	var arrVars = {};
	
	var objVar;
	
	if (sVars == null)
		objVar = window.location.search;
	else
		objVar = sVars;
	
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

function loadContent(sURL, bPush, bReplace) {
	
	var $Box = $("#loaded");
	
	// Uncomment the next comment block if for some reason the pictures
	// section is acting bitchy.
	
	/*
	if (sURL == "pictures") {
		
		oPictures = oVars.oModal;
		
		delete oPictures.buttons.Cancel;
		
		oPictures.buttons.OK = function() {
				
			$(this).dialog('close');
			
		}
		
		$(oPHP.const.TEXT_PICS_PROB).dialog(oPictures).bind(
			
			"dialogclose",
			
			function(event, ui) {
			
			// I do this two ways because if we start out on the pictures page,
			// the bottom half of the back of the site is gone.  Otherwise, all
			// is well.
			
			if ( extractPage() == "pictures" )
				location.replace("home");
			else
				loadContent("home", true, false);
				//history.back();					// This would be ok but ending scripts don't get loaded?
			
			}
			
		);
		
	}
	*/
	
	oVars.sCurrent = sURL.split("&")[0];
	
	// Closes any picture open in Colorbox.
	
	if (oVars.sCurrent != "pictures" && oVars.bI)
		$.colorbox.close();
	
	if (!oVars.bIE) {
		
		if (bPush) {
			
			var objState = { page: oVars.sCurrent };
			
			if (bReplace)
				history.replaceState(objState, "", "");
			else
				history.pushState(objState, "", sURL);
			
		} else {}
		
	}
	
	if (!bReplace) {
		
		// Load the page.
		
		$Box.slideUp(100);
		
		$.get(
			
			"index.php" + oVars.sPrefix() + sURL,
			
			oVars.oNHF,
			
			function(sData) {
				
				document.documentElement.className = extractPage("ending");
				
				document.title = oPHP.const.NAME + oPHP.const.TEXT_DIVIDER + ucwords(oVars.sCurrent);
				
				$Box.html(sData).slideDown(oVars.iSpeed, "easeOutBounce");
				
				// Check if the script exists and if it does and has not been loaded, do so.
				
				var sFile = oPHP.const.DIR_JS_LOGIC + "/" + sURL;
				
				// Reload the Facebook widgets for the current page.
				
				reloadWidgets();
				
			}
			
		);
		
	}
	
}

function possessive(sValue) {
	
	var sWord = sValue;
	
	if ( sWord.substr(-1) == 's' )
		sWord += "'";
	else
		sWord += "'s";
	
	return sWord;
	
}

function read(sURL) {
	
	if ( FB._userStatus == "connected" ) {
		
		FB.api( ( '/me/news.reads?article=' + sURL + "&access_token=" + FB.getAccessToken() ) , 'post',
		
		function(response) {
			
			if (!response || response.error) {
				console.debug('Error with reading article at URL ' + sURL + ':');
				console.debug(response);
			} else
				console.debug('The article at URL ' + sURL + ' was successfully read.');
			
		} );
		
	}
	
}

function relativeTime(sTime) {
	
	var values = sTime.split(" ");
	
	sTime = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
	
	var parsed_date = Date.parse(sTime);
	
	var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
	
	var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
	
	delta = delta + (relative_to.getTimezoneOffset() * 60);
	
	if (delta < 60)
		return 'less than a minute ago';
	else if (delta < 120)
		return 'about a minute ago';
	else if ( delta < (60 * 60) )
		return (parseInt(delta / 60)).toString() + ' minutes ago';
	else if (delta < (120 * 60))
		return 'about an hour ago';
	else if (delta < (24 * 60 * 60))
		return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
	else if (delta < (48 * 60 * 60))
		return '1 day ago';
	else
		return (parseInt(delta / 86400)).toString() + ' days ago';
	
}

function reloadAddThis() {
	
	//if (!oVars.bI) {
		
		// I never thought these plugins would be so deficient that I would have to hand-code
		// basically all the methods to handle them.  Yeesh.
		
		// TODO: I might want to employ $.each considering I'm using jQuery.
		
		var i = 0,
			aTbxs = document.getElementsByClassName("addthis_toolbox");
		
		for (var oTbx in aTbxs) {
			
			oCurrent = $(".addthis_toolbox").get(i);
			oCounter = $(".addthis_toolbox .addthis_counter").get(i);
			
			addthis.toolbox(oCurrent);
			addthis.counter(oCounter);
			
			i++;
			
		}
		
	//}
	
}

function reloadWidgets(sNewURL) {
	
	if (!oVars.bI) {
		
		$(oVars.$Social.selector).find(".inner").fadeOut(oVars.iSpeed, "", function() {
			
			var rData = /data-(href|url)=\"(.*)\"/;
			
			var sPath = window.location.pathname,
				sURL;
			
			sURL = 'http://' + window.location.host;
			
			if (!sNewURL) {
				
					sURL += sPath;
					
					if ( sPath.indexOf("home") == -1 && oVars.sCurrent == "home" )
						sURL += "home";
					
			} else
				sURL += "/" + sNewURL;
			
			//console.debug(sURL)
			
			var sData = "data-$1=\"" + sURL + "\"";
			
			var $Comments = $(".fb-comments");
			var $Like = $("#fb-like");
			var $Plus = $("#___plusone_0");
			var $Twtr = $( oPHP.const.CODE_TWTR.replace(rData, sData) );
			
			$Comments.html( oPHP.const.CODE_COMMENTS.replace(rData, sData) );
			$Like.html( oPHP.const.CODE_LIKE.replace(rData, sData) );
			$Plus.html( oPHP.const.CODE_PLUS.replace(rData, sData) );
			
			// Reload Facebook.
			
			FB.XFBML.parse( $Like.get(0) );
			FB.XFBML.parse( $Comments.get(0) );
			
			// Reload Plus One.
			
			gapi.plusone.go("social-popout");
			
			// Reload and its nasty overly-complicated ayuss.
			
			$(".twitter-share-button").remove();
			$("#tweet").append($Twtr);
			
			twttr.widgets.load();
			
		} ).delay(oVars.iSpeed).fadeIn(oVars.iSpeed);
				
	}
	
}

function resizeTitles() {
	
	$("div#home .inner > span.title").each( function() {
		
		var iLength = $(this).text().length;
		
		if ( iLength > 27 && iLength <= 31 )
			$(this).css('font-size', '14px');
		else if ( iLength > 31 && iLength < 43 )
			$(this).css('font-size', '12px');
		else if ( iLength >= 43 )
			$(this).css('font-size', '10px');
		
	} );

}

function scrollRight() {
	
	$Win = $(window);
	
	sLeft = $Win.width() + "px";
	sTop = window.scrollY + "px"
	
	$('html,body').stop().animate( {
	
		  scrollTop: sTop,
		  
		  scrollLeft: sLeft
	
	} )
	
}

function setCookie(sName, value, intDays) {
	
	var objDate = new Date();
	
	objDate.setDate( objDate.getDate() + intDays );
	
	var sValue = escape(value) + ( (intDays == null) ? "" : "; expires=" + objDate.toUTCsing() );
	
	document.cookie = sName + "=" + sValue;
	
}

function sleep(iMs) {
	
	iMs += new Date().getTime();
	
	while (new Date() < iMs) {}
	
}

/*

// Ancient idea (minimize buttons for boxes).

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
	
	var sID = $Master.attr("id");
	
	if ( $Body.is(":visible") ) {
		
		if (bInstant)
			$Body.hide()
		else
			$Body.slideUp();
		
		setCookie(sID, "min", 1000);
		
		$Image.attr("src", "img/restore.png");
		
	} else {
		
		if (bInstant)
			$Body.show()
		else
			$Body.slideDown();
		
		setCookie(sID, "max", 1000);
		
		$Image.attr("src", "img/minimize.png");
		
	}
	
}

*/

function track(sURL) {
	
	_gaq.push( ['_trackPageview', "/" + sURL] );
	
}

function ucwords(str) {
	
	return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
		
        return $1.toUpperCase();
		
    } );
	
}

( function ($) {
	
	$.fn.glow = function(sColor, iTime, iRadius) {
		
		if (!sColor)
			var sColor = "FFFFFF";
		
		if (!iTime)
			var iTime = oVars.iSpeed;
			
		if (!iRadius)
			var iRadius = 10;
		
		sOriginal = jQuery(this).css("color");
		
		sColor = "#" + sColor;
		
		return this.each( function () {
			
			jQuery(this).animate( {
							
							color: sColor,
							
							textShadow: sColor + " 0 0 " + iRadius + "px"
							
						}, iTime).animate( {
							
							color: sOriginal,
							
							textShadow: "#000000 0 0 0"
							
						}, 500);
			
		} );
		
	};
	
} )(jQuery);

( function() {
	
	var $Warning = $("div.dialog.warning");
	
	var sndYeah = new buzz.sound("etc/yeah.wav");
	
	if (!oVars.bI) {
		
		$( function() {
			
			window.f = new flux.slider("#slider", {
				
				controls: true,
				
				pagination: false
				
			} );
		
		} );
		
	}
	
	$(document).mousemove( function() {
		
		// This is so sheisty but it works.
		
		if ( typeof FB !== "undefined" ) {
			
			// Previously was using a variable bFBLoad to tell.  I think this way is better.
			
			fbInfo();
			
			$(document).unbind("mousemove");
			
		}
		
	} );
	
	$( function() {
		
		// MAIN:
		
		// Let's run ASAP to get it over with.
		
		// Google first.
		
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-27838426-1']);
		_gaq.push(['_trackPageview']);
		
		( function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		} )();
		
		( function() {
			
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			
			po.src = 'https://apis.google.com/js/plusone.js';
			
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			
		} )();
		
		// Twitter second.
		
		!function(d, s, id) {
			var js,
			fjs = d.getElementsByTagName(s)[0];
			if (!d.getElementById(id)) {
				js = d.createElement(s);
				js.id = id;
				js.src = "//platform.twitter.com/widgets.js";
				fjs.parentNode.insertBefore(js, fjs);
			}
		} (document, "script", "twitter-wjs");
				
		window.onpopstate = function(event) {
			
			var objState = event.state;
			
			if (objState && objState.page)
				loadContent(objState.page, ( (!oVars.bLoaded) ? true : false ) );
			
			if (!oVars.bLoaded)
				oVars.bLoaded = true;
			
		}
		
		if ($.browser.msie && $.browser.version <= 6)
			$(document).pngFix();
		
		if (oVars.bI)
			window.scrollTo(0, 1);
		
		if (!oVars.bI /*&& oVars.sCurrent != "archive"*/) {
			
			var bConditions;
			
			$Archive.waypoint( function(event, direction) {
				
				bConditions = (direction === "down") && ( oVars.oArchive.isOpen() ) && ( $(window).width() >= 1110 );
				
				$(this).toggleClass("sticky right", bConditions);
				
			} );
			
		}
		
		// Considering the archive popout only works with the JavaScript site,
		// I might as well load the appropriate page into it via the same method.
		// This saves a lot of headaches caused by including it with PHP like
		// if I use the same page twice they are both affected by the same
		// variables.  I took it out of the prior conditional because sometimes
		// it's not wise to have it set like this.  If you visit a new page that
		// is not the Archive and you loaded that initially, there should still
		// be a way to open that popout.
		
		$.get("archive.php", function(data) {
			
			$(oVars.$Archive.selector).html(data);
			
		} );
		
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
		
		if (!oVars.bI) {
			
			if ( !flux.browser.supportsansitions && !getCookie("gotcha") )
				$Warning.dialog("open");
			
		}
		
		$("a").hover( function() {
			
			// Kind of a janky CSS fix.
			
			$(this).has("img").css("border", "none");
			$(this).has("img").css("text-decoration", "none");
		
		} );
		
		if (!Modernizr.csstransitions) {
				
				// Fall back to jQuery's animate() if CSS transitions are not supported.
				
				$(".fade-in").hover( function() {
					
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
			
			var sHREF = $(this).attr("href");
			var sPage = sHREF.replace( oVars.sPrefix() , "" );
			
			if (oVars.sCurrent != sPage) {
				
				// As long as we're not currently on the page we just clicked on...
				
				//oVars.sCurrent = sPage;
				
				if ( oVars.oArchive.isOpen() )
					oVars.oArchive.toggle();
				
				loadContent(sHREF, true);
				
				track(sPage);
				
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
		
		// TWEETS LOADER
		
		var $Box = $("#twitter_box");
		var $Ol = $Box.find(".overlay");
		var $Tweets = $("#tweets");
		
		var bEnd = false;
		
		var iCount = 3;
		var iPage = 1;
				
		var appendTweet = function(tweet, id, sDate) {
			
			$("<p />").html(tweet + " ")
					  .append( $('<a class="time" target="_blank">' + relativeTime(sDate) + '</a>')
					  			.attr("href", "http://twitter.com/" + oPHP.const.TWTR_DOMAIN + "/status/" + id)
								.attr("title", "Check tweet on Twitter") )
					  .appendTo( $($Tweets.selector) );
				
		};
		
		var loadTweets = function() {
			
			var sURL = oPHP.const.TWTR_URL + "status/user_timeline/" + oPHP.const.TWTR_DOMAIN + ".json?count=" + iCount + "&page=" + iPage + "&callback=?";
			
			$.ajax( {
				
				url: sURL,
				
				// async is set to false because we want to modify a variable outside the
				// scope of the following callback.
				
				async: false,
				
				dataType: 'json',
				
				complete: function(data, sStatus) {
					
					if (sStatus != "success") {
						
						$Ol.fadeOut();
						
						$Tweets.html("Error loading tweets!");
						
					}
					
				},
				
				success: function(data) {
					
					if (!bEnd) {
						
						// Now, we check if the next page is empty, and if so, stop loading tweets.
						
						$.ajax( {
							
							url: sURL.replace(/page=(\d)*/, "page=" + (iPage + 1) ),
							
							async: false,
							
							dataType: 'json',
							
							success: function(data) {
								
								if (!data.length)
									bEnd = true;
								
							}
							
						} );
						
						// In all my time as a programmer, I don't think I have EVER seen a scenario like this
						// and it made sense to do so: a variable is being checked if it's false inside a block
						// with the same conditions.  I'm not even sure this is appropriate but I'm doing it anyway.
						// Ah, the wonder of events.
						
						if (!bEnd) {
							
							$.each(data, function(i, post) {
								
								appendTweet(post.text, post.id, post.created_at);
								
							} );
							
							$Ol.find(".count").html( ( $("#tweets p").length ) + " total tweets loaded").delay(1000).end().fadeOut();
							
						}
						
					}
					
				}
				
			} );
			
		};
		
		loadTweets();
		
		$Tweets.scroll( function() {
			
			if ( $(this)[0].scrollHeight - $(this).scrollTop() + 2 == $(this).outerHeight() ) {
				
				if (!bEnd) {
					
					iPage++;
					
					if (iPage > 10) {
						
						$Ol.find(".count").html("Maximum tweets loaded.").end().fadeIn(oVars.iSpeed, "", function() { $(this).fadeOut() } );;
						
						return false;
						
					} 
					
					$Ol.fadeIn();
					
					loadTweets();
					
				}
				
			}
		});
		
		//$Tweets.lionbars();
		
		// END LOADER
		
		loadContent(oVars.sCurrent, true, true);
		
		fbInfo();
		
	} );
	
}() );

$(window).load( function() {
	
	// Mainly to do with the social popout and Twitter.
	
	fbInfo();
	
	twttr.widgets.load();
	
	$(oVars.$Social.selector).animate( {
		
		top:  10
		
		}, oVars.iSpeed * 2, "easeOutElastic" , function() {
			
			// Callback after it's done moving down.
			
			$(this).waypoint( function(event, direction) {
				
				bConditions = (direction === "down");
				
				$(this).toggleClass("sticky left", bConditions);
				
			} ).animate( {
				
				// Change background while fading in the interior simultaneously.
				
				backgroundColor: "#CCCCFF"
				
			}, oVars.iSpeed * 2 ).find(".inner")
								 .fadeIn(oVars.iSpeed * 2);
			
		}
		
	);
	/*
	$(window).one("scroll", function() {
		
		if ( $(window).scrollTop() < -2000  )
			$(oVars.$Social.selector).css("top", iTop);
		
	} );
	*/
} );


if (!oVars.bI) {
	
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
	
	hs.captionEval = 'this.a.title';
	hs.align = 'center';
	hs.maxHeight = $(window).height() - 300;
	hs.showCredits = false;
	hs.transitions = ['expand', 'crossfade'];
	
	/*
	
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
			} );
	
			if (this.outline) {
				stl = this.outline.table.style;
				hs.setStyles (this.outline.table, {
					position: on ? 'fixed' : 'absolute',
					zoom: 1, // IE7 hasLayout bug,
					left: (parseInt(stl.left) + sign * hs.page.scrollLeft) +'px',
					top: (parseInt(stl.top) + sign * hs.page.scrollTop) +'px'
				} );
	
			}
			
			this.fixed = on; // flag for use on dragging
			
		},
		
		onAfterExpand: function() {
			this.fix(true); // fix the popup to viewport coordinates
		},
	
		onBeforeClose: function() {
			this.fix(false); // unfix to get the animation right
		}
		
	} );*/

}