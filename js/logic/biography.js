var $Body = $("#biography .inner");
var $Load = $(".load");
var $Main = $("#biography .main");
var $Solo = $("#biography .solo");
var $Title = $("#biography .title");

$("#biography img.band").click( function() {
    
	$Load.animate( {
		
		opacity: 1
		
	} );
	
    var sFirst = $(this).attr("title");
    
    var aInfo = oPHP.vars.bios[sFirst].split("|");
    
    var sLast = aInfo[0];
    
    var sName = sFirst + " " + sLast;
    
    var sBio = aInfo[1];
    
    var sImage = oPHP.const.DIR_BAND + "/profiles/" + sFirst.toLowerCase() + ".jpg";
    
    $Body.slideUp(100, "", function() { 
		
		$Main.hide();
		
		$Solo.find("img.profile").one("load", function() {
			
			$Solo.find("div.biography").html("<strong>" + sName + "</strong> - " + sBio);
			
			$Solo.show(0, "", function() {
				
				$Body.slideDown(1000, "easeOutBounce");
				
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
	
    $Body.slideUp(100, "", function() {
		
		$Solo.hide();
		
		$Main.show(0, "", function() {
			
			$Body.slideDown(1000, "easeOutBounce");
			
			$Title.text( oPHP.vars.titles['biography'] );
			
			$Load.animate( {
				
				opacity: 0
				
			} );
			
		} );
		
	} );
	
    track( extractPage() );
	
} );