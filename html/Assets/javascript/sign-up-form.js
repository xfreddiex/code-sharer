var allValid = false;

$('#sign-up-form input[name="password-again"]').keyup(checkPasswordEquality);
$('#sign-up-form input[name="password"]').keyup(checkPasswordLength);
$('#sign-up-form input[name="password"]').change(checkPasswordEquality);
$('#sign-up-form input[name="username"]').change(checkUsername);

function checkPasswordEquality(){
	if($('#sign-up-form input[name="password-again"]').val() != $('#sign-up-form input[name="password"]').val()){
		$('#password-again-wrapper').addClass("has-error");
		allValid = false;
	}
	else{
		$('#password-again-wrapper').removeClass("has-error");
		allValid = true;
	}
}
function checkPasswordLength(){
	if($('#sign-up-form input[name="password"]').val().length < 6){
		$('#password-wrapper').addClass("has-error");
		allValid = false;
		$(".alert").text($('#sign-up-form input[name="password"]').val().length);
	}
	else{
		$('#password-wrapper').removeClass("has-error");
		allValid = true;
	}
}

function checkUsername(){
	
}

$("#sign-up-form").submit(function(event){
    event.preventDefault();
    if(allValid){
	    var formData = $("#sign-up-form").serialize();
	    $.ajax({
		    type: 'POST',
		    url: '/sign-up',
		    data: formData,
		    dataType : "script",
		});
	}
});