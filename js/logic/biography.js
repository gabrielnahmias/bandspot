var $Body = $("#biography .inner");
var $Load = $(".load");
var $Main = $("#biography .main");
var $Solo = $("#biography .solo");
var $Title = $("#biography .title")
var $Add = $Solo.find(oPHP.const.TEXT_FRIEND);

$("#biography img.band").click( function() {
    
	$Load.animate( {
		
		opacity: 1
		
	} );
	
	var bFriends = false;
	
    var sFirst = $(this).attr("title");
    
    var aInfo = oPHP.vars.bios[sFirst].split("|");
    
    var sLast = aInfo[0];
    
    var sName = sFirst + " " + sLast;
    
    var sFBID = aInfo[1];
	
    var sBio = aInfo[2];
    
    var sImage = oPHP.const.DIR_BAND + "/profiles/" + sFirst.toLowerCase() + ".jpg";
    
    $Body.slideUp(100, "", function() { 
		
		$Main.hide();
		
		friendButton($Add.selector, sFBID);
		
		$Solo.find("img.profile").one("load", function() {
			
			$(this).attr("title", $(this).attr("title").replace("<NAME>", possessive(sFirst) ) );
			
			$Solo.find("div.biography").html("<strong>" + sName + "</strong> - " + sBio);
			
			$Solo.find("img.profile").wrap('<a href="' + oPHP.const.FB_URL + '/profile.php?id=' + sFBID + '" target="_blank"></a>');
			
			$Solo.show(0, "", function() {
				
				$Body.slideDown(oVars.iSpeed, "easeOutBounce", function() {
					
					$Solo.find("div.back").show("slide", { direction: "right" }, function() { } );
					
					$Add.slideDown();
					
				} );
				
				$Title.text(sName + "'s Biography");
				
				$Load.animate( {
					
					opacity: 0
					
				} );
				
			} );
			
		} ).attr("src", sImage);
		
	} );
    
    track( extractPage() + "/" + sFirst);
    
} );

$("#biography a.back").click( function() {
    
	$Load.animate( {
		
		opacity: 1
		
	} );
	
	$Solo.find("div.back").hide("slide", { direction: "right" }, function() { } );
	
	$Add.hide("slide", { direction: "right" }, function() {
		
		$Body.slideUp(100, "", function() {
			
			$Solo.hide();
			
			$Main.show(0, "", function() {
				
				$Body.slideDown(oVars.iSpeed, "easeOutBounce");
				
				$Title.text( oPHP.vars.titles['biography'] );
				
				$Load.animate( {
					
					opacity: 0
					
				} );
				
			} );
			
		} );
		
	} );
	
    track( extractPage() );
	
} );