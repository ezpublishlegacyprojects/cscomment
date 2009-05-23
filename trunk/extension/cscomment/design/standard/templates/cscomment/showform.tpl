<div id="replycomment">
	<table class="comment-table" align="center">
		<tr class="comment-row">
			<td class="comments-title" width="1%">{'Name'|i18n( 'cscomment/showform' )}:*</td>
			<td width="99%"><input type="text" class="commentinput" id="CSnameCommentReply" name="CSnameCommentReply" value="" ></td>
		</tr>
		<tr class="comment-row">
			<td class="comments-title">{'E-mail'|i18n( 'cscomment/showform' )}:</td>
			<td><input type="text" class="commentinput" id="CSemailCommentReply" name="CSemailCommentReply" value="" ></td>
		</tr>
		<tr class="comment-row">
			<td colspan="2" class="comments-title">{'Comment'|i18n( 'cscomment/showform' )}:* </td>
		</tr>
		<tr class="comment-row">
			<td colspan="2">
				<textarea class="commentMessage" id="CSmessageCommentReply" name="CSmessageCommentReply"></textarea>
			</td>
		</tr>
		<tr class="comment-row">
			<td colspan="2">
				<table>
					<tr>
						<td><img id="SubComment" onclick="cscomments.regenerateCaptcha('SubComment','/comment/addreplycaptcha/')" title="{'Click if you want to regenerate'|i18n( 'cscomment/showform' )}" src={"comment/addreplycaptcha"|ezurl()} alt="{'Click if you want to regenerate'|i18n( 'cscomment/showform' )}" ></td>	
						<td><span class="comment-explain">{'Enter captcha'|i18n( 'cscomment/showform' )}</span></td>
					</tr>			
				</table>
			</td>
		</tr>
		<tr class="comment-row">
			<td class="comments-title">{'Code'|i18n( 'cscomment/showform' )}:*</td>
			<td><input type="text" class="commentinput" id="CScaptchaCommentReply" name="CScaptchaCommentReply" value="" ></td>
		</tr>
		<tr class="comment-row">
			<td colspan="2" align="center">
				<a class="button buttongrey">
					<span><span><span><span><input value="{'Send'|i18n( 'cscomment/showform' )}" name="ActionCollectInformation" onclick="cscomments.addreplytocomment({$comment_reply},{$node_reply})" class="vote-but" type="button"></span></span></span></span>
				</a>
			</td>
		</tr>
	</table>
</div>