<div class="panel panel-primary" style="width: 99%;margin-top: 42px;">
	<div class="panel-heading">
	   <h3 class="panel-title"><?php _e('MailChimp Settings'); ?></h3>
	      </div>
	      <div class="panel-body">
		<form action='' method='POST'>
			<table>
	
  <tr>
    <th width="20%"><?php _e('MailChimp API Key'); ?></th>
    <td width="30%"><input id='sf_mailchimp_api_key' name='sf_mailchimp_api_key' type='text' value="<?php echo $sf_mailchimp_api_key?>" maxlength='255' required/> </td>
  </tr>
<tr style="position: relative; top: 4px;">
		<th width="20%"><?php _e('Enable mailchimp subscription checkbox  :'); ?></th>
		<td>
		<select name='sf_mailchimp_enable_subscription'>
		<option value='1' <?php if( $sf_mailchimp_enable_subscription ) echo 'selected'?>><?php _e('Yes')?></option>
		<option value='0' <?php if( !$sf_mailchimp_enable_subscription ) echo 'selected'?>><?php _e('No')?></option>
		</select></td>
	</tr>		
</table>
<input class='button button-primary' id='savemailchimpkey' name='savemailchimpkey' type='submit' value='Submit'/> 
</form>
</div>
</div>