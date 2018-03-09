var surveySFOpen = false;
var surveySFURL = '';
var mouseOnSFDiv = false;
var surveySFActive = false;
var surveyshortSFOpen = false;


/*

*/
function triggerLightBox(iImage, iKey, iURL) {
	jQuery("img[src$='" + iImage + "']").each(function() {
		if (jQuery(this).parent().attr('href') != '') {
			jQuery(this).parent().attr('href', "#question_" + iKey);
			//jQuery(this).parent().addClass('fancybox') /*removed and replaced in 2.0.8 for multiple lightboxes*/
			jQuery(this).parent().fancybox({
		hideOnContentClick: false,
		transitionIn:	'elastic',
		transitionOut: 'elastic',
		speedIn: 600,
		speedOut: 200,
		overlayShow: true,
		afterLoad: function() {

			loadSFFunnel(iKey, -1, iURL, true);

		},

		onClosed: function() {
			/* Commented below as its conflicting in trigger+leftside SF display */
			//jQuery('.surveyFunnelDiv').html('<br><center><img src="' + iURL + '/survey_funnel/images/loading.gif"></center>');
		}
	});

		} else {


		}

	});

	jQuery('a.fancybox').fancybox({
		hideOnContentClick: false,
		transitionIn:	'elastic',
		transitionOut: 'elastic',
		speedIn: 600,
		speedOut: 200,
		overlayShow: true,
		afterLoad: function() {
			loadSFFunnel(iKey, -1, iURL, true);
		},

		onClosed: function() {
			jQuery('.surveyFunnelDiv').html('<br><center><img src="' + iURL + '/public/images/loading.gif"></center>');
		}
	});
}



/*

*/
function prepareSFFunnel(iKey, iIndex, iURL, iPos) {

jQuery( "#ajax-loading-screen" ).addClass( "loading-screen" );

	if (surveySFOpen) {
		return false;
	}

	surveySFOpen = true;
	surveySFActive = true;
	surveySFURL = iURL;


	jQuery('.surveyFunnelDiv').hover(function() {

		mouseOnSFDiv = true;
    }, function() {
        mouseOnSFDiv = false;
    });


	jQuery("body").mouseup(function() {

		if ((surveySFOpen) && (!mouseOnSFDiv) && (!iPos)) closeSFFunnel(iKey, false);
    });


	jQuery('#' + iKey).css({'opacity' : 0.25});

	var tmpDiv = jQuery('#question_' + iKey);

	tmpDiv.show();

	if(!iPos) {
			tmpDiv.animate({left:0}, 'slow', function() {
			loadSFFunnel(iKey, iIndex, iURL ,iLightBox='slide');
		});
	}

	else{

			var max_width = parseInt(jQuery(tmpDiv).css('max-width'),10);
			jQuery('#sfp_minimize').css({'bottom': (Math.min(tmpDiv.height()+60, 320)-30)+"px"});
			if(jQuery('#sfp_minimize').position().left < 1204){
				jQuery('#sfp_minimize').css({'left': (Math.min(tmpDiv.width()+4,max_width))+"px"});
			}
			tmpDiv.animate({iPos}, 'slow', function() {
			loadSFFunnel(iKey, iIndex, iURL ,iLightBox='slide');
		});
	}

}

//minimize maximize functionality of survey funnel slider
function sfMinimize(survey_key,height,width){
	var left = jQuery('#sfp_minimize').position().left;
	jQuery('#sfp_minimize').css('bottom',(height+32)+'px');
	jQuery('#sfp_minimize').css('left',(left)+'px');

	jQuery('#sfp_minimize').click(function(){
		jQuery('#question_'+survey_key).toggle(0,function(){
			if( jQuery(this).css('display') == 'none'){
				jQuery('#sfp_minimize').css('bottom','15px');
				jQuery('#sfp_minimize').css('z-index','999px');
				jQuery('#sfp_minimize').html('+');
				jQuery('#sf_min').css('display','block');
				jQuery('#sf_min').css('width',(width+34)+'px');
			}
			else{
				jQuery('#sfp_minimize').css('bottom',(height+32)+'px');
				jQuery('#sfp_minimize').html('-');
				jQuery('#sf_min').css('display','none');
			}
		});
	});
}

function shortcodeSFFunnel(iKey, iIndex, iURL) {

	/*
	 * Commented to display multiple surveys on single page/post - Samir Patil
	 *
	 * if (surveyshortSFOpen) {
		return false;
	}*/
	surveyshortSFOpen = true;
	surveySFActive = true;
	surveySFURL = iURL;

	jQuery('#' + iKey).css({'opacity' : 0.25});
	var tmpDiv = jQuery('#question_' + iKey);

	tmpDiv.show();

		loadSFFunnel(iKey, iIndex, iURL, null);

}

function loadSFFunnel(iKey, iIndex, iURL, iLightBox) {
	if (iLightBox == null) {
		iLightBox = false;
	}

		var r = jQuery.post(iURL + '/json.php?action=LOAD_FUNNEL', {survey_key: iKey, trigger_answer: iIndex, url: iURL, light_box: iLightBox}, function(data) {
		jQuery('#question_' + iKey).html(data.replace('|||eosf', ''));
		if (data.indexOf('|||eosf') > -1) {
			completeSFFunnel(iKey);
		}
		if(jQuery("#sfp_minimize").length > 0) {
		sfMinimize(iKey, jQuery('#question_' + iKey).height(),jQuery('#question_' + iKey).width());
		}
	});
}

/*

*/
function submitSFAnswer(SurveyFunnelKey, iURL, iID, iQuestionID, iPriority, iAnswer, iExtra_answer, iColor, iAnswerIndex,iSurvey_theme) {
	var tmpHeight = jQuery('#question_' + SurveyFunnelKey).height();
	var tmpWidth = jQuery('#question_' + SurveyFunnelKey).width();
	//jQuery('#question_' + SurveyFunnelKey).prepend('<div style="width: ' + tmpWidth + 'px; height: ' + tmpHeight + 'px; position: absolute; background-color: #999; opacity:0.4; filter:alpha(opacity=40);"><br><center><img src="' + iURL + '/survey_funnel/images/loading.gif"></center></div>');
	jQuery('#question_' + SurveyFunnelKey).prepend('<div style="width: ' + tmpWidth + 'px; height: ' + tmpHeight + 'px; position: absolute;"><br><center><img src="' + iURL + '/public/images/loading.gif"></center></div>');

	jQuery.post(iURL + '/json.php?action=SUBMIT_ANSWER', {survey_key: SurveyFunnelKey, answer: iAnswer, extra_ans: iExtra_answer, url: iURL, survey_id: iID, survey_question_id: iQuestionID, survey_priority: iPriority, color: iColor, answer_index: iAnswerIndex, survey_theme: iSurvey_theme}, function(data) {
		jQuery('#question_' + SurveyFunnelKey).html(data.replace('|||eosf', ''));

		if(jQuery("#sfp_minimize").length > 0) {
		sfMinimize(SurveyFunnelKey, jQuery('#question_' + SurveyFunnelKey).height(),jQuery('#question_' + SurveyFunnelKey).width());
		}

		if (data.indexOf('|||eosf') > -1) {
			//jQuery('#question_' + SurveyFunnelKey).css('display','none');
			jQuery('#sfp_minimize').css('display','none');
			completeSFFunnel(SurveyFunnelKey);
		}
	});
}

//BEGIN Added By Arvind On 7-AUG-2013  For Add UserInformation

function mysfUserinfo(SurveyFunnelKey, iURL, iID, iQuestionID, iPriority, iAnswer, iColor){

	var uname= document.getElementById("uname").value;
	var email= document.getElementById("email").value;
    var form_values = jQuery('#usercontent form').serializeArray();
	if(validateEmail(email)){
	var tmpHeight = jQuery('#question_' + SurveyFunnelKey).height();
	var tmpWidth = jQuery('#question_' + SurveyFunnelKey).width();
	jQuery('#question_' + SurveyFunnelKey).prepend('<div style="width: ' + tmpWidth + 'px; height: ' + tmpHeight + 'px; position: absolute;"><br><center><img src="' + iURL + '/public/images/loading.gif"></center></div>');

	 jQuery.post(iURL + '/json.php?action=SUBMIT_USERINFO', {survey_key: SurveyFunnelKey, answer: iAnswer, url: iURL, survey_id: iID, survey_question_id: iQuestionID, survey_priority: iPriority, color: iColor,user_name:uname,email_id:email,form:form_values}, function(data) {

		jQuery('#question_' + SurveyFunnelKey).html(data.replace('|||eosf', ''));
		if (data.indexOf('|||eosf') > -1) {
			completeSFFunnel(SurveyFunnelKey);
		}
	});
	}else {
		alert('Please Enter Valid Email address');
	}
}
function cancelUserInfo(SurveyFunnelKey, iURL, iID, iQuestionID, iPriority, iAnswer, iColor){


	var tmpHeight = jQuery('#question_' + SurveyFunnelKey).height();
	var tmpWidth = jQuery('#question_' + SurveyFunnelKey).width();
	jQuery('#question_' + SurveyFunnelKey).prepend('<div style="width: ' + tmpWidth + 'px; height: ' + tmpHeight + 'px; position: absolute;"><br><center><img src="' + iURL + '/public/images/loading.gif"></center></div>');

	 jQuery.post(iURL + '/json.php?action=CANCELUSERINFO', {survey_key: SurveyFunnelKey, answer: iAnswer, url: iURL, survey_id: iID, survey_question_id: iQuestionID, survey_priority: iPriority, color: iColor}, function(data) {

		jQuery('#question_' + SurveyFunnelKey).html(data.replace('|||eosf', ''));
		if (data.indexOf('|||eosf') > -1) {
			completeSFFunnel(SurveyFunnelKey);
		}
	});

	}
// End By Arvind

function validateEmail(email) {
           var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
           return re.test(email);
}


/*

*/

function completeSFFunnel(iKey) {
	surveySFActive = false;
//	jQuery.cookie("survey_completed", 1,{ expires: 7, path: '/' });
}


/*

*/
function closeSFFunnel(iKey, iTotalHide) {
	if (iTotalHide == null) {
		iTotalHide = true;
	}

	if (surveySFActive == false) {
		iTotalHide = true;
	}

	surveySFActive = false;
	surveySFOpen = false;

	var tmpDiv = jQuery('#question_' + iKey);

	if (iTotalHide) {
		jQuery('#' + iKey).fadeOut('fast');
		tmpDiv.animate({left: '-=' + tmpDiv.width(), opacity: 0}, 'fast', function() {
			jQuery('.surveyFunnelDiv img').hide();
			jQuery(this).hide();
		});

	} else {
		jQuery('#' + iKey).css({'opacity' : 1});
		tmpDiv.animate({left: '-=' + tmpDiv.width(), opacity: 0}, 'fast', function() {
			jQuery(this).html('<br><center><img src="' + surveySFURL + '/public/images/loading.gif"></center>');
			jQuery(this).hide();
			jQuery(this).css({'opacity' : 1});
		});
	}
}



		jQuery(document).ready(function(){

			jQuery('#closeBtn').click(function(){
					jQuery('#fullscreen').css('display','none');
			});

	});
