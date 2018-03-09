<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- BEGIN CONTAINER -->
<div class="page-container page-content-inner page-container-bg-solid">
   <!-- BEGIN CONTENT -->
   <div class="container-fluid container-lf-space margin-top-30">

     <div class="row">
          <div class="col-md-10">

        <div class="portlet light bordered">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-bar-chart font-red"></i>
               <span class="caption-subject font-red bold uppercase"> Survey Funnel Results :  </span>
               <span class="caption-subject font-green-sharp bold uppercase"> <?php echo $SurveyManage->survey_name; ?> </span>
            </div>
         </div>

         <?php
            global $wpdb;
            $select = "SELECT {$wpdb->prefix}sf_survey_questions.survey_question_id, {$wpdb->prefix}sf_survey_questions.question, {$wpdb->prefix}sf_survey_questions.answers,{$wpdb->prefix}sf_survey_questions.other_answer, {$wpdb->prefix}sf_survey_questions.text_answer_allowed
                            FROM {$wpdb->prefix}sf_survey_questions
            				WHERE {$wpdb->prefix}sf_survey_questions.survey_id = '$SurveyManage->survey_id'
            				AND {$wpdb->prefix}sf_survey_questions.active_status_id = 1
            				AND {$wpdb->prefix}sf_survey_questions.question_type = 1
            				ORDER BY {$wpdb->prefix}sf_survey_questions.priority";

            $tr = $wpdb->get_results($select);
            $select1 = "SELECT user_id FROM {$wpdb->prefix}sf_survey_results , {$wpdb->prefix}sf_survey_questions
            				WHERE {$wpdb->prefix}sf_survey_results.survey_id = '$SurveyManage->survey_id'
            				AND {$wpdb->prefix}sf_survey_questions.survey_question_id =  {$wpdb->prefix}sf_survey_results.survey_question_id
            				AND {$wpdb->prefix}sf_survey_questions.active_status_id = '1'";

            $user_ids = $this->wpdb->get_results($select1);
            if($user_ids){
            	foreach( $user_ids as $user_id )
            		$users[] = $user_id->user_id;

            	$counts = count((array_count_values($users)));

            }else{
            	$counts= 0;
            }
            ?>

         <div class="portlet-body">
            <div class="alert alert-success alert-dismissable">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
               <strong>WOW!</strong> You have <a href="" class="alert-link"> <?php echo $counts; ?> </a> Responders.
            </div>

            <!-- Tabs start here -->
            <div class="tabbable-line">

               <ul class="nav nav-tabs">
                  <li class="active">
                     <a href="#tab_1_1" data-toggle="tab"> Question Summary </a>
                  </li>
                  <li>
                     <a href="#tab_1_2" data-toggle="tab"> Individual Summary </a>
                  </li>
               </ul>

               <div class="tab-content">

                  <!-- Question Summary Tab start here -->
                  <div class="tab-pane active" id="tab_1_1">
                     <section id="new">

                       <?php
                          $questions = array();
                          $answers = array();
                          $heights = array();
                          foreach ($tr as $r) {

                          	$heights[$r->survey_question_id] = count(explode("|||", $r->answers)) * 25;

                          	if(!empty($r->other_answer)){
                          		$r->answers .= "|||".$r->other_answer;
                          	}
                          	foreach (explode("|||", $r->answers) as $answer_name) {
                                if(isset($r->text_answer_allowed) && $r->text_answer_allowed== 'multi'){

                          		        $answer_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}sf_survey_results WHERE survey_question_id = %s AND answer like %s",$r->survey_question_id,'%'.$answer_name.'%'));

                                }
                                else{
                                    $answer_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}sf_survey_results WHERE survey_question_id = %s AND answer = %s",$r->survey_question_id,$answer_name));
                                }
                                $questions[$r->survey_question_id] = $r->question;
                          		$answers[$r->survey_question_id][$answer_name] = $answer_count;
                          	}

                          	if(!empty($r->other_answer)){
                          		$other_answer_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}sf_survey_results WHERE survey_question_id = %s AND extra_ans = 'true'",$r->survey_question_id));
                          		$questions[$r->survey_question_id] = $r->question;
                          		$answers[$r->survey_question_id][$answer_name] = $other_answer_count;
                          	}
                          }

                          if (count($questions)) {
                          	$questionCount = 1;
                          	echo '<div class="tab-pane active" id="ques">';
                          	foreach ($questions as $question_id => $question_name) {
                          		arsort($answers[$question_id]);

                          		$max_value = 0;
                          		$value_string = '';
                          		$legend_string = '';
                          		$totalAns_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}sf_survey_results WHERE survey_question_id = %s",$question_id));
                              ?>

                        <!-- Question list start here -->
                        <div class="portlet box blue-hoki">

                           <div class="portlet-title">
                              <div class="caption">
                                 <i class="fa fa-question-circle"></i> Question <?php echo $questionCount.': '.strip_tags(html_entity_decode($question_name)); ?>
                              </div>
                              <div class="tools">
                                 <a href="javascript:;" class="collapse"> </a>
                              </div>
                           </div>

                           <div class="portlet-body ">

                              <!-- Individual Question detail start here -->
                              <div class="table-responsive">
                                 <table class="table table-striped table-bordered table-hover">
                                    <thead class="flip-content">
                                       <tr>
                                          <th width="20%"> Answer Choice </th>
                                          <th> Response in Percentage </th>
                                          <th class="numeric"> Response in Counts </th>
                                       </tr>
                                    </thead>
                                    <tbody>

                                      <?php
                                      foreach ($answers[$question_id] as $answer_name => $answer_count) {

                                  			if ($answer_count > $max_value) { $max_value = $answer_count; }
                                  			$value_string .= "$answer_count,";
                                  			$legend_string .= urlencode("$answer_name: $answer_count") . "|";
                                  			if($totalAns_count == 0){
                                  				$percent = 0;
                                  			}else{
                                  				$percent = $answer_count/$totalAns_count*100;
                                  				$percent = round($percent);
                                  			}
                                       ?>

                                       <tr>
                                          <td> <?php echo $answer_name; ?> </td>
                                          <td>
                                             <div class="dashboard-stat2 ">
                                                <div class="progress-info">
                                                   <div class="progress">
                                                      <span style="width: <?php echo $percent."%"; ?>;" class="progress-bar progress-bar-success blue-sharp">
                                                      <span class="sr-only"><?php echo $percent."%"; ?> Progress</span>
                                                      </span>
                                                   </div>
                                                   <div class="status">
                                                      <div class="status-title"> Progress </div>
                                                      <div class="status-number"> <?php echo $percent."%"; ?> </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </td>
                                          <td class="numeric"> <?php echo $answer_count;?> </td>
                                       </tr>

                                       <?php
                                     }

                                     $value_string = substr($value_string, 0, strlen($value_string) - 1);
                                     $legend_string = substr($legend_string, 0, strlen($legend_string) - 1);
                                        ?>

                                       <tr>
                                          <th> Total Responses </th>
                                          <td> &nbsp; </td>
                                          <th class="numeric"> <?php echo $totalAns_count; ?> </th>
                                       </tr>

                                    </tbody>
                                 </table>
                              </div>
                              <!-- Individual Question detail end here -->

                           </div>
                        </div>
                        <!-- Question list end here -->

                        <?php 		$questionCount++;
                        	}
                        }

                        ?>


                     </section>
                  </div>
                  <!-- Question Summary Tab end here -->

                  <!-- Individual Summary Tab start here -->
                  <div class="tab-pane" id="tab_1_2">
                     <section id="indv">

                       <?php

                       if($userCounts == 0){
                        ?>
                        <div class="alert alert-info alert-dismissable">
                           <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                           <strong>Oop's!</strong> Their are no users to Analyze.
                        </div>
                        <?php
                       }else {
                       foreach ($indv_results as $user_id  => $indv) {

                       	$user_name = ($indv[0]['user_name'] == '' ? 'Not Set' : $indv[0]['user_name']);
                       	$email_id = ($indv[0]['email_id'] == '' ? 'Not Set' : $indv[0]['email_id']);
                       	?>



                       <!-- Individual user start here -->
                        <div class="portlet box blue-hoki">
                           <div class="portlet-title">
                              <div class="caption">
                                 <i class="fa fa-user"></i> #<?php echo $user_id; ?>
                              </div>
                              <div class="tools">
                                 <a href="javascript:;" class="collapse"> </a>
                              </div>
                           </div>

                           <div class="portlet-body">
                              <div class="timeline">
                                 <!-- TIMELINE ITEM -->
                                 <div class="timeline-item">
                                    <div class="timeline-badge">
                                       <div class="timeline-icon">
                                          <i class="icon-user-following font-green-haze" style="margin-top:-8px;"></i>
                                       </div>
                                    </div>
                                    <div class="timeline-body">
                                       <div class="timeline-body-arrow"> </div>
                                       <div class="timeline-body-head">
                                          <div class="timeline-body-head-caption">
                                             <a href="javascript:;" class="timeline-body-title font-blue-madison"><?php echo $user_name." | ".$email_id; ?></a>
                                             <span class="timeline-body-time font-grey-cascade">Replied at <?php echo $indv[0]['date_created']?></span>
                                          </div>
                                       </div>
                                       <div class="timeline-body-content">
                                          <div class="table-responsive">
                                             <table class="table table-striped table-bordered table-hover">
                                                <thead class="flip-content">
                                                   <tr>
                                                      <th width="20%"> Question </th>
                                                      <th> Answer </th>
                                                      <th> Descriptive Answer </th>
                                                   </tr>
                                                </thead>
                                                <tbody>

                                                   <!-- Individual user result start here -->
                                                   <?php
                                                 		foreach ($indv as $qa){
                                                 		//	print_r($qa);
                                                 			?>
                                                   <tr>
                                                      <td> <?php echo strip_tags(html_entity_decode($qa['question'])) ; ?> </td>
                                                      <td> <?php  $multi_ans = '';
                                                 			if (strpos($qa['answer'], '|||') !== false) {
                                                 					 $multiple_answers = explode("|||", $qa['answer']);
                                                 					 for($i=0; $i<count($multiple_answers)-1; $i++)
                                                 					 {
                                                 					 $j = $i + 1;
                                                 					 $multi_ans .= $j.". ".$multiple_answers[$i]."<br/>";
                                                 					 }
                                                 			 }
                                                 			 else
                                                 				 $multi_ans .= $qa['answer'];
                                                          echo $multi_ans; ?> </td>
                                                      <td> <?php echo $qa['extra_ans']; ?> </td>
                                                   </tr>
                                                   <!-- Individual user result end here -->
                                                   <?php
                                                 }
                                               ?>
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- END TIMELINE ITEM -->
                              </div>
                           </div>
                        </div>
                        <!-- Individual user end here -->
                        <?php } ?>

                        <ul class="pagination">
                        <?php
                        $pages = ceil($userCounts/10);
                        if($pages > 1){
                        	     for($i=1; $i <= $pages ; $i++)
                               {
                        		    echo '<li><a href="#" onclick="get_indv_result('.$i.')">'.$i.'</a></li>';
                        	     }
                        	}
                        ?>
                        </ul>
                        <?php
                      }
                    ?>

                     </section>
                  </div>
                  <!-- Individual Summary Tab end here -->

               </div>
            </div>
            <!-- Tabs end here -->

         </div>

      </div>

    </div>
   </div>
   <!-- ROW CONTENT -->
</div>
<!-- END CONTAINER -->

</div>

<?php $survey_nonce = wp_create_nonce($this->survey_id);?>

<script type="text/javascript" >
function get_indv_result($pageno){

var sf_data = {ajax_url:ajaxurl, nonce: '<?php echo $survey_nonce; ?>'};

  var data = {
      '_wpnonce': sf_data.nonce,
      'action': 'surveyfunnel_get_individual_results',
      'page_no': $pageno,
      'survey_id': '<?php echo $this->survey_id; ?>'
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sf_data.ajax_url, data, function(response) {
      jQuery('#indv').html(response);
    });
}
</script>
