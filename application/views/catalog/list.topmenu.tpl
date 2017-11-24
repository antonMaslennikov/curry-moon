{if $user.personal_header != ''}
<div style="display:block; margin: 0 auto 0; padding-top: 4px;   position: relative;  width: 980px;">
	<img src="{$user.personal_header}" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" title="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" />
</div>
{/if}
