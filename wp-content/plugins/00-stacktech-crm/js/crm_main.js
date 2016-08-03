(function($){
	$(function(){
		$('#setting_model').change(function(){
			var box = $(this).attr('box');
			$.post(
				ajaxurl, 
				{
					'model': $(this).val(),
					'box': box,
					'action': 'stacktech-crm-load-options-ajax'
				}, 
				function(response){
					if( box == 'dropbox' )
						$('#setting_dropbox').html(response);
					if( box == 'sortbox' )
						$('#setting_sortbox').html(response);
				}
			);
		});

		$('.option_save_button').on("click",function(){
			var option_id = $(this).attr('option-id');
			var ele = '#modal'+option_id;
			var box = $('#box').val();
			$(ele).modal('hide');
			$.post(
				ajaxurl, 
				{
					'data': $('#form_option_save'+option_id).serializeArray(),
					'action': 'stacktech-crm-option-save-ajax',
					'box': box
				}, 
				function(response){
					var options = $.parseJSON(response);

					switch(box)
					{
						case 'dropbox':
							var html = '';
							$.each(options, function(name, val) {
								html += '<div class="option_item">'+val+'</div>';
							});
							$('#option_list'+option_id).html(html);

							break;

						case 'sortbox':
							$('#option_sort'+option_id).html('#'+options.sort+'&nbsp;');
							if( parseInt(options.is_required) > 0 ){
								$('#option_is_required'+option_id).html('&nbsp;<font color="red">*</font>');
							}else{
								$('#option_is_required'+option_id).html('&nbsp;');
							}
							if( parseInt(options.is_hidden) > 0 ){
								$('#option_is_hidden'+option_id).html('&nbsp;(隐藏)');
							}else{
								$('#option_is_hidden'+option_id).html('&nbsp;');
							}
							$('#option_title'+option_id).html(options.title);
							$('#myModalLabel'+option_id).html('编辑页面布局 - '+options.title);
							
							break;

						default:
							break;
					}

					var notice = new PNotify({
					    title: '保存成功',
					    delay: 3000,
					    text: $('#myModalLabel'+option_id).html()+' 已修改',
					    type: 'success',
					    buttons: {
					        closer: false,
					        sticker: false
					    }
					});

					notice.get().click(function() {
					    notice.remove();
					});
				}
			);//end of ajax
		});//end of $('.option_save_button').click(function()

		$('#add_member_form').click(function(e){
			e.preventDefault();
			var obj = $(this);

			$.post(
				ajaxurl, 
				{
					'action': 'stacktech-crm-customer-form-options-ajax'
				}, 
				function(response){
					obj.before(response);
				}
			);
		});

		$('.delete_member').on('click',function(e){
			e.preventDefault();
			var mem_id = $(this).attr('member-id');
			$(this).parent().remove();

			if( mem_id > 0 ){
				var obj = $(this);
				swal({   
					title: "确认删除?",   
					//text: "是否彻底删除",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "删除",   
					cancelButtonText: "取消",   
					closeOnConfirm: false,   
					//closeOnCancel: false 
				}, function(isConfirm){   
					if (isConfirm) {     
						swal("已删除!", "", "success");
						$.post(
							ajaxurl, 
							{
								'mem_id' : mem_id,
								'action': 'stacktech-crm-customer-remove-member-ajax'
							}, 
							function(response){

							}
						);
					}
				});
			}
		});

		$('.list_item_delete').click(function(){
			var obj = $(this);
			swal({   
				title: "确认删除?",   
				//text: "是否彻底删除",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "删除",   
				cancelButtonText: "取消",   
				closeOnConfirm: false,   
				//closeOnCancel: false 
			}, function(isConfirm){   
				if (isConfirm) {     
					swal("已删除!", "", "success");
					$.post(
						ajaxurl, 
						{
							'model' : $('#model').val(),
							'pid' : obj.attr('data-pid'),
							'action': 'stacktech-crm-remove-model-item-ajax'
						}, 
						function(response){
							obj.parent().parent().remove();
						}
					);
				}
			});
		});
		
		$('#record_custom_change').on('change',function(){
			var obj = $(this);

			if( obj.val() > 0 ){
				$.post(
					ajaxurl, 
					{
						'customer_id' : obj.val(),
						'action': 'stacktech-crm-get-members-list-ajax'
					}, 
					function(response){
						$('#members_list').html(response);
					}
				);
			}else{
				$('#members_list').html('');
			}
		});

		/*$.datepicker._gotoToday = function(id) {
		    var target = $(id);
		    var inst = this._getInst(target[0]);
		    if (this._get(inst, 'gotoCurrent') && inst.currentDay) {
	            inst.selectedDay = inst.currentDay;
	            inst.drawMonth = inst.selectedMonth = inst.currentMonth;
	            inst.drawYear = inst.selectedYear = inst.currentYear;
		    }
		    else {
	            var date = new Date();
	            inst.selectedDay = date.getDate();
	            inst.drawMonth = inst.selectedMonth = date.getMonth();
	            inst.drawYear = inst.selectedYear = date.getFullYear();
	            // the below two lines are new
	            this._setDateDatepicker(target, date);
	            this._selectDate(id, this._getDateDatepicker(target));
		    }
		    this._notifyChange(inst);
		    this._adjustDate(target);
		}*/

		$('.timepicker').datetimepicker({
			showOn: "button",
			//defaultTime: new Date(),
      		buttonText: "<i class='fa fa-calendar calendar-icon'></i>",
			numberOfMonths:1,
            showButtonPanel:true,//是否显示按钮面板  
            dateFormat: 'yy-mm-dd',//日期格式  
            timeFormat: "HH:mm:00",
            clearText:"清除",//清除日期的按钮名称  
            closeText:"关闭",//关闭选择框的按钮名称  
            yearSuffix: '年', //年的后缀  
            showMonthAfterYear:true,//是否把月放在年的后面  
            changeMonth: true,
            changeYear: true,
            monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
            monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],  
            dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],  
            dayNamesMin: ['日','一','二','三','四','五','六'],
            currentText:"当前日期",
            timeText : '时间',
            hourText : '小时',
            minuteText : '分钟',
            showHour: true,
		});

		$('.datepicker').datepicker({
			showOn: "button",
			//defaultTime: new Date(),
      		buttonText: "<i class='fa fa-calendar calendar-icon'></i>",
      		numberOfMonths:1,
			showButtonPanel:true,
			dateFormat: 'yy-mm-dd',//日期格式  
			clearText:"清除",//清除日期的按钮名称  
            closeText:"关闭",//关闭选择框的按钮名称
            showMonthAfterYear:true,//是否把月放在年的后面  
            changeMonth: true,
            changeYear: true,
            monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
            monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],  
            dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],  
            dayNamesMin: ['日','一','二','三','四','五','六'],
            currentText:"当前日期",  
		});

		function get_country_address( current_val, type ) {
			if( type.length == 0 ) {
				type = 'all';
			}

			$.post(
				ajaxurl, 
				{
					'current_val' : current_val,
					'type' : type,
					'action': 'stacktech-crm-get-country-address-ajax'
				}, 
				function(response){
					response = JSON.parse(response);
					if( type == 'province' ){
						$('#crm_city').html(response.city_html);
						$('#crm_area').html(response.area_html);
					}else if( type == 'city' ) {
						$('#crm_area').html(response.area_html);
					}
				}
			);
		}

		$('#crm_province').on('change',function(){
			var code = $('#crm_province option:selected').val();
			get_country_address( code, 'province' );
			$('#crm_bill_code').html( '<input type="text" name="bill_code" value="'+code+'" id="crm_bill_code">' );
		});

		$('#crm_city').on('change',function(){
			var code = $('#crm_city option:selected').val();
			get_country_address( code, 'city' );
			$('#crm_bill_code').html( '<input type="text" name="bill_code" value="'+code+'" id="crm_bill_code">' );
		});

		$('#crm_area').on('change',function(){
			var code = $('#crm_area option:selected').val();
			$('#crm_bill_code').html( '<input type="text" name="bill_code" value="'+code+'" id="crm_bill_code">' );
		});

		$('.send_select_mail').on('click',function(){
			selected_action( 'mail' );
		});

		function selected_action( exec ) {
			var selected = '';
			var model = $('#model').val();
			$("[name='bulk-delete[]']").each(function(){
				if( $(this).is(':checked') )
					selected = selected + $(this).val() + ',';
			});

			if( selected == '' )
				return;

			$.post(
				ajaxurl, 
				{
					'exec' : exec,
					'model' : model,
					'selected' : selected,
					'action': 'stacktech-crm-selected-action-ajax'
				}, 
				function(response){
					response = JSON.parse(response);
					switch( exec )
					{
						case 'mail':
							window.location.href = response.url;
							break;

						default:
							break;
					}
				}
			);
		}

		$('.edit_send_view').on('click',function(){
			var viewer_id = $('#send_editor_view').val();
			var return_obj = {};

			if( viewer_id != 0 ){
				$.ajax({
					method: "POST",
					url: ajaxurl,
					data: { 
						viewer_id: viewer_id, 
						action: "stacktech-crm-get-viewer-content-ajax" 
					}
				}).done(function( response ) {
				    return_obj = JSON.parse(response);
					$('#viewer_name').val(return_obj.viewer_name);
					$('#viewer_id').val(return_obj.viewer_id);
					tinymce.editors['viewer_content'].setContent(return_obj.viewer_content);
					
				});
			}
			$('#modal_send_view').modal('show');

		});

		$('.add_send_view').on('click',function(){
			$('#viewer_name').val('');
			$('#viewer_id').val(0);
			tinymce.editors['viewer_content'].setContent('');
			$('#modal_send_view').modal('show');
		});

		$('#send_viewer_save').on('click',function(e){
			e.preventDefault();
			$('#modal_send_view').modal('hide');

			$.post(
				ajaxurl, 
				{
					'viewer_id': $('#viewer_id').val(),
					'viewer_content': tinymce.editors['viewer_content'].getContent(),
					'viewer_name': $('#viewer_name').val(),
					'model' : $('#model').val(),
					'action': 'stacktech-crm-viewer-save-ajax',
				}, 
				function(response){
					//console.log(response);
					response = JSON.parse(response);
					$('#send_editor_view').append("<option value='"+response.viewer_id+"' selected>"+response.viewer_name+"</option>");
					tinymce.editors['send_content'].setContent(response.viewer_content);
				}
			);
		});

		$('#send_editor_view').on('change',function(){
			var viewer_id = $('#send_editor_view').val();
			var return_obj = {};

			if( viewer_id != 0 ){
				$.ajax({
					method: "POST",
					url: ajaxurl,
					data: { 
						viewer_id: viewer_id, 
						action: "stacktech-crm-get-viewer-content-ajax" 
					}
				}).done(function( response ) {
				    return_obj = JSON.parse(response);
					tinymce.editors['send_content'].setContent(return_obj.viewer_content);
				});
			}
		});
	}); 
})(jQuery);