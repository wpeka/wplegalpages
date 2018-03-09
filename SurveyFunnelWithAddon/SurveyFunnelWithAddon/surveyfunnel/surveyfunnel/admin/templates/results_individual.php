<?php
// print_r($indv_results);
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
															 <td> <?php echo strip_tags(html_entity_decode($qa['question'])); ?> </td>
															 <td> <?php
                               $multi_ans = '';
                               echo $qa['answer']." >> ".strpos($qa['answer'], '|||');
                         			if (strpos($qa['answer'], '|||') !== false) {
                         					 $multiple_answers = explode("|||", $qa['answer']);
                         					 for($i=0; $i<count($multiple_answers)-1; $i++)
                         					 {
                         					 $j = $i + 1;
                         					 $multi_ans .= $j.". ".$multiple_answers[$i]."\n";
                         					 }
                         			 }
                         			 else
                         				 $multi_ans .= $qa->answer;

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
