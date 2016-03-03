
function Validator(form){
	this.ok = true;
	this.form = form;
	this.form.find('input[validation="ajax"][validation-event="keyup"]').keyup({validator : this}, validateByAjax);
	this.form.find('input[validation="ajax"][validation-event="change"]').change({validator : this}, validateByAjax);
	this.form.find('input[validation="compare"][validation-event="keyup"]').keyup({validator : this},validateCompare);
	this.form.submit({validator : this}, function(event){
		if(!event.data.validator.ok){
			event.preventDefault();
		}
	});
}

validateByAjax = function(event){
	var validator = event.data.validator;
	var input = $(this);
	if(input.val().length > 0){
		var data = {};
		data[input.attr("validation-type")] = input.val();
		$.ajax({
			type: 'POST',
			url: '/validate-one',
			data: data,
			dataType : "json",
			context: {input : input, validator : validator},
			success: function(response){
				var error = response["error"];
				if(error){
					this.input.popover({"template" : '<div class="popover alert alert-danger"><div class="arrow"></div><div class="popover-content"></div></div>', "trigger" : "manual", "placement" : "top", "content" : error["message"]}).popover('show');
					this.input.parent().addClass("has-error");
					this.validator.ok = false;
				}
				else{
					this.input.popover('hide').popover('destroy');
					this.input.parent().removeClass("has-error");
					this.validator.ok = true;
				}
			}
		});
	}
	else{
		input.popover('hide').popover('destroy');
		input.parent().removeClass("has-error");
		validator.ok = true;
	}
}

validateCompare = function(event){
	var validator = event.data.validator;
	var input = $(this);
	var pattern = $('input[validation-name="' + input.attr('validation-name') + '"]');
	if(input.val() != pattern.val()){
		input.popover({"template" : '<div class="popover alert-danger alert"><div class="arrow"></div><div class="popover-content"></div></div>', "trigger" : "manual", "placement" : "top", "content" : input.attr('validation-name') + " do not match."}).popover('show');
		input.parent().addClass("has-error");
		validator.ok = false;
	}
	else{
		input.popover('hide').popover('destroy');
		input.parent().removeClass("has-error");
		validator.ok = true;
	}
	pattern.keyup(function(){
		if(pattern.val() != input.val()){
			input.popover({"template" : '<div class="popover alert-danger alert"><div class="arrow"></div><div class="popover-content"></div></div>', "trigger" : "manual", "placement" : "top", "content" : "Passwords do not match."}).popover('show');
			input.parent().addClass("has-error");
			validator.ok = false;
		}
		else{
			input.popover('hide').popover('destroy');
			input.parent().removeClass("has-error");
			validator.ok = true;
		}
	});
}
