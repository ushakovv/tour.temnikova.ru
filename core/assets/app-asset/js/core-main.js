App = {
	init: function(){
		$('.theModal .close').on('click', function(e){
			e.preventDefault();
			$.theModal().close();
		});
	},
	getCsrfToken: function(){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
		if(!csrfToken){
			console.error('CSRF token not found on page!');
		}
		return csrfToken;
	}
}

CheckboxColumn = {
	toggle: function(obj, url, attribute, id){
		var $obj = $(obj);
		//$obj.toggleClass("checked");
		var value = obj.checked ? 1 : 0;
		var _csrf = $("#hidden_csrf").val();
		$.post(url, {attribute: attribute, id: id, value: value, _csrf: _csrf}, function(){

		}, "json");

	}
}

MediaImage = {
	remove: function(obj, url, attribute, id){
		var $obj = $(obj);
		$obj.parents('.mediaImageInfo').remove();
		$.post(url, {attribute: attribute, id: id, value: null}, function(){
		}, "json");
		return false;
	}
}

Moderation = {
	approve: function(buttonObj, section, id){
		$.post(section, {id :id}, function(){
			$(buttonObj).parent().parent().removeClass("alert-danger").removeClass("alert-info").addClass("alert-success");
		});

		return false;
	},
	deny: function(buttonObj, section, id){
		$.post(section, {id :id}, function(){
			$(buttonObj).parent().parent().removeClass("alert-success").addClass("alert-danger");
		});
		return false;
	},
	approveAll: function(section, selector){
		var ids = [];
		$(selector).each(function(){
			ids.push( $(this).data().id );
		})
		$.post(section, { ids :ids }, function(){
			location.reload();
		});
		return false;
	}
}

Texts = {
	popup: null,
	current: null,
	currentInput: null,
	adapter: null,
	$listContent: null,
	init: function(){
		$('.textsTr').click(function(){
			var $this = $(this);
			var itemData = $this.data();
			if(itemData.type == "checkbox") return;
			Texts.$listContent = $this.find(".contentView");
			$('#textsPopup').theModal().open({
				closeOnESC: false,
				onOpen: function(el, options){
					Texts.open(el, itemData);
				}
			});
			return false;
		});
		$('.textsTr .cmsCbDiv').click(function(){
			var $this = $(this);
			$this.toggleClass("checked");
			var itemData = $this.parents(".textsTr").data();
			var value = $this.hasClass("checked") ? 1 : 0;
			$.post("texts/ajax-save/", {id: itemData.id, value: value}, function(data){

			}, "json");
		})

		$(".contentView").each(function(){
			var $this = $(this);
			if($this.height() > 200){
				$this.addClass('overflow');
			}
		})
	},
	open: function(el, itemData){
		var type = itemData.type;
		if(!TextsAdapters[type]){
			alert("Неверный тип блока: " + type);
		}
		this.adapter = TextsAdapters[type];
		el.find(".type-block").hide();
		$("#type-" + type).show();

		this.popup = el;
		this.current = itemData;
		el.find('h4').html(itemData.name + ' <span class="label label-info" style="text-transform: uppercase">'+itemData.lang+'</span>');
		var value = Texts.$listContent.html();
		this.adapter.set(value);
	},
	save: function(){
		var value = this.adapter.get();
		var id = this.current.id;
		var url = window.location.pathname.replace('/index', '') + '/ajax-save/';
		$.post(url, { id: id, value: value, _csrf: App.getCsrfToken() }, function(data){
			Texts.alert(true, "Изменения успешно сохранены");
			Texts.$listContent.html(value);
			//$.theModal().close();
		}, "json");
	},
	alert: function(success, text){
		var alertDiv = this.popup.find(".alert");
		alertDiv.html(text).show("fast");
		setTimeout(function(){
			alertDiv.hide("fast");
		}, 2000);
	}
}

TextsAdapters = {
	"textarea": {
		get: function(){
			return $("#ContentBlock_content").val();
		},
		set: function(value){
			$("#ContentBlock_content").val(value);
		}
	},
	"html": {
		get: function(){
			return aceEditor_ace.getValue();
		},
		set: function(value){
			aceEditor_ace.setValue(value);
		}
	},
	"wysiwyg": {
		get: function(){
			return $('#redactor').redactor('get', false);
		},
		set: function(value){
			$('#redactor').redactor('set', value, false);
		}
	}
}

Password = {
	showhide: function (obj) {
		var t = $(obj);
		var input = $("input", t.parent("div"));
		var eye = $(".pass", t);
		if (eye.hasClass("show")) {
			eye.removeClass("show");
			input.attr("type", "password");
		}
		else {
			eye.addClass("show");
			input.attr("type", "text");
		}
	},
	generate: function (obj, length) {
		// Генерация
		var result = '';
		var words = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
		var max_position = words.length - 1;
		for (i = 0; i < length; i++) {
			position = Math.floor(Math.random() * max_position);
			result = result + words.substring(position, position + 1);
		}

		// Подстановка в поле
		var t = $(obj);
		var input = $("input", t.parent("div"));
		var eye = $(".pass", t.parent("div"));
		if (!eye.hasClass("show")) {
			eye.addClass("show");
		}
		input.attr("type", "text");
		input.val(result);

		return false;
	}
}

Tabular = {
	index: 1000,
	deleteItem: function(obj){
		$(obj).parents('.tabular_item').remove();
	},
	addItem: function(obj){
		var $block = $(obj).parents('.tabular_block');
		var tpl = $block.find('.tabular_blank').html();
		this.index++;
		tpl = tpl.replace(/\{index\}/g, 't' + this.index);
		$block.find('.tabular_list').append(tpl);
	}
};

MediaFile = {
	del: function(obj, url, attribute, id){
		var $block = $(obj).parents(".js-mediaFile");
		$block.css('opacity', 0.5);
		$.post(url, {attribute: attribute, id: id, value: null}, function(){
			$block.remove();
		}, "json");
	}
};

LangTabs = {
	init: function () {
		$(".js-lang-tabs").each(function () {
			$(this).find("li > a").on("shown.bs.tab", function(e) {
				$.cookie('aplang', this.innerHTML, { expires: 7, path: '/' });
			});
		})
	}
};

$(function () {
	LangTabs.init();
});