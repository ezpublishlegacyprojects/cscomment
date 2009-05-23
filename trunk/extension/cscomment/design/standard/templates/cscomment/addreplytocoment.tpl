<div class="class-subcomment">
	<div class="comment-header-line">
		<span class="name">{$subcomment.name}</span>, {$subcomment.date|datetime('commentdate')}
	</div>
	<div class="comment-body">
		{$subcomment.message|wash('xhtml')}
	</div>
</div>	