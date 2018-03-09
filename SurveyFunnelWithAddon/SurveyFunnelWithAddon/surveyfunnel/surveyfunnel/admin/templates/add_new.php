<?php
class AddNewSurvey {
	var $survey_key;
	var $survey_name;
	var $survey_id;


	function __construct($addSurveyMode){
		switch ($addSurveyMode){
			case 'new': $this->showAddSurveyForm();break;
			case 'saveSurvey': $this->saveSurvey();break;
			case 'updateSurvey':$this->updateSurvey();break;
		}
	}

	function showAddSurveyForm(){
		global $wpdb;
		$title="Create Survey";
		$surveyTitle="";
		$survey_id=0;
		$addSurveyMode="saveSurvey";
		$createBtnText="Create & Design";
		$cancelBtnHref="admin.php?page=survey_funnel_welcome";
		if(isset($_REQUEST['survey_id'])){
			$survey_id=$_REQUEST['survey_id'];
			$title="Change Survey Details";
			$r = $wpdb->get_row("SELECT survey_name FROM {$wpdb->prefix}sf_surveys WHERE survey_id = ".$_REQUEST['survey_id']);
			$surveyTitle=$r->survey_name;
			$addSurveyMode="updateSurvey";
			$createBtnText="Change";
			$cancelBtnHref="admin.php?page=survey_funnel_edit&survey_id=".$survey_id;
		}
		?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#sfFormSunmitBtn').click(function(){
				if(jQuery('#sfEnterTitle').val().trim()==''){
					jQuery('#sfEnterTitle').tooltip('show');
					jQuery('#sfEnterTitle').val('');
				}
				else {
					jQuery('#sfEnterTitle').val(jQuery('#sfEnterTitle').val().trim());
					jQuery('#sfFormTitleSubmit').submit();
				}
			});
			jQuery('#sfFormCancelBtn').click(function(){
				window.location.href = '<?php echo $cancelBtnHref;?>'
			});
		});
		</script>
			<link type="text/css" rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/surveyfunnel/includes/assets/css/datepicker.css'?>" />
			<style type="text/css">#ui-datepicker-div { display: none}</style>

			<div class="container-fluid form-start">
	  <div class="row">

	    <div class="col-md-8">
	      <div class="portlet box blue-hoki">
	        <div class="portlet-title">
	          <div class="caption">
	            <i class="fa fa-plus"></i> <?php echo $title;?>
	          </div>
	        </div>
	        <div class="portlet-body form">
	          <!-- BEGIN FORM-->
	          <form action="" class="form-horizontal" id="sfFormTitleSubmit" method="post" role="form">
	            <div class="form-body">
	              <div class="form-group">
	                <label class="col-md-3 control-label">Survey Title</label>
	                <div class="col-md-4">
	                  <input type="text" id="sfEnterTitle" name="sfTitle" class="form-control input-circle" placeholder="Enter Survey Title"  value="<?php echo $surveyTitle;?>">
	                  <input type="hidden" name="addSurveyMode" value="<?php echo $addSurveyMode;?>"/>
	                  <input type="hidden" name="survey_id" value="<?php echo $survey_id;?>"/>
	                </div>
	              </div>
	            </div>
	            <div class="form-actions">
	              <div class="row">
	                <div class="col-md-offset-3 col-md-9">
	                  <button type="submit" id="sfFormSunmitBtn" class="btn btn-circle green"><?php echo $createBtnText;?></button>
	                  <button type="button"  id="sfFormCancelBtn" class="btn btn-circle grey-salsa btn-outline">Cancel</button>
	                </div>
	              </div>
	            </div>
	          </form>
	          <!-- END FORM-->
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
		<?php
	}

	function saveSurvey(){
		global $wpdb;
		$this->survey_key=uniqid("survey_funnel_");
		$this->survey_key = substr($this->survey_key, 0 , 25);
		$this->survey_name=$_REQUEST['sfTitle'];
		$date = date("Y-m-d H:i:s");
		$wpdb->insert($wpdb->prefix . 'sf_surveys',
					array(
 						'survey_name' => $this->survey_name,
						'survey_key' => $this->survey_key,
						'active_status_id' => 1,
						'width' => 320,
						'height' => 250,
						'date_created' => $date,
						'survey_theme' => '1'));

		$this->survey_id = $wpdb->insert_id;
		header('location:admin.php?page=survey_funnel_edit&survey_id='.$this->survey_id);
	}

	function updateSurvey(){
		global $wpdb;
		$this->survey_name=$_REQUEST['sfTitle'];
		$survey_id=$_REQUEST['survey_id'];
		$date = date("Y-m-d H:i:s");
		$wpdb->update($wpdb->prefix . 'sf_surveys',
				array(
						'survey_name' => $this->survey_name,
						'date_modified' => $date),
				array('survey_id' => $survey_id));

		header('location:admin.php?page=survey_funnel_edit&survey_id='.$survey_id);
	}
}


$addSurveyMode='new';
if(isset($_REQUEST['addSurveyMode'])) $addSurveyMode=$_REQUEST['addSurveyMode'];
new AddNewSurvey($addSurveyMode);
?>
