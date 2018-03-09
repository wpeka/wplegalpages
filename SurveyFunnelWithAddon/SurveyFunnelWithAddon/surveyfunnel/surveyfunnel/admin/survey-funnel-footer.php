<?php
wp_enqueue_script('sf_jquery_min_scripts', SF_PLUGIN_URL .'/admin/assets/global/plugins/jquery.min.js');
wp_enqueue_script('sf_bootstrap_min_scripts', SF_PLUGIN_URL .'/admin/assets/global/plugins/bootstrap/js/bootstrap.min.js');

wp_enqueue_script('sf_bootstrap_select_min_script', SF_PLUGIN_URL .'/admin/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js');
wp_enqueue_script('sf_app_min_script', SF_PLUGIN_URL .'/admin/assets/global/scripts/app.min.js');

wp_enqueue_script('sf_component_bootstrap_select_min_script', SF_PLUGIN_URL .'/admin/assets/pages/scripts/components-bootstrap-select.min.js');
wp_enqueue_script('sf_toaste_js', SF_PLUGIN_URL .'/admin/js/toastr.min.js');

wp_enqueue_script('sf_bootbox_script', SF_PLUGIN_URL .'/admin/js/bootbox.min.js');
wp_enqueue_script('sf_app_script', SF_PLUGIN_URL .'/admin/js/survey-funnel-admin.js');
?>
