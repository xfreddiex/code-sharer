$(document).ready(function(){
	$('button[name="new-permission-button"]').click(function(event){
		var number = parseInt($('#update-permissions-form .row').last().find('input[number]').attr("number")) + 1;
		if($(this).val() == "user")
			$("#permissions").append('<hr /><div class="row"><div class="col-xs-3 col-sm-6 col-md-6 col-lg-9"><div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input class="form-control" type="text" number="'+number+'" name="user['+number+'][username]"></div></div><div class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right"><label class="checkbox"><input type="checkbox" name="user['+number+'][permission]" value="1"><i class="fa fa-eye"></i><i class="fa checkbox-button"></i></label></div><div class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right"><label class="checkbox"><input type="checkbox" name="user['+number+'][permission]" value="2"><i class="fa fa-pencil-square-o"></i><i class="fa checkbox-button"></i></label></div><div class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right"><label class="checkbox"><input type="checkbox" name="user['+number+'][permission]" value="0"><i class="fa fa-ban"></i><i class="fa checkbox-button"></i></label></div></div>').children(':last').hide().slideDown();
		else if($(this).val() == "group")
			$("#permissions").append('<hr /><div class="row"><div class="col-xs-3 col-sm-6 col-md-6 col-lg-9"><div class="input-group"><span class="input-group-addon"><i class="fa fa-users"></i></span><input class="form-control" type="text" number="'+number+'" name="group['+number+'][name]"></div></div><div class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right"><label class="checkbox"><input type="checkbox" name="group['+number+'][permission]" value="1"><i class="fa fa-eye"></i><i class="fa checkbox-button"></i></label></div><div class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right"><label class="checkbox"><input type="checkbox" name="group['+number+'][permission]" value="2"><i class="fa fa-pencil-square-o"></i><i class="fa checkbox-button"></i></label></div><div class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right"><label class="checkbox"><input type="checkbox" name="group['+number+'][permission]" value="0"><i class="fa fa-ban"></i><i class="fa checkbox-button"></i></label></div></div>').children(':last').hide().slideDown();
	});

	$('button[name="save-private"]').click(function(){
		var url = "/pack/" + $(this).attr("pack") + "/update";
		var data = $('input[name="private"]').serialize();
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			dataType : "json",
			context: $(this),
			success: function(response){
				if(response["status"] == "ok"){
					sendMessage("Pack was successfuly updated.", "success");
				};
			}
		});
	});
});