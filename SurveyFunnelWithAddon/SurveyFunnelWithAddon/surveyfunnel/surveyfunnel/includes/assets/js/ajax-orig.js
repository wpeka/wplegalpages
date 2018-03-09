/**
 * Ajax v2.0
 *
 * @author Kevin Smithwick
 * @license GPL
 * @version 2.0
 * @copyright Kevin Smithwick
 * @website http://www.smithwickdesign.com
 *
 */

var templateForm;
var desinationForm;


/*
Wrapper for the JQuery AJAX post function
*/
function submitAJAX(iPage, iData, iSync) {
	if (iSync == null) {
		iSync = false;
	}
	;
	
	jQuery.ajax({
		type: "POST", 
		dataType: "json",
    	url: iPage,
    	global: true,
    	async: iSync, 
    	data: iData, 
    	success: function(data, textStatus) {
    	
			handleAJAXResponse(data, textStatus);
    	}
   	});
}


/*
Handle the JQuery AJAX Post response
*/
function handleAJAXResponse(jsonObj, textStatus) {
	
		
	// Update the destination form
	if ((templateForm) && (desinationForm)) {
		desinationForm.html(templateForm.html());
	}
	
	templateForm = '';
	desinationForm = '';
	
	if (textStatus == 'success') {
		processJSONData(jsonObj);
		
	} else {
		alert('AJAX Execution Error: ' + textStatus);
	}	
}


/*
Process the specified JSON data object
*/
function processJSONData(iJSON, iContainer) {
	jQuery.each(iJSON, function(iVariable, iValue) {
		if (iValue) {
			if (iContainer) {
				var tmpObj = jQuery('#' + iContainer + ' #' + iVariable);
				//if (!tmpObj.size()) { var tmpObj = jQuery('#' + iContainer + " [name=" + iVariable + "]"); }
				//if (!tmpObj.size()) { var tmpObj = jQuery('#' + iContainer + " [id*=" + iVariable + "]"); }
				//if (!tmpObj.size()) { var tmpObj = jQuery('#' + iContainer + " [name*=" + iVariable + "]"); }

			} else {			
				var tmpObj = jQuery('#' + iVariable);
				//if (!tmpObj.size()) { var tmpObj = jQuery("[name=" + iVariable + "]"); }
				//if (!tmpObj.size()) { var tmpObj = jQuery("[id*=" + iVariable + "]"); }
				//if (!tmpObj.size()) { var tmpObj = jQuery("[name*=" + iVariable + "]"); }
			}
			
			// If the current object is another JSON oject
			if ((typeof iValue == 'object') && (iValue.toString().indexOf('object') > -1)) {
				if (iContainer) {
					processJSONData(iValue, iContainer + ' #' + iVariable);
				} else {
					processJSONData(iValue, iVariable);
				}
				
			} else {
				//alert(iContainer + '.' + iVariable + ' = ' + iValue + ' : ' + tmpObj.size());

				// Object exists on the page
				if (tmpObj.size()) {
					setFieldValue(tmpObj, iValue);
					
    	   		// Object doesn't exist on the page
				} else {
					// Check for a JS function
					if (iVariable.indexOf('script_') == 0) {
						setTimeout(iValue, 10);
						//eval(iValue);
					
					// Check for a form validation error
					} else if (iVariable.indexOf('_error') > -1) {
						setFormError(iVariable.replace('_error', ''));
					
					} else {
						//alert('AJAX Variable Error: ' + iVariable);
					}
				}
			}
		}
	});
}


function setFieldValue(iFieldObj, iValue) {
	switch (iFieldObj.attr('type')) {
		case 'text':
		case 'password':
		case 'file':
		case 'hidden':
		case 'submit':
		case 'button':
		case 'reset':
		case 'textarea':
		case 'select-multiple':
			iFieldObj.val(iValue);
			//alert(jQuery(tmpObj).fieldValue());
			break;

		case 'checkbox':
			if (!iFieldObj.attr('checked')) {
				iFieldObj.trigger('click');
			} else {
				//iFieldObj.attr('checked', false);
			}
			break;
								
		case 'select-one':
			iFieldObj.val(iValue);
			break;
								
		case 'radio':
			jQuery("input[name=" + iFieldObj.attr('id') + "]").each(function() {
				if (jQuery(this).val() == iValue) {
					jQuery(this).attr('checked', 'checked');
				}
			});
			break;
								
		default:
			if (document.getElementById(iFieldObj.attr('id')).type == 'select-one') {
				iFieldObj.val(iValue);
								
			} else if (iFieldObj.attr('id')) {
				iFieldObj.html(iValue);
			}
		
			break;
	}
}


/*
Add the error classes to the specified field
*/
function setFormError(iErrorName) {
	//alert(iErrorName);	
	if (iErrorName.indexOf(']') > 0) {
		var tmpData = iErrorName.split('[');
		var tmpIndex = 0;
		
		for (i = 1; i < tmpData.length; i ++) {
			tmpData[i] = tmpData[i].replace(']', '');
			//if ((tmpData[i] * 1) == 0) { tmpData[i] = 1; }
			tmpIndex = tmpIndex + (tmpData[i] * 1);
		}

		//alert(tmpData[0] + '\n' + tmpIndex);		
		jQuery('[id*=' + tmpData[0] + ']:input').eq(tmpIndex).addClass('errorElem');
		jQuery('[id*=' + tmpData[0] + '_label]').eq(tmpIndex).addClass('errorCell');
				
	} else {
		jQuery('#' + iErrorName).addClass('errorElem');
		//jQuery("[name=" + iErrorName + "]").addClass('errorElem');
		jQuery('#' + iErrorName + '_label').addClass('errorCell');
	}
}


/*
Clean all error elements in the specified form
*/
function clearFormErrors(iFormObj) {
	jQuery('.errorElem').removeClass('errorElem');
	jQuery('.errorCell').removeClass('errorCell');
}


/*
The error element has been focused so clear any error objects
*/
function cleanFormError(iErrorObj) {
	/*
	var tmpName = iErrorObj.id || iErrorObj.name || iErrorObj.attr('id') || iErrorObj.attr('name');
	if (tmpName) {
		jQuery('#' + tmpName).removeClass('errorElem');
		//jQuery("[name=" + tmpName + "]").removeClass('errorElem');
		jQuery('#' + tmpName + '_label').removeClass('errorCell');
	}
	*/
	
	jQuery(iErrorObj).removeClass('errorElem');
	jQuery(iErrorObj).parent().parent().find('#' + jQuery(iErrorObj).attr('id') + '_label').removeClass('errorCell');
}


/*
Disable a form button, and set updating display label
*/
function doFormSubmit(iDisplay, iForm) {
	var tmpName = iForm.name || iForm.id || iForm.attr('id');

	// Clear all form errors
	clearFormErrors(iForm);
	
	jQuery('#' + tmpName + ' #updateBtn').attr('disabled', true);	
	//iDisplay = replace(iDisplay, '...', '<img src=\'/images/progress_dots.gif\'>');	
	jQuery('#' + tmpName + ' .updateMsg').html('<b>' + iDisplay + '</b><br><br>');
}


/*
Show a data form for the specified form
*/
function showDataForm(iID, iFormName) {
	var Now = new Date();
	var Start = Now.getTime()
	
	// Remove all existing update forms
	//jQuery('[id*=update_]').html('');
	//jQuery('[id*=image_]').attr('src', '/images/plus_box.gif');
	
 	toggleLinks('update_' + iID, 'image_' + iID); 
 	
 	if ((jQuery('#image_' + iID).attr('src')).indexOf('minus') == -1) {
 		return false;
 	}
 	
 	jQuery('#update_' + iID).html('<br><center><b>Loading data...</b></center><br>'); 
 	
 	var tmpAddress = window.location.href;
	if (tmpAddress.indexOf('?') > 0) {
		var tmpDat = tmpAddress.split('?');
		tmpAddress = tmpDat[0];
	}

	templateForm = jQuery('#' + iFormName);
	desinationForm = jQuery('#update_' + iID);
	
 	submitAJAX(tmpAddress + 'add_new.php?action=XML_GET', {0: iID});
 	
	var Now = new Date();
	//alert((Now.getTime() - Start));
	
	//setTimeout('alert(jQuery(\'#update_1 #user_id\').fieldValue());', 2000);
}


/*
Replaces text with by in string
*/
function replace(string, text, by) {
    var strLength = string.length, txtLength = text.length;
    if ((strLength == 0) || (txtLength == 0)) return string;

    var i = string.indexOf(text);
    if ((!i) && (text != string.substring(0,txtLength))) return string;
    if (i == -1) return string;

    var newstr = string.substring(0,i) + by;

    if (i+txtLength < strLength)
        newstr += replace(string.substring(i+txtLength,strLength),text,by);

    return newstr;
}
