{include file="adminlte/header.tpl"}
{if $PAGE->tpl}
    {if count($flashMessages)}
        {foreach from=$flashMessages key="typeFlash" item="messageFlash"}
            {include file="adminlte/pages/alert.tpl" type=$typeFlash message=$messageFlash}
        {/foreach}
    {/if}
    {include file=$PAGE->tpl}
{else}
    Не указан шаблон страницы
{/if}
{include file="adminlte/footer.tpl"}