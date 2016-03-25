$(document).ready(function(){

	$('button[name="edit-description"]').click(function(){
		$("#description").hide();
		$('textarea[name="description"]').show();
		$(this).hide();
		$('button[name="save-description"]').show();
	});

	$('button[name="save-description"]').click(function(){
		var url = "/pack/" + $(this).attr("pack") + "/update";
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