function updateOrientation() {
	
	var strClass;
	
	switch(window.orientation) {
		
		case 0:
			strClass = "portrait";
			break;
		
		case -90:
		case 90:
			strClass = "landscape";
			break;
		
	}
	
	addBodyClass(strClass);
	
	/*
	
	// This next bit could evolve into formatting for other mobile platforms
	// besides the iPhone.
	
	if (strClass == "portrait"){
		
		$("#container-wrapper").width(document.documentElement.clientWidth + "px");
		$("#page").width( (document.documentElement.clientWidth - 4) + "px");
		
	} else if (strClass == "landscape"){
		
		$("#container-wrapper").width(document.documentElement.clientWidth + "px");
		$("#page").width( (document.documentElement.clientWidth - 4) + "px");
		
	}
	
	*/
	
}