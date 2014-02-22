function validateLoginAJAX(username, password) {
	//TODO : Contact server via AJAX to validate
	return username == "aprogift" && password == "w!dge7";
}


function validateNonFbLogin(bt) {
	$parent = $(bt).parent();
	
	var username = $parent.find("input[name='username']").val();
	var password = $parent.find("input[name='password']").val();
	
	if (validateLoginAJAX(username, password)) {
		displayCard($("#card2-non-fb"));
	}
	else {
		alert("Wrong username/password");
	}	
	
	
}
