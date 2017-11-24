<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" lang="{$PAGE->lang}">
<head>

	<title>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}{if (($module == 'catalog'||$module == 'catalog.v2') && $good) || $PAGE->url == "/tag/" || ($user && $module == 'catalog' &&!$filters.category && !$good)}{else} - Maryjane | Мэри Джейн{/if}</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta content="{if $PAGE->udescription}{$PAGE->udescription}{else}{$PAGE->description}{/if}" name="description" />
	<meta content="{if $PAGE->ukeywords}{$PAGE->ukeywords}{else}{$PAGE->keywords}{/if}" name="keywords" />
	
</head>

<body>
	
    {if $PAGE->tpl} 
        {include file=$PAGE->tpl}
    {else}
        Не указан шаблон $content_tpl
    {/if}
	
</body>
</html>