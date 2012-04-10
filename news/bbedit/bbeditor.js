function OnNewsSubmit(id)
{

}

// TODO: Figure out how to add a link to the Facebook URL containing their post and put it in the bottom
// right hand corner of the article box for each one.

var sAction;

var bTestMode = false;

var sFB = "ikonstyle";
var sName = "Ikon";

function SocialPost() {
	
	// I forgot all about opener; what a handy little fucker!
	
	var $Form = $("form[name=addnewsfrm]");
	var $Load = $(".fb-load");
	
	var bSubmit = true;
	
	sAction = opener.getUrlVars(window.location.search)['a'];
	
	if ( opener.fbLoggedIn() && sAction == "addnews" ) {
		
		$Load.animate( {
			
			opacity: 1
			
		} );
		
		// We are assuming that an admin is viewing this site henceforth they are on Facebook
		// so we can make posting on the site and posting on FB synonymous.  This is only in effect
		// on the add news page, though.
		
		opener.FB.api("/me/accounts/", function(response) {
			
			var sToken = "";
			
			for (i in response.data) {
				
				// If the name of the page is the name of the band, store the access token so
				// we can post as the page.
				
				if ( response.data[i]['name'].toLowerCase() == ( (bTestMode) ? sName.toLowerCase() : opener.oPHP.const.FB_DOMAIN.toLowerCase() ) )
					sToken = response.data[i]['access_token'];
				
			}
			
			if (sToken != "") { 
				
				// if there's no token, don't do any of the following...
				
				var sTitle = document.getElementById("headline").value;
				var sStory = document.getElementById("shortstory").value;	//document.querySelector("textarea#shortstory")?
				
				// Filter all tags so the Facebook post doesn't end up looking strange.
				
				var rTags = /(<([^>]+)>)/ig;
				var rBB = /\[(\w+)[^w]*?](.*?)\[\/\1]/g;
				
				sTitle = sTitle.replace(rTags, "").replace(rBB, "$2");
				
				sStory = sStory.replace(rTags, "").replace(rBB, "$2");
				
				if (sStory.length == 0 && sTitle.length == 0)
						
					return false;
					
				else {
					
					// Other possible options: message, picture, link, name, caption, description, source parameters
					
					var wallPost = {
						
						access_token: sToken,
						
						caption: opener.oPHP.const.NAME + ' News',
						
						// Add some funny random description generator here
						
						description: opener.oPHP.const.TEXT_READ,
						
						icon: opener.oVars.sDomain + "/" + opener.oPHP.const.DIR_IMG + "/news.gif",
						
						// I think this next bit may need a bit of modification to always be right
						// but it works.  Could use oPHP.const.DOMAIN.
						
						link: opener.oVars.sDomain + $("#vars").val(),
						
						message: sStory,
						
						name: "Read \"" + sTitle + "\"",
						
						picture: opener.oVars.sDomain + "/" + opener.oPHP.const.DIR_IMG + "/news.png",
						
						type: 'article'
						
					}
					
					opener.FB.api('/' + ( (bTestMode) ? sFB : opener.oPHP.const.FB_DOMAIN ) + '/feed', 'post', wallPost, function(response) {
						
						if ( (!response || response.error) ) {
						
							console.warn('Facebook error #' + response.error.code + ' occurred: ' + response.error.message );
							
							$("#fb").html(response.error.message + ".<br /><br /><em>Do you want to forego posting to <strong>Facebook</strong> and just post to this site?</em>").dialog( {
								
								resizable: false,
								
								modal: true,
								
								title: 'Facebook Error #' + response.error.code,
								
								buttons: {
									
									'Yeah': function() {
										
										$(this).dialog("close");
										
										$Form.submit();
										
									},
									
									'No Way!': function() {
										
										$(this).dialog("close");
										
									}
									
								}
								
							} );
							
						} else {
							
							var sID = response.id;
							var sLink = ( (bTestMode) ? opener.oPHP.const.FB_URL + sFB : opener.oPHP.const.URL_FB ) + "/posts/" + sID.split("_")[1];
							
							var iCnt;
							var iTime = 3;
							 
							countDown = function() {
								
								iTime--;
								
								$(".counter").text(iTime);
								
								if (iTime == 0)
									$("#fb").dialog("close");
								
							}
							
							console.info('Successfully posted to your Facebook Page!');
							console.info('Post ID (for Facebook): ' + sID);
							
							$("#fb").html("The URL for your new Facebook post is:<br /><a href=\"" + sLink + "\" target=\"_blank\">" + sLink + "</a>.<br /><br /><em>Press <strong>OK</strong> to post to the current site or it will happen automatically in <span class='bold cerulean counter'>" + iTime + "</span> seconds.</em>").dialog( {
								
								resizable: false,
								
								modal: true,
								
								title: 'Facebook Post Link',
								
								buttons: {
									
									OK: function() {
										
										$(this).dialog("close");
										
									}
								
								},
								
								close: function(event, ui) {
									
									$Form.submit();
									
									clearInterval(iCnt);
									
								},
								
								open: function(event, ui) {
									
									iCnt = setInterval(countDown, 1000);
									
								}
								
							} );
							
						}
						
					} );
					
				}
				
			}
						
			$Load.animate( {
				
				opacity: 0
				
			} );
			
		} );
		
	} else
		return true;
	
	return false;
	
}

function MakeUrlCode()
{
  var url = prompt('Enter the url to the web page','http://');
  var title = prompt('Enter a name for the web page');
  if (url==null || url=='http://'){alert('You must enter a valid url');return ''; exit;}
  if (title==''){alert('You must enter a title');return ''; exit;}
  return '[url='+url+']'+title+'[/url]';
}

function MakeVIMGCode()
{
  var url = prompt('Enter the url to the image','http://');
  var title = prompt('Enter a name for the image');
  if (url==null || url=='http://'){alert('You must enter a valid url');return ''; exit;}
  if (title==''){alert('You must enter a title');return ''; exit;}
  return '[vimg='+url+']'+title+'[/vimg]';
}

function InsertBBCode(tg, bbcode)
{
  var target = document.getElementById(tg);
  if (document.selection)
   {
    target.focus();
    sel = document.selection.createRange();
    sel.text = bbcode;
   }
   else if (target.selectionStart || target.selectionStart == 0)
   {
    var startPos = target.selectionStart;
    var endPos = target.selectionEnd;
    target.value = target.value.substring(0, startPos)+ bbcode+ target.value.substring(endPos, target.value.length);
   } else 
   {
    target.value += bbcode;
   }
   target.focus();
}

function InsertImage(tg,url)
{
 InsertBBCode(tg,'[img]'+url+'[/img]'); 
}
