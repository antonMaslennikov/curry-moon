<div class="pa10">
{foreach from=$FAQ item="f"}
<div>
<h4>{$f.title}</h4>
<br />
<p>{$f.text}</p>
</div>
<hr />
{/foreach}
</div>