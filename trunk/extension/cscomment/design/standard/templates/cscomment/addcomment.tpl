
<div class="class-comment-view" >
	<div class="comment-header-line">
		<span class="name">{$comment.name|wash('xhtml')}</span>, {$comment.date|datetime('commentdate')}
	</div>
	<div class="comment-body">
		{$comment.message|wash('xhtml')}
	</div>
	<div class="reply-comment" id="comment-{$comment.id}">
		<a href="javascript:cscomments.formdisplay({$comment.id},{$comment.node_id})" >{'Reply'|i18n( 'cscomment/addcomment' )}</a>
	</div>		
</div>
