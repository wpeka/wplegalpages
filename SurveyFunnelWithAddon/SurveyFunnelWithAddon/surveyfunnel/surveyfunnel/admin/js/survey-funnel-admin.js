jQuery(document).ready(function(){

// Social Sharing Icon click on Image
jQuery(".image-radio img").click(function(){
  jQuery(this).prev().attr('checked',true);
});

 //start - all pages option is checked or not to disable page/post select box (on change)
  jQuery("#all_pages").change(function() {
       if(jQuery(this).prop('checked') == true) {
           jQuery('#post_ids').attr('disabled', 'disabled');
       } else {
          jQuery('#post_ids').removeAttr('disabled');
       }
   });

  if(jQuery('#all_pages').prop('checked') == true)
    jQuery('#post_ids').attr('disabled', 'disabled');
  else
    jQuery('#post_ids').removeAttr('disabled');
//end - all pages option is checked or not to disable page/post select box (on page load)


  jQuery(".code").click(function(){
     var divID = '#survey-code_' + jQuery(this).attr('id');

    if(jQuery(divID).is(':visible')) {
      jQuery(divID).hide();
    } else {
      jQuery(divID).show();
    }
    });

  var quesSec = ["screen_", "question_header_", "queNumber_", "edit_", "delete_", "question_", "questions_id_", "questions_content_", "questions_answer_", "next_questions_", "other_options_", "option_texts_", "desc_answers_", "answer_content_", "questions_type_"];
  var queSecLen = jQuery(quesSec).length;

  var ansSec = ["answer_list_", "answers_", "answer_delete_", "answer_image_upload_", "next_question_"];
  var ansSecLen = jQuery(ansSec).length;

  jQuery("#desc_answer").change(function() {
       if(jQuery(this).val() == 'yes') {
             jQuery( ".mt-repeater" ).addClass( "display-none" );
             // Hide Answer Content on selecting Descriptive Answer - Swapnil
             jQuery( ".answer_content_div" ).addClass( "display-none" );
       } else {
          jQuery( ".mt-repeater" ).removeClass( "display-none" );
          jQuery( ".answer_content_div" ).removeClass( "display-none" );
       }
   });

  //Add multiple question here
  jQuery('#add_question').on("click",function() {

      jQuery( "#section_question" ).removeClass( "hidden_section" );
      jQuery( "#section_header" ).addClass( "hidden_section" );
      jQuery( "#section_message" ).addClass( "hidden_section" );

      // Displayed in top screen list
      var questionNum = jQuery('.screen').length + 1;
      var question_sec = "<div id='screen_"+questionNum+"' class='screen'><span id='question_header_"+questionNum+"'><a id='edit_"+questionNum+"' class='edit_question'>Question <b id='queNumber_"+questionNum+"'>"+questionNum+"</b></a></span><span style='float:right'><a id='delete_"+questionNum+"' class='question_delete'><i class='fa fa-close'></i> </a></span><br/><strong id='question_"+questionNum+"'>Did you find what you were looking for?</strong><input type='hidden' name='questions_ids[]' id='questions_id_"+questionNum+"' value=''><input type='hidden' name='questions_content[]' id='questions_content_"+questionNum+"' value='Did you find what you were looking for?'><input type='hidden' name='questions_answer[]' id='questions_answer_"+questionNum+"' value=''><input type='hidden' name='next_questions[]' id='next_questions_"+questionNum+"' value=''><input type='hidden'  name='other_options[]' id='other_options_"+questionNum+"' value=''><input type='hidden' name='option_texts[]' id='option_texts_"+questionNum+"' value=''><input type='hidden' name='desc_answers[]' id='desc_answers_"+questionNum+"' value=''><input type='hidden' name='answer_content[]' id='answer_content_"+questionNum+"' value=''><input type='hidden' name='questions_type[]' id='questions_type_"+questionNum+"' value='1'></div>";

      jQuery( "#screens_list" ).append( question_sec );
      jQuery( "#question_number" ).html( questionNum );

      jQuery("#question_content").val("Did you find what you were looking for?");
      jQuery( "#answer_list" ).empty();

      jQuery('#other_option').prop('checked', false);
-      jQuery( "#option_text" ).val("");

      jQuery("#desc_answer option").prop("selected", false);

      // Set initially answer content as nothing
      jQuery("#answer_contents option").prop("selected", false);

  });

  // Added by Swapnil Shinde - Change answers on changing answer content
  jQuery("#answer_contents").change(function(){
      //jQuery("#answer_list").addClass("display-none");
      jQuery(".answer_screen").remove();
      if(jQuery(this).val()==""){
        jQuery("#add_answer").hide();
      }
      else {
        jQuery("#add_answer").show();
      }

  });


  //Add multiple answer here
  // Changed by Swapnil Shinde to add images in the answers
  jQuery('#add_answer').on("click",function() {

      // If answer type is image then append image uploader
      var image_uploader_code = "";
      //if(isset($("#answer_content").val()))
      var answerNum = jQuery('.answer_screen').length + 1;
      //{
      if(jQuery("#answer_contents").val()=="image"){
        // Remove Text Box for image
        image_uploader_code = "<input type='text' placeholder='' class='form-control mt-repeater-input-inline' value='Upload the Image' name='answers[]' id='answers_"+answerNum+"' readonly/><a id='answer_image_upload_"+answerNum+"' class='btn btn-primary answer_image_upload mt-repeater-del-right mt-repeater-btn-inline'><i class='fa fa-upload'></i></a>";
      }
      else {
        image_uploader_code = "<input type='text' placeholder='' class='form-control mt-repeater-input-inline' name='answers[]' id='answers_"+answerNum+"' />";
      }
      //}
      var answer_sec = "<div id='answer_list_"+answerNum+"' class='row answer_screen'><div class='col-md-6 col-xs-6 col-sm-6 col-xl-6'><div class='form-group'><label class='control-label'>Add Answer</label><div class='mt-repeater-cell'><input type='hidden' name='image_answers[]' id='image_answers_"+answerNum+"'>"+image_uploader_code+"<a id='answer_delete_"+answerNum+"' class='btn btn-danger answer_delete mt-repeater-del-right mt-repeater-btn-inline'><i class='fa fa-close'></i></a></div></div></div><div class='col-md-6 col-xs-6 col-sm-6 col-xl-6'><div class='form-group'><label class='control-label'>Question Redirect</label><select class='form-control' name='next_question[]'' id='next_question_"+answerNum+"'></div></div></div></div>";

      jQuery( "#answer_list" ).append( answer_sec );

      jQuery('#next_question_'+answerNum).append(jQuery('<option>', {
      value: 0,
      text: 'Next Question'
      }));

      var questionNum = jQuery('.screen').length;
      for(var i=1; i<=questionNum; i++)
      {
        if(jQuery( "#questions_type_"+i ).val() == "1")
          var textSelect = "Question : "+jQuery( "#questions_content_"+i ).val();
        else if(jQuery( "#questions_type_"+i ).val() == "2")
         var textSelect = "Message : "+jQuery( "#questions_answer_"+i ).val();
        else if(jQuery( "#questions_type_"+i ).val() == "3")
         var textSelect = "Lead Generation form : "+jQuery( "#questions_answer_"+i ).val();


       jQuery('#next_question_'+answerNum).append(jQuery('<option>', {
       value: i,
       text: textSelect
       }));
      }
    });

//Delete questions code here
jQuery(document).on('click', ".question_delete",function(){

  var questionId = jQuery(this).attr('id');
  var arr = questionId.split('_');

  bootbox.confirm({
  message: "Do you want to delete this question?",
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
  callback: function (result) {
          if(result == true){

          var startId = parseInt(arr[1]) + 1;
          var endId = jQuery('.screen').length;

          jQuery( "#screen_"+arr[1] ).remove();
          jQuery( "#section_question" ).addClass( "hidden_section" );

          for(var i=startId; i<=endId; i++ )
          {
              var newExt = i-1;
              jQuery( "#queNumber_"+i ).html( newExt );
              for(var j=0; j<queSecLen; j++)
              {
                  jQuery("#"+quesSec[j]+i).attr('id',quesSec[j]+newExt+"_tmp");
              }
          }

          for(var i=startId-1; i<endId; i++ )
          {
            for(var j=0; j<queSecLen; j++)
            {
                jQuery("#"+quesSec[j]+i+"_tmp").attr('id',quesSec[j]+i);
            }
          }

      }else {
        bootbox.hideAll();
        return false;
      }
  }
});
});

// Image uploading as an answer option - Swapnil Shinde

jQuery(document).on('click', ".answer_image_upload",function(event){
  //alert("Image uploading as an answer goes here !!");
  // answer_image_upload_count is in tempid (ID of the answer)
  //var tempid = jQuery(this).attr('id');
  var custom_image_uploader2;
  event.preventDefault();

  var answerId = jQuery(this).attr('id');
  var arr = answerId.split('_');
  var ansNum = parseInt(arr[3]);
  //alert(ansNum);
                //Extend the wp.media object
  custom_image_uploader2 = wp.media.frames.file_frame = wp.media({
      title: 'Upload the Image',
      button: {
        text: 'Upload Image'
              },
      multiple: false
  });

    //When a file is selected, grab the URL and set it as the text field's value
    custom_image_uploader2.on('select', function()
      {
          image_attachment = custom_image_uploader2.state().get('selection').first().toJSON();
          var id 				=  image_attachment.id;
          var url 			=  image_attachment.url;
          //jQuery("#"+image_id+"_src").attr("src",url);
          //jQuery("#"+image_id+"_url").val(url);

            jQuery("#image_answers_"+ansNum).val(url);

            //alert($("#image_answers_"+ansNum).val());
         	  toastr.success('Image Uploaded Successfully');

      });

      //Open the uploader dialog
      custom_image_uploader2.open();

});

//Delete answer here
jQuery(document).on('click', ".answer_delete",function(){

  var answerId = jQuery(this).attr('id');
  var arr = answerId.split('_');

  bootbox.confirm({
    message: "Do you want to delete this answer?",
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
    callback: function (result) {
        if(result == true){

          var startId = parseInt(arr[2]) + 1;
          var endId = jQuery('.answer_screen').length;

          jQuery( "#answer_list_"+arr[2] ).remove();

          for(var i=startId; i<=endId; i++ )
          {
              var newExt = i-1;
              for(var j=0; j<ansSecLen; j++)
              {
                  jQuery("#"+ansSec[j]+i).attr('id',ansSec[j]+newExt+"_tmp");
              }
          }

          for(var i=startId-1; i<endId; i++ )
          {
            for(var j=0; j<ansSecLen; j++)
            {
                jQuery("#"+ansSec[j]+i+"_tmp").attr('id',ansSec[j]+i);
            }
          }
        } else {
          bootbox.hideAll();
          return false;
        }
    }
});
  });

//Update question
jQuery(document).on('click', "#add_question_btn",function(){

if(jQuery( "#desc_answer" ).val()!="" || jQuery( "#answer_contents" ).val()!=""){
  var updQue = jQuery( "#question_number" ).html();

  jQuery( "#question_"+updQue ).html(jQuery( "#question_content" ).val());
  jQuery( "#questions_content_"+updQue ).val(jQuery( "#question_content" ).val());

  var ansNum = jQuery('.answer_screen').length;
  var ansContent = "", nextQuestion = "";



  for(var i=1; i<=ansNum; i++)
  {
        if(i == ansNum)
        {
          // DEBUG_COMMENT
          //alert("Last Answer "+jQuery( "#image_answers_"+i ).val());
          // Check if answer type is text or image
          if(jQuery( "#answer_contents" ).val() == "text")
          {
            ansContent+= jQuery( "#answers_"+i ).val();
          }
          else if(jQuery( "#answer_contents" ).val() == "image"){
            // Storing address of the image in the answers column
            ansContent+= jQuery( "#image_answers_"+i ).val();
          }
          nextQuestion+= jQuery( "#next_question_"+i ).val();
        }
        else
        {
          // DEBUG_COMMENT
          //alert("Intermediate Answer "+jQuery( "#image_answers_"+i ).val());
          if(jQuery( "#answer_contents" ).val() == "text")
          {
            ansContent+= jQuery( "#answers_"+i ).val()+"|||";
          }
          else if(jQuery( "#answer_contents" ).val() == "image"){
            // image_answers_ is hidden field stores image url's
            ansContent += jQuery( "#image_answers_"+i ).val()+"|||";
          }
          nextQuestion+= jQuery( "#next_question_"+i ).val()+"|||";
        }
  }

  jQuery( "#questions_answer_"+updQue ).val(ansContent);
  jQuery( "#next_questions_"+updQue ).val(nextQuestion);

  if(jQuery("#other_option").is(':checked'))
    jQuery( "#other_options_"+updQue ).val("yes"); // checked
  else
  jQuery( "#other_options_"+updQue ).val("no"); // unchecked

  jQuery( "#option_texts_"+updQue ).val(jQuery( "#option_text" ).val());

  jQuery( "#desc_answers_"+updQue ).val(jQuery( "#desc_answer" ).val());  // unchecked

  // Take value of answer content as text or image
  jQuery( "#answer_content_"+updQue ).val(jQuery( "#answer_contents" ).val());
  //alert($("#answer_contents").val());

  toastr.success('Question Updated Successfully');
}
else {
  toastr.error('Answer\'s Section is Empty');
}
});

//Edit Question here
jQuery(document).on('click', '.edit_question', function(){

  jQuery( "#section_question" ).removeClass( "hidden_section" );
  jQuery( "#section_header" ).addClass( "hidden_section" );
  jQuery( "#section_message" ).addClass( "hidden_section" );


  //preview answer
  var ansDivs = '';

  var questionId = jQuery(this).attr('id');
  var arr = questionId.split('_');

  jQuery( "#question_number" ).html(arr[1]);
  jQuery( "#question_content" ).val(jQuery( "#questions_content_"+arr[1]).val());

  //preview question
  var QueDiv = '<div style="text-align:center;padding-bottom:20px;">'+jQuery( "#questions_content_"+arr[1]).val()+'</div>';

  var ansContent = jQuery( "#questions_answer_"+arr[1] ).val();
  var nextQuestion = jQuery( "#next_questions_"+arr[1] ).val();

  var answers = ansContent.split('|||');
  var nextQues = nextQuestion.split('|||');

  jQuery( "#answer_list" ).empty();
  var answerLen = answers.length;

  if(jQuery( "#desc_answers_"+arr[1]).val() == 'no'){
  var radioStyle = "border-radius:10px !important;";
  var ansType = "radio";
  }
  if(jQuery( "#desc_answers_"+arr[1]).val() == 'multi'){
  var ansType = "checkbox";
  var radioStyle = '';
  }

  // DEBUG_COMMENT - Used to display survey content to the user for editing (Already saved survey)
  for(var i=0; i<answerLen; i++)
  {
    var answerNum = i + 1;
    var textbox_or_image_append="";
    if(jQuery("#answer_content_"+arr[1]).val() == "text"){
      textbox_or_image_append = "<input type='text' placeholder='' class='form-control mt-repeater-input-inline' name='answers[]' id='answers_"+answerNum+"' />";
    }
    else if(jQuery("#answer_content_"+arr[1]).val() == "image"){
      textbox_or_image_append = "<input type='text' placeholder='' class='form-control mt-repeater-input-inline' value='Upload/Change the Image' name='answers[]' id='answers_"+answerNum+"' readonly/><a id='answer_image_upload_"+answerNum+"' class='btn btn-primary answer_image_upload mt-repeater-del-right mt-repeater-btn-inline'><i class='fa fa-upload'></i></a>";
    }
    var answer_sec = "<div id='answer_list_"+answerNum+"' class='row answer_screen'><div class='col-md-6 col-xs-6 col-sm-6 col-xl-6'><div class='form-group'><label class='control-label'>Add Answer</label><div class='mt-repeater-cell'>"+textbox_or_image_append+"<input type='hidden' name='image_answers[]' id='image_answers_"+answerNum+"'><input type='hidden' name='answer_content[]' id='answer_content_"+questionNum+"' value=''><a id='answer_delete_"+answerNum+"' class='btn btn-danger answer_delete mt-repeater-del-right mt-repeater-btn-inline'><i class='fa fa-close'></i></a></div></div></div><div class='col-md-6 col-xs-6 col-sm-6 col-xl-6'><div class='form-group'><label class='control-label'>Question Redirect</label><select class='form-control' name='next_question[]'' id='next_question_"+answerNum+"'></div></div></div></div>";

    jQuery( "#answer_list" ).append( answer_sec );

    jQuery('#next_question_'+answerNum).append(jQuery('<option>', {
    value: 0,
    text: 'Next Question'
    }));

    var questionNum = jQuery('.screen').length;
    for(var j=1; j<=questionNum; j++)
    {
      if(jQuery( "#questions_type_"+j ).val() == "1")
        var textSelect = "Question : "+jQuery( "#questions_content_"+j ).val();
      else if(jQuery( "#questions_type_"+j ).val() == "2")
       var textSelect = "Message : "+jQuery( "#questions_answer_"+j ).val();
      else if(jQuery( "#questions_type_"+j ).val() == "3")
       var textSelect = "Lead Generation form : "+jQuery( "#questions_answer_"+j ).val();

       jQuery('#next_question_'+answerNum).append(jQuery('<option>', {
       value: j,
       text: textSelect
       }));
    }

     // If given question has image answers type then store answers for editing in the hidden field image_answers_
     if(jQuery( "#answer_content_"+arr[1] ).val() == "image")
     {
       // Storing answers for editing in the image_answers_ hidden field
       //alert(answerNum+answers[i]);
       jQuery( "#image_answers_"+answerNum ).val(answers[i]);
     }
     else if (jQuery( "#answer_content_"+arr[1] ).val() == "text") {
       jQuery( "#answers_"+answerNum ).val(answers[i]);
       //alert(answerNum+answers[i]);
     }
     else {
       jQuery("#answer_list_"+answerNum).addClass("display-none");
     }
     jQuery('#next_question_'+answerNum+' option[value='+nextQues[i]+']').attr('selected','selected');

     //preview answer
     ansDivs += '<div class="sfQuestion"  id="'+i+'"><label style="display: block;"><input class="sfAnswer" type="'+ansType+'" style="cursor: pointer;'+radioStyle+'" name="sf-answer" id="sf-answer"><span style="cursor:pointer; padding-left: 5px;padding-right: 5px;">'+answers[i]+'</span></label></div>';

  }

    if(jQuery( "#other_options_"+arr[1]).val() == "yes")
      jQuery('#other_option').prop('checked', true);
    else
      jQuery('#other_option').prop('checked', false);

    jQuery( "#option_text" ).val("");
    jQuery( "#option_text" ).val(jQuery("#option_texts_"+arr[1]).val());


  if(jQuery( "#desc_answers_"+arr[1]).val() == "yes")
  {
    jQuery( ".mt-repeater" ).addClass( "display-none" );

    // For descriptive answer - answer_content_div should be disabled
    jQuery(".answer_content_div").addClass( "display-none" );
    ansDivs = '<div class="sfQuestion"><label style="display: block;"><textarea style="width:100%;line-height:1.5;" cols="5"></textarea></label></div><span style="float:right;"><input type="button" style="cursor: pointer;padding: 6px 12px;font-weight: bolder; margin: 5px;background-image: none; background-color:transparent\" value="Send" /></span>';
  } else {
    jQuery( ".mt-repeater" ).removeClass( "display-none" );
    jQuery(".answer_content_div").removeClass("display-none");
 }

 /*
 // If Answer content in DB is text then hide image upload
 if($("#answer_content_"+arr[1]).val() == "text"){
   $(".answer_image_upload").hide();
 }
 else {
   $(".answer_image_upload").show();
 }
 */

   if(jQuery("#desc_answers_"+arr[1]).val()!=""){
     jQuery('#desc_answer option[value='+jQuery("#desc_answers_"+arr[1]).val()+']').prop('selected',true);
   }
  jQuery('#answer_contents option[value='+jQuery("#answer_content_"+arr[1]).val()+']').prop('selected',true);
    //preview content
    if(jQuery( "#answer_content_"+arr[1] ).val() == "image"){
      ansDivs = '<p><center>Preview for Image Answers is not available</center></p>';
    }
    var surveyContent = QueDiv + ansDivs;
    jQuery("#survey_question_content_preview").html(surveyContent);
    applyCss_preview();

});

//Create/Edit tab onclick
jQuery(document).on('click', "#tab_create",function(){

  applyCss_preview();

});

//Add question header here..
jQuery('#add_header').on("click",function(){
  jQuery( "#section_header" ).removeClass( "hidden_section" );
  jQuery( "#section_question" ).addClass( "hidden_section" );
  jQuery( "#section_message" ).addClass( "hidden_section" );
});

//Add multiple message here...
jQuery('#add_message').on("click",function(){
  jQuery("#section_message").removeClass( "hidden_section" );
  jQuery( "#section_header" ).addClass( "hidden_section" );
  jQuery( "#section_question" ).addClass( "hidden_section" );

  // Number of '/'
  var path=window.location.href;
  var count=0;
  for(var i=0;i<path.length;i++)
  {
      if(path[i]=='/')
      {
        count++;
      }
  }

      // Find URL of Plugin Folder
      var plugin_path=path.substring(0,path.indexOf('/wp-admin'));
      var questionNum = jQuery('.screen').length + 1;

      // Get size of Icon selected by the user
      var icon_size= jQuery("input[name='social_sharing']:checked").val();

      // Get all checked social sharing Sites
      //var fbon = jQuery( "#social_sharing_facebook").val();
      var facebook_on=0,twitter_on=0,linkedin_on=0;
      if(jQuery("#enable_facebook").is(':checked')){
          facebook_on=1;
      }
      if(jQuery("#enable_twitter").is(':checked')){
        twitter_on=1;
      }
      if(jQuery("#enable_linkedin").is(':checked')){
        linkedin_on=1;
      }
      //alert(fbon);

      // Message Appending to Thank You
      var msg_content_append="";
      if(!(facebook_on==0 && twitter_on==0 && linkedin_on==0))
      {
        msg_content_append+="<p style='' class='social_sharing_message'>Share on - </p>";
      }
      if(facebook_on==1){
        msg_content_append += "<a class='sharing_anchor' href=facebook_url target='_blank'><img class='sharing_image' src='"+plugin_path+"/wp-content/plugins/surveyfunnel/admin/images/fb"+icon_size+".png' alt='FB'/></a>";
      }
      if (twitter_on==1) {
        msg_content_append+="<a class='sharing_anchor' href=twitter_url target='_blank'><img class='sharing_image_next' src='"+plugin_path+"/wp-content/plugins/surveyfunnel/admin/images/twitter"+icon_size+".png' alt='Twitter'/></a>";
      }
      if(linkedin_on==1){
        msg_content_append+="<a class='sharing_anchor' href=linkedin_url target='_blank'><img class='sharing_image_next' src='"+plugin_path+"/wp-content/plugins/surveyfunnel/admin/images/linkedin"+icon_size+".png' alt='Linkedin'/></a>";
      }

      // This msg_sec and question_sections are used to just display in ScreenList in Create/Edit Option
      var msg_sec = "<div id='screen_"+questionNum+"' class='screen'><span id='question_header_"+questionNum+"'><a id='edit_"+questionNum+"' class='edit_message'>Message <b id='queNumber_"+questionNum+"'>"+questionNum+"</b></a></span><span style='float:right'><a id='delete_"+questionNum+"' class='question_delete'><i class='fa fa-close'></i> </a></span><br/><strong id='question_"+questionNum+"'><p>Thank You!!!</p>"+msg_content_append+"</strong><input type='hidden' name='questions_ids[]' id='questions_id_"+questionNum+"' value=''><input type='hidden' name='questions_content[]' id='questions_content_"+questionNum+"' value=''><input type='hidden' name='questions_answer[]' id='questions_answer_"+questionNum+"' value='Thank You!!!'><input type='hidden' name='next_questions[]' id='next_questions_"+questionNum+"' value=''><input type='hidden' name='other_options[]' id='other_options_"+questionNum+"' value=''><input type='hidden' name='option_texts[]' id='option_texts_"+questionNum+"' value=''><input type='hidden' name='desc_answers[]' id='desc_answers_"+questionNum+"' value=''><input type='hidden' name='answer_content[]' id='answer_content_"+questionNum+"' value=''><input type='hidden' name='questions_type[]' id='questions_type_"+questionNum+"' value='2'></div>";

      jQuery( "#screens_list" ).append( msg_sec );
      jQuery( "#message_number" ).html( questionNum );
      jQuery( "#msg_content" ).val( "<p style='' class='thank_you_text'>Thank You!!!</p>" + msg_content_append );
      jQuery( "#redirect_url" ).val( "" );

      var updMsg = jQuery( "#message_number" ).html();

      jQuery( "#question_"+updMsg ).html(jQuery( "#msg_content" ).val());
      jQuery( "#questions_answer_"+updMsg ).val(jQuery( "#msg_content" ).val());
      jQuery( "#questions_content_"+updMsg ).val(jQuery( "#redirect_url" ).val());


});

//update message content...
jQuery(document).on('click', "#add_message_btn",function(){

  var updMsg = jQuery( "#message_number" ).html();

  jQuery( "#question_"+updMsg ).html(jQuery( "#msg_content" ).val());
  jQuery( "#questions_answer_"+updMsg ).val(jQuery( "#msg_content" ).val());
  jQuery( "#questions_content_"+updMsg ).val(jQuery( "#redirect_url" ).val());
  toastr.success('Message Updated Successfully');
});

//edit message content
jQuery(document).on('click', ".edit_message",function(){

  jQuery("#section_message").removeClass( "hidden_section" );
  jQuery( "#section_header" ).addClass( "hidden_section" );
  jQuery( "#section_question" ).addClass( "hidden_section" );

  var questionId = jQuery(this).attr('id');
  var arr = questionId.split('_');

  jQuery( "#message_number" ).html(arr[1]);
  // Edited by: Swapnil Shinde(To avoid empty message content box on re-editing the message)
  jQuery( "#msg_content" ).val(jQuery( "#questions_answer_"+arr[1]).val());
  jQuery( "#redirect_url" ).val(jQuery( "#questions_content_"+arr[1]).val());
  //jQuery( "#social_sharing_facebook_"+arr[1]).val();
});

//Add lead generation form...
jQuery('#add_lead_generation_form').on("click",function(){
  jQuery("#section_question").addClass( "hidden_section" );
  jQuery( "#section_header" ).addClass( "hidden_section" );
  jQuery( "#section_message" ).addClass( "hidden_section" );

  var questionNum = jQuery('.screen').length + 1;
  var msg_sec = "<div id='screen_"+questionNum+"' class='screen'><span id='question_header_"+questionNum+"'><a id='edit_"+questionNum+"' class='edit_form'>Lead Generation Form <b id='queNumber_"+questionNum+"'>"+questionNum+"</b></a></span><span style='float:right'><a id='delete_"+questionNum+"' class='question_delete'><i class='fa fa-close'></i> </a></span><br/><strong id='question_"+questionNum+"'>User Information (Name/Email)</strong><input type='hidden' name='questions_ids[]' id='questions_id_"+questionNum+"' value=''><input type='hidden' name='questions_content[]' id='questions_content_"+questionNum+"' value=''><input type='hidden' name='questions_answer[]' id='questions_answer_"+questionNum+"' value='User Information (Name/Email)'><input type='hidden' name='next_questions[]' id='next_questions_"+questionNum+"' value=''><input type='hidden' name='other_options[]' id='other_options_"+questionNum+"' value=''><input type='hidden' name='option_texts[]' id='option_texts_"+questionNum+"' value=''><input type='hidden' name='desc_answers[]' id='desc_answers_"+questionNum+"' value=''><input type='hidden' name='answer_content[]' id='answer_content_"+questionNum+"' value=''><input type='hidden' name='questions_type[]' id='questions_type_"+questionNum+"' value='3'></div>";

  jQuery( "#screens_list" ).append( msg_sec );
});

//Edit Lead generation Form
jQuery(document).on('click', ".edit_form",function(){

  bootbox.alert("You can not modify lead generation form.");

    jQuery("#section_question").addClass( "hidden_section" );
    jQuery( "#section_header" ).addClass( "hidden_section" );
    jQuery( "#section_message" ).addClass( "hidden_section" );

});

//Preview tab onclick
jQuery(document).on('click', "#tab_preview",function(){

  loadSurvey(1);
  applyCss();

});

jQuery(document).on('click', "#sfp_minimize",function(){

   var htmlString = jQuery( this ).html();
   var size_height = jQuery('#size_height').val();
    if(htmlString == "-")
    {
       jQuery('#survey_content').css("display", "none");
       jQuery('#question_survey_funnel_front').css("height", "1px");
       jQuery('#question_survey_funnel_front').css("padding", "0px");
       jQuery( this ).html("+");
    }
    if(htmlString == "+")
    {
       jQuery('#survey_content').css("display", "block");
       jQuery('#question_survey_funnel_front').css("height", size_height+"px");
       jQuery('#question_survey_funnel_front').css("padding", "15px");
       jQuery( this ).html("-");
    }
});

jQuery(document).on('click', ".sfAnswer",function(){
    onAnswerClick();
});

      //upload image
      jQuery(document).on('click', ".sf_upload_media",function(e){

          var image_id = jQuery(this).attr('id');

          e.preventDefault();

                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });

                //When a file is selected, grab the URL and set it as the text field's value
                custom_uploader.on('select', function()
                {
                	attachment 			=  custom_uploader.state().get('selection').first().toJSON();
                	var id 				=  attachment.id;
                	var url 			=  attachment.url;
                  jQuery("#"+image_id+"_src").attr("src",url);
                  jQuery("#"+image_id+"_url").val(url);
                });

                //Open the uploader dialog
                custom_uploader.open();

      });

      jQuery(document).on("click", ".sf_remove_media", function(){

            // Find URL of Plugin Folder
            var path=window.location.href;
            var plugin_path = path.substring(0,path.indexOf('/wp-admin'));

            if(confirm("Do you want to remove this image?")){
                  var imageId = jQuery(this).attr('id');
                  jQuery("#"+imageId+"_src").attr("src","");
                  jQuery("#"+imageId+"_url").val("");
                  // To set default image on removing previous image
                  if(imageId=="tab_image"){
                    jQuery("#"+imageId+"_src").attr("src",plugin_path+"/wp-content/plugins/surveyfunnel/admin/images/tabs/click_here.png");
                    jQuery("#"+imageId+"_url").val(plugin_path+"/wp-content/plugins/surveyfunnel/admin/images/tabs/click_here.png");
                  }
            }

      });

});

var queIndex, ansIndex;

function onAnswerClick(){
  queIndex = parseInt(jQuery( "#queIndex" ).val()) + 1;
  jQuery( "#survey_content" ).empty();
  loadSurvey(queIndex);
  applyCss();
}

function loadSurvey(queIndex)
{
  var ansDivs = '';

  var QueContent =  jQuery('#questions_content_'+queIndex).val();
  var questionsType = jQuery( "#questions_type_"+queIndex).val();

  var ansContent = jQuery( "#questions_answer_"+queIndex).val();
  var nextQuestion = jQuery( "#next_questions_"+queIndex).val();

  if(questionsType == 1)
  {
      var QueDiv = '<div style="text-align:center;padding-bottom:20px;">'+QueContent+'</div>';
      var answers = ansContent.split('|||');
      var nextQues = nextQuestion.split('|||');

      var answerLen = answers.length;

      if(jQuery("#rtl").is(':checked'))
      {
            for(var i=0; i<answerLen; i++)
            {
                ansDivs += '<div class="sfQuestion" id="'+i+'"><label style="display: block; text-align:right;"><span style="cursor:pointer; padding-left: 5px;padding-right: 5px;">'+answers[i]+'</span><input class="sfAnswer" type="radio" style="cursor: pointer;" name="sf-answer" id="sf-answer"></label></div>';
            }
          }else {
            for(var i=0; i<answerLen; i++)
            {
                ansDivs += '<div class="sfQuestion"  id="'+i+'"><label style="display: block;"><input class="sfAnswer" type="radio" style="cursor: pointer;" name="sf-answer" id="sf-answer"><span style="cursor:pointer; padding-left: 5px;padding-right: 5px;">'+answers[i]+'</span></label></div>';
            }
          }

          var surveyContent = QueDiv + ansDivs;
        }

      if(questionsType == 2)
      {
            var surveyContent = '<div class="question_survey_funnel_front" align="center">'+ansContent+'</div>';
      }

      if(questionsType == 3)
      {
          var surveyContent = '<div class="question_survey_funnel_front" style=" padding: 5px; cursor: pointer; cursor: hand;" id="usercontent"><h2 style="font-size:inherit;font-weight: inherit; text-align:center;font-family:inherit;margin: 3px;" align="center"> User Information</h2> <div id="userinfo_form_1" style="display:flex; padding:5px;"><div style="width:25%;"><span>Name</span></div><div><input style="padding: 5px;font-size:13px;font-family:Lato, sans-serif;" type="text" name="uname" id="uname"></div></div></div><div id="userinfo_form_2" style="display:flex;padding:5px 5px 5px 8px; "><div style="width:25%;"><span>Email</span></div><div><input style="padding: 5px;font-size:13px;font-family:Lato, sans-serif;" type="email" name="uname" id="uname"></div></div></div>   <div id="userinfo_form_3" style="display:flex; padding:5px;"><div style="width:50%;padding-left:50px;"> <button style="padding:4px 10px;border:1px solid #FFF;border-radius:12px !important;font-size:14px;background-color:inherit;" >Submit</button> </div><div> <button style="padding:4px 10px;border:1px solid #FFF;border-radius:12px !important;font-size:14px;background-color:inherit;" >Cancel</button> </div></div></div>   </div>';
      }

      if(jQuery('input[name=sf-brand]:checked', '#survey_edit').val() == "on")
      var brandDiv = '<div style="position:absolute;bottom:5px;"><a class="brandText" href="http://app.surveyfunnel.com" target="_blank" style="font-size:10px;font-family:helvetica !important;text-decoration:none;cursor:pointer;">Powered by SF App</a></div>';
      else
      var brandDiv = '';


      var queIndexCont = '<input type="hidden" name="queIndex" id="queIndex" value="'+queIndex+'">';
      jQuery("#survey_content").html(surveyContent+brandDiv+queIndexCont);

}

function applyCss_preview(){
  var backColor, queBackColor, textColor, hoverBackColor, hoverTextColor, borderColor, themeIndex;

 backColor = jQuery('input[name=theme-color]:checked', '#survey_edit').val();
 var themeColors = ['#373536','#D2CECE','#EAA8CB',
 '#749FB4','#7EB26E','#B26F70',
 '#B885D4','#65573E','#8CCBEE',
 '#C6E98D','#E8E28D','#D6DFE5'];

 var relateColors = ['#474646','#ECECEC','#D396B7',
 '#96B9C8','#99C889','#C28B8C',
 '#C39FD8','#8D7E69','#A5DDFA',
 '#B5CE8D','#D5D1A6','#ECECEC'];

 var textWhite = ['#373536','#EAA8CB',
 '#749FB4','#7EB26E','#B26F70',
 '#B885D4','#65573E'];

 themeIndex = jQuery.inArray( backColor, themeColors);
 queBackColor = hoverTextColor = borderColor = relateColors[themeIndex];

 if(jQuery.inArray(backColor, textWhite) >= 0)
 {
   textColor = hoverBackColor = '#FFF';
 }
 else{
   textColor = hoverBackColor = '#000';
 }


 var size_width = jQuery('#size_width').val();
 var size_height = jQuery('#size_height').val();
 var position = jQuery('input[name=position]:checked', '#survey_edit').val();

  jQuery('#survey_question_content_preview').css("display", "block");
  jQuery('#question_survey_funnel_preview').css("padding", "15px");

 jQuery('#question_survey_funnel_preview').css("background-color", backColor);
 jQuery('#question_survey_funnel_preview').css("color", textColor);

 jQuery('#question_survey_funnel_preview').css("border-color", borderColor);
 jQuery('#question_survey_funnel_preview').css("box-shadow-color", backColor);

 jQuery('#question_survey_funnel_front').css("max-width", size_width+"px");
 jQuery('#question_survey_funnel_front').css("height", size_height+"px");

 jQuery('.sfQuestion').css("background-color", queBackColor);
 jQuery('.sfQuestion').css("color", textColor);

}

function applyCss(){
  var backColor, queBackColor, textColor, hoverBackColor, hoverTextColor, borderColor, themeIndex;

 backColor = jQuery('input[name=theme-color]:checked', '#survey_edit').val();
 var themeColors = ['#373536','#D2CECE','#EAA8CB',
 '#749FB4','#7EB26E','#B26F70',
 '#B885D4','#65573E','#8CCBEE',
 '#C6E98D','#E8E28D','#D6DFE5'];

 var relateColors = ['#474646','#ECECEC','#D396B7',
 '#96B9C8','#99C889','#C28B8C',
 '#C39FD8','#8D7E69','#A5DDFA',
 '#B5CE8D','#D5D1A6','#ECECEC'];

 var textWhite = ['#373536','#EAA8CB',
 '#749FB4','#7EB26E','#B26F70',
 '#B885D4','#65573E'];

 themeIndex = jQuery.inArray( backColor, themeColors);
 queBackColor = hoverTextColor = borderColor = relateColors[themeIndex];

 if(jQuery.inArray(backColor, textWhite) >= 0)
 {
   textColor = hoverBackColor = '#FFF';
 }
 else{
   textColor = hoverBackColor = '#000';
 }

   var size_width = jQuery('#size_width').val();
   var size_height = jQuery('#size_height').val();
   var position = jQuery('input[name=position]:checked', '#survey_edit').val();

  jQuery('#survey_content').css("display", "block");
  jQuery('#question_survey_funnel_front').css("padding", "15px");

 jQuery('#sfp_minimize').html("-");
 jQuery('#sfp_minimize').css("background-color", backColor);
 jQuery('#sfp_minimize').css("color", textColor);
 jQuery('#sfp_minimize').css("border-color", borderColor);

 if(position == "left")
 jQuery('#sfp_minimize').css("margin-left", (parseInt(size_width) - parseInt(20))+"px");
 else
 jQuery('#sfp_minimize').css("margin-left", (parseInt(jQuery(".sf-preview").width()) - parseInt(40))+"px");

 if(position == "left")
 jQuery('#question_survey_funnel_front').css("margin-left", "10px");
 else
 jQuery('#question_survey_funnel_front').css("margin-left", (parseInt(jQuery(".sf-preview").width()) - (parseInt(size_width) + parseInt(10)))+"px");

 jQuery('#question_survey_funnel_front').css("background-color", backColor);
 jQuery('#question_survey_funnel_front').css("color", textColor);

 jQuery('#question_survey_funnel_front').css("border-color", borderColor);
 jQuery('#question_survey_funnel_front').css("box-shadow-color", backColor);

 jQuery('#question_survey_funnel_front').css("max-width", size_width+"px");
 jQuery('#question_survey_funnel_front').css("height", size_height+"px");

 jQuery('.sfQuestion').css("background-color", queBackColor);
 jQuery('.sfQuestion').css("color", textColor);

 jQuery('.brandText').css("color", textColor);
}
