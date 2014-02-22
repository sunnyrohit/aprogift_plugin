$(document).ready(function() {
	
	$(".trigger").on("click", function() {
		if ($(this).attr("id") == "login-trigger")
			openContainer($("#login-container"), $("#login-trigger>span"));
		else if ($(this).attr("id") == "signup-trigger")
			openContainer($("#signup-container"), $("#signup-trigger>span"));
	});
	
});
