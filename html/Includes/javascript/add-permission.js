$(document).ready(function(){
	$('#new-permission-button').click(function(event){
		alert($('#update-permissions-form .row').last().find('input[type="hidden"]').val());
		//alert(number);
		/*
		//.find('input[type="text"]').attr("number");
		$("#permissions").append('<hr /><div class="row"><div class="col-xs-4"><div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input class="form-control" type="text" name="user['+number+'][username]"></div></div><div class="col-xs-4 col-sm-offset-4 col-sm-2 col-lg-offset-6 col-lg-1 text-right"><label class="checkbox"><input type="checkbox" name="user['+number+'][permission]" value="1"><i class="fa fa-eye"></i><i class="fa checkbox-button"></i></label></div><div class="col-xs-4 col-sm-2 col-lg-1"><label class="checkbox"><input type="checkbox" name="user['+number+'][permission]" value="2"><i class="fa fa-pencil-square-o"></i><i class="fa checkbox-button"></i></label></div></div>');*/
	});
});