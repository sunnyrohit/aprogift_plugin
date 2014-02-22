
function openContainer($containerToOpen, $triggerArrow, $containerToHide) {
	if (($containerToOpen).is(":visible"))
		return;
	// default argument
	$containerToHide = typeof $containerToHide !== 'undefined' ? $containerToHide : $(".z_container");

	var TRIGGER_CLOSED_ICON = "&#x25B2;";
	var TRIGGER_OPENED_ICON = "&#x25BC;";
	
	var SLIDE_UP_TIME = 230;
	var SLIDE_DOWN_TIME = 230;
	var DELAY = 50;
	
	$containerToHide.slideUp(SLIDE_UP_TIME);
	setTimeout(
		function() {
			$(".trigger>span").html(TRIGGER_CLOSED_ICON);
			
			$containerToOpen.slideDown(SLIDE_DOWN_TIME);
			$triggerArrow.html(TRIGGER_OPENED_ICON);
		}
		, DELAY
	);
	
}


/*
 * Displays a jquery DOM object using fading effects		 
 * Also hides the $elToHide [ default argument is $(".cards") ] argument's elements	 
 */
function displayCard($elToShow, $elToHide) {
	// default argument
	$elToHide = typeof $elToHide !== 'undefined' ? $elToHide : $(".cards");
	
	// time to fade Constants
	var FADE_IN_TIME = 230;
	var FADE_OUT_TIME = 320;
	
	// first hide all elements
	$elToHide.fadeOut(FADE_OUT_TIME);
	
	// show the element to display after all elements
	// have been hidden using [setTimeout]
	setTimeout(
		function() {
			$elToShow.fadeIn(FADE_IN_TIME);
			$elToShow.addClass("displayed");
			}
		, FADE_OUT_TIME
	);
}
