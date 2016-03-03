$(document).ready(function(){
	var signUpValidator = new Validator($("#sign-up-form"));
	$("h1").click(function(){
		$("h1").text(signUpValidator.ok);
	});
});