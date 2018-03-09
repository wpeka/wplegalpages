<div id="survey_funnel_content">
	<form id="sf_content_form" onsubmit="return false;">
		<input type="hidden" name="WP_PLUGIN_URL" value="<?php echo WP_PLUGIN_URL_SLLSAFE; ?>">
		<input type="hidden" name="question_id" id="question_id" value="">
		<input type="hidden" name="question_type" id="question_type" value="2">

		<h3>Content Area : </h3>

		<table width="100%" cellpadding="3" cellspacing="1" id="question_table">
			<tr>
				<td width="100%" colspan="2" id="main_content_cell">
					<textarea name="question" id="question" cols="80" rows="8" style="width: 100%;"></textarea>
					<br>
					<span class="description">HTML and Shortcode is allowed</span><span class="description">Please make sure to add valid html & script or it may break the funnel settings page.</span>
				</td>
			</tr>
		</table>

		<br>
		<div id="updateMsg2" class="updateMsg"></div>
	</form>
</div>
