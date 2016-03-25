$(document).ready(function(){

	$('.pack-wrapper button[name="edit-description"]').click(function(){
		$("#description").hide();
		$('textarea[name="description"]').show();
		$(this).hide();
		$('button[name="save-description"]').show();
	});

	$('.pack-wrapper button[name="save-description"]').click(function(){
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


	bindDeleteButtons();

	function bindDeleteButtons(){
		$('.pack-wrapper button[name="delete-file"]').click(function(){
			var url = "/pack/" + $(this).attr("pack") + "/" + $(this).attr("file") + "/delete";
			$.ajax({
				type: 'GET',
				url: url,
				dataType : "json",
				context: $(this),
				success: function(response){
					$("#files-list").load("/pack/" + this.attr("pack") + "/files-list", function(){
			           bindDeleteButtons(); 
			        });
				}
			});
		});
	}


	$('.pack-wrapper button[name="send-comment"]').click(function(){
		var url = "/pack/" + $(this).attr("pack") + "/add-comment";
		var data = $('textarea[name="comment"]').serialize();
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			dataType : "json",
			context: $(this),
			success: function(response){
				if(response["status"] == "ok"){
					$("#comment-form").collapse('hide');
					$('textarea[name="comment"]').val("");
					$("#comments").load("/pack/"+this.attr("pack")+"/get-comments");
				};
			}
		});
	});

	bindPaginationButtons();

	function bindPaginationButtons(){
		$('.pack-wrapper .pagination button').click(function(){
			$("#comments").load('/pack/' + $("#comments").attr("pack") + '/get-comments?page=' + $(this).val(), function(){
				bindPaginationButtons();
			});
		});	
	}
	
});