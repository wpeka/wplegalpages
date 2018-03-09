<?php $FormDisplay = new FormDisplay(); ?>

<input type="hidden" name="survey_id" id="survey_id" value="<?php echo $SurveyManage->survey_id; ?>">

<table width="99%" cellpadding="3" cellspacing="1">
	<tr>
		<td width="50%" valign="top">
			<table width="100%" cellpadding="3" cellspacing="1">
				<tr><td colspan="3" class="sfUpdateSection" style="background: url('<?php echo SF_PLUGIN_URL; ?>/images/arrow_update.gif') left top no-repeat;">Display Settings</td></tr>
				<tr><td height="15"></td></tr>

				<tr>
					<td rowspan="10" nowrap><img src="<?php echo SF_PLUGIN_URL; ?>/admin/images/blank.gif" width="20" height="1"></td>
					<td align="right" id="survey_name_label" nowrap>Survey Name: &nbsp;</td>
					<td width="100%"><input type="text" name="survey_name" id="survey_name" size="35" maxlength="150" onfocus="cleanFormError(this);" value="<?php echo $SurveyManage->survey_name; ?>"></td>
				</tr>
				<tr><td height="10"></td></tr>

				<tr>
					<td align="right" id="funnel_theme_label" nowrap>Funnel Theme: &nbsp;</td>
					<td width="100%">
						<select name="survey_theme" id="survey_funnel_theme">
							<option value="custom">Custom</option>
							<?php echo get_funnel_themes($SurveyManage);?>
						</select>
					</td>
				</tr>

				<tr class="sf_custom_theme">
					<td align="right" id="background_color_label" nowrap>Background: &nbsp;</td>
					<td width="100%">
						<input type="text" name="background_color" id="background_color" class="colors" size="7" value="<?php echo $SurveyManage->background_color; ?>">
					</td>
				</tr>

				<tr class="sf_custom_theme">
					<td align="right" id="background_image_label" nowrap>Background Image: &nbsp;</td>
					<td width="100%">
						<label for="background_image">
							<input id="background_image" type="hidden" size="35" name="background_image" value="<?php echo $SurveyManage->background_image; ?>">
							<input id="background_image_button" class="WPMediaBtn" type="button" value="Browse...">
						</label>
					</td>
				</tr>

				<tr>
					<td align="right" id="size_label" nowrap>Width x Height: &nbsp;</td>
					<td width="100%">
						<input type="text" name="width" id="width" size="5" maxlength="5" value="<?php echo $SurveyManage->width; ?>" onfocus="cleanFormError(this);"> x
						<input type="text" name="height" id="height" size="5" maxlength="5" value="<?php echo $SurveyManage->height; ?>" onfocus="cleanFormError(this);">
					</td>
				</tr>

				<tr class="sf_custom_theme">
					<td align="right" id="padding_label" nowrap>Padding: &nbsp;</td>
					<td width="100%">
						<input type="text" name="padding" id="padding" size="5" maxlength="5" value="<?php echo $SurveyManage->padding; ?>" onfocus="cleanFormError(this);">
					</td>
				</tr>

				<tr class="sf_custom_theme">
					<td align="right" id="border_color_label" nowrap>Border: &nbsp;</td>
					<td width="100%">
						<select name="border_size" id="border_size">
							<?php for ($i = 0; $i <= 10; $i ++) { ?>
								<option value="<?php echo $i; ?>" <?php if ($i == $SurveyManage->border_size) { ?>selected<?php } ?>><?php echo $i; ?>px</option>>
							<?php } ?>
						</select>
						<input type="text" name="border_color" id="border_color" class="colors" size="7" value="<?php echo $SurveyManage->border_color; ?>">
					</td>
				</tr>

				<tr class="sf_custom_theme">
					<td align="right" id="question_background_color_label" nowrap>Question Background: &nbsp;</td>
					<td width="100%">
						<input type="text" name="question_background_color" id="question_background_color" class="colors" size="7" value="<?php echo $SurveyManage->question_background_color; ?>">
					</td>
				</tr>
				<tr><td height="10"></td></tr>

				<tr>
					<td align="right" id="cookie_label" valign="top" nowrap>Cookie: &nbsp;</td>
					<td width="100%">
						<input type="checkbox" id="use_cookie" name="use_cookie" value="1" onchange="if (jQuery(this).attr('checked')) { jQuery('#cookie_days').attr('disabled', false); } else { jQuery('#cookie_days').attr('disabled', true); }" <?php if ($SurveyManage->use_cookie) { ?>checked<?php } ?>>&nbsp; <a href="javascript:void(0);" onclick="jQuery('#use_cookie').trigger('click');">Use Cookie</a>
						<br>
						Expire cookie after <input type="text" name="cookie_days" id="cookie_days" value="<?php echo $SurveyManage->cookie_days; ?>" size="5" maxlength="5" <?php if (!$SurveyManage->use_cookie) { ?>disabled<?php } ?>> days
					</td>
				</tr>
			</table>
		</td>

		<td><img src="<?php echo SF_PLUGIN_URL; ?>/admin/images/blank.gif" width="20" height="1"></td>

		<td width="50%" valign="top">
			<table width="100%" cellpadding="3" cellspacing="1">
				<tr><td colspan="3" class="sfUpdateSection" style="background: url('<?php echo SF_PLUGIN_URL; ?>'/images/arrow_update.gif') left top no-repeat;">Trigger Settings</td></tr>
				<tr><td height="15"></td></tr>
			</table>

			<div id="trigger_tabs">
				<ul>
					<li><a href="#page">Pages</a></li>
					<li><a href="#lightbox">Image</a></li>
					<li><a href="#shortcode">ShortCode</a></li>
				</ul>

				<div id="page">
					<table width="100%" cellpadding="3" cellspacing="1">
						<tr>
							<td align="right" id="tab_image_label" nowrap>Question: &nbsp;</td>
							<td width="100%">
								<label for="tab_image">
									<input id="tab_image" type="hidden" size="35" name="tab_image" value="<?php if ($SurveyManage->tab_image != '') { echo $SurveyManage->tab_image; } else { echo SF_PLUGIN_URL .'/admin/images/tabs/click_here.png'; }?>" onfocus="cleanFormError(this);">
									<input id="tab_image_button" class="WPMediaBtn" type="button" value="Browse...">
								</label>
							</td>
						</tr>

						<tr id="">
							<td align="right" id="trigger_question_1_label" nowrap>Answer(s): &nbsp;</td>
							<td width="100%">
								<label for="trigger_question_1">
									<input id="trigger_question_1" type="hidden" size="35" name="trigger_answers[]" value="<?php echo (isset($SurveyManage->trigger_answers[0]))?$SurveyManage->trigger_answers[0]:''; ?>" onfocus="cleanFormError(this);">
										<input id="trigger_question_1_button" class="WPMediaBtn" type="button" value="Browse...">
										&nbsp;Start Question: <select name="start_flows[]" onmousedown="updateSFFlows(this);" class="sfRuleDropDown">
										<?php for ($i = 1; $i <= $SurveyManage->start_flows[0]; $i ++) { ?>
											<option value="<?php echo $i; ?>" <?php if ($SurveyManage->start_flows[0] == $i) { ?>selected<?php } ?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
									&nbsp;[ <a href="javascript:void(0);" onclick="addSFAnswerQuestion();">Add</a> ]
								</label>
							</td>
						</tr>

						<?php if (count($SurveyManage->trigger_answers)) { ?>
							<?php foreach ($SurveyManage->trigger_answers as $key => $answer) { ?>
									<?php if ($key > 0) { ?>
									<tr id="answer_trigger_<?php echo $key; ?>">
										<td align="right" nowrap>&nbsp;</td><td width="100%">
											<label>
												<input id="trigger_question__<?php echo ($key + 1); ?>" type="hidden" size="35" name="trigger_answers[]" value="<?php echo $SurveyManage->trigger_answers[$key]; ?>" onfocus="cleanFormError(this);">
												<input id="trigger_question__<?php echo ($key + 1); ?>_button" class="WPMediaBtn" type="button" value="Browse...">
												&nbsp;Start Question: <select name="start_flows[]" onmousedown="updateSFFlows(this);" class="sfRuleDropDown">
													<?php for ($i = 1; $i <= $SurveyManage->start_flows[$key]; $i ++) { ?>
														<option value="<?php echo $i; ?>" <?php if ($SurveyManage->start_flows[$key] == $i) { ?>selected<?php } ?>><?php echo $i; ?></option>
													<?php } ?>
												</select>
												&nbsp;[ <a href="javascript:void(0);" onclick="removeSFTriggerAnswer('trigger_<?php echo $key; ?>');">Remove</a> ]
											</label>
										</td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>

						<tr id="trigger_question_row"><td></td></tr>
						<tr><td height="10"></td></tr>

						<tr>
							<td align="right" id="page_id_label" valign="top" nowrap>Page(s): &nbsp;</td>
							<td width="100%">
								<input type="checkbox" name="all_pages" id="all_pages" value="1" onchange="if (jQuery(this).attr('checked')) { jQuery('#post_ids').attr('disabled', true); } else { jQuery('#post_ids').attr('disabled', false); }" <?php if ($SurveyManage->all_pages) { ?>checked<?php } ?>>&nbsp; <a href="javascript:void(0);" onclick="jQuery('#all_pages').trigger('click');">All Pages</a>
								<br>
								<?php $FormDisplay->getPost('post_ids[]', $SurveyManage->post_ids, $SurveyManage->all_pages); ?>
							</td>
						</tr>
					</table>
				</div>

				<div id="lightbox">
					<table width="100%" cellpadding="3" cellspacing="1">
						<tr>
							<td align="right" id="lightbox_image_label" nowrap>Trigger Image: &nbsp;</td>
							<td width="100%">
								<label for="lightbox_image">
									<input id="lightbox_image" type="hidden" size="35" name="lightbox_image" value="<?php echo $SurveyManage->lightbox_image; ?>" onfocus="cleanFormError(this);">
									<input id="lightbox_image_button" class="WPMediaBtn" type="button" value="Browse...">
								</label>
							</td>
						</tr>

						<tr>
						<td colspan="2">
						<input type="checkbox" id="use_widget" name="use_widget" value="1" <?php if ($SurveyManage->use_widget) { ?>checked<?php } ?>>&nbsp; Use In A Sidebar Widget
												<br /><br />
						<a href="" id="resetTriggerImage">Reset</a>
						</td>
						</tr>
					</table>
				</div>
	<!--    Begin Started by Dinesh on 6th June, 2013  -->
	<div id="shortcode">

					<table width="100%" cellpadding="3" cellspacing="1">
					<!--  	<tr>
							<td align="right" id="shortcode_image_label" nowrap>ShortCode Image: &nbsp;</td>
							<td width="100%">
								<label for="shortcode_image">
									<input id="shortcode_image" type="hidden" size="35" name="shortcode_image" value="<?php echo $SurveyManage->lightbox_image; ?>" onfocus="cleanFormError(this);">
									<input id="shortcode_image_button" class="WPMediaBtn" type="button" value="Browse...">
								</label>
							</td>
						</tr>-->

						<tr>
						<td colspan="2">
						<input type="checkbox" id="use_shortcode" name="use_shortcode" value="1" <?php if ($SurveyManage->use_shortcode) { ?>checked<?php } ?>>&nbsp; Embed in Page
												<br /><br />
						<!-- <a href="" id="resetTriggerImage">Reset</a> -->
						</td>
						</tr>
					</table>

				</div>


	<!-- // End By Dinesh  -->

			</div>
		</td>
	</tr>
</table>

<script type="text/javascript">
	jQuery(document).ready(function() {
		showHideCustomTheme('<?php echo $SurveyManage->survey_theme;?>');
		jQuery('#survey_funnel_theme').change(function(){
			showHideCustomTheme(jQuery(this).val());
		});
	});
	function showHideCustomTheme(theme){
		if(theme == 'custom'||theme == ''){
			jQuery('.sf_custom_theme').show();
		}
		else{
			jQuery('.sf_custom_theme').hide();
		}
	}
</script>
<?php
function get_funnel_themes($SurveyManage){
	$survey_theme_dir = ABSPATH . 'wp-content/plugins/'.SF_PLUGIN_DIR.'/survey_theme';
	$survey_theme_names='';
	if ($handle = opendir($survey_theme_dir)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && is_dir($survey_theme_dir.'/'.$entry)) {
				$survey_theme_names.='<option ';
				if($SurveyManage->survey_theme == $entry) $survey_theme_names.='selected="selected"';
				$survey_theme_names.=' value="'.$entry.'">'.ucfirst($entry).'</option>';
			}
		}
		closedir($handle);
	}
	return $survey_theme_names;
}
?>
