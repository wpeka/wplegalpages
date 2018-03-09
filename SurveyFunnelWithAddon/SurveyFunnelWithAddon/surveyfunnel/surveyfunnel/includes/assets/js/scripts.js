var $dialogContent;
var WPMediaFormField;
var currentSFQuestionNumber;
var newSFQuestionNumber;
var question_background_color;

/*
Reload the current window after a set time
*/
function reloadWindowTimeout() {
	setTimeout('window.location.reload();', 250);
}

/*

*/



function updateTheme(selected){
	var themeBG;
	switch (selected.id){
      case "color_black":
          themeBG = selected.value;
          question_background_color = "#474646";
          break;
      case "color_lightgray":
    	  themeBG = selected.value;
          question_background_color = "#ECECEC";
          break;
      case "color_pink":
          themeBG = selected.value;
          question_background_color = "#d396b7";
          break;
      case "color_blue":
          themeBG = selected.value;
          question_background_color = "#96B9C8";
          break;
      case "color_green":
          themeBG = selected.value;
          question_background_color = "#99C889";
          break;
      case "color_maroon":
          themeBG = selected.value;
          question_background_color = "#C28B8C";
          break;
      case "color_purple":
          themeBG = selected.value;
          question_background_color = "#C39FD8";
          break;
      case "color_brown":
          themeBG = selected.value;
          question_background_color = "#8d7e69";
          break;
      case "color_lightblue":
          themeBG = selected.value;
          question_background_color = "#A5DDFA";
          break;
      case "color_lightgreen":
          themeBG = selected.value;
          question_background_color = "#b5ce8d";
          break;
      case "color_yellow":
          themeBG = selected.value;
          question_background_color = "#d5d1a6";
          break;
      case "color_ash":
          themeBG = selected.value;
          question_background_color = "#ECECEC";
          break;
	}
	var text_color = '#FFF';
	var color_arr = ['#D2CECE','#8CCBEE','#C6E98D','#E8E28D','#D6DFE5'];
	if(jQuery.inArray(themeBG,color_arr) !== -1){
		text_color = '#373536';
	}
	jQuery('input[name="border_color"]').val(question_background_color);
	jQuery('.sf_preview_container').css('background-color',themeBG);
	jQuery('.sf_preview_que').css('color',text_color,'important');
	jQuery('.answerDisplay').css('color',text_color,'important');
	jQuery('.answerDisplay').css('background-color',question_background_color,'important');

  }


/*
Redirect the current window after a set time
*/
function redirectWindowTimeout(iLocation) {
	setTimeout('window.location.href="' + iLocation + '"', 250);
}


/*

*/
function initSFEditor() {
	jQuery("#sfFlowList").sortable({
		axis: 'y',
		revert: true,
		cursor: 'move',
		forcePlaceholderSize: true,
		placeholder: 'sfQuestionDropDiv',
		start: function(event, ui) {
			currentSFQuestionNumber = (jQuery("#sfFlowList .sf_que_li").index(ui.item)) + 1;
		},
		update: function(event, ui) {
			newSFQuestionNumber = (jQuery("#sfFlowList .sf_que_li").index(ui.item)) + 1;
			updateSFColumns();
		}
	});

	/* jQuery(".colors").miniColors({
		change: function(hex, rgb) {

		}
	}); */

	initSFMediaButtons();
	updateSFColumns();

	jQuery('.sfQuestionDiv .toolbar').width(jQuery('.sfQuestionDiv').width());
}


/*

*/
function initSFMediaButtons() {
	jQuery('.WPMediaBtn').unbind('click');

	jQuery('.WPMediaBtn').each(function() {
		var tmpID = jQuery(this).attr('id').replace('_button', '');
		var imgurl = jQuery('#' + tmpID).val();
		if (imgurl) {
			jQuery('#' + tmpID).parent().find('div').remove();
			jQuery('#' + tmpID).after('<div class="survey_funnel_thumbnail" id="' + tmpID + '_tbimage"><img src="' + imgurl + '"></div>');
		}
	});

	jQuery('.WPMediaBtn, .survey_funnel_thumbnail').click(function() {
		WPMediaFormField = jQuery(this).attr('id').replace('_button', '');
		WPMediaFormField = WPMediaFormField.replace('_tbimage', '');

		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		//jQuery('#width').val(jQuery('img', html).attr('width'));
		//jQuery('#height').val(jQuery('img', html).attr('height'));

		if (jQuery(html).find('img').size()) {
			imgurl = jQuery(html).find('img').attr('src');
		} else {
			imgurl = jQuery(html).attr('src');
		}

		jQuery('#' + WPMediaFormField).parent().find('div').remove();
		jQuery('#' + WPMediaFormField).after('<div class="survey_funnel_thumbnail" id="' + imgurl + '_tbimage"><img src="' + imgurl + '"></div>');

		jQuery('#' + WPMediaFormField).val(imgurl);
		tb_remove();
	}
}


/*

*/
function addSFQuestion(iURL) {
	$dialogContent = jQuery("#survey_funnel_question");

    $dialogContent.dialog({
    	title: "Add Question",
    	closeOnEscape: true,
        modal: true,
        resizable: false,
        width: 670,
        minHeight: 225,
		autoResize: true,

        close: function() {
        	$dialogContent.dialog("destroy");
        	$dialogContent.hide();
		},

		open: function() {
			$dialogContent.find('#header_display').html('Add');
			$dialogContent.find('#question').val('');
			$dialogContent.find('#answers').val('');
			$dialogContent.find('#updateMsg2').html('');
			$dialogContent.find('#question_id').val(0);
			$dialogContent.find('#question').focus();
			$dialogContent.find('#question_type').val(1);

			/*
				Get label value
			*/
			$dialogContent.find('#answer_label').val('');

		},

        buttons: {
			"Cancel": function() {
        		$dialogContent.dialog("destroy");
        		$dialogContent.hide();
			},

        	"Add Question": function() {
        		doFormSubmit('Adding Question...', jQuery('#sf_question_form'));
        		submitAJAX(iURL + '/json.php?action=ADD_QUESTION', jQuery('#sf_question_form').serialize());
			},
		}
	}).show();
    jQuery('.ui-dialog-titlebar-close').html('x');
    jQuery('.ui-dialog-titlebar-close').attr('style','color:red;padding-top:0px;height:30px;margin:-16px 0 0;width:30px;');
}

/*

*/
function addDefaultQueHeader(iURL) {
	$dialogContent = jQuery("#survey_funnel_question_default_header");

    $dialogContent.dialog({
    	title: "Add Default Question Header",
    	closeOnEscape: true,
        modal: true,
        resizable: false,
        width: 680,
        minHeight: 225,
		autoResize: true,

        close: function() {
        	$dialogContent.dialog("destroy");
        	$dialogContent.hide();
		},

		open: function() {
			$dialogContent.find('#header_display').html('Add');
			$dialogContent.find('#default_question_header').val(jQuery("<div/>").html(jQuery('#sf_defaultQuestionHeader').val()).text());
		},

        buttons: {
			"Cancel": function() {
        		$dialogContent.dialog("destroy");
        		$dialogContent.hide();
			},

        	"Add Header": function() {
        		doFormSubmit('Adding Default Question Header...', jQuery('#sf_default_question_header_form'));
        		submitAJAX(iURL + '/json.php?action=ADD_DEFAULT_QUESTION_HEADER', jQuery('#sf_default_question_header_form').serialize());
			},
		}
	}).show();
    jQuery('.ui-dialog-titlebar-close').html('x');
    jQuery('.ui-dialog-titlebar-close').attr('style','color:red;padding-top:0px;height:30px;margin:-16px 0 0;width:30px;');
}

function updateDefaultHeader(headerContent){
	jQuery('#sf_defaultQuestionHeader').val(headerContent);
	jQuery('#tempDefaultHeaderHTML').val($dialogContent.find('#default_question_header').val());
	jQuery('.sf_preview_header').html($dialogContent.find('#default_question_header').val());
	$dialogContent.find('#updateMsg2').html('');
	$dialogContent.dialog("destroy");
	$dialogContent.hide();
}
/*

*/


function performSFQuestionAdd(iID, iURL) {

	jQuery("#survey_frm").find("#question_"+iID).val($dialogContent.find("#question").val());

	var str=$dialogContent.find('#answers').val();
	var tmpAnswers =str.split(/\r\n|\r|\n/g);

	var tmpHTML = '<li id="li_' + iID + '" class="sf_que_li">\
		<div class="sfQuestionDiv">\
			<div class="toolbar">\
				<a href="#" onclick="editSFQuestion(\'' + iID + '\', \'' + iURL + '\');return false;\"><img src="' + iURL + '/admin/images/btn/edit_btn.png" border="0"></a>\
				<a href="#" onclick="removeSFQuestion(\'' + iID + '\');return false;\"><img src="' + iURL + '/admin/images/btn/delete_btn.png" border="0"></a>\
			</div>\
			<div class="display"></div>\
			<div class="sf_preview_container" style="">\
				<div class="sf_preview_header">'+jQuery('#tempDefaultHeaderHTML').val()+'</div>\
				<div class="sf_preview_que" style="">' + $dialogContent.find('#question').val() + '</div>\
				<div class="answers">';


	jQuery.each(tmpAnswers, function() {
		if (this != '') {
			tmpHTML = tmpHTML + '<div class="answerDisplay" style="">' + this + '</div>';
			tmpHTML = tmpHTML + '<div class="answerRule"><select name="answer_rules[' + iID + '][]" onmousedown="updateSFFlows(this, 1);" class="sfRuleDropDown"><option value="">Next question</option></select></div>';
			tmpHTML = tmpHTML + '<br clear="all">';
		}
	});

	/*
	*	Add new div to JS dialog Frame
	*/
	tmpHTML = tmpHTML + '<div class="other_answer" style="">' + $dialogContent.find('#answer_label').val() + '</div>\
				</div></div></div></li>';

	jQuery('#sfFlowList').append(tmpHTML);
	applySFTheme();

	/*
	*	Make Survey Height auto, if lot of answers are added
	*/
	applySF_onload_Theme();
}


/*

*/
function editSFQuestion(iID, iURL) {

	$dialogContent = jQuery("#survey_funnel_question");

    $dialogContent.dialog({
    	title: "Edit Question",
    	closeOnEscape: true,
        modal: true,
        resizable: false,
        width: 670,
        minHeight: 225,
		autoResize: true,

        close: function() {
        	$dialogContent.dialog("destroy");
        	$dialogContent.hide();
		},

		open: function() {
			$dialogContent.find('#header_display').html('Edit');
			$dialogContent.find('#question').val(jQuery('#question_' + iID).val());
			$dialogContent.find('#question_id').val(iID);
			$dialogContent.find('#question_type').val(1);
			$dialogContent.find('#question').focus();
			var ans=jQuery('#answers_' + iID).val().split('|||').join('\n');
			$dialogContent.find('#answers').val(ans);
			$dialogContent.find('#updateMsg2').html('');

			/*
				Display text-field when checkbox value is Checked
				else remove the DIV
			*/
			$dialogContent.find('#answer_label').val(jQuery('#other_answer_' + iID).val());
			$dialogContent.find('#checkbox_value').click(function(){
				if(jQuery(this).is(':checked')){
				}
				else{
					$dialogContent.find('#answer_label').val('');
					jQuery('#li_' + iID + ' .other_answer').remove();
				}
			});
			if( ( jQuery('#text_answers_' + iID).val() ) == 'yes'){
				jQuery('#text_answer_checkbox').prop('checked', true);
			}
			else{
				jQuery('#text_answer_checkbox').prop('checked', false);
			}
		},

        buttons: {
			"Cancel": function() {
        		$dialogContent.dialog("destroy");
        		$dialogContent.hide();
			},

        	"Update Question": function() {
        		doFormSubmit('Updating Question...', jQuery('#sf_question_form'));
        		submitAJAX(iURL + '/json.php?action=UPDATE_QUESTION', jQuery('#sf_question_form').serialize());
			}
		}
	}).show();
    jQuery('.ui-dialog-titlebar-close').html('x');
    jQuery('.ui-dialog-titlebar-close').attr('style','color:red;padding-top:0px;height:30px;margin:-16px 0 0;width:30px;');
}


/*

*/
function performSFQuestionUpdate(iID, iURL) {

	jQuery("#survey_frm").find("#question_"+iID).val($dialogContent.find("#question").val());
	jQuery("#li_"+iID+" .sf_preview_que").html($dialogContent.find("#question").val());

	var str=$dialogContent.find('#answers').val();
	var tmpAnswers =str.split(/\r\n|\r|\n/g);

	var tmpHTML = '';


	var answerDom = jQuery('#li_' + iID + ' .answers').find('.answerDisplay');
	var lastIndex = 0;

	jQuery.each(tmpAnswers, function(index, value) {
		if (answerDom.eq(index).size()) {
			answerDom.eq(index).html(value);
			lastIndex = index;
		}
	});

	// Add new questions
	if (tmpAnswers.length > answerDom.size()) {

		var sfp_preview_container_height = get_sfp_preview_container_height(tmpAnswers.length, answerDom.size());

		jQuery.each(jQuery(tmpAnswers).slice(lastIndex + 1), function() {
			if (this != '') {
				tmpHTML = tmpHTML + '<div class="answerDisplay">' + this + '</div>';
				tmpHTML = tmpHTML + '<div class="answerRule"><select name="answer_rules[' + iID + '][]" onmousedown="updateSFFlows(this, 1);" class="sfRuleDropDown"><option value="">Next question</option></select></div>';
				tmpHTML = tmpHTML + '<br clear="all">';
			}
		});
		jQuery('#li_' + iID + ' .answers').append(tmpHTML);

	// Remove old questions
	} else if (tmpAnswers.length < answerDom.size()) {
		jQuery('#li_' + iID + ' .answers').find('.answerDisplay').slice(tmpAnswers.length).remove();
		jQuery('#li_' + iID + ' .answers').find('.answerRule').slice(tmpAnswers.length).remove();
		jQuery('#li_' + iID + ' .answers').find('br').slice(tmpAnswers.length).remove();
	}
	/*
	*	Div structure for Update Dialog Frame
	*/
	jQuery("#survey_frm").find("#other_answer"+iID).val($dialogContent.find("#answer_label").val());
	jQuery("#li_" + iID + " .other_answer").html($dialogContent.find("#answer_label").val());

	applySFTheme();
	applyPreviewContainerHeight(sfp_preview_container_height, iID);
}


function get_sfp_preview_container_height(tmp_ans_height, original_ans_height){

	var new_ans_cnt = tmp_ans_height - original_ans_height;
	var new_element_height = jQuery('.answerDisplay').outerHeight();
	var final_height = new_element_height * new_ans_cnt;

	var survey_pre_container_height = jQuery('#sfFunnelHeght').val();
	final_height = (parseInt(survey_pre_container_height) + final_height);

	return final_height;
}

/*

*/
function addSFContent(iURL) {
	$dialogContent = jQuery("#survey_funnel_content");

    $dialogContent.dialog({
    	title: "Add Content",
    	closeOnEscape: true,
        modal: true,
        resizable: false,
        width: 640,
        minHeight: 225,
		autoResize: true,

        close: function() {
        	$dialogContent.dialog("destroy");
        	$dialogContent.hide();
		},

		open: function() {
			$dialogContent.find('#header_display').html('Add');
			$dialogContent.find('#updateMsg2').html('');
			$dialogContent.find('#question_id').val(0);
			$dialogContent.find('#question').val('');
			$dialogContent.find('#question_type').val(2);
			$dialogContent.find('#question').focus();

			//$dialogContent.find('#main_content_cell').html('<iframe src="' + iURL + '/survey_funnel/form_templates/wysiwyg_editor.php" width="100%" height="100%" frameborder="0"></iframe>');
		},

        buttons: {
			"Cancel": function() {
        		$dialogContent.dialog("destroy");
        		$dialogContent.hide();
			},

        	"Add Content": function() {
        		doFormSubmit('Adding Content...', jQuery('#sf_content_form'));
        		submitAJAX(iURL + '/json.php?action=ADD_QUESTION', jQuery('#sf_content_form').serialize());
			}
		}
	}).show();
    jQuery('.ui-dialog-titlebar-close').html('x');
    jQuery('.ui-dialog-titlebar-close').attr('style','color:red;padding-top:0px;height:30px;margin:-16px 0 0;width:30px;');
}

/*
// BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation

*/
function addSFDefaultContent(iURL) {

$dialogContent = jQuery("#survey_funnel_userinfo");

$dialogContent.dialog({
	title: "Add Content",
	closeOnEscape: true,
   modal: true,
   resizable: false,
   width: 640,
   minHeight: 225,
	autoResize: true,

   close: function() {
   	$dialogContent.dialog("destroy");
   	$dialogContent.hide();
	},

	open: function() {
		$dialogContent.find('#header_display').html('Add');
		$dialogContent.find('#updateMsg').html('');
		$dialogContent.find('#question_id').val(0);
		$dialogContent.find('#question_type').val(3);
		$dialogContent.find('#adduserinfo').val(1);
	},

   buttons: {
		"Cancel": function() {
   		$dialogContent.dialog("destroy");
   		$dialogContent.hide();
		},

   	"Add": function() {

   		doFormSubmit('Adding user information...', jQuery('#sf_userinfo_form'));
   		submitAJAX(iURL + '/json.php?action=ADD_QUESTION', jQuery('#sf_userinfo_form').serialize());
   		 }


	}
}).show();
jQuery('.ui-dialog-titlebar-close').html('x');
jQuery('.ui-dialog-titlebar-close').attr('style','color:red;padding-top:0px;height:30px;margin:-16px 0 0;width:30px;');

}
function performSFUserContentAdd(iID, iURL) {

	var tmpAnswers = new Array($dialogContent.find('#first_answer').val());

	jQuery('#li_' + iID + ' .answers').html(tmpHTML);
	var tmpHTML = '<li id="li_' + iID + '" class="sf_que_li">\
		<div class="sfQuestionDiv">\
			<div class="toolbar">\
				<a href="#" onclick="removeSFQuestion(\'' + iID + '\');return false;\"><img src="' + iURL + '/admin/images/btn/delete_btn.png" border="0"></a>\
			</div>\
			<div class="display">' + $dialogContent.find('#add').val() + '</div>\
			<div class="answers">';
	$dialogContent.find('[id*=answer_row_] input').each(function() {
		tmpAnswers.push(jQuery(this).val());
	});

	jQuery.each(tmpAnswers, function() {
		if (this != '') {
			tmpHTML = tmpHTML + '<div class="answerDisplay" style="">' + $dialogContent.find('#add').val() + '</div>';
			tmpHTML = tmpHTML + '<div class="answerRule"><select name="answer_rules[' + iID + '][]" onmousedown="updateSFFlows(this, 1);" class="sfRuleDropDown"><option value="">Next question</option></select></div>';
			tmpHTML = tmpHTML + '<br clear="all">';
		}
	});

	tmpHTML = tmpHTML + '<div class="other_answer" style="">' + $dialogContent.find('#answer_label').val() + '</div>\
						</div></div></li>';

	jQuery('#sfFlowList').append(tmpHTML);
}

// End By Arvind

/*

*/
function performSFContentAdd(iID, iURL) {
	var tmpAnswers = new Array($dialogContent.find('#first_answer').val());

	// jQuery('<div/>').text($dialogContent.find('#question').val()).html()

	var tmpHTML = '<li id="li_' + iID + '" class="sf_que_li">\
		<div class="sfQuestionDiv">\
			<div class="toolbar">\
				<a href="#" onclick="editSFContent(\'' + iID + '\', \'' + iURL + '\');return false;\"><img src="' + iURL + '/admin/images/btn/edit_btn.png" border="0"></a>\
				<a href="#" onclick="removeSFQuestion(\'' + iID + '\');return false;\"><img src="' + iURL + '/admin/images/btn/delete_btn.png" border="0"></a>\
			</div>\
			<div class="display"></div>\
                        <div class="sf_preview_container" style="">\
                                        <div class="sf_preview_header">'+jQuery('#tempDefaultHeaderHTML').val()+'</div>\
                                        <div class="que_content" style="">' + $dialogContent.find('#question').val() + '</div>\
                        </div></li>';

	jQuery('#sfFlowList').append(tmpHTML);
        applySFTheme();
}


/*

*/
function editSFContent(iID, iURL) {
	$dialogContent = jQuery("#survey_funnel_content");

    $dialogContent.dialog({
    	title: "Edit Content",
    	closeOnEscape: true,
        modal: true,
        resizable: false,
        width: 640,
        minHeight: 225,
		autoResize: true,

        close: function() {
        	$dialogContent.dialog("destroy");
        	$dialogContent.hide();
		},

		open: function() {
			//var decodedText = jQuery("<div/>").html(jQuery('#answers_' + iID).val()).text();
			var decodedText = jQuery('#answers_' + iID).val();

			$dialogContent.find('#header_display').html('Edit');
			$dialogContent.find('#updateMsg2').html('');
			$dialogContent.find('#question_id').val(iID);
			$dialogContent.find('#question').val(decodedText);
			$dialogContent.find('#question_type').val(2);
			$dialogContent.find('#question').focus();
		},

        buttons: {
			"Cancel": function() {
        		$dialogContent.dialog("destroy");
        		$dialogContent.hide();
			},

        	"Update Content": function() {
        		doFormSubmit('Updating Content...', jQuery('#sf_content_form'));
        		submitAJAX(iURL + '/json.php?action=UPDATE_QUESTION', jQuery('#sf_content_form').serialize());
			}
		}
	}).show();
    jQuery('.ui-dialog-titlebar-close').html('x');
    jQuery('.ui-dialog-titlebar-close').attr('style','color:red;padding-top:0px;height:30px;margin:-16px 0 0;width:30px;');
}


/*

*/
function addSFAnswer() {
	var newDate = new Date;
	var tmpID = newDate.getTime();

	jQuery('#survey_funnel_question #question_table').append('<tr id="answer_row_' + tmpID + '"><td><input type="text" size="30" maxlength="250" name="answers[]" value=""></td><td>[ <a href="#" onclick="removeSFAnswer(\'' + tmpID + '\');return false;">Remove</a> ]</td></tr>');
}


/*

*/
function removeSFAnswer(iID) {
	jQuery('#answer_row_' + iID).fadeOut('fast', function() {
		jQuery(this).remove();
	});
}


/*

*/
function removeSFQuestion(iID) {
	var tmpResp = confirm('Are you sure you want to remove this item?');

	if (!tmpResp) {
		return false;
	}

	jQuery('#key_' + iID).remove();
	jQuery('#question_' + iID).remove();
	jQuery('#font_' + iID).remove();
	jQuery('#font_size_' + iID).remove();
	jQuery('#font_color_' + iID).remove();
	jQuery('#answers_' + iID).remove();
	jQuery('#priority_' + iID).remove();
	jQuery('#question_type_' + iID).remove();

	jQuery('#li_' + iID).fadeOut('fast', function() {
		jQuery(this).remove();
		updateSFColumns();
	});
}


/*

*/
function updateSFColumns() {
	var tmpPriority = 1;

	var lowNum = currentSFQuestionNumber;
	var highNum = newSFQuestionNumber;

	if (newSFQuestionNumber < currentSFQuestionNumber) {
		lowNum = newSFQuestionNumber;
		highNum = currentSFQuestionNumber;
	}

	// Update and rule drop down options
	jQuery('.sfRuleDropDown').each(function() {
		updateSFFlows(this, 1);

		if ((jQuery(this).val() >= lowNum) && (jQuery(this).val() <= highNum)) {
			if (jQuery(this).val() == currentSFQuestionNumber) {
				jQuery(this).val(newSFQuestionNumber);

			} else if ((jQuery(this).val() <= newSFQuestionNumber) && (currentSFQuestionNumber < newSFQuestionNumber)) {
				var tmpValue = (jQuery(this).val() * 1) - 1;
				jQuery(this).val(tmpValue);

			} else if ((jQuery(this).val() >= newSFQuestionNumber) && (currentSFQuestionNumber > newSFQuestionNumber)) {
				var tmpValue = (jQuery(this).val() * 1) + 1;
				jQuery(this).val(tmpValue);
			}
		}
	});


	jQuery('#sfFlowList .sf_que_li').each(function() {
		var tmpID = jQuery(this).attr('id').replace('li_', '');

		jQuery('#column_' + tmpID).val(1);
		jQuery('#priority_' + tmpID).val(tmpPriority);

		jQuery(this).find('.display .questionNumber').remove();
		jQuery(this).find('.display').prepend('<span class="questionNumber">' + tmpPriority + ". &nbsp; </span>");

		tmpPriority ++;
	});
}



/*

*/
function addSFAnswerQuestion() {
	var newDate = new Date;
	var tmpID = newDate.getTime();

	jQuery('#trigger_question_row').after('<tr id="answer_' + tmpID + '"><td align="right" nowrap>&nbsp;</td><td width="100%"><label><input id="' + tmpID + '" type="hidden" size="35" name="trigger_answers[]" onfocus="cleanFormError(this);"> <input id="' + tmpID + '_button" class="WPMediaBtn" type="button" value="Browse..."> &nbsp;Start Question: <select name="start_flows[]" onmousedown="updateSFFlows(this);" class="sfRuleDropDown"><option value="1">1</option></select>&nbsp;[ <a href="#" onclick="removeSFTriggerAnswer(' + tmpID + ');return false;">Remove</a> ]</label></td></tr>');

	initSFMediaButtons();

	/*
	jQuery('#' + tmpID + '_button').click(function() {
		WPMediaFormField = tmpID;
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	});
	*/
}


/*

*/
function removeSFTriggerAnswer(iID) {
	jQuery('#answer_' + iID).fadeOut('fast', function() {
		jQuery(this).remove();
	});
}


/*

*/
function updateSFFlows(iSelect, iStartNumber) {
	if (iStartNumber == null) {
		iStartNumber = 2;
	}

	var tmpCount = jQuery("#sfFlowList .sf_que_li").size();

	for (i = iStartNumber; i <= tmpCount; i ++) {
		if (!jQuery(iSelect).find('option[value="' + i + '"]').size()) {
			jQuery(iSelect).append('<option value="' + i + '">' + i + '</option>');
		}
	}
}


/*

*/
function copySFFunnel(iID, iURL) {

	bootbox.prompt('Please specify the new survey name', function(tmpName){


		if(tmpName != null){

				//2.0.9
			var data = {
				action: 'sf_clone',
				survey_id: iID,
				url: iURL,
				survey_name: tmpName
			};

			//2.0.9
			jQuery.post(ajaxurl, data, function(response, textStatus) {


				//handleAJAXResponse(response, textStatus);//2.0.9
			}, "json");

			reloadWindowTimeout();
				//submitAJAX(iURL + '/survey_funnel/json.php?action=COPY_FUNNEL',  {survey_id: iID, survey_name: tmpName, url: iURL});//2.0.9

			}
	});

}





function activateSFFunnel(iID, iURL) {

		bootbox.confirm({
    message: "Are you sure you want to activate this funnel?",
    buttons: {
        confirm: {
            label: 'Yes',
            className: 'btn-success'
        },
        cancel: {
            label: 'No',
            className: 'btn-danger'
        }
    },
    callback: function (result){

		if(result == true)
		{

				//2.0.9
				var data = {
					action: 'sf_activate',
					survey_id: iID,
					url: iURL
				};

				//2.0.9
				jQuery.post(ajaxurl, data, function(response, textStatus) {


					//handleAJAXResponse(response, textStatus);//2.0.9
				}, "json");

					//submitAJAX(iURL + '/survey_funnel/json.php?action=DELETE_FUNNEL',  {survey_id: iID,  url: iURL});//2.0.9
					reloadWindowTimeout();
		}

			}
		});


}

function deleteSFFunnel(iID, iURL) {

		bootbox.confirm({
    message: "Are you sure you want to deactivate this funnel?",
    buttons: {
        confirm: {
            label: 'Yes',
            className: 'btn-success'
        },
        cancel: {
            label: 'No',
            className: 'btn-danger'
        }
    },
    callback: function (result){

		if(result == true)
		{

				//2.0.9
				var data = {
					action: 'sf_delete',
					survey_id: iID,
					url: iURL
				};

				//2.0.9
				jQuery.post(ajaxurl, data, function(response, textStatus) {


					//handleAJAXResponse(response, textStatus);//2.0.9
				}, "json");

					//submitAJAX(iURL + '/survey_funnel/json.php?action=DELETE_FUNNEL',  {survey_id: iID,  url: iURL});//2.0.9
					reloadWindowTimeout();
		}

			}
		});


}



/*

*/
function keepSFAlive(iURL) {
	jQuery.get(iURL + '/json.php?action=KEEP_ALIVE',
   		function(data){
   			setTimeout('keepSFAlive(\'' + iURL + '\');', 30000);
   		},
   	"html");
}


jQuery(document).ready(function() {

	jQuery('#resetTriggerImage').click(function(){

		jQuery('#lightbox_image').val(null);
		//jQuery('#tab_image').val(null);
		jQuery('.survey_funnel_thumbnail').hide();
		return false;
	});

	/*
	*	Script to toggle label field on Checbox selection
	*/
	jQuery('#checkbox_value').change(function(){

		if( jQuery('#checkbox_value').is(':checked') ){
			jQuery('#text-field').css('display','inline-block');
		}
		else{
			jQuery('#text-field').val('');
			jQuery('#text-field').css('display','none');
		}

	});
});

function applyFunnelChanges(){
	var funnelCSS='background-color:'+jQuery('#container_background_color').val()+';'+
	  'border:'+jQuery('#container_border_size').val()+' solid '+jQuery('#container_border_color').val()+';'+
	  'padding:'+jQuery('#container_padding').val()+';';

	jQuery('#frm_container_css').val(funnelCSS); //insert into form

	var arrFunnelCSS=funnelCSS.split(";");
	var funnelStrCSS='{';
	for (var i=0;i<arrFunnelCSS.length-1;i++){
		if(i!=0)funnelStrCSS+=',';
		var arrKeyValue=arrFunnelCSS[i].split(":");
		funnelStrCSS+='"'+arrKeyValue[0]+'":"'+arrKeyValue[1]+'"';
	}
	funnelStrCSS+='}';
	var funnelStrCSSjSon = jQuery.parseJSON(funnelStrCSS);
	jQuery('#sfDemoFunnelContainer').css(funnelStrCSSjSon);

	var qustionCSS='font-family:'+jQuery('#question_font_family').val()+';'+
	  'font-size:'+jQuery('#question_font_size').val()+';'+
	  'font-weight:'+jQuery('#question_font_weight').val()+';'+
	  'font-style:'+jQuery('#question_font_style').val()+';'+
	  'color:'+jQuery('#question_text_color').val()+';';

	if(jQuery('#question_use_background').val()=='yes') qustionCSS+='background-color:'+jQuery('#question_back_color').val()+';';
	else qustionCSS+='background-color:none;';
	if(jQuery('#question_use_border').val()=='yes') qustionCSS+='border:'+jQuery('#question_border_size').val()+' solid '+jQuery('#question_border_color').val()+';';
	else qustionCSS+='border:none;';
	qustionCSS+='border-radius:'+jQuery('#question_border_radius').val()+';'+
	  'padding:'+jQuery('#question_padding').val()+';'+
	  'margin:'+jQuery('#question_margin').val()+';';

	jQuery('#frm_question_css').val(qustionCSS); //insert into form
	jQuery('#frm_question_use_background').val(jQuery('#question_use_background').val()); //insert into form
	jQuery('#frm_question_use_border').val(jQuery('#question_use_border').val()); //insert into form

	jQuery('#sfDemoFunnelQuestion').attr('style',qustionCSS);

	var answerCSS='font-family:'+jQuery('#answer_font_family').val()+';'+
	  'font-size:'+jQuery('#answer_font_size').val()+';'+
	  'font-weight:'+jQuery('#answer_font_weight').val()+';'+
	  'font-style:'+jQuery('#answer_font_style').val()+';'+
	  'color:'+jQuery('#answer_text_color').val()+';';

	if(jQuery('#answer_use_background').val()=='yes') answerCSS+='background-color:'+jQuery('#answer_back_color').val()+';';
	else answerCSS+='background-color:none;';
	if(jQuery('#answer_use_border').val()=='yes') answerCSS+='border:'+jQuery('#answer_border_size').val()+' solid '+jQuery('#answer_border_color').val()+';';
	else answerCSS+='border:none;';
	answerCSS+='border-radius:'+jQuery('#answer_border_radius').val()+';'+
	  'padding:'+jQuery('#answer_padding').val()+';'+
	  'margin:'+jQuery('#answer_margin').val()+';';

	jQuery('#frm_answer_css').val(answerCSS); //insert into form
	jQuery('#frm_answer_use_background').val(jQuery('#answer_use_background').val()); //insert into form
	jQuery('#frm_answer_use_border').val(jQuery('#answer_use_border').val()); //insert into form

	jQuery('.sfDemoFunnelAnswer').attr('style',answerCSS);
	jQuery('.sfDemoFunnelAnswer label').css('font-weight',jQuery('#answer_font_weight').val());

	/*
	*	Apply Button CSS on preview Funnel backend Option
	*	And insert its values in form
	*/
	var buttonCSS='font-family:'+jQuery('#button_font_family').val()+';'+
	  'font-size:'+jQuery('#button_font_size').val()+';'+
	  'font-weight:'+jQuery('#button_font_weight').val()+';'+
	  'font-style:'+jQuery('#button_font_style').val()+';'+
	  'color:'+jQuery('#button_text_color').val()+';';

	if(jQuery('#button_use_background').val()=='yes'){
		var button_bgCSS = '';
		button_bgCSS ='background:'+jQuery('#button_back_color').val()+';';

		jQuery('#sfDemoFunnelButton :button').attr('style',button_bgCSS);
	}
	else{
		jQuery('#sfDemoFunnelButton :button').attr('style','background-color:none;');
	}
	if(jQuery('#button_use_border').val()=='yes') buttonCSS+='border:'+jQuery('#button_border_size').val()+' solid '+jQuery('#button_border_color').val()+';';
	else buttonCSS+='border:none;';

	buttonCSS+='border-radius:'+jQuery('#button_border_radius').val()+';'+
	  'padding:'+jQuery('#button_padding').val()+';'+
	  'margin:'+jQuery('#button_margin').val()+';';

	jQuery('#frm_button_css').val(buttonCSS+button_bgCSS); //insert into form
	jQuery('#frm_button_use_background').val(jQuery('#button_use_background').val()); //insert into form
	jQuery('#frm_button_use_border').val(jQuery('#button_use_border').val()); //insert into form

	jQuery('#sfDemoFunnelButton').attr('style',buttonCSS);
}
