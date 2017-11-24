<div id="disabled-notees">
<div>Эти работы отключены в общем каталоге, однако они по прежнему продаются на Вашей <a href="/catalog/{$user.user_login}/">персональной витрине</a>.</div>
<div>О возможных причинах отключения работ, читайте <a href="http://www.maryjane.ru/blog/view/5041/">здесь</a>.</div>
<div>Используйте боковое меню, чтобы увидеть все Ваши работы на любом из носителей - Одежда, Гаджеты, Постеры и т.д</div>
</div>

<table id="disabled-goods">
{foreach from=$pics item="p"}

	{foreach from=$p item="c"}
	<tr>
		<td><a href="/senddrawing.design/{$c.good_id}/#!/step-2">{$c.category_name} - {$c.good_name}</a></td>
	</tr>
	{/foreach}

{foreachelse}

	<tr>
		<td colspan="2" class="center">Отсутствуют</td>
	</tr>

{/foreach}
</table>

{literal}
<style>
	#disabled-goods {width: 100%;margin-top:10px}
	#disabled-goods tr td {padding:5px 0}
	
	#disabled-notees {margin-bottom:15px}
	#disabled-notees div {margin:6px 0}
</style>
{/literal}