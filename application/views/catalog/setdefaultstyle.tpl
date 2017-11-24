<form method="post" action="/catalog/setdefaultstyle/{$good->id}/" id="default-styles-form">
<ul id="default-styles-list">
	{foreach from=$styles item="c"}
	<li>
		<h4>{$c.title}</h4>
		<ul class="clearfix">
		{foreach from=$c.styles item="s"}
			<li class="left {$s.category} {if $good->good_default_style == $s.style_id}active{/if}">
				<label>
					{if $s.disabled}
						{if $s.src == "patterns"}
							<a href="/senddrawing.pattern/{$good->id}/" target="_blank">
						{else}
							<a href="/senddrawing.design/{$good->id}/#!/step-2,{$s.src}" target="_blank">
						{/if}
					{/if}
					<img src="{$s.preview}" style="vertical-align:middle;{if $s.disabled}opacity: 0.4{/if}">
					{if $s.disabled}</a>{/if}
					<br/>
					<input type="radio" name="style_id" value="{$s.style_id}" {if $good->good_default_style == $s.style_id} checked="checked"{/if} {if $s.disabled}disabled="disabled"{/if} />
					<div class="txt {if $s.disabled}gray-link{/if}">
						{if $s.disabled}
							{if $s.src == "patterns"}
								<a href="/senddrawing.pattern/{$good->id}/" target="_blank">
							{else}
								<a href="/senddrawing.design/{$good->id}/#!/step-2,{$s.src}" target="_blank">
							{/if}
						{/if}
						{if $s.category == "poster"}{$s.style_description}{else}{$s.style_name}{/if}
						{if $s.disabled}</a>{/if}
					</div>
				</label>
			</li>
		{/foreach}
		</ul>
	</li>
	{/foreach}
</ul>
<input type="submit" name="submit" value="Выбрать" style="width:100%" >
</form>

{literal}
<script>
	$('#default-styles-form').submit(function() {
		$.post($(this).attr('action'), $(this).serialize(), function() {
			
		});
		
		window.location.reload(true);
		tb_remove();
		
		return false;
	});
</script>

<style>
.gray-link a {color:#ccc}
</style>
{/literal}