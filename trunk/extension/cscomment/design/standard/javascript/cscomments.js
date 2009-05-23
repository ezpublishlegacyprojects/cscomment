$.postJSON = function(url, data, callback) {
	$.post(url, data, callback, "json");
};

var cscomments = {
	
	formreplycomment : '/comment/showform/',
	formaddreply : '/comment/addreplytocoment/',
	formaddcomment : '/comment/addcomment/',
	formAddPath: null,		
	
	formdisplay : function (postid,nodeid)
	{		
		$("#replycomment").remove();		
		$.ajax({
				url: this.formAddPath + this.formreplycomment,
				type:"POST",
				timeout: 20000,
		        data: {postid:postid,nodeid:nodeid},
		        error: function(){
		         
		        },
		        success: function(html){
		           $("#comment-"+postid).append(html);
		           return true;
		        }
			});		
	},
	
	setPath : function (path)
	{		
		this.formAddPath = path;
	},
	
	getPath : function(path)
	{		
		return this.formAddPath;
	},
	
	regenerateCaptcha : function(imageID,url)
	{		
		$("#"+imageID).attr('src',this.formAddPath + url +Math.round(new Date().getTime() / 1000));
	},
	
	addreplytocomment : function (postid,nodeid)
	{
		var pdata = {
				username	: $("#CSnameCommentReply").val(),
				email		: $("#CSemailCommentReply").val(),
				comment		: $("#CSmessageCommentReply").val(),
				captcha		: $("#CScaptchaCommentReply").val(),
				postid		: postid,
				nodeid		: nodeid
		}

		$.postJSON(this.formAddPath + this.formaddreply, pdata , function(data){	
			if (data.error == 'false')
			{		
				$('#validation-error').remove();	
				if ($('#subcomments-container-'+postid).is('*')) {			        			        	
	        		$('#subcomments-container-'+postid).prepend(data.result);     		
	        	} else {
	        		$("#comment-"+postid).after('<div class="subcomment-container" id="subcommetns-container-'+postid+'">'+data.result+'</div>');
	        	}
	        	$("#replycomment").fadeOut();
	        		        	
	        	
			} else {				
				$('#validation-error').remove();
				$('#replycomment').before(data.result);		
			}
           return true;	          
		});
		
	},
	
	addcomment : function (node_id){		
		var pdata = {
				username	: $("#CSnameComment").val(),
				email		: $("#CSemailComment").val(),
				comment		: $("#CSmessageComment").val(),
				captcha		: $("#CScaptchaComment").val(),
				node_id		: node_id
		}
		
		$.postJSON(this.formAddPath + this.formaddcomment, pdata , function(data){				
			if (data.error == 'false')
			{
				$("#comment-feedback-message").remove();
				$("#validation-error").remove();
				
				$("#reply-comments"+node_id).prepend(data.result);
				$("#header-comment-afterprepend").after(data.feedback);
				
				$("#CSnameComment").val('');
				$("#CSemailComment").val('');
				$("#CSmessageComment").val('');
				$("#CScaptchaComment").val('');
				
				$("#MainCaptcha").attr('src',cscomments.getPath() + '/comment/addcommentcaptcha/'+Math.round(new Date().getTime() / 1000));
							
					
			} else {				
				$("#validation-error").remove();
				$("#comment-feedback-message").remove();				
				$("#header-comment-afterprepend").after(data.result);
			}			
		});				
	}		
}