/**
 * Скрипт быстрой корзины в топлайне.
 */
var qBask = {		
	basketSum: '#basket-sum',
	baskConteiner: '#b-qbasket-conteiner',
	baskDataConteiner: '#goods-wrap',
	delBtn: '.q-delete_good',
	goodsLine: '.qg1',
	emptyLine: '.no-goods',
	confirmText: 'Вы уверенны, что хотите удалить этот товар?!',
	link2oldBasket: '/basket/',
	submitBtnID: '#submitQBask',
		
	linkGetAjaxBasket: 'http://www.maryjane.ru/ajax/getQuickBasket/',
	recalcBasketSumLink: 'http://www.maryjane.ru/ajax/delete_good/0/0/?ajax=1',
	
	closeBask: '',							
	closeInterval: 10000,
	mouseOver: false,
	
	
	init: function(){
		this.initShow();
		this.initDel();
		this.initDelivForm();
	},

	initDelivForm: function () {
	//debugger;
		$(this.baskDataConteiner + " .obertka input").click(function(e){ 
			var self = this;
			$.post($(this).attr('href'), function(r){
				//self.checked = !self.checked;
			});
			e.stopPropagation(); 
		});		
		
		$("#show-link").click(function(event){
			$(".b-calc-delivery").hide();
			$("#calc-delivery-form").show();
			event.stopPropagation();
			return false;
		});
		
		// Клики по форме 
		$("#calc-delivery-form").click(function(event){
			event.stopPropagation();
			return false;
		});
		
		// Переход в оформление сразу 
		$(this.submitBtnID).click(function(event){
			document.location = '/order/';
			event.stopPropagation();
			return false;
		});
	},
	
	initShow: function (){
		$(".b-calc-delivery").show();
		$("#calc-delivery-form").hide();
				
		$(this.basketSum+', .basket-bg-wrap').click(function(){
			if ($(qBask.baskConteiner).css("display") == 'block') {				
				document.location = qBask.link2oldBasket;
				//return true;
			} else {
				$(qBask.baskDataConteiner).html("");
				$(qBask.baskConteiner).show();
				// Загружаем содержимое корзине
				qBask.loadQBasket();
			}
			return false;
		});
		
		$('body, #xq-close').click(function(){
			qBask.hideBasket();
		});	// При клике на боди нельзя возвращать false
		
		$('#xq-close').click(function(){
			qBask.hideBasket();
			return false;
		});
	},
	// Прячем корзину и другие действия
	hideBasket: function(){
		$(qBask.baskConteiner).hide();
		$(".b-calc-delivery").show();
		$("#calc-delivery-form").hide();
	},
	
	// Загрузка ajax-ом данных корзины
	loadQBasket: function () {
		$.get(qBask.linkGetAjaxBasket, function (qBData) {
			$(qBask.baskDataConteiner).html(qBData);
			qBask.initDel();
			qBask.initDelivForm();
			qBask.initThickbox();
			qBask.checkDisabledSubmit();
		});		
	},
	
	initThickbox: function (){
		try {
			tb_init('a.thickbox, area.thickbox, input.thickbox');
		} catch(e) {}
		
		$("a.thickbox").click(function(event){
			event.stopPropagation();
			return false;
		});
		
		// Проинициализировать клики для переходы на футболки
		$(".q-name a").click(function(event){
			event.stopPropagation();
			return true;
		});
	},
	
	checkDisabledSubmit: function () {
		if ($(".q-goods").length < 1) {
			$(qBask.submitBtnID).addClass('disabled');
		} else {
			$(qBask.submitBtnID).removeClass('disabled');
		}
	},
	
	// Показать быструю корзину и проскролить к ней
	showBasketAndScroll: function () {
		// Показать блок быстрой корзины
		$(qBask.baskConteiner).show();
		// Загрузить Аджаксом быструю корзину				
		this.loadQBasket();
		// проскролить вверх 
		window.scrollTo(0,0);
		// Пересчитать сумму
		this.recalcBaskSum();
	},
	
	showDeliveryCalc: function(){		
		qBask.showBasketAndScroll();
		$(".b-calc-delivery").hide();
		$("#calc-delivery-form").show();		
	},
	
	// Пересчитать стоимость товаров в корзине
	recalcBaskSum: function (){
		// Так как мы не можем пересчитать сумму и бонусы, поэтому посылаем 
		// Антону фейковый запрос на удаление и он возвращает нам нужные данные
		$.get(qBask.recalcBasketSumLink,function(response){
			// тут делаем какой-то AJAX запрос на сервер для удаления товара, айдишку где-то взять.
			response = eval('('+response+')');																						
			if ($(qBask.goodsLine).length < 1) {
				$(qBask.emptyLine).show();
				$(".sum-bonus h3").text("Итого: 0 руб.");
				$(".sum-bonus .you_return").text("Бонус за заказ: 0 руб.");
			} else {
				$(".sum-bonus h3").text("Итого: "+response[0]+" руб.");
				$(".sum-bonus .you_return").text("Бонус за заказ: "+response[1]+" руб.");
				$(qBask.emptyLine).hide();
			}
			$(qBask.basketSum).text(response[0]+' руб.');
			// Если в корзине ничего небыло - то нужно поставить стили для непустой
			$(".empty-basket").removeClass("empty-basket");
			$("#basket-sum").css({fontSize:'11px', fontWeight:'normal'});
		}); 
	},
	// Удаление товара из быстрой корзины
	initDel: function(){
		$(this.delBtn).unbind('click');
		
		$(this.delBtn).click(function(){
			if (confirm(qBask.confirmText)) {
				$(this).parent(".q-goods").remove();
				$.get($(this).attr("href")+'?ajax=1', function(response){
					// тут делаем какой-то AJAX запрос на сервер для удаления товара, айдишку где-то взять.
					response = eval('('+response+')');																						
					if ($(qBask.goodsLine).length < 1) {
						$(qBask.emptyLine).show();
						$(".sum-bonus h3").text("Итого: 0 руб.");
						$(".sum-bonus .you_return").text("Бонус за заказ: 0 руб.");
					} else {
						$(".sum-bonus h3").text("Итого: "+response[0]+" руб.");
						$(".sum-bonus .you_return").text("Бонус за заказ: "+response[1]+" руб.");
						$(qBask.emptyLine).hide();
					}
					$(qBask.basketSum).text(response[0]+' руб.');
					qBask.checkDisabledSubmit();
				});
													// Пересчитать цену после удаления
			}
			return false;
		});
		
		// инициализация пустой корзины
		if ($(qBask.goodsLine).length < 1) {
			$(qBask.emptyLine).show();
		} else {
			$(qBask.emptyLine).hide();
		}
	}
};