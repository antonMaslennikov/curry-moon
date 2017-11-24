<div class="good-tpl-poster">
	<ul></ul>
	<div class="poster-params">
		<div class="items clearfix"><!--noindex-->
			{foreach from=$styles.poster item="p" name="poster"}
			{if !$p.relations}
			<div class="item" style_id="{$p.style_id}" hash="{$p.style_slug}" subcategory="{$p.subcategory}">
				<div class="icon">
					<a href="#" title="{$p.style_name}" rel="nofollow"><span></span></a>
				</div>
				<div class="title">{if $modal}{$p.style_name}{else}<br replaces="{$p.style_name}"/>{/if}</div>
				<ul class="size-ul">
					{foreach from=$p.sizes item="size" key="size_id"}
					<li value="{$size_id}"><a href="#!{$size_id}" title="{$size.en}" rel="nofollow">{if $modal}{$size.en} см{else}<br replaces="{$size.en} см"/>{/if}</a></li>
					{/foreach}
				</ul>
			</div>
			{/if}
			{/foreach}
			<!--/noindex-->			
		</div>
	</div>
</div>

{*literal}
<script type="text/javascript">
	$(document).ready(function(){
	
		//заполняем ручками постеры и размеры
		//на данный момент, у нас есть сформированный список, который перезатирается. нужно взять ид активного элемента.
		
		var active = false;
		if($('.good-tpl-poster .items .item').length && $('.good-tpl-poster .items .item').filter('.active').length){
			active = $('.good-tpl-poster .items .item').filter('.active').attr('style_id');
		}
		$('.good-tpl-poster .items').html('');
		var tpl = '<div class="item" style_id="{style_id}" hash="{style_slug}" subcategory="{subcategory}">'+
					'	<div class="icon">'+
					'		<a href="#" title="{style_name}" rel="nofollow"><span></span></a>'+
					'	</div>'+
					'	<div class="title">{style_name}</div>'+
					'	<ul class="size-ul"></ul>'+
					'</div>';
		
		if (goodForm.designData.poster) {
			var p = goodForm.designData.poster;
			var _arr = {};
			var f = function(id){
				var s='';
				if (p[id])
				for(var j in p[id].sizes)
					if (p[id].sizes[j])
						s+='<li class="'+(active==p[id].style_id?"on":"")+'" value="'+j+'" style_id="'+p[id].style_id+'" hash="'+p[id].style_slug+'" subcategory="'+p[id].subcategory+'" quantity="'+p[id].sizes[j].quantity+'"><a href="#!'+(p[id].sizes[j].size_id?p[id].sizes[j].size_id:'')+'" title="'+p[id].sizes[j].en+'" rel="nofollow">'+p[id].sizes[j].en+' см</a></li>';
				_arr[id] = p[id];
				return s;
			}
			for(var i in p) if (p[i])
			if (!_arr[i] && p[i].relations) {
				var s = $(tpl.replace('{style_id}',p[i].style_id).replace('{style_slug}',p[i].style_slug).replace('{subcategory}',p[i].subcategory).replace(/{style_name}/g,p[i].style_name));
				var d = s.find('.size-ul');
				if (p[i].relations) {
				for(var j in p[i].relations) if (p[i].relations[j] && !_arr[j])
					d.append(f(p[i].relations[j]));
				}/* else if (p[i].sizes) {
				for(var j in p[i].sizes) if (p[i].sizes[j])
					d.append('<li value="'+j+'" style_id="'+p[i].style_id+'" hash="'+p[i].style_slug+'" subcategory="'+p[i].subcategory+'"><a href="#!'+(p[i].sizes[j].size_id?p[i].sizes[j].size_id:'')+'" title="'+p[i].sizes[j].en+'" rel="nofollow">'+p[i].sizes[j].en+' см</a></li>');
				}*/
				if(s.find('.size-ul li.on').length){
					s.addClass('active');
				}
				$('.good-tpl-poster .items').append(s);
			}
			$('.good-tpl-poster .items').append('<div style="clear:both;"></div>');
		}
		
		function Rebuildposter() {
			//goodForm.curGender = $('.good-tpl-poster .items .item.active').attr('style_id');
			if ($('.good-tpl-poster .item.active .size-ul li.on').length = 0)
				$('.good-tpl-poster .item.active .size-ul li:first').addClass('on');
			goodForm.curSize = $('.good-tpl-poster .item.active .size-ul li.on').attr('value');
			if ($('.good-tpl-poster .item.active .size-ul li.on').length > 0)
				goodForm.curGender = $('.good-tpl-poster .item.active .size-ul li.on').attr('style_id');
			else goodForm.curGender = $('.good-tpl-poster .items .item.active').attr('style_id');
			
			var data = goodForm.getDesignData();
			if (data) {
				if (!data.sizes[goodForm.curSize].colors[goodForm.curColor])
					for(var c in data.sizes[goodForm.curSize].colors) if (data.sizes[goodForm.curSize].colors[c]) { goodForm.curColor = c; }
				var d = $('.good-tpl-poster');
				d.find('ul:first').remove();
				d.prepend(data.style_composition);
			}
			
            //goodForm.buildFirst();
			goodForm.initVisibleGalleryItems();
			goodForm.reloadImg();
			goodForm.changePrice();
			goodForm.makeBreadCrumps();
			
		}
		
		$('.o_good[subcategory=poster]').unbind('click').bind('click',function(){
			Rebuildposter();
			return false;
		});
		
		$('.good-tpl-poster .items .item').unbind('click').bind('click',function(){
			
			$('.good-tpl-poster .items .item').removeClass('active');
			$(this).addClass('active');

			$('.good-tpl-poster .items .item .size-ul li').removeClass('on');
			$('.good-tpl-poster .items .item.active .size-ul li:first').addClass('on');
			
			Rebuildposter();
			return false;
		});
		
		$('.good-tpl-poster .item .size-ul li a').unbind('click').bind('click',function(){
			
			if ($(this).parents('.item:first').attr('style_id') != $('.good-tpl-poster .items .item.active').attr('style_id')) {
				$('.good-tpl-poster .items .item').removeClass('active');
				$(this).parents('.item:first').addClass('active');
			}
			
			$('.good-tpl-poster .items .item .size-ul li').removeClass('on');
			$('.good-tpl-poster .items .item.active .size-ul li[value='+$(this).parent().attr('value')+']').addClass('on');
			
			Rebuildposter();
			return false;
		});
		
		if ($('.good-tpl-poster .items .item.active').length == 0) {
			if ($('.good-tpl-poster .items .item .size-ul li[style_id="'+goodForm.curGender+'"]').length > 0)
				$('.good-tpl-poster .items .item .size-ul li[style_id="'+goodForm.curGender+'"]').parents('.item').click();
			else $('.good-tpl-poster .items .item[style_id="'+goodForm.curGender+'"]').click();
		}
	});
</script>
{/literal*}