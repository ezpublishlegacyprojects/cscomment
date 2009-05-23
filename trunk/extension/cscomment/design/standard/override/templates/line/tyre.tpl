<div class="content-view-line">
    <div class="class-tyre float-break">
    
    {if $node.data_map.image.has_content}
	   <div class="attribute-image">
	   	{attribute_view_gui href=$node.url_alias|ezurl border_size=1 border_color='#909090' attribute=$node.data_map.image image_class=tyrethumb}
	   </div>
    {/if}
   
    <div class="attribute-header-line-container">
		<h2 class="attribute-header-line"><a href={$node.url_alias|ezurl}>{$node.data_map.title.content|wash}<span class="sub-attributes"> {$node.data_map.width.content.name|wash}/{$node.data_map.height.content.name|wash} {$node.data_map.diameter.content.name|wash}</span></a></h2>
	</div>
	 <div class="attribute-price">
      <p>
       {attribute_view_gui attribute=$node.data_map.price}
      </p>
    </div>
    
    <div class="attribute-tyres">
    	<p>
    		{if $node.data_map.make.has_content}
    			<span class="attribute-title">{'Car make'|i18n('design/ezwebin/line/tyre')}</span>
    			{def $manufacturer=''}
    			{def $subitemsaddress=array()}
    			{foreach $node.data_map.make.content.relation_list as $manufacturer}
    				{set $manufacturer=fetch( content, object, hash( object_id, $manufacturer.contentobject_id ))}
    				{set $subitemsaddress=$subitemsaddress|append($manufacturer.name)}
    			{/foreach}
    			{$subitemsaddress|implode( ', ' )};
    		{/if}
    		
    		{if $node.data_map.width.has_content}
    			<span class="attribute-title">{'Width'|i18n('design/ezwebin/line/tyre')}: </span>{$node.data_map.width.content.name};
    		{/if}
    		
    		{if $node.data_map.height.has_content}
    			<span class="attribute-title">{'Height'|i18n('design/ezwebin/line/tyre')}: </span>{$node.data_map.height.content.name};
    		{/if}
    		
    		{if $node.data_map.diameter.has_content}
    			<span class="attribute-title">{'Diameter'|i18n('design/ezwebin/line/tyre')}: </span>{$node.data_map.diameter.content.name};
    		{/if}
    		    		
    		{if $node.data_map.tyre_type.has_content}
    			<span class="attribute-title">{'Type'|i18n('design/ezwebin/line/tyre')}: </span>{$node.data_map.tyre_type.content.name};
    		{/if}
    		
    		{if $node.data_map.tyre_maker.has_content}
    			<span class="attribute-title">{'Maker'|i18n('design/ezwebin/line/tyre')}: </span>{$node.data_map.tyre_maker.content.name};
    		{/if}
    		
    	</p>
    </div>
    <div class="action-row">
    	<a class="addtocompare" rel="{$node.contentobject_id}" onclick="{literal}$(this).jHelperTip({trigger: 'click',source: 'textattribute',literaltext: '{/literal}{'Product added to compare'|i18n('design/ezwebin/line/tyre')}{literal}',autoClose: true,opacity: 0.9}){/literal}">{'Add to compare'|i18n('design/ezwebin/line/tyre')}</a>
    	<a class="addtobasket"  rel="{$node.contentobject_id}_{$node.node_id}" onclick="{literal}$(this).jHelperTip({trigger: 'click',source: 'textattribute',literaltext: '{/literal}{'Product added to basket'|i18n('design/ezwebin/line/tyre')}{literal}',autoClose: true,opacity: 0.9}){/literal}">{'Add to basket'|i18n('design/ezwebin/line/tyre')}</a>
    </div>
	
   
    
    </div>
</div>