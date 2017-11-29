{include file="adminlte/header.tpl"}
{if $PAGE->tpl}
    {include file=$PAGE->tpl}
{else}
    Не указан шаблон страницы
{/if}
{include file="adminlte/footer.tpl"}