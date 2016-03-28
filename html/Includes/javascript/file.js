$(document).ready(function(){

	$('.file-wrapper button[name="edit-description"]').click(function(){
		$("#description").hide();
		$('textarea[name="description"]').show();
		$(this).hide();
		$('button[name="save-description"]').show();
	});

	$('.file-wrapper button[name="save-description"]').click(function(){
		var url = "/pack/" + $(this).attr("pack") + "/" + $(this).attr("file") + "/update";
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
	

	$('.file-wrapper button[name="edit-code"]').click(function(){
		$(this).hide();
		$('button[name="save-code"]').show();
		editor.setOption("readOnly", false);
	});

	$('.file-wrapper button[name="save-code"]').click(function(){
		var url = "/pack/" + $(this).attr("pack") + "/" + $(this).attr("file") + "/update";
		var data = {"content": editor.getValue()};
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			dataType : "json",
			context: $(this),
			success: function(response){
				if(response["status"] == "ok"){
					this.hide();
					$('button[name="edit-code"]').show();
					sendMessage("File has been saved", "success");
					editor.setOption("readOnly", true);
				};
			}
		});
	});

});