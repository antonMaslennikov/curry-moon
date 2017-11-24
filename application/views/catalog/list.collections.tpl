{* выпадающий список со списком популярных коллекций *}

{if !$user && $TAG.slug != "parnie_futbolki"}
	{if $SEARCH && $MobilePageVersion}
		{*непоказываем*}
	{else}
		
		<div class="NLtags{if $MobilePageVersion}-mobile{/if}">
			<select class="tags" _category="{$filters.category}" _color="{$filters.color}" _size="{$filters.size}" _sex="{$filters.sex}" _style="{if $filters.category!='bag' && $filters.category!='cup'}{$Style->style_slug}{/if}">
				<option value="">{$L.LIST_menu_collections}</option>
				
				{foreach from=$TAGS item="t"}
					<option value="{$t.slug}" img="{$t.picture_path}" {if $TAG.slug == $t.slug}selected="selected"{/if}>{$t.name}</option>
				{/foreach}
				
				{*<option value="8march" {if $PAGE->module == '8march'}selected="selected"{/if} _collection="true">8 марта</option>*}
				{*<option value="14february" {if $PAGE->module == '14february'}selected="selected"{/if} _collection="true">14 февраля</option>*}
				{*<option value="23february" {if $PAGE->module == '23february'}selected="selected"{/if} _collection="true">23 февраля</option>*}
			</select>
		</div>
		
		{literal}
		<script>
			$(document).ready(function() { 	
				$('.pageTitle select.tags').change(function(){
					if ($(this).val().length > 0)
					{
						var cat   = $(this).attr('_category');
						var color = $(this).attr('_color');
						var sex   = $(this).attr('_sex');
						var size  = $(this).attr('_size');
						var stl   = $(this).attr('_style');
						
						link = (($(this).children('option:selected').attr('_collection')) ? '/' : '/tag/') + $(this).val()+'/'+ cat +'/' + ((stl.length > 0) ? stl + '/' : ''); 

						location.href = link;
					}
				});
			});
		</script>
		{/literal}	
	{/if}
{/if}