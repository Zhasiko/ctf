		<? if (
			($_SERVER['PHP_SELF'] != '/log.php') &&
			($_SERVER['PHP_SELF'] != '/forget.php') &&
			($_SERVER['PHP_SELF'] != '/index.php')
		) { ?>				

            </div>
        </div>
		<style>
			.footer {
						background-color: #21232c; /* Цвет фона */
					}
		</style>
        <? if ($api->Managers->check_auth() == true) { ?>
        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">

                </nav>
                <div class="copyright ml-auto">

                </div>
            </div>
        </footer>
        <? } ?>
    </div>
    <? } ?>
</div>
<div id="black" onclick="closeWindow();"></div>
<div class="windowForm windowCats wC">
	<a onClick="javascript:closeWindow('no');" class="close">x</a>
    <div id="window" class="window"></div>
</div>
<script type="text/javascript" src="/library/js/core/popper.min.js"></script>
<script type="text/javascript" src="/library/js/core/bootstrap.min.js"></script>
<!-- jQuery UI -->
<script type="text/javascript" src="/library/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="/library/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<!-- Moment JS -->
<script type="text/javascript" src="/library/js/plugin/moment/moment.min.js"></script>
<!-- DateTimePicker -->
<script type="text/javascript" src="/library/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>
<? if ($api->Managers->check_auth() == true) { ?>
<!-- jQuery Scrollbar -->
<script type="text/javascript" src="/library/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<!-- jQuery Sparkline -->
<script type="text/javascript" src="/library/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
<!-- Datatables -->
<script type="text/javascript" src="/library/js/plugin/datatables/datatables.min.js"></script>
<!-- Bootstrap Notify -->
<script type="text/javascript" src="/library/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<!-- Bootstrap Toggle -->
<script type="text/javascript" src="/library/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<!-- Bootstrap Wizard -->
<script type="text/javascript" src="/library/js/plugin/bootstrap-wizard/bootstrapwizard.js"></script>
<!-- Select2 -->
<script type="text/javascript" src="/library/js/plugin/select2/select2.full.min.js"></script>
<!-- Sweet Alert -->
<script type="text/javascript" src="/library/js/plugin/sweetalert/sweetalert.min.js"></script>
<!-- Magnific Popup -->
<script type="text/javascript" src="/library/js/plugin/jquery.magnific-popup/jquery.magnific-popup.min.js"></script>
<? } ?>
<!-- Atlantis JS -->
<script type="text/javascript" src="/library/js/atlantis.min.js"></script>
<script type="text/javascript" src="/library/js/scripts.js?ver=1.2"></script>
<script type="text/javascript" src="/library/js/jquery.cooks.js"></script>
<script type="text/javascript" src="/library/js/jquery.inputmask.js"></script>
<script type="text/javascript">
			
	jQuery('.multiselect').select2({
		theme: "bootstrap"
	});
	
	jQuery('.dateInp').datetimepicker({
		format: 'DD.MM.YYYY',
	    locale: 'en'
	});
		
	jQuery('.dateInput').datetimepicker({
		format: 'YYYY-MM-DD',
	    locale: 'en'
	});
	
	function closeModal()
	{
		jQuery(".swal-overlay").removeClass("swal-overlay--show-modal");
	}
	
	jQuery(document).ready(function()
	{		
		jQuery('.only_int').bind("change keyup input click", function() {
			if (this.value.match(/[^0-9]/g)) {
				this.value = this.value.replace(/[^0-9]/g, '');
			}
		});
	});
	
</script>
</body>
</html>