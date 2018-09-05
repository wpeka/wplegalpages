<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$lpObj = new WP_Legal_Pages();
$baseurl=esc_url(get_bloginfo('url'));
$page = isset($_REQUEST['page'])?sanitize_text_field($_REQUEST['page']):'';
$lptype = isset($_REQUEST['lp-type'])?sanitize_text_field($_REQUEST['lp-type']):'';
$template = isset($_REQUEST['lp-template'])?sanitize_text_field($_REQUEST['lp-template']):'';
$privacy= file_get_contents(WP_PLUGIN_URL.'/wplegalpages-lite/templates/privacy.html');

?>
<div style="width:1000px;float:left;">
	<h1>WP Legal Pages</h1>
	<div style="line-height: 2.4em;">
	<a href="https://club.wpeka.com/product/wplegalpages/?utm_source=legalpages%20lite%20banner&utm_campaign=legal%20pages%20lite%20banner&utm_medium=banner" target="_blank">
	<img alt="Upgrade to Pro" src="<?php echo WP_PLUGIN_URL.'/wplegalpages-lite/admin/images/upgrade-to-pro.jpg'; ?>">
	</a>
	</div>
</div>
	<div style="clear:both;"></div>
<div class="wrap">
<?php
if(!empty($_POST) && $_POST['lp-submit']=='Publish') :
$title = $_POST['lp-title'];
$content = $_POST['lp-content'];
$post_args = array(
					'post_title' => apply_filters( 'the_title', $title ),
					'post_content' => $content,
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_author' => 1
				);
$pid = wp_insert_post( $post_args );

update_post_meta($pid,'is_legal','yes');
$url = get_permalink($pid);
 ?>
	<div id="message">
    	<p><span class="label label-success myAlert">Page Successfully Created. You can view your page as a normal Page in Pages Menu. </span></p>
        <p><a href="<?php echo get_admin_url(); ?>/post.php?post=<?php echo $pid;?>&action=edit">Edit</a> | <a href="<?php echo esc_url($url); ?>">View</a></p>
    </div>
<?php
	  endif;

	  $general = get_option('wpgattack_general');
	  $checked = 'checked="checked"'; $selected = 'selected="selected"';
?>

<?php
    global $wpdb;
	$postTbl = $wpdb->prefix . "posts";
	$postmetaTbl = $wpdb->prefix . "postmeta";
	$countofPages = $wpdb->get_results("SELECT count(meta_id) as cntPages FROM $postTbl, $postmetaTbl
			WHERE $postTbl.ID = $postmetaTbl.post_id and $postTbl.post_status='publish'
			AND $postmetaTbl.meta_key =  'is_legal'");
	if($countofPages[0]->cntPages<15){
?>

<div class="postbox ">
	<h3 class="hndle myLabel-head"  style="cursor:pointer; padding:7px 10px; font-size:20px;"> Create Page :</h3>
    <div id="lp_generalid">

    <p>&nbsp;&nbsp;</p>
        <form name="terms" method="post" enctype="multipart/form-data">
	<?php if(!empty($template)){

       		$row = $wpdb->get_row($wpdb->prepare('SELECT * from '.$lpObj->tablename.' where id=%d',$template));
	   }?>
        	<p><input type="text" class="form-control myText" name="lp-title" id="lp-title" value="<?php echo isset($row->title)?$row->title:'Privacy Policy';?>" /></p>
            <p>
            <div id="poststuff">
                <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" >
                	<?php
					$content = isset($row->content)?$row->content:$privacy;
					$lp_find = array("[Domain]","[Business Name]","[Phone]","[Street]","[City, State, Zip code]","[Country]","[Email]","[Address]","[Niche]");
					$lp_general = get_option('lp_general');
					$content = str_replace($lp_find, $lp_general, stripslashes($content));
					$editor_id = 'lp-content';
					$args = array();
					wp_editor(stripslashes(html_entity_decode($content)),$id = 'content',$args);
		?>
                </div>
                    <script type="text/javascript">

                    function sp_content_save(){
                        var obj = document.getElementById('lp-content');

                        var content = document.getElementById('content');
                        console.log(content);
                        tinyMCE.triggerSave(0,1);
                        obj.value = content.value;
                    }


                    </script>
                    <textarea id="lp-content" name="lp-content" value="5" style="display:none" rows="10"></textarea>
            </div></p>
            <p>
            <input type="submit"  class="btn btn-primary mybtn" onclick="sp_content_save();" name="lp-submit" value="Publish" />
            </p>

        </form>
    </div>
<div class="lp_generalid_right_wraper">
  <div id="lp_generalid_right" class="postbox ">
    	<h3 class="hndle"  style="cursor:pointer; padding:0px 10px 12px 10px; font-size:20px;"> Choose Template </h3><br/>
        <ul>
        <?php

	$result = $wpdb->get_results("select * from $lpObj->tablename");
		foreach($result as $ras){
			?>
            <li><span id="legalpages<?php echo $ras->id;?>"><a class="myLink" href="<?php echo esc_url($baseurl);?>/wp-admin/admin.php?page=<?php echo $page;?>&lp-type=<?php echo $lptype;?>&lp-template=<?php echo $ras->id;?>"><?php echo $ras->title;?> &raquo;</a></span></li>
            <?php }?>

        </ul>
    </div>

		<div id="lp_generalid_right">
			<a href="https://club.wpeka.com/checkout/?add-to-cart=5942" style="text-decoration:none;padding-left:20px;" target="_blank">
			Upgrade to Pro for All templates
			</a>
		</div>

		<div id="lp_generalid_right" class="postbox ">
	    	<h3 class="hndle"  style="padding:0px 10px 12px 10px; font-size:20px;"> WP LegalPages Pro Templates </h3><br/>
	        <ul>
						<li>Terms of use <strong>(forced agreement - don't allow your users to proceed without agreeing to your terms)</strong></li>
 	<li>Linking policy template</li>
 	<li>External links policy template</li>
 	<li>Terms and conditions template</li>
 	<li>Refund policy template</li>
 	<li>Affiliate disclosure template</li>
 	<li>Privacy Policy template</li>
 	<li>Affiliate agreement template</li>
 	<li>FB privacy policy template</li>
 	<li>Earnings Disclaimer template</li>
 	<li>Antispam template</li>
 	<li>Double dart cookie template</li>
 	<li>Disclaimer template</li>
 	<li>FTC statement template</li>
 	<li>Medical disclaimer template</li>
 	<li>Testimonials disclosure template</li>
 	<li>Amazon affiliate template</li>
 	<li>DMCA policy</li>
 	<li>California Privacy Rights</li>
 	<li>Blog Comment Policy</li>
 	<li>Children's Online Privacy Protection Act</li>
 	<li>Digital Products Refund Policy</li>
 	<li>Newsletter Subscription and Disclaimer template</li>
 	<li>Return Refund Policy template</li>
 	<li>End User License Agreement template</li>
</ul>
	    </div>
</div>
<?php } else { ?>
		<div id="message" class="updated">
	    	<p>You are exceeding the limit of creating 15 legal pages.</p>

	    </div>
<?php }?>
