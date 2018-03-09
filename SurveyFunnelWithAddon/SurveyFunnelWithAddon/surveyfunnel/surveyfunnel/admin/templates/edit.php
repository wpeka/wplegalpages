<?php
class DesignSurvey {

	function __construct($designSurveyMode){
		switch ($designSurveyMode){
			case 'viewAll': $this->showAllSettings();break;
		}
	}


	function showAllSettings(){
		global $wpdb;
		$SurveyManage = new SurveyManage();
		$SurveyManage->loadSurvey($_REQUEST['survey_id']);
		?>
		<style>
			#surveyTitle:HOVER {
				text-decoration: underline;
			}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function(){

				//display image option for slider if position is left else hide it
				jQuery('#survey_slider_left').change(function(){
					if( jQuery('#survey_slider_left').is(':checked') ){
						jQuery('.slider_img').css('display','table-row');
					}
				});
				jQuery('#survey_slider_left_bottom').change(function(){
					if( jQuery('#survey_slider_left_bottom').is(':checked') ){
						jQuery('.slider_img').css('display','none');
					}
				});
				jQuery('#survey_slider_right_bottom').change(function(){
					if( jQuery('#survey_slider_right_bottom').is(':checked') ){
						jQuery('.slider_img').css('display','none');
					}
				});

				var enable_progress_bar = "<?php echo  $SurveyManage->enable_progress_bar;?>";
				if(enable_progress_bar == 0){
					jQuery('.progress_bar_properties').css('display','none');
				}else if(enable_progress_bar == 1){
					jQuery('.progress_bar_properties').css('display','inline-block');
				}

				jQuery('#enable_progress_bar').change(function(){

					if( jQuery('#enable_progress_bar').is(':checked') ){
						jQuery('.progress_bar_properties').css('display','inline-block');
					}
					else{
						jQuery('.progress_bar_properties').css('display','none');
					}

				});

				jQuery('#funnel_size_1').change(function(){

					if( jQuery('#funnel_size_1').is(':checked') ){
						jQuery('#funnelSizeDiv').css('display','inline-block');
						jQuery('#sfFunnelWidth').val("320");
					}

				});

				jQuery('#funnel_size_2').change(function(){

					if( jQuery('#funnel_size_2').is(':checked') ){
						jQuery('#funnelSizeDiv').css('display','none');
						jQuery('#sfFunnelWidth').val("950");
					}


				});



				jQuery('#survey_type_slider').change(function(){

					if( jQuery('#survey_type_slider').is(':checked') ){
						jQuery('#SliderDiv').css('display','inline-block');
						jQuery('#PopupDiv').css('display','none');
						jQuery('#ShortcodeDiv').css('display','none');
					}
					else{
						jQuery('#SliderDiv').css('display','none');
					}

				});

				jQuery('#survey_type_popup').change(function(){

					if( jQuery('#survey_type_popup').is(':checked') ){
						jQuery('#SliderDiv').css('display','none');
						jQuery('#PopupDiv').css('display','inline-block');
						jQuery('#ShortcodeDiv').css('display','none');
					}
					else{
						jQuery('#PopupDiv').css('display','none');
					}

				});


				jQuery('#survey_type_shortcode').change(function(){

					if( jQuery('#survey_type_shortcode').is(':checked') ){
						jQuery('#SliderDiv').css('display','none');
						jQuery('#PopupDiv').css('display','none');
						jQuery('#ShortcodeDiv').css('display','inline-block');
					}
					else{
						jQuery('#ShortcodeDiv').css('display','none');
					}

				});




				jQuery('#surveyTitle').hover(function(){
					jQuery(this).tooltip('show');
				});
				jQuery("#survey_funnel_theme").change(function(){applySFTheme();});
				jQuery('#survey_funnel_question_flow').show();
				applySFTheme();
				<?php if ($SurveyManage->lightbox_image != '') { ?>
					jQuery('#trigger_tabs').tabs({selected: 1});
				<?php } else { ?>
		//			jQuery('#trigger_tabs').tabs();
				<?php } ?>

				initSFEditor();
			});
			function applySFTheme(){

				var bg_color = jQuery("input[name='color']:checked").val();
				if(bg_color == '#373536'){
					question_background_color = '#474646';
				}
				if (typeof question_background_color === 'undefined') {
				    question_background_color = '<?php echo $SurveyManage->question_background_color; ?>';
				}
				var text_color = '#FFF';
				var color_arr = ['#D2CECE','#8CCBEE','#C6E98D','#E8E28D','#D6DFE5'];
				if(jQuery.inArray( bg_color, color_arr ) !==-1 ){
					text_color = '#373536';
				}

				var funnelCSS = 'width:'+jQuery('#sfFunnelWidth').val()+'px; height:'+jQuery('#sfFunnelHeght').val()+'px;border-radius: 10px;padding: 8px;background-color:'+bg_color;

				jQuery('.sf_preview_container').attr( "style", funnelCSS );
				applySF_onload_Theme();

				var QueCSS='border-radius: 5px !important;font-family: helvetica,sans-serif;font-size: 12px;font-style: normal;font-weight: normal;padding: 8px;text-align: center;';
				jQuery('.sf_preview_que').attr( "style", QueCSS );
				jQuery('.sf_preview_que').css('color',text_color);

				var AnsCSS='background-color: '+question_background_color+' ;border-radius: 20px;font-family: Lato,sans-serif; font-size: 12px;font-style: normal;font-weight: normal;margin-bottom: 5px;margin-top: 5px;opacity: 1;padding: 10px;';
				jQuery('.answerDisplay').attr( "style", AnsCSS );
				jQuery('.answerDisplay').css('color',text_color);



				/*
				*	Give funnel CSS to new answer Option
				*	Added by Kaustubh
				*/
				var OptionCSS='border-radius: 20px;font-family: Lato,sans-serif; font-size: 12px;font-style: normal;font-weight: normal;margin-bottom: 5px;margin-top: 5px;opacity: 1;padding: 10px;';
				jQuery('.other_answer').attr( "style", OptionCSS );
			}
			function applyPreviewContainerHeight(calc_height, iID){
				jQuery('#li_'+ iID).find('.sf_preview_container').css({
					'height': calc_height+'px'
				});
			}
			function applySF_onload_Theme(){
				jQuery('.sf_preview_container').css({
					'height': 'auto'
				});
			}
		</script>

		<?php   include('new-edit.php'); ?>

		<?php  // include('old-edit.php'); ?>

		<?php
	}
}


$designSurveyMode='viewAll';
if(isset($_REQUEST['addSurveyMode'])) $designSurveyMode=$_REQUEST['addSurveyMode'];
new DesignSurvey($designSurveyMode);
?>
