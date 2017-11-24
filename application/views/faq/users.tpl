{if $USER->meta->mjteam == "super-admin" || $USER->meta->mjteam == "manager" || $USER->id == 250919}
	<a name="edit"></a>
	<form method="post" action="" id="faq-edit-form">
		<textarea name="text"></textarea>
		<input type="hidden" name="id" value="" />
		<input type="submit" name="save" value="Сохранить" />
	</form>
{/if}

<ul id="faq_feedback">
	{foreach from=$messages item="m"}
	<li>
		{if $USER->meta->mjteam == "super-admin" || $USER->meta->mjteam == "manager" || $USER->id == 250919}<a href="#edit" class="edit" data-id="{$m.question_id}">edit</a>{/if} <span>{$m.question}</span>
		<div>{if $USER->meta->mjteam == "super-admin" || $USER->meta->mjteam == "manager" || $USER->id == 250919}<a href="#edit" class="edit" data-id="{$m.answer_id}">edit</a>{/if} <span>{$m.answer}</span></div>
		<hr />
	</li>
	{/foreach}
</ul>

{literal}
<script>
	$('a.edit').click(function() {
		var id = $(this).data('id');
		
		$('#faq-edit-form').find('input[name=id]').val(id);
		$('#faq-edit-form').find('textarea').val($(this).next('span').text());
	});
</script>


<style>
	#faq_feedback {
		padding:0;
		margin:0;
		list-style:none;
		margin-top:30px;
	}
	
	#faq_feedback li {
		margin-top:20px;
		position:relative;
	}
	
	#faq_feedback li:hover a.edit {
		display:block;
	}
	
	#faq_feedback li div {
		margin:7px 0 0 30px;
		border-left:2px solid #ccc;
		padding:10px;
		background: #f7f7f7;
		position:relative;
	}
	
	#faq_feedback li hr {
	    width: 94%;
	    margin: auto;
	    margin-top: 20px;
	    border-top: 1px dashed #CDCFCF;
   }
	
	a.edit {
		position:absolute;
		right:0;
		margin-right:10px;
		display:none;
	}
	
	#faq-edit-form {
		padding:10px;
		border:1px dashed orange;
	}
	
	#faq-edit-form textarea {
		width:99.5%;
		height:60px;
	}
	
	#faq-edit-form input {
		width:100%;
	}
</style>
{/literal}