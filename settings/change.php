<?
$lang="ru";
$title="Изменить пароль";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true) {
?>
<style>

	body {
		background-color: #0d1b2a; /* Цвет фона */
	}
	.card {
		background-color: #0d1b2a; /* Цвет фона */
	}
	.card-body{
		color: white !important;
	}

	/* .basic-datatables{
		background-color: #21232c;
	} */
</style>
<div class="card">		
    <div class="card-body">    
		<div class="form-group form-show-validation row">
            <label for="user_pass_old" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Старый пароль <span class="required-label">*</span></label>
            <div class="col-lg-4 col-md-9 col-sm-8">
                <input type="password" class="form-control" id="user_pass_old" value="" />
                <span class="control__help" id="user_pass_info_old"></span>
            </div>            
        </div>
        
        <div class="form-group form-show-validation row">
            <label for="user_pass1" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Новый пароль <span class="required-label">*</span></label>
            <div class="col-lg-4 col-md-9 col-sm-8">
                <input type="password" class="form-control" id="user_pass1" value="" />
                <span class="control__help" id="user_pass_info1"></span>
            </div>            
        </div>
        
        <div class="form-group form-show-validation row">
            <label for="user_pass2" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Подтверждение пароля <span class="required-label">*</span></label>
            <div class="col-lg-4 col-md-9 col-sm-8">
                <input type="password" class="form-control" id="user_pass2" value="" />
                <span class="control__help" id="user_pass_info2"></span>
            </div>            
        </div>
	</div>
    
    <div class="card-action t-right">
        <button class="btn btn-success" id="action" class="button button_green" onclick="savePass();">Сохранить</button>
        <div class="loading">
        	<img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
        </div>
        <div id="protocol"></div>
    </div>
        	   
</div>
<script type="text/javascript">
	
	function savePass()
	{
		var err_key = 0;
		var focused = 0;				
				
		jQuery(".calc__card input").css("border-color", "#c9cbcd");
		jQuery(".control__help").html('').hide();											
		
		if (jQuery("#user_pass1").val() != '' && jQuery("#user_pass_old").val() != '')
		{										
			if ((jQuery("#user_pass1").val() == '') || (jQuery("#user_pass1").val().length < 4))  
			{ 
				err_key = 1; 
				jQuery("#user_pass_info1").html('не верно указан  Пароль, пароль должен быть не короче 4х символов').css("display", "inline-block"); 
				jQuery("#user_pass1").css("border-color", " #f00"); 
				if (focused == 0) { jQuery("#user_pass1").focus(); focused = 1; } 
			} 
			
			if (jQuery("#user_pass2").val() == '')	
			{ 
				err_key = 1; 
				jQuery("#user_pass_info2").html('не указано подтверждение Пароля').css("display", "inline-block"); 
				jQuery("#user_pass2").css("border-color", "#f00");	
				if (focused == 0) { jQuery("#user_pass2").focus(); focused = 1; }
			} 
			
			if (jQuery("#user_pass1").val() != jQuery("#user_pass2").val()) 
			{ 
				err_key = 1; 
				jQuery("#user_pass_info2").html('не верное подтверждение Пароля').css("display", "inline-block"); 
				jQuery("#user_pass1").css("border-color", "#f00"); 
				jQuery("#user_pass2").css("border-color", "#f00"); 	
				if (focused == 0) { jQuery("#user_pass1").focus(); focused = 1; }  
			}
		}
		else
		{
			err_key = 1; 					
			jQuery("#user_pass1").css("border-color", "#f00"); 
			jQuery("#user_pass2").css("border-color", "#f00"); 	
			if (focused == 0) { jQuery("#user_pass1").focus(); focused = 1; }  	
		}
				
		if (err_key == 0)
		{			
			jQuery.ajax(
			{
				url: "ajax.php",
				data: "do=settings&pass_old="+jQuery("#user_pass_old").val()+"&pass="+jQuery("#user_pass1").val()+"&x=secure",
				type: "POST",
				dataType : "html",
				cache: false,
	
				beforeSend: function()		{ jQuery("#protocol").html(""); jQuery("#action").hide(); jQuery(".loading").show(); },
				success:  function(data)	{ jQuery("#protocol").html(data); jQuery("#action").show(); jQuery(".loading").hide(); },
				error: function()			{ alert("Невозможно связаться с сервером"); jQuery("#action").show(); jQuery(".loading").hide(); }
			});
		}		
	}
	
</script>
<? 
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>