<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="ru-ru"  class="frontpage" >
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    
    <title>{$PAGE->title}</title>
    
    <link href="/public/css/normalize.css" rel="stylesheet" type="text/css" />
    
    <script src="/public/js/jquery.min.js" type="text/javascript"></script>
    <script src="/public/js/jquery-noconflict.js" type="text/javascript"></script>
    <script src="/public/js/jquery-migrate.min.js" type="text/javascript"></script>
    
    <script src="/public/js/bootstrap.min.js" type="text/javascript"></script>

<body>

    {include file="header.tpl"}
    
    {if $PAGE->tpl} 
        {include file=$PAGE->tpl}
    {else}
        Не указан шаблон страницы
    {/if}
   
    {include file="footer.tpl"}  	

</body>
</html>