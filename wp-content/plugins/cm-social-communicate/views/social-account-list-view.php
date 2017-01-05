<h1>賬號管理</h1>

<?php if( !empty( $_GET['action'] ) ){ ?>
<div id="setting-error-settings_updated" class="<?php echo $this->data['error'] ? 'error' : 'updated' ?> settings-error notice is-dismissible" style="margin-left: 0;"> 
	<p><strong><?php echo $this->data['msg'] ?></strong></p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
</div>
<?php } ?>

<div class="account_list_content">
<h3>賬號列表</h3>
<?php $this->data['accounts_list']->display() ?>
</div>

<hr />

<div>
<h3>賬號授權:</h3>
<table class="form-table">
<tbody>
	<!-- <tr>
		<th scope="row"><label for="account_login">賬號</label></th>
		<td><input name="account_login" type="text" id="account_login" class="regular-text"></td>
	</tr> -->
	<tr>
		<th scope="row"><label>賬號類型</label></th>
		<td>
		<select name="account_type" id="account_type">
			<option value="1" data-url="<?php echo htmlspecialchars( $this->data['fb_auth_url'] ); ?>">facebook</option>
			<option value="2" data-url="<?php echo htmlspecialchars( $this->data['tw_auth_url'] ); ?>">twitter</option>
			<option value="3" data-url="<?php echo htmlspecialchars( $this->data['gg_auth_url'] ); ?>">google+</option>
		</select>	
		</td>
	</tr>
</tbody>
</table>
<p class="submit"><a class="button button-primary" href="<?php echo htmlspecialchars( $this->data['fb_auth_url'] ); ?>" id="auth_submit">授權</a></p>
</div>
