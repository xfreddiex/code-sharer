$(document).ready(function(){
	var signUpValidator = new Validator($("#sign-up-form"), "/user-validate-one");
	var settingsValidator = new Validator($("#settings-form"), "/user-validate-one");
	var newPackValidator = new Validator($("#new-pack-form"), "/pack-validate-one");

	$("textarea").click(function(){
		$("h1").text($('textarea[name="tags"]').val());
	});
});