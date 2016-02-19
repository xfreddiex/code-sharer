
$('#sign-up-form input[name="password-again"]').keyup(passwordsMatch);
$('#sign-up-form input[name="password"]').keyup(checkPasswordLength);
$('#sign-up-form input[name="password"]').change(passwordsMatch);
$('#sign-up-form input[name="username"]').change(checkUsername);
$('#sign-up-form input[name="username"]').keyup(checkUsernameLength);
$('#sign-up-form input[name="email"]').change(checkEmail);

var alerts = new Array();

var alertText = {
	"password_short" : "Password must contain at least 6 characters.",
	"passwords_different" : "Passwords do not match.",
	"email_occupied" : "Email is already used.",
	"username_occupied" : "Username already exists.",
	"username_long" : "Username is too long"
}

function displayAlert(name){
	if(alerts.indexOf(name) < 0){
		alerts.push(name);
		$("#sign-up-form .alert").slideUp().text(alertText[name]).slideDown();
	}
}

function hideAlert(name){
	if(alerts.indexOf(name) >= 0){
		alerts.splice(alerts.indexOf("name"),1);
		$("#sign-up-form .alert").slideUp().text("");
	}
	if(alerts.length > 0)
		$("#sign-up-form .alert").text(alertText[alerts[alerts.length - 1]]).slideDown();
}

function passwordsMatch(){
	if($('#sign-up-form input[name="password-again"]').val() != $('#sign-up-form input[name="password"]').val()){
		$('#password-again-wrapper').addClass("has-error");
		displayAlert("passwords_different");
		return false;
	}
	$('#password-again-wrapper').removeClass("has-error");
	hideAlert("passwords_different");
	return true;
}

function checkPasswordLength(){
	if($('#sign-up-form input[name="password"]').val().length < 6 && $('#sign-up-form input[name="password"]').val().length > 0){
		$('#password-wrapper').addClass("has-error");
		displayAlert("password_short");
		return false;
	}
	$('#password-wrapper').removeClass("has-error");
	hideAlert("password_short");
	return true;
}

var usernameExists = true;

function usernameExistsRequest(username){
	$.ajax({
		type: 'GET',
		url: '/username-exists',
		data: {username : username},
		dataType : "json",
		success: function(response){
			usernameExists = response["usernameExists"];
			if(usernameExists){
				$('#username-wrapper').addClass("has-error");
				displayAlert("username_occupied");
			}
			else{
				$('#username-wrapper').removeClass("has-error");
				hideAlert("username_occupied");
			}
		}
	});
}

function checkUsername(){
	if($('#sign-up-form input[name="username"]').val().length > 0){
		usernameExistsRequest($('#sign-up-form input[name="username"]').val());
	}
	else{
		usernameExists = true;
		hideAlert("username_occupied");
	}
}

function checkUsernameLength(){
	if($('#sign-up-form input[name="username"]').val().length > 32){
		$('#username-wrapper').addClass("has-error");
		displayAlert("username_long");
		return false;
	}
	$('#username-wrapper').removeClass("has-error");
	hideAlert("username_long");
	return true;
}

var emailExists = true;

function emailExistsRequest(email){
	$.ajax({
		type: 'GET',
		url: '/email-exists',
		data: {email : email},
		dataType : "json",
		success: function(response){
			emailExists = response["emailExists"];
			if(emailExists){
				$('#email-wrapper').addClass("has-error");
				displayAlert("email_occupied");
			}
			else{
				$('#email-wrapper').removeClass("has-error");
				hideAlert("email_occupied");
			}
		}
	});
}

function checkEmail(){
	if($('#sign-up-form input[name="email"]').val().length > 0){
		emailExistsRequest($('#sign-up-form input[name="email"]').val());
	}
	else{
		emailExists = true;
		hideAlert("email_occupied");
	}
}

$("#sign-up-form").submit(function(event){
	if(userExists || emailExists || !checkUsernameLength() || !checkPasswordsLength() || !checkPasswordsMatch()){
		event.preventDefault();
	}
});