{if $comments|count > 0}
	<h4>Комментарии Худсовета:</h4>

	<ul class="cancel-reasons">	
	{foreach from=$comments item="c"}
		
		<li><b>{$c.user_login}</b>: {$c.comment}</li>
		
	{/foreach}
	</ul>
	
	<style>
		.cancel-reasons {
			margin-top:25px
		}
		.cancel-reasons li {
			margin: 7px 0
		}
	</style>
	
{else}

	<p>Комментарии отсутствуют</p>

{/if}
