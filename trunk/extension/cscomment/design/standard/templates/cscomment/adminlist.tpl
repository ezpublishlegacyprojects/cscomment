{include uri='design:infocollection_validation.tpl'}
{include uri='design:window_controls.tpl'}

<div class="content-navigation">

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{let hide_status=""}
{section show=$node.is_invisible}
{set hide_status=concat( '(', $node.hidden_status_string, ')' )}
{/section}


{def $js_class_languages = $node.object.content_class.prioritized_languages_js_array
     $disable_another_language = cond( eq( 0, count( $node.object.content_class.can_create_languages ) ),"'edit-class-another-language'", '-1' ) }

<h1 class="context-title"><a href={concat( '/class/view/', $node.object.contentclass_id )|ezurl} onclick="ezpopmenu_showTopLevel( event, 'ClassMenu', ez_createAArray( new Array( '%classID%', {$node.object.contentclass_id}, '%objectID%', {$node.contentobject_id}, '%nodeID%', {$node.node_id}, '%currentURL%', '{$node.url|wash( javascript )}', '%languages%', {$js_class_languages} ) ), '{$node.class_name|wash(javascript)}', -1, {$disable_another_language} ); return false;">{$node.class_identifier|class_icon( normal, $node.class_name )}</a>&nbsp;{$node.name|wash}&nbsp;[{$node.class_name|wash}]&nbsp;{$hide_status}</h1>

{undef $js_class_languages $disable_another_language}

{/let}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

<form method="post" action={'content/action'|ezurl}>
<div class="box-ml"><div class="box-mr">

<div class="context-information">
<p class="modified">{'Last modified'|i18n( 'design/admin/node/view/full' )}: {$node.object.modified|l10n(shortdatetime)}, <a href={$node.object.current.creator.main_node.url_alias|ezurl}>{$node.object.current.creator.name|wash}</a></p>
<p class="translation">{$node.object.current_language_object.locale_object.intl_language_name}&nbsp;<img src="{$node.object.current_language|flag_icon}" style="vertical-align: middle;" /></p>
<div class="break"></div>
</div>

</div></div>

{* Buttonbar for content window. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

<div class="block">

<div class="left">
{* Edit button. *}
{def $can_create_languages = $node.object.can_create_languages
     $languages            = fetch( 'content', 'prioritized_languages' )}
{section show=$node.can_edit}
    {if and(eq( $languages|count, 1 ), is_set( $languages[0] ) )}
            <input name="ContentObjectLanguageCode" value="{$languages[0].locale}" type="hidden" />
    {else}
            <select name="ContentObjectLanguageCode">
            {foreach $node.object.can_edit_languages as $language}
                       <option value="{$language.locale}"{if $language.locale|eq($node.object.current_language)} selected="selected"{/if}>{$language.name|wash}</option>
            {/foreach}
            {if gt( $can_create_languages|count, 0 )}
                <option value="">{'Another language'|i18n( 'design/admin/node/view/full')}</option>
            {/if}
            </select>
    {/if}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit the contents of this item.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <select name="ContentObjectLanguageCode" disabled="disabled">
        <option value="">{'Not available'|i18n( 'design/admin/node/view/full')}</option>
    </select>
    <input class="button-disabled" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}
{undef $can_create_languages}

{* Move button. *}
{section show=$node.can_move}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'Move this item to another location.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button-disabled" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to move this item to another location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}

{* Remove button. *}
{section show=$node.can_remove}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'Remove this item.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button-disabled" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}
</div>

{* Custom content action buttons. *}
<div class="right">
{section var=ContentActions loop=$node.object.content_action_list}
    <input class="button" type="submit" name="{$ContentActions.item.action}" value="{$ContentActions.item.name}" />
{/section}
</div>

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/admin/node/view/full')}" /> *}

<div class="break"></div>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</form>

</div>


<!--ghjgj-->
<div class="content-view-children">

<!-- Children START -->

<div class="context-block">
<form name="children" method="post" action={'comment/delete'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
{* Generic children list for admin interface. *}
{let item_type=ezpreference( 'admin_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     can_remove=false()
     can_edit=false()
     can_create=false()
     can_copy=false()    
     children=$list}

                                          
                                          
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title"><a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level.'|i18n(  'design/admin/node/view/full'  )}"><img src={'back-button-16x16.gif'|ezimage} alt="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" /></a>&nbsp;{'Sub items [%children_count]'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $children_count ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* If there are children: show list and buttons that belong to the list. *}
{section show=$children}

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
        {/case}

        {/switch}
    </p>
</div>

<div class="break"></div>

</div>
</div>

<div class="content-navigation-childlist">
    <table class="list" cellspacing="0">
    <tr>
        <th class="remove"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="ezjs_toggleCheckboxes( document.children, 'DeleteIDArray[]' ); return false;" /></th>
        <th class="name">{'Message'|i18n( 'cscomment/adminlist' )}</th>
        <th class="class">{'Username'|i18n( 'cscomment/adminlist' )}</th>
        <th class="edit">{'Date'|i18n( 'cscomment/adminlist' )}</th>
        <th class="edit">{'IP'|i18n( 'cscomment/adminlist' )}</th>
        <th class="edit">{'Email'|i18n( 'cscomment/adminlist' )}</th>
    </tr>

{section var=Nodes loop=$children sequence=array( bglight, bgdark )}
<tr class="{$Nodes.sequence}">
    <td>
    	<input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.id}_{$Nodes.node_id}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to  remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash()}" />
    </td>
    <td>{$Nodes.message|wash('xhtml')}
    	
    </td>        
    <td class="class">{$Nodes.name}</td>

     <td nowrap>
    	{$Nodes.date|datetime('custom','%Y-%m-%d %H:%i:%s')}
    </td>
    
    <td>
    	{$Nodes.ip}
    </td>        
    <td>
    	{$Nodes.email}
    </td>
</tr>

		{if gt($Nodes.subcount,0)}			
			{foreach $Nodes.subcommentlist as $subcomment}
				
				<tr class="{$Nodes.sequence}">
				<td>
				<input type="checkbox" name="DeleteIDArray[]" value="{$subcomment.id}_{$subcomment.node_id}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to  remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash()}" />
				</td>
				<td>
				&raquo; {$subcomment.name}
				</td>        
				<td class="class">{$subcomment.message|wash('xhtml')}</td>
				
				<td>
					{$subcomment.date|datetime('custom','%Y-%m-%d %H:%i:%s')}
				</td>
				
				<td>
				{$subcomment.ip}
				</td>        
				<td>
				{$subcomment.email}
				</td>
				</tr>
						
			{/foreach}
			</div>
		{/if}
{/section}

</table>
</div>

{* Else: there are no children. *}
{section-else}

<div class="block">
    <p>{'The current item does not contain any sub items.'|i18n( 'design/admin/node/view/full' )}</p>
</div>

{/section}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/alphabetical.tpl'
         page_uri=concat("comment/adminlist/",$node_id)
         item_count=$children_count
         view_parameters=$view_parameters         
         item_limit=$number_of_items}
</div>

{* DESIGN: Content END *}</div></div></div>


{* Button bar for remove and update priorities buttons. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    {* Remove button *}
    <div class="left">  
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'Remove the selected items from the list above.'|i18n( 'design/admin/node/view/full' )}" />
    
    </div>

    <div class="break"></div>
</div>




{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</form>

</div>

<!-- Children END -->

{/let}
</div>
<!--hjkhjk-->


</div>





