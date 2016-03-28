$(document).ready(function(){
	var signUpValidator = new Validator($("#sign-up-form"), "/user/validate-one");
	var settingsValidator = new Validator($("#settings-form"), "/user/validate-one");
	var newPackValidator = new Validator($("#new-pack-form"), "/pack/validate-one");
	var newGroupValidator = new Validator($("#new-group-form"), "/group/validate-one");
	var groupDescriptionValidator = new Validator($(".group-wrapper #description").parent(), "/group/validate-one");
	var groupNameValidator = new Validator($(".group-wrapper #name").parent(), "/group/validate-one");
	var packDescriptionValidator = new Validator($(".pack-wrapper #description").parent(), "/pack/validate-one");
	var fileDescriptionValidator = new Validator($(".file-wrapper #description").parent(), "/file/validate-one");
	var packCommentValidator = new Validator($(".pack-wrapper #comment").parent(), "/comment/validate-one");
});