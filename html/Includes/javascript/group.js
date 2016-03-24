$(document).ready(function(){
	
	bindRemoveButtons();

	function bindRemoveButtons(){
		$('button[name="remove-user"]').click(function(){
			var url = "/group/" + $(this).attr("group") + "/remove-user/" + $(this).attr("user");
			$.ajax({
				type: 'GET',
				url: url,
				dataType : "json",
				context: $(this),
				success: function(response){
					$("#users-list").load("/group/" + this.attr("group") + "/users-list", function(){
			           bindRemoveButtons(); 
			        });
				}
			});
		});
	}

	$('button[name="save-button"]').hide();	

	$('button[name="add-user"]').click(function(event){
		$("#users-list").append('<hr /><div class="row"><div class="col-xs-4"><input class="form-control" placeholder="username" type="text" name="username[]"></div></div>').children(':last').hide().slideDown();
		$('button[name="save-button"]').fadeIn();
	});

	$('button[name="save-button"]').click(function(){
		var data = $('input[name="username[]"]').serializeArray();
		var url = "/group/" + $(this).attr("group") + "/add-users";
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			dataType : "json",
			context: $(this),
			success: function(response){
				$("#users-list").load("/group/" + this.attr("group") + "/users-list", function(){
					bindRemoveButtons();
					$('button[name="save-button"]').fadeOut();
				});
			}
		});
	});
});