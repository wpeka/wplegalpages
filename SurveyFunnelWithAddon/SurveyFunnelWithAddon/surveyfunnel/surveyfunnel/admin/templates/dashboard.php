

<?php
global $wpdb;

$email_display = get_option('sf_email_id');
if(!$email_display){
       $email_display = '';
}
$email = isset($_POST['sf-email'])? $_POST['sf-email']:'';
if($email != '') {
	update_option('sf_email_id',$email);
	$email_display = $email;
}
?>





<!-- BEGIN CONTAINER -->
<div class="page-container page-content-inner page-container-bg-solid form-start">
   <!-- BEGIN CONTENT -->
   <div class="container-fluid container-lf-space">

     <div class="row">
       <div class="col-md-10">

           <div class="portlet light bordered">
               <div class="portlet-title tabbable-line">
                   <div class="caption">
                       <i class="icon-bubbles font-dark hide"></i>
                       <span class="caption-subject font-dark bold uppercase">Dashboard</span>
                   </div>
                   <ul class="nav nav-tabs">
                       <li class="active">
                           <a href="#portlet_comments_1" data-toggle="tab"> Active </a>
                       </li>
                       <li>
                           <a href="#portlet_comments_2" data-toggle="tab"> Inactive </a>
                       </li>
                   </ul>
               </div>
               <div class="portlet-body">
                   <div class="tab-content">
                       <div class="tab-pane active" id="portlet_comments_1">
                            <!-- BEGIN: Comments -->

                            <div class="mt-comments">

                                 <?php
                                 $tr = $wpdb->get_results("SELECT
                                                {$wpdb->prefix}sf_surveys.survey_id,
                                                {$wpdb->prefix}sf_surveys.survey_name,
                                                {$wpdb->prefix}sf_surveys.use_shortcode,
                                                {$wpdb->prefix}sf_surveys.survey_key,
                                                DATE_FORMAT({$wpdb->prefix}sf_surveys.date_created,'%b %d, %Y %h:%i %p') as date_created,
                                                TIMESTAMPDIFF(MONTH,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_month,
                                                TIMESTAMPDIFF(DAY,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_day,
                                                TIMESTAMPDIFF(HOUR,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_hour,
                                                TIMESTAMPDIFF(MINUTE,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_min,
                                                TIMESTAMPDIFF(SECOND,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_sec,
                                                {$wpdb->prefix}sf_survey_stats.imprints,
                                                {$wpdb->prefix}sf_survey_stats.completions FROM {$wpdb->prefix}sf_surveys
                                                LEFT JOIN
                                                {$wpdb->prefix}sf_survey_stats
                                                ON
                                                ({$wpdb->prefix}sf_surveys.survey_id = {$wpdb->prefix}sf_survey_stats.survey_id AND {$wpdb->prefix}sf_survey_stats.active_status_id = 1)
                                                WHERE {$wpdb->prefix}sf_surveys.active_status_id = 1 ORDER BY {$wpdb->prefix}sf_surveys.date_created ASC");

                                  ?>

                                  <?php
                                  $count=0;
                                  foreach ($tr as $r) {
                                    $count++;
                                   if ($r->completions == '') { $r->completions = 0; }
                                   if ($r->imprints == '') { $r->imprints = 0; }
                                   $displaysc= "Not set";

                                   $max_value = $r->imprints;

                                   $modified='';
                                   if ($r->date_modified_hour<0)$modified='Unmodified';
                                   else if ($r->date_modified_month) $modified=$r->date_modified_month.' months ago';
                                   else if ($r->date_modified_day) $modified=$r->date_modified_day.' days ago';
                                   else if ($r->date_modified_hour) $modified=$r->date_modified_hour.' hours ago';
                                   else if ($r->date_modified_min) $modified=$r->date_modified_min.' minutes ago';
                                   else $modified=$r->date_modified_sec.' seconds ago';

                                   if($r->use_shortcode) {
                                     $displaysc = "[survey_funnel key = '$r->survey_key']";
                                   }
                                   $select1 = "SELECT user_id FROM {$wpdb->prefix}sf_survey_results , {$wpdb->prefix}sf_survey_questions
                                           WHERE {$wpdb->prefix}sf_survey_results.survey_id = '$r->survey_id'
                                           AND {$wpdb->prefix}sf_survey_questions.survey_question_id =  {$wpdb->prefix}sf_survey_results.survey_question_id
                                           AND {$wpdb->prefix}sf_survey_questions.active_status_id = '1'";

                                   $user_ids = $wpdb->get_results($select1);
                                   unset($users);
                                   if($user_ids){
                                     foreach( $user_ids as $user_id )
                                       $users[] = $user_id->user_id;

                                     $completions = count((array_count_values($users)));

                                   }else{
                                     $completions= 0;
                                   }

                                   $date1 = new DateTime($r->date_created);
                                  $date2 = new DateTime(date('Y-m-d H:i:s'));

                                    $diff = $date2->diff($date1)->format("%a");



                                     echo '
                                     <div class="mt-comment ">

                                         <div class="mt-comment-body">

                                             <div class="mt-comment-text">';

                                             echo '<div class="row">
                                             <div class="col-md-6">
                                             <strong>'.$r->survey_name.'</strong><br/>';
                                             if($r->use_shortcode) {
                                               echo "[survey_funnel key=$r->survey_key]";
                                             }else {
                                               echo "-";
                                             }
                                             echo '</div>
                                             <div class="col-md-6">
                                                <div class="survey_element">
                                                   <div class="element">
                                                   <div class="btn-group">
                                                        <a class="btn btn-circle blue" href="javascript:;" data-toggle="dropdown">
                                                            Export
                                                            <i class="fa fa-angle-down"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                 <a class="blue" href="' . SF_PLUGIN_URL . '/json.php?action=EXPORT_TO_XLS&survey_id='.$r->survey_id.'" target="_blank">
                                                                 <i class="fa fa-file-excel-o"></i>Summary</a>
                                                            </li>
                                                            <li>
                                                                <a class="blue" href="'. SF_PLUGIN_URL . '/json.php?action=EXPORTXML&survey_id='.$r->survey_id.'" target="_blank">
                                                                <i class="fa fa-file-excel-o"></i> Details</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                  </div>
                                                    <div class="element">
                                                    '.$diff.' <br/>
                                                    <label>Days</label>
                                                    </div>
                                                    <div class="element">
                                                    '.$r->imprints.'<br/>
                                                    <label>Views</label>
                                                    </div>
                                                    <div class="element">
                                                    '.$completions.'<br/>
                                                    <label>Responses</label>
                                                    </div>
                                                </div>
                                             </div>
                                             </div>';


                                             echo '</div>
                                             <div class="mt-comment-details">
                                                 <span>';
                                                 echo "<a class=\"btn btn-xs red\" href=\"javascript:void(0);\" onclick=\"deleteSFFunnel($r->survey_id, '" . SF_PLUGIN_URL . "');\">Deactivate</a>";
                                                 echo '</span>
                                                 <ul class="mt-comment-actions">';
                                                     echo "<li>
                                                          <a class=\"btn btn-xs blue\" href=\"admin.php?page=survey_funnel_edit&survey_id=$r->survey_id\">Edit</a>
                                                     </li>
                                                     <li>
                                                         <a class=\"btn btn-xs blue\" href=\"javascript:void(0);\" onclick=\"copySFFunnel($r->survey_id, '" . SF_PLUGIN_URL . "');\">Clone</a>
                                                     </li>
                                                     <li>
                                                         <a class=\"btn btn-xs blue\" href=\"admin.php?page=survey_funnel_results&survey_id=$r->survey_id\">View Stats</a>
                                                     </li>";
                                                 echo '</ul>
                                             </div>
                                         </div>

                                     </div>';

                                  }

                                  if($count==0){

                                    echo '<div class="mt-comment">

                                        <div class="mt-comment-body">
                                            <div class="mt-comment-info">
                                                <span class="mt-comment-author">
                                                No Funnel Added.  <a href="admin.php?page=survey_funnel_add"><b>Click Here</b></a>  to add New Funnel</span>
                                            </div>
                                        </div>
                                    </div>';

                                  }
                                  ?>

                           </div>
                              <!-- END: Comments -->

                       </div>
                       <div class="tab-pane" id="portlet_comments_2">
                           <!-- BEGIN: Comments -->
                           <div class="mt-comments">

                                <?php
                                $tr = $wpdb->get_results("SELECT
                                               {$wpdb->prefix}sf_surveys.survey_id,
                                               {$wpdb->prefix}sf_surveys.survey_name,
                                               {$wpdb->prefix}sf_surveys.use_shortcode,
                                               {$wpdb->prefix}sf_surveys.survey_key,
                                               DATE_FORMAT({$wpdb->prefix}sf_surveys.date_created,'%b %d, %Y %h:%i %p') as date_created,
                                               TIMESTAMPDIFF(MONTH,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_month,
                                               TIMESTAMPDIFF(DAY,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_day,
                                               TIMESTAMPDIFF(HOUR,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_hour,
                                               TIMESTAMPDIFF(MINUTE,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_min,
                                               TIMESTAMPDIFF(SECOND,{$wpdb->prefix}sf_surveys.date_modified,UTC_TIMESTAMP()) as date_modified_sec,
                                               {$wpdb->prefix}sf_survey_stats.imprints,
                                               {$wpdb->prefix}sf_survey_stats.completions FROM {$wpdb->prefix}sf_surveys
                                               LEFT JOIN
                                               {$wpdb->prefix}sf_survey_stats
                                               ON
                                               ({$wpdb->prefix}sf_surveys.survey_id = {$wpdb->prefix}sf_survey_stats.survey_id AND {$wpdb->prefix}sf_survey_stats.active_status_id = 1)
                                               WHERE {$wpdb->prefix}sf_surveys.active_status_id = 0 ORDER BY {$wpdb->prefix}sf_surveys.date_created ASC");

                                 ?>

                                 <?php
                                 $count=0;
                                 foreach ($tr as $r) {
                                   $count++;
                                  if ($r->completions == '') { $r->completions = 0; }
                                  if ($r->imprints == '') { $r->imprints = 0; }
                                  $displaysc= "Not set";

                                  $max_value = $r->imprints;

                                  $modified='';
                                  if ($r->date_modified_hour<0)$modified='Unmodified';
                                  else if ($r->date_modified_month) $modified=$r->date_modified_month.' months ago';
                                  else if ($r->date_modified_day) $modified=$r->date_modified_day.' days ago';
                                  else if ($r->date_modified_hour) $modified=$r->date_modified_hour.' hours ago';
                                  else if ($r->date_modified_min) $modified=$r->date_modified_min.' minutes ago';
                                  else $modified=$r->date_modified_sec.' seconds ago';

                                  if($r->use_shortcode) {
                                    $displaysc = "[survey_funnel key = '$r->survey_key']";
                                  }
                                  $select1 = "SELECT user_id FROM {$wpdb->prefix}sf_survey_results , {$wpdb->prefix}sf_survey_questions
                                          WHERE {$wpdb->prefix}sf_survey_results.survey_id = '$r->survey_id'
                                          AND {$wpdb->prefix}sf_survey_questions.survey_question_id =  {$wpdb->prefix}sf_survey_results.survey_question_id
                                          AND {$wpdb->prefix}sf_survey_questions.active_status_id = '1'";

                                  $user_ids = $wpdb->get_results($select1);
                                  unset($users);
                                  if($user_ids){
                                    foreach( $user_ids as $user_id )
                                      $users[] = $user_id->user_id;

                                    $completions = count((array_count_values($users)));

                                  }else{
                                    $completions= 0;
                                  }

                                  $date1 = new DateTime($r->date_created);
                                   $date2 = new DateTime(date('Y-m-d H:i:s'));

                                   $diff = $date2->diff($date1)->format("%a");

                                    echo '
                                    <div class="mt-comment ">

                                        <div class="mt-comment-body">

                                            <div class="mt-comment-text">';

                                            echo '<div class="row">
                                            <div class="col-md-6">
                                            <strong>'.$r->survey_name.'</strong><br/>';
                                            if($r->use_shortcode) {
                                              echo "[survey_funnel key=$r->survey_key]";
                                            }else {
                                              echo "-";
                                            }
                                            echo '</div>
                                            <div class="col-md-6">
                                               <div class="survey_element">
                                                  <div class="element">
                                                  <div class="btn-group">
                                                       <a class="btn btn-circle blue" href="javascript:;" data-toggle="dropdown">
                                                           Export
                                                           <i class="fa fa-angle-down"></i>
                                                       </a>
                                                       <ul class="dropdown-menu">
                                                           <li>
                                                                <a class="blue" href="' . SF_PLUGIN_URL . '/json.php?action=EXPORT_TO_XLS&survey_id='.$r->survey_id.'" target="_blank">
                                                                <i class="fa fa-file-excel-o"></i>Summary</a>
                                                           </li>
                                                           <li>
                                                               <a class="blue" href="'. SF_PLUGIN_URL . '/json.php?action=EXPORTXML&survey_id='.$r->survey_id.'" target="_blank">
                                                               <i class="fa fa-file-excel-o"></i> Details</a>
                                                           </li>
                                                       </ul>
                                                   </div>
                                                  </div>
                                                   <div class="element">
                                                   '.$diff.' <br/>
                                                   <label>Days</label>
                                                   </div>
                                                   <div class="element">
                                                   '.$r->imprints.'<br/>
                                                   <label>Views</label>
                                                   </div>
                                                   <div class="element">
                                                   '.$completions.'<br/>
                                                   <label>Responses</label>
                                                   </div>
                                               </div>
                                            </div>
                                            </div>';


                                            echo '</div>
                                            <div class="mt-comment-details">
                                                <span>';
                                                echo "<a class=\"btn btn-xs green\" href=\"javascript:void(0);\" onclick=\"activateSFFunnel($r->survey_id, '" . SF_PLUGIN_URL . "');\">Activate</a>";
                                                echo '</span>
                                                <ul class="mt-comment-actions">';
                                                    echo "<li>
                                                         <a class=\"btn btn-xs blue\" href=\"admin.php?page=survey_funnel_edit&survey_id=$r->survey_id\">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a class=\"btn btn-xs blue\" href=\"javascript:void(0);\" onclick=\"copySFFunnel($r->survey_id, '" . SF_PLUGIN_URL . "');\">Clone</a>
                                                    </li>
                                                    <li>
                                                        <a class=\"btn btn-xs blue\" href=\"admin.php?page=survey_funnel_results&survey_id=$r->survey_id\">View Stats</a>
                                                    </li>";
                                                echo '</ul>
                                            </div>
                                        </div>

                                    </div>';

                                 }

                                 if($count==0){

                                   echo '<div class="mt-comment">

                                       <div class="mt-comment-body">
                                           <div class="mt-comment-info">
                                               <span class="mt-comment-author">
                                               No Funnel Added.  <a href="admin.php?page=survey_funnel_add"><b>Click Here</b></a>  to add New Funnel</span>
                                           </div>
                                       </div>
                                   </div>';

                                 }
                                 ?>

                           <!-- END: Comments -->
                       </div>
                   </div>
               </div>
           </div>

         </div>
       </div>

  </div>
  <!-- ROW CONTENT -->
 </div>
 <!-- END CONTAINER -->
