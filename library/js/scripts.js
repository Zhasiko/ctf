var navItemClicked = $('.page-navigation > .nav-item');
navItemClicked.on("click", function(e) {
	if(window.matchMedia('(max-width: 991px)').matches) {
		if(!($(this).hasClass('show-submenu'))) {
			navItemClicked.removeClass('show-submenu');
			$(this).addClass('show-submenu');
		} else {
			$(this).removeClass('show-submenu');
		}
	}
});

jQuery(document).ready(function() {

	jQuery(".logout_btn").click(function()
	{
		jQuery.ajax(
		{
			url: "/logout.php",
			data: "do=logout&x=secure",
			type: "POST",
			dataType : "html",
			cache: false,

			beforeSend: function()		{ jQuery("#log_protocol").html("").hide(); },
			success:  function(data)	{ jQuery("#log_protocol").html(data); }

		});
		return false;
	});

	jQuery('.only_int').bind("change keyup input click", function() {
		if (this.value.match(/[^0-9-]/g)) {
			this.value = this.value.replace(/[^0-9]/g, '');
		}
	});

	jQuery('.only_float').bind("change keyup input click", function() {
		if (this.value.match(/[^0-9-.,]/g)) {
			this.value = this.value.replace(/[^0-9.,]/g, '');
		}
	});

	jQuery('.only_int_lat').bind("change keyup input click", function() {
		if (this.value.match(/[^a-zA-Z0-9]/g)) {
			this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
		}
	});
	
	jQuery('.vin').bind("change keyup input click", function() {
		if (this.value.match(/[^abcdefghjklmnprstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ0-9]/g)) {
			this.value = this.value.replace(/[^abcdefghjklmnprstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ0-9]/g, '');
		}
	});
});

function closeWindow(type)
{
	jQuery("#black").hide();
	jQuery(".wC").hide();
	if (type != 'no')
		jQuery(".window").empty();
	else
	{
		jQuery(".windowCatsAdd input[type='text']").val('').css("border-color", "#c9cbcd");
		jQuery(".windowCatsAdd select").val('').css("border-color", "#c9cbcd");
		jQuery(".control__help").html('').hide();
		jQuery("#for_cat").slideUp();
	}
}

function closeWindow2()
{
	jQuery(".window").empty();
	jQuery("#black").hide();
	jQuery(".wC").hide();
}

function showWindowCats()
{
	jQuery("#black").show();
	jQuery(".windowCats").show();
}

function showAddCats()
{
	jQuery("#black").show();
	jQuery(".windowCatsAdd").show();
}

jQuery(function(){
	jQuery("input.phone").inputmask("+7(999)9999999");
});

function format(x) {
	return isNaN(x)?"":x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function EmailCheck(value)
{
	var re = /^\w+([\.-]?\w+)*@(((([a-z0-9]{2,})|([a-z0-9][-][a-z0-9]+))[\.][a-z0-9])|([a-z0-9]+[-]?))+[a-z0-9]+\.([a-z]{2}|(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum))$/i;

	if(re.test(value)) { return true; }
	else { return false; }
}

function login()
{
	var login = jQuery("#login").val();
	login = login.replace(/_/g, "");

	if (jQuery("#login").val()!=''/* && login.length == 14 && jQuery("#login_pass").val()!=''*/)
	{
		var remember = 0;
		if (jQuery("#remember").prop("checked") == true)	{ remember=1; }

		jQuery.ajax(
		{
			url: "/login.php",
			data: "do=auth&login="+jQuery("#login").val()+"&pass="+jQuery("#pass").val()+"&remember="+remember/*+"&g-recaptcha-response="+grecaptcha.getResponse()*/+"&x=secure",
			type: "POST",
			dataType : "html",
			cache: false,

			beforeSend: function()		{ jQuery("#log_protocol").html(""); },
			success:  function(data)	{ jQuery("#log_protocol").html(data); }

		});
	}
	else { jQuery("#protocolLog").html("<p style=\"color:#f00;margin:10px 0;\">Заполните поля!</p>").slideDown(700); }
}

function func_login(event){
	if(event.keyCode==13){
		login();
	}
}

function nextForget()
{
	jQuery("#login").css("border-color", "#ebedf2");

	var login = jQuery("#login").val();
	login = login.replace(/_/g, "");

	if (jQuery("#login").val()!='' && login.length == 14)
	{
		jQuery.ajax(
		{
			url: "forget.php",
			data: "do=nextForget&login="+jQuery("#login").val()+"&x=secure",
			type: "POST",
			dataType : "html",
			cache: false,

			beforeSend: function()		{ jQuery("#nextStep").html(""); jQuery(".nextForget").hide(); jQuery(".loading").show(); },
			success:  function(data)	{ jQuery("#nextStep").html(data); jQuery(".loading").hide(); },
			error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".nextForget").show(); jQuery(".loading").hide(); }
		});
	}
	else
	{
		jQuery("#login").css("border-color", "#f00");
		jQuery("#login").focus();
	}
}

function funcNext(event){
	if(event.keyCode==13){
		nextForget();
	}
}

function nextCode()
{
	jQuery("#login").css("border-color", "#ebedf2");

	var login = jQuery("#login").val();
	login = login.replace(/_/g, "");

	if (jQuery("#forget_code").val()!='' && jQuery("#forget_code").val().length == 4)
	{
		jQuery.ajax(
		{
			url: "forget.php",
			data: "do=nextCode&login="+jQuery("#login").val()+"&code="+jQuery("#forget_code").val()+"&x=secure",
			type: "POST",
			dataType : "html",
			cache: false,

			beforeSend: function()		{ jQuery(".nextCode").hide(); jQuery(".loading").show(); },
			success:  function(data)	{ jQuery("#nextStep").html(data); jQuery(".loading").hide(); },
			error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".nextCode").show(); jQuery(".loading").hide(); }
		});
	}
	else
	{
		jQuery("#forget_code").css("border-color", "#f00");
		jQuery("#forget_code").focus();
	}
}

function funcCode(event){
	if(event.keyCode==13){
		nextCode();
	}
}

function changePass()
{
	var err_key = 0;
	var focused = 0;

	jQuery(".login-form input").css("border-color", "#ebedf2");

	if ((jQuery("#pass").val() == '') || (jQuery("#pass").val().length < 4))
	{
		err_key = 1;
		jQuery("#pass").css("border-color", " #f00");
		if (focused == 0) { jQuery("#pass").focus(); focused = 1; }
	}

	if (jQuery("#pass2").val() == '')
	{
		err_key = 1;
		jQuery("#pass2").css("border-color", "#f00");
		if (focused == 0) { jQuery("#pass2").focus(); focused = 1; }
	}

	if (jQuery("#pass").val() != jQuery("#pass2").val())
	{
		err_key = 1;
		jQuery("#pass").css("border-color", "#f00");
		jQuery("#pass2").css("border-color", "#f00");
		if (focused == 0) { jQuery("#pass").focus(); focused = 1; }
	}

	if (err_key == 0)
	{
		jQuery.ajax(
		{
			url: "forget.php",
			data: "do=changePass&login="+jQuery("#login").val()+"&code="+jQuery("#forget_code").val()+"&pass="+jQuery("#pass").val()+"&x=secure",
			type: "POST",
			dataType : "html",
			cache: false,

			beforeSend: function()		{ jQuery(".changePass").hide(); jQuery(".loading").show(); },
			success:  function(data)	{ jQuery("#nextStep").html(data); jQuery(".loading").hide(); },
			error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".changePass").show(); jQuery(".loading").hide(); }
		});
	}
}

function funcPass1(event){
	if(event.keyCode==13){
		jQuery("#pass2").focus();
	}
}

function funcPass2(event){
	if(event.keyCode==13){
		changePass();
	}
}

/*
function escapeHtml(text) 
{
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};

	return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
*/
function chooseCats(id, ids, name_cats)
{
	jQuery("#id_cats").val(ids);
	jQuery("#id_cats2").val(id);
	jQuery("#name_cats").val(name_cats);

	closeWindow('no');	
}

function chooseSort(id)
{
	if (jQuery("#c_id_sort_"+id).prop("checked") == true)
		jQuery("#id_sort_"+id).prop('readonly', false);
	else
		jQuery("#id_sort_"+id).prop('readonly', true);
}

jQuery(document).ready(function(){
	var width_table = jQuery("table.dataTable").width();
	jQuery(".up_scroll").css("width", width_table);
});

jQuery(function(){
	jQuery(".table-responsive_up_scroll").scroll(function(){
		jQuery(".table-responsive").scrollLeft($(".table-responsive_up_scroll").scrollLeft());
	});

	jQuery(".table-responsive").scroll(function(){
		jQuery(".table-responsive_up_scroll").scrollLeft($(".table-responsive").scrollLeft());
	});
});

function saveField(name, id)
{
	jQuery.ajax({
		url: "ajax.php",
		data: {
			do:	'saveField',
			name: name,
			id: id,
			value: jQuery('#'+name+'_'+id).val(),			
			x: 'secure'	
		},
		type: "POST",
		dataType : "html",
		cache: false,
		
		beforeSend: function()		{  },
		success:  function(data)	{ jQuery("#saveField").html(data); },
		error: function()			{ alert("Невозможно связаться с сервером");  }
	});															
}
