$(document).ready(function(){
	
	bindRemoveButtons();

	function bindRemoveButtons(){
		$('.group-wrapper button[name="remove-user"]').click(function(){
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

	$('button[name="save-users"]').hide();	

	$('.group-wrapper button[name="add-user"]').click(function(event){
		$("#users-list").append('<hr /><div class="row"><div class="col-xs-4"><input class="form-control" placeholder="username" type="text" name="username[]"></div></div>').children(':last').hide().slideDown();
		$('button[name="save-users"]').fadeIn();
	});

	$('.group-wrapper button[name="save-users"]').click(function(){
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
					$('button[name="save-users"]').fadeOut();
				});
			}
		});
	});



	$('.group-wrapper button[name="edit-description"]').click(function(){
		$("#description").hide();
		$('textarea[name="description"]').show();
		$(this).hide();
		$('button[name="save-description"]').show();
	});

	$('.group-wrapper button[name="save-description"]').click(function(){
		var url = "/group/" + $(this).attr("group") + "/update";
		var data = $('textarea[name="description"]').serialize();
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			dataType : "json",
			context: $(this),
			success: function(response){
				if(response["status"] == "ok"){
					$('textarea[name="description"]').hide();
					if(typeof response["data"]["description"] != "undefined"){
						$("#description").text(response["data"]["description"]).show();
					}
					$("#description").show();
					$('button[name="save-description"]').hide();
					$('button[name="edit-description"]').show();
				};
			}
		});
	});
});