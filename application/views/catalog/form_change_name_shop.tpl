{literal}
<style>
.change {margin: 0;padding: 0px;position: absolute;top:-1px;left: 0;}
.change a {color:green;	padding-bottom: 10px;}
</style>
{/literal}


<div id="change-form" style="display:none;">
   <div class="change-form">
        <!--div style="position:relative;width:100%;height:5px;"><div class="close"></div></div-->
        <form method="POST" action="/{$module}/{$user.user_login}/savePersonalData/" enctype="multipart/form-data">
            <div class="title">{$L.SHOPWINDOW_title_hint}</div>
			<span>{$L.SHOPWINDOW_100_symbols_left}</span>
			<input class="name" placeholder="{if $user.personal_title == ''}Заголовок{/if}" name="personal_title" value="{if $user.personal_title}{$user.personal_title}{else}Магазин футболок {$user.user_login}{/if}" type="text"/>
            <span>{$L.SHOPWINDOW_1000_symbols_left}</span>
            <textarea placeholder="{if $user.meta_description == ''}Описание{/if}" cols="1" rows="2" name="meta_description">{$user.meta_description}</textarea>
            <div class="file">
                <div>{$L.SHOPWINDOW_title_picture} jpg 980x100</div>
                <div><input type="file" name="personal_header" /></div>
                
                {if $USER->meta->personal_header}
            	<div><a href="/{$module}/{$user.user_login}/savePersonalData/delete_header/" onclick="return confirm('Вы уверены?')">Удалить</a></div>
            	{/if}
            </div>
            
            <input type="submit" value="Сохранить" />
        </form>
    </div>
</div>
<!--шаблон form_change_name_shop.tpl-->
{*
<div class="change">
	<a class="thickbox" href="/#TB_inline?width=484&height=280&inlineId=change-form" title="" rel="nofollow">Изменить заголовок</a>
	<!-- a href="#" title="изменить" rel="nofollow">изменить</a -->
	
	 {if $USER->meta->mjteam == 'super-admin'}
		| <a href="/{$module}/{$user.user_login}/disabled/" rel="nofollow" class="thickbox" title="Отключённые работы"><span>Отключённые работы</span></a>
	{/if}
</div>
*}

{literal}
<script type="text/javascript">
	$('.change-form').find('textarea')
	.keypress(function(){
		return (this.countWord > $(this).val().length);
	})
	.change(function(){
		return (this.countWord > $(this).val().length);
	})
	.keyup(function(){
		$(this).prev('span').html('Осталось '+(this.countWord-$(this).val().length)+' символов');
	})[0].countWord = 10000;	
	$('.change-form textarea').keyup();	
	
	$('.change-form').find('input[type="text"]')
	.keypress(function(){
		return (this.countWord > $(this).val().length);
	})
	.change(function(){
		return (this.countWord > $(this).val().length);
	})
	.keyup(function(){
		$(this).prev('span').html('Осталось '+(this.countWord-$(this).val().length)+' символов');
	})[0].countWord = 100;
	$('.change-form input[type="text"]').keyup();
	
	/* версия красивого показа всплывающего окна возле кнопки изменить 11.11.13
	change = {
		init: function(p){
			p = (p?p:{});
			for(var n in p) this[n] = p[n];
			var self = this;

			if ($(this.action).length == 0) return;

			//$(this.div).find('form').ajaxForm(function() {
			//	self.hide();
			//});

			$(this.div).find('textarea')
					.keypress(function(){
						return (self.countWord > $(this).val().length);
					})
					.change(function(){
						return (self.countWord > $(this).val().length);
					})
					.keyup(function(){
						$(self.div).find('span').html('Осталось '+(self.countWord-$(this).val().length)+' символов');
					})

			$(this.div).find('.close').click(function(){ self.hide(); });

			$(this.action)
					.mousedown(function(){ $(this).css('color','red'); })
					.mouseup(function(){ $(this).css('color',''); })
					.click(function(){
						if (self.blocked) return;
						self.blocked = true;
						if ($(self.div)[0].style.display == 'none')
							self.show();
						else
							self.hide();
						return false;
					});
		},
		show: function(){
			var self = this;

			var d = $(this.action);
			var d1 = $(this.action).offset();
			//var d2 = $(this.action).position();
			
			//$('#TB_overlay').show();
			
			//по центру
			//$(this.div).css({ left: ((d1.left+d.width()/2) - ($(this.div).width()/2))+'px', top: (d1.top+25)+'px' });
			//$(this.div).css({ left: ($(window).width()/2 - ($(this.div).width()/2))+'px', top: ($(window).height()/2 - ($(this.div).height()/2) - 25)+'px' });

			//возле кнопки
			$(this.div).css({ left: Math.max(d1.left, 20)+'px', top: (d1.top+60)+'px' });

			//$(this.div).find('.name, textarea, .file input').val('');
			//$(this.div).find('span').html('Осталось '+(this.countWord)+' символов');
			$(self.div).find('span').html('Осталось '+(self.countWord-$(self.div).find('textarea').val().length)+' символов');
			this.blocked = true;
			$(this.div).fadeIn(500, function(){ self.blocked = false; });
		},
		hide: function() {
			var self = this;
			this.blocked = true;
			//$('#TB_overlay').hide();
			$(this.div).fadeOut(500, function(){ self.blocked = false; });
		}
	}

	$(document).ready(function(){
		change.init({ div: '.change-form', countWord: 256, action: '.change a' });
	});*/
</script>
{/literal}