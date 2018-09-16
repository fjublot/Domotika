/* updateOrientation checks the current orientation, sets the body's class attribute to portrait, landscapeLeft, or landscapeRight, 
   and displays a descriptive message on "Handling iPhone or iPod touch Orientation Events".  */
	function updateOrientation() {
		setTimeout('window.scrollTo(0, 1);',100);
		switch(window.orientation) {
			case 0:
				$("body").addClass("portrait");
				$("body").removeClass("paysage");
				break;	
			case 90:
				$("body").addClass("paysage");
				$("body").removeClass("portrait");
				break;
			case -90:	
				$("body").addClass("paysage");
				$("body").removeClass("portrait");
				break;
			default:
				$("body").addClass("paysage");
				$("body").removeClass("portrait");
		}  
	}

	// Point to the updateOrientation function when iPhone switches between portrait and landscape modes.
	window.onorientationchange = updateOrientation;

