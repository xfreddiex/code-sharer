function sendMessage(text, type){
	$("#message-box").append('<div class="row"><div class="col-xs-12"><div class="alert alert-'+type+' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><i class="fa fa-close"></i></button>'+text+'</div></div></div>').children().last().hide().slideDown();
}