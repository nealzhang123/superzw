<?php if( !empty( $this->data['msg'] ) ){ ?>
<div id="setting-error-settings_updated" class="<?php echo $this->data['error'] ? 'error' : 'updated' ?> settings-error notice is-dismissible" style="margin-left: 0;"> 
	<p><strong><?php echo $this->data['msg'] ?></strong></p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
</div>
<br />
<?php } ?>

<h1>內容發佈</h1>

<form id="social_form" method="post" enctype="multipart/form-data">
<table class="form-table">
<tbody>
	<!-- <tr>
		<th scope="row"><label for="account_login">賬號</label></th>
		<td><input name="account_login" type="text" id="account_login" class="regular-text"></td>
	</tr> -->
	<!-- <tr>
		<th scope="row"><label for="title">標題</label></th>
		<td>
			<input type="text" name="title" size="30" id="title" spellcheck="true" autocomplete="off">
		</td>
	</tr> -->
	<tr>
		<th scope="row"><label for="content">内容</label></th>
		<td>
			<textarea name="content" id="content" style="width: 100%;height: 150px;max-width: 500px;"></textarea>
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="video">分享視頻鏈接</label></th>
		<td>
			<input type="text" name="video" id="video" style="width: 100%;max-width: 500px;">
			<p><a style="color: red;">*上傳圖片會使該鏈接失效</a></p>
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="social_upload">图片上传</label></th>
		<td>
			<input name="social_upload" id="social_upload" style="display:none;" type="file" />
		    <img id="social_upload_img" src="<?php echo CM_SOCIAL_IMG_URL . 'upload.jpg'; ?>" style="width: 200px;height: 200px;cursor: pointer;" />
		    <p>
		    	<a style="color: red;">
		    	*上傳的圖片格式需為 gif, jpeg, jpg, bmp, png. 大小不得超過10M
		    	
		    	</a>
		    </p>
		</td>
	</tr>

	<tr>
		<th scope="row"><label>選擇社交平台</label></th>
		<td id="front-static-pages">
			<fieldset>
			<?php if( !empty( $this->data['fb_accounts'] ) ) { ?>
			<span>
			<ul>
			<?php foreach ( $this->data['fb_accounts'] as $fb_account ) { ?>
				<li>
					<input type="checkbox" name="facebook[]" id="<?php echo $fb_account['id'] ?>" value="<?php echo $fb_account['id'] ?>" /><label for="<?php echo $fb_account['id'] ?>"><img src="<?php echo CM_SOCIAL_IMG_URL . 'facebook.gif'; ?>" style="width: 15px;height: 15px"> <?php echo $fb_account['account_name'] ?></label>
				</li>
			<?php } ?>
			</ul>
			<?php } ?>
			</span>
			</fieldset>

			<fieldset>
			<?php if( !empty( $this->data['tw_accounts'] ) ) { ?>
			<span>
			<ul>
			<?php foreach ( $this->data['tw_accounts'] as $tw_account ) { ?>
				<li>
					<input type="checkbox" name="twitter[]" id="<?php echo $tw_account['id'] ?>" value="<?php echo $tw_account['id'] ?>" /><label for="<?php echo $tw_account['id'] ?>"><img src="<?php echo CM_SOCIAL_IMG_URL . 'twitter.png'; ?>" style="width: 15px;height: 15px"> <?php echo $tw_account['account_name'] ?></label>
				</li>
			<?php } ?>
			</ul>
			<?php } ?>
			</span>
			</fieldset>
		</td>
		
	</tr>


</tbody>
</table>

<?php wp_nonce_field('social_public','social_public'); ?>
<input type="submit" class="button button-primary" value="提交" />
</form>
