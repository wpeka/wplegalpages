<div id="survey_funnel_question">
	<form id="sf_question_form" onsubmit="return false;">
		<input type="hidden" name="WP_PLUGIN_URL" value="<?php echo WP_PLUGIN_URL_SLLSAFE; ?>">
		<input type="hidden" name="question_id" id="question_id" value="">
		<input type="hidden" name="question_type" id="question_type" value="1">


		<table width="100%" cellpadding="10px" cellspacing="10px" id="question_table">
			<tr>
				<td  id="question_label" nowrap align="left" width="30%" valign="top">Question : &nbsp;</td>
				<td width="70%" colspan="2"><textarea name="question" id="question" rows="3" cols="55"></textarea>
				<br>  <span class="description">(HTML Allowed) </span> <br> </td>
			</tr>

			<tr>
				<td align="left" width="30%" valign="top" id="ans_label" nowrap>Answers : &nbsp;</td>
				<td width="100%" colspan="2"><textarea name="answers" id="answers" rows="3" cols="55"></textarea>
				<br>  <span class="description">(Enter each answer on a separate line.)</span> <br></td>
			</tr>


			<tr>
				<td width="100%" colspan="2">
					<input id="checkbox_value" type="checkbox" /> Display Other Answer Option
				</td>
			</tr>


			<tr>
				<td align="left" width="100%" colspan="2">
					<span id="text-field">
							Text label : <input type="text" name="other_answer_text" id="answer_label">
						</span>
				</td>
			</tr>

			<!--
				New field under answers-textarea
				Gets Displayed on JS pop-up Frame
				Added By Kaustubh
			-->


			<tr>
				<td width="100%" colspan="2">
					<input name="text_answers" id="text_answer_checkbox" type="checkbox"/> Allow Descriptive Answers</td>
			</tr>
			
			<tr>
			<td width="100%" colspan="2">
			<span class="description">(This will show textarea below each answer.)</span> </td>
			</tr>

		</table>

		<br/>
		<div id="updateMsg2" class="updateMsg"></div>
	</form>
</div>
<div id="survey_funnel_question_default_header" style="display: none;">
	<form id="sf_default_question_header_form" onsubmit="return false;">
		<input type="hidden" name="WP_PLUGIN_URL" value="<?php echo WP_PLUGIN_URL_SLLSAFE; ?>">


		<table width="100%" cellpadding="3" cellspacing="1" id="question_table">
			<tr>
				<td align="left" valign="top" id="question_label" nowrap width="35%">Question header :  </td>
				<td width="65%" colspan="2"><textarea name="default_question_header" id="default_question_header" rows="3" cols="50" ></textarea>
				<br> <span class="description">(HTML Allowed)</span></td>
			</tr>


		</table>

		<br/>
 		<div id="updateMsg2" class="updateMsg"></div>
 	</form>
 </div>
