
<div class="content-view-comments">

<div class="attribute-header">
    <h1><a href={$node.url_alias|ezurl()} >{$node.data_map.title.content|wash()}</a></h1>
</div>


<script type="text/javascript">
    cscomments.setPath({"/"|ezurl()});
</script>
<br />
     
<div class="content-view-children" id="reply-comments{$node_id}">
{foreach $list as $comment}
	<div class="class-comment-view" >
		<div class="comment-header-line">
			<span class="name">{$comment.name}</span>, {$comment.date|datetime('commentdate')}
		</div>
		<div class="comment-body">
			{$comment.message|wash('xhtml')}
		</div>
		<div class="reply-comment" id="comment-{$comment.id}">
			<a href="javascript:cscomments.formdisplay({$comment.id},{$comment.node_id})" >{'Reply'|i18n( 'cscomment/list' )}</a>
		</div>
		
		{if gt($comment.subcount,0)}
			<div class="subcomment-container" id="subcomments-container-{$comment.id}">
			{foreach $comment.subcommentlist as $subcomment}
				<div class="class-subcomment">
					<div class="comment-header-line">
						<span class="name">{$subcomment.name}</span>, {$subcomment.date|datetime('commentdate')}
					</div>
					<div class="comment-body">
						{$subcomment.message|wash('xhtml')}
					</div>
				</div>			
			{/foreach}
			</div>
		{/if}
		
	</div>
{/foreach}
</div>



<div class="navigator-comments">
{include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=concat("comment/list/",$node_id)
                     item_count=$children_count
                     view_parameters=$view_parameters
                     item_limit=$page_limit}
                     
</div>

<div class="comment-form">


<div align="center">
<div class="header-comment-form" id="header-comment-afterprepend">{'Write comment'|i18n( 'cscomment/list' )}</div>

<div style="width:345px;">
	<table class="comment-table">
		<tr class="comment-row">
			<td class="comments-title" width="1%">{'Name'|i18n( 'cscomment/list' )}:*</td>
			<td width="99%"><input type="text" class="commentinput" id="CSnameComment" name="CSnameComment" value="" ></td>
		</tr>
		<tr class="comment-row">
			<td class="comments-title">{'Email'|i18n( 'cscomment/list' )}:</td>
			<td><input type="text" class="commentinput" id="CSemailComment" name="CSemailComment" value="" ></td>
		</tr>
		<tr class="comment-row">
			<td colspan="2" class="comments-title">{'Comment'|i18n( 'cscomment/list' )}:* </td>
		</tr>
		<tr class="comment-row">
			<td colspan="2">
				<textarea class="commentMessage" id="CSmessageComment" name="CSmessageComment"></textarea>
			</td>
		</tr>
		<tr class="comment-row">
			<td colspan="2">
				<table>
					<tr>
						<td><img id="MainCaptcha" title="{'Click if you want to regenerate'|i18n( 'cscomment/list' )}" onclick="cscomments.regenerateCaptcha('MainCaptcha','/comment/addcommentcaptcha/')" src={"comment/addcommentcaptcha"|ezurl()} alt="{'Click if you want to regenerate'|i18n( 'cscomment/list' )}" ></td>	
						<td><span class="comment-explain">{'Enter captcha'|i18n( 'cscomment/list' )}</span></td>
					</tr>			
				</table>
			</td>
		</tr>
		<tr class="comment-row">
			<td class="comments-title">{'Code'|i18n( 'cscomment/list' )}:*</td>
			<td><input type="text" class="commentinput" id="CScaptchaComment" name="CScaptchaComment" value="" ></td>
		</tr>
		<tr class="comment-row">
			<td colspan="2" align="center">
				<a class="button buttongrey">
					<span><span><span><span><input value="{'Send'|i18n( 'cscomment/list' )}" onclick="cscomments.addcomment({$node_id})" name="ActionCollectInformation" class="vote-but" type="button"></span></span></span></span>
				</a>
			</td>
		</tr>
	</table>
</div>
</div>

</div>

</div>

