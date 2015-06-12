<!-- BEGIN FOOTER -->
	<div data-ng-include="'tpl/footer.html'" data-ng-controller="FooterController" class="page-footer">
	</div>
	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE JQUERY PLUGINS -->
	<!--[if lt IE 9]>
	<script src="../../../assets/global/plugins/respond.min.js"></script>
	<script src="../../../assets/global/plugins/excanvas.min.js"></script> 
	<![endif]-->
	<script src="{{ Request::root() }}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>	
	<script src="{{ Request::root() }}/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<!-- END CORE JQUERY PLUGINS -->

	<!-- BEGIN CORE ANGULARJS PLUGINS -->
	<script src="{{ Request::root() }}/assets/global/plugins/angularjs/angular.min.js" type="text/javascript"></script>	
	<script src="{{ Request::root() }}/assets/global/plugins/angularjs/angular-sanitize.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/angularjs/angular-touch.min.js" type="text/javascript"></script>	
	<script src="{{ Request::root() }}/assets/global/plugins/angularjs/plugins/angular-ui-router.min.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-route.min.js"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/angularjs/plugins/ocLazyLoad.min.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/global/plugins/angularjs/plugins/ui-bootstrap-tpls.min.js" type="text/javascript"></script>
	<!-- END CORE ANGULARJS PLUGINS -->
	
	<!-- BEGIN APP LEVEL ANGULARJS SCRIPTS -->
	<script src="js/app.js" type="text/javascript"></script>
	<script src="js/directives.js" type="text/javascript"></script>
	<!-- END APP LEVEL ANGULARJS SCRIPTS -->

	<!-- BEGIN APP LEVEL JQUERY SCRIPTS -->
	<script src="{{ Request::root() }}/assets/global/scripts/metronic.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
	<script src="{{ Request::root() }}/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>  
	<!-- END APP LEVEL JQUERY SCRIPTS -->

	<script type="text/javascript">
		/* Init Metronic's core jquery plugins and layout scripts */
		$(document).ready(function() {   
			Metronic.init(); // Run metronic theme
			Metronic.setAssetsPath('../../../assets/'); // Set the assets folder path			
		});
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
