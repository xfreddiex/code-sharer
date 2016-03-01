$(document).ready(function(){	
	$('form.validate input[name="password-again"]').keyup({ compare: $('form.validate input[name="password"]')}, validateMatch);
	$('form.validate input[name="password"]').change({ compare: $('form.validate input[name="password-again"]')}, validateMatch);
	$('form.validate input[name="password"]').keyup(validateOne);
	$('form.validate input[name="password"]').change(validateOne);
	$('form.validate input[name="username"]').change(validateOne);
	$('form.validate input[name="username"]').keyup(validateOne);
	$('form.validate input[name="email"]').change(validateOne);

	var ok = true;

	function validateOne(){
		if($(this).val().length > 0){
			var data = $(this).serialize();
			$.ajax({
				type: 'POST',
				url: '/validate-one',
				data: data,
				dataType : "json",
				context: $(this),
				success: function(response){
					var error = response["error"];
					if(error){
						this.popover({"template" : '<div class="popover alert-danger"><div class="arrow"></div><div class="popover-content"></div></div>', "trigger" : "manual", "placement" : "top", "content" : error["message"]}).popover('show');
						this.parent().addClass("has-error");
						ok = false;
					}
					else{
						this.popover('hide').popover('destroy');
						this.parent().removeClass("has-error");
						ok = true;
					}
				}
			});
		}
		else{
			this.popover('hide').popover('destroy');
			$(this).parent().removeClass("has-error");
			ok = true;
		}

	}

	function validateMatch(event){
		if($(this).val() != event.data.compare.val()){
			$(this).popover({"template" : '<div class="popover alert-danger"><div class="arrow"></div><div class="popover-content"></div></div>', "trigger" : "manual", "placement" : "top", "content" : "Passwords do not match."}).popover('show');
			$(this).parent().addClass("has-error");
			ok = false;
		}
		else{
			$(this).popover('hide').popover('destroy');
			$(this).parent().removeClass("has-error");
			ok = true;
		}
	}

	$("form.validate").submit(function(event){
		if(!ok){
			event.preventDefault();
		}
	});
});