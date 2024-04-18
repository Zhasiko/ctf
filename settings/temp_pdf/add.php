<?
$lang="ru";
$title="Добавить шаблон";
if (isset($_GET["edit"]) && intval($_GET["edit"])!=0)
	$title="Редактировать шаблон";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true)
{
	if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2)
	{
		?>
		<div class="card">
            <div class="card-body">
				<?
        		$no_need = Array('id');
				$table = 'i_temp_pdf';
				$query = "SHOW COLUMNS FROM $table";
				if ($output = mysql_query($query)):
					$columns = array();
					while($result = mysql_fetch_assoc($output)):
						if (!in_array($result['Field'], $no_need))
							$columns[] = $result['Field'];
					endwhile;
				endif;
				
				$fields = Array(); $type_field = Array(); $mandat = Array(); $class = Array(); $java = '';
				foreach($columns as $k=>$v)
				{
					$fields[$v] = '';
					$type_field[$v] = 'textarea';
					$mandat[$v] = 0;
					$class[$v] = '';
					
					$java .= '+"&'.$v.'="+jQuery("#'.$v.'").val()';
				}				
				
                if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0)				
                {
					$id = intval($_GET["edit"]);
					$s=mysql_query("SELECT * FROM `".$table."` WHERE `id`='".$id."' LIMIT 1");
                    if (mysql_num_rows($s) > 0)
                    {
                        $r=mysql_fetch_array($s);

                        foreach($columns as $k=>$v)				
							$fields[$v] = stripslashes($r[$v]);
                    }
                }
		
				$name_ru = Array();
				$name_ru["name_temp"] = 'Название шаблона';
				$name_ru["name"] = 'Наименование компании';
				$name_ru["adres"] = 'Адрес';
				$name_ru["phone"] = 'Телефон/факс';
				$name_ru["mail"] = 'E-mail';
				$name_ru["number"] = 'Номер';
				$name_ru["date_do"] = 'Дата до';
				$name_ru["director"] = 'Директор';		
										
				$mandat["name_temp"] = 1;
				$mandat["name"] = 1;
				$mandat["adres"] = 1;			
				$mandat["number"] = 1;
				$mandat["date_do"] = 1;
				$mandat["director"] = 1;
						
				$type_field["phone"] = 'input';
				$type_field["mail"] = 'input';
				$type_field["number"] = 'input';
				$type_field["date_do"] = 'input';
				$type_field["director"] = 'input';
                ?>                
                				
				<? foreach($columns as $k=>$v)	{ ?>
				<div class="form-group form-show-validation row">
                    <label for="name" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right"><?=$name_ru[$v]?> <?=($mandat[$v]==1?'<span class="required-label">*</span>':'')?></label>
                    <div class="col-lg-5 col-md-9 col-sm-8">						
							<? if ($type_field[$v] == 'input') { ?>
                        <input type="text" class="form-control<?=$class[$v]?>" id="<?=$v?>" value="<?=$fields[$v]?>" />
							<? } else { ?>
						<textarea class="form-control" id="<?=$v?>"><?=$fields[$v]?></textarea>
							<? } ?>						
                        <span class="control__help" id="error_<?=$v?>"></span>
                    </div>
                </div>								
				<? } ?>
				
			</div>

            <div class="card-action t-right" id="action">				
            	<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
                <button type="button" class="btn btn-danger action"  style="margin-right:50px" onclick="deleteT();">Удалить</button>
                <? } else { ?>
				<a class="btn btn-warning action" style="float:left" href="javascript:history.go(-1)">Вернуться назад</a>
				<? } ?>
                <button class="btn btn-success action" onclick="addT();"><?=(isset($_GET["edit"]) ? 'Сохранить' : 'Добавить')?></button>
                <div class="loading">
                    <img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
                </div>
                <div id="protocol"></div>
            </div>

		</div>
		<script type="text/javascript">

			function addT()
			{
				var err_key = 0;
				var focused = 0;

				jQuery(".card-body input").css("border-color", "#c9cbcd");
				jQuery(".card-body textarea").css("border-color", "#c9cbcd");
				jQuery(".control__help").html('').hide();

				<? foreach($mandat as $k=>$v) { ?>
					<? if ($v == 1) { ?>
				if (jQuery("#<?=$k?>").val() == '')
				{
					err_key = 1;
					jQuery("#<?=$k?>").css("border-color", "#f00");
					jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
					if (focused == 0) { jQuery("#<?=$k?>").focus(); focused = 1; }
				}
					<? } ?>
				<? } ?>												

				if (err_key == 0)
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? 'edit' : 'add')?>&x=secure<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? '&edit='.intval($_GET["edit"]) : '')?>"<?=$java?>,
						type: "POST",
						dataType : "html",
						cache: false,

						beforeSend: function()		{ jQuery("#protocol").html(""); jQuery(".action").hide(); jQuery(".loading").show(); },
						success:  function(data)	{ jQuery("#protocol").html(data); <?php /*?>jQuery(".action").show();<?php */?> jQuery(".loading").hide(); },
						error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".action").show(); jQuery(".loading").hide(); }
					});
				}
			}

			<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
			function deleteT()
			{
				var err_key = 0;
				var focused = 0;

				if (confirm("Вы действительно хотите удалить шаблон?"))
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=delete&edit=<?=intval($_GET["edit"])?>&x=secure",
						type: "POST",
						dataType : "html",
						cache: false,

						beforeSend: function()		{ jQuery("#protocol").html(""); jQuery("#action").hide(); },
						success:  function(data)	{ jQuery("#protocol").html(data); jQuery("#action").show(); },
						error: function()			{ alert("Невозможно связаться с сервером"); jQuery("#action").show(); }
					});
				}
			}
			<? } ?>

		</script>
		<?
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
