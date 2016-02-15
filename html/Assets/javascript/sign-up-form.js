
$('#sign-up-form input[name="password-again"]').keyup(checkPasswordEquality);
$('#sign-up-form input[name="password"]').keyup(checkPasswordLength);
$('#sign-up-form input[name="password"]').change(checkPasswordEquality);
$('#sign-up-form input[name="username"]').change(checkUsername);
$('#sign-up-form input[name="email"]').change(checkEmail);

function checkPasswordEquality(){
	if($('#sign-up-form input[name="password-again"]').val() != $('#sign-up-form input[name="password"]').val()){
		$('#password-again-wrapper').addClass("has-error");
		$('#password-again-wrapper + .alert').text("Passwords do not match.").addClass("alert-danger").slideDown();
		return false;
	}
	$('#password-again-wrapper').removeClass("has-error");
	$('#password-again-wrapper + .alert').text("").slideUp();
	return true;
}

function checkPasswordLength(){
	if($('#sign-up-form input[name="password"]').val().length < 6 && $('#sign-up-form input[name="password"]').val().length > 0){
		$('#password-wrapper').addClass("has-error");
		$('#password-wrapper + .alert').text("Password must contain at least 6 characters.").addClass("alert-danger").slideDown();
		return false;
	}
	$('#password-wrapper').removeClass("has-error");
	$('#password-wrapper + .alert').text("").slideUp();
	return true;
}

var usernameExists = true;

function checkUsername(){
	if($('#sign-up-form input[name="username"]').val().length > 0){
		var username = $('#sign-up-form input[name="username"]').serialize();
		$.ajax({
			type: 'GET',
			url: '/username-exists',
			data: username,
			dataType : "json",
			success: function(response){
				usernameExists = response["usernameExists"];
				if(usernameExists){
					$("#username-wrapper + .alert").removeClass("alert-success").addClass("alert-danger").text("Username already exists.").slideDown();
					return;
				}
				$("#username-wrapper + .alert").addClass("alert-success").removeClass("alert-danger").text("Username OK.").slideDown().delay(1000).slideUp();
			}
		});
		return;
	}
	usernameExists = true;
	$("#username-wrapper + .alert").text("").slideUp();
}

var emailExists = true;

function checkEmail(){
	if($('#sign-up-form input[name="email"]').val().length > 0){
		var email = $('#sign-up-form input[name="email"]').serialize();
		$.ajax({
			type: 'GET',
			url: '/email-exists',
			data: email,
			dataType : "json",
			success: function(response){
				emailExists = response["emailExists"];
				if(emailExists){
					$("#email-wrapper + .alert").removeClass("alert-success").addClass("alert-danger").text("Email is already in use.").slideDown();
					return;
				}
				$("#email-wrapper + .alert").addClass("alert-success").removeClass("alert-danger").text("Email OK.").slideDown().delay(1000).slideUp();
			}
		});
		return;
	}
	emailExists = true;
	$("#email-wrapper + .alert").text("").slideUp();
}

$("#sign-up-form").submit(function(event){
    event.preventDefault();
    if(!usernameExists && !emailExists && checkPasswordLength() && checkPasswordEquality()){
	    var formData = $("#sign-up-form").serialize();
	    $.ajax({
		    type: 'POST',
		    url: '/sign-up',
		    data: formData,
		    dataType : "script",
		});
	}
});