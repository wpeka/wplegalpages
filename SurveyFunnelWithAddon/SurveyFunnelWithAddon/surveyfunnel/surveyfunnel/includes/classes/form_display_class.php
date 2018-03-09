<?php
class FormDisplay {

	/**
	 * Form Display Constructor Function
	 *
	 * @return FormDisplay
	 */
	function __construct() {

	}


	/**
	 * Enter description here...
	 *
	 */
	function getPost() {
		$name = "post_ids[]";
		$selected = "";
		$all_pages = false;

		if (func_num_args() > 0) {
			$name = func_get_arg(0);
			if (func_num_args() > 1) { $selected = func_get_arg(1); }
			if (func_num_args() > 2) { $all_pages = func_get_arg(2); }
		}

		$id = str_ireplace(array("[", "]"), "", $name);

		global $wpdb;

		// AND post_type != 'page'
		// AND post_date < NOW()
		$tr = $wpdb->get_results("SELECT ID, post_title, post_type FROM {$wpdb->prefix}posts WHERE post_status = 'publish' AND (LOWER(post_type) = 'page' OR LOWER(post_type) = 'post') ORDER BY post_type DESC, post_title");

		echo "<select name=\"$name\" id=\"$id\" onfocus=\"cleanFormError(this);\" multiple style=\"height: 150px; width: 250px;\""; if ($all_pages) { echo " disabled"; } echo ">";

		$group = '';

		if (count($selected) == 0) {
			$selected[] = -1;
		}

		foreach ($tr as $r) {
			if ($r->post_type != $group) {
				if ($group != '') { echo "</optgroup>"; }
				echo "<optgroup label=\"" . ucwords($r->post_type) . "s\">";
				$group = $r->post_type;
			}

			echo "<option value=\"$r->ID\""; if (in_array($r->ID, $selected)) { echo " selected"; } echo ">$r->post_title</option>";
		}

		/* option for categories */
		echo "<optgroup label='Categories'>";

		$categories =  get_categories();
		foreach ($categories as $category) {
			if($category->count != 0)
			{
				(isset($_GET['survey_id']))?$survey_id = $_GET['survey_id']:$survey_id = 0;
				$sql = "select category_ids from {$wpdb->prefix}sf_surveys where survey_id=".$survey_id;
				$sf_category_ids_result = $wpdb->get_row($sql);
				$sf_category_ids="";
				(isset($sf_category_ids_result->category_ids))?$sf_category_ids = $sf_category_ids_result->category_ids:$sf_category_ids = "";
				$resexp = explode(",",$sf_category_ids);

				echo "<option value='catid_$category->term_id'"; if (in_array($category->term_id,$resexp)) {
				echo " selected";
				} echo">".$category->cat_name."</option>";
			}
		}


		echo "</optgroup></select>";
	}


} // FormDisplay Class
?>
