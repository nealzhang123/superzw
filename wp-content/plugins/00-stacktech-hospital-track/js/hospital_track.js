(function($){
	$(function(){
		function hos_notify( title, text, type ){
			var notice = new PNotify({
			    title: title,
			    delay: 1500,
			    text: text,
			    type: type,
			    buttons: {
			        closer: false,
			        sticker: false
			    }
			});

			notice.get().click(function() {
			    notice.remove();
			});
		}

		//仪表盘的不再提醒
		$('.table_ignore').click(function(e){
			e.preventDefault();
			var title = $(this).prev().html();
			var type = $(this).attr('data-type');

			var obj = $(this);
			swal({   
				title: title + "是否不再提醒?",   
				//text: "是否彻底删除",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "确定",   
				cancelButtonText: "取消",   
				closeOnConfirm: false,   
				//closeOnCancel: false 
			}, function(isConfirm){   
				if (isConfirm) {     
					swal("不再提醒!", "", "success");
					obj.parent().parent().hide();
					hos_notice_next_show(obj.parent().parent());

					$.post(
						ajaxurl, 
						{
							'type' : type,
							'tab_id': obj.attr('tab_id'),
							'region': obj.attr('region'),
							'action': 'hospital-track-ignore-ajax'
						}, 
						function(response){
							hos_notify( '操作成功', title+' 不再提醒', 'success' );
						}
					);
				}
			});
		});

		//不再提醒后显示下一个
		function hos_notice_next_show(obj){
			if( obj.next().length == 0 )
				return;

			if( obj.next().hasClass('hos_widget_hid') ){
				obj.next().removeClass('hos_widget_hid');
			}else{
				hos_notice_next_show(obj.next());
			}
		}

		//页面跳转时ajax获取内容
		function reload_list_content( current_page,key, element ) {
			if(!arguments[1]) 
				key = "";
			var group = $("#group").val();
			//alert(group);
			var model = $('#model').val();
			if( $("input[name='model']:checked").length > 0 )
				var model = $("input[name='model']:checked").val();

			if( $.inArray( model, ['m1','m6','y1','y2'] ) != '-1'  ) {
				if( $("input[name='model']:checked").length > 0 ) {
					$.post(
						ajaxurl, 
						{
							'action': 'hospital-pre-birth-edit-list-ajax',
							'hos_action': $('#hos_action').val(),
							'model': $("input[name='model']:checked").val(),
							'tab_no': $('#tab_no_edit').val(),
							'page_per_num': $('#page_per_num').val(),
							'current_page': current_page,
							'edit_track_no1' : $('#edit_track_no1').val()
						}, 
						function(response){
							response = $.parseJSON(response);
							$('#hos_table_nav_top').html( response.hos_table_nav_top );
							$('#hos_table_content').html( response.hos_table_content );
							$('#hos_table_nav_bottom').html( response.hos_table_nav_bottom );
	
							
						}
					);
				}else{
					$.post(
						ajaxurl, 
						{
							'action': 'hospital-track-search-ajax',
							'hos_action': $('#hos_action').val(),
							'model': $('#model').val(),
							'min_mon_date': $('#min_mon_date').val(),
							'max_mon_date': $('#max_mon_date').val(),
							'min_track_date': $('#min_track_date').val(),
							'max_track_date': $('#max_track_date').val(),
							'page_per_num': $('#page_per_num').val(),
							'current_page': current_page,
							'tab_no': $('#tab_no').val()
						}, 
						function(response){
							response = $.parseJSON(response);
							$('#hos_table_nav_top').html( response.hos_table_nav_top );
							$('#hos_table_content').html( response.hos_table_content );
							$('#hos_table_nav_bottom').html( response.hos_table_nav_bottom );
							
						}
					);
				}
			}

			if( $.inArray( model, ['exam'] ) != '-1'  ) {
				if( key.length > 0 )
					var from_key = 1;
				else
					var from_key = 0;
				$.post(
					ajaxurl, 
					{
						'action': 'hospital-exam-search-ajax',
						'hos_action': $('#hos_action').val(),
						'hos_type': $('#hos_type').val(),
						'model': $("model").val(),
						'page_per_num': $('#page_per_num').val(),
						'current_page': current_page,
						'key_word' : $('#ex_search_text').val(),
						'from_key' : from_key,
						'form_data' : $('#four_search_form').serializeArray()
					}, 
					function(response){
						response = $.parseJSON(response);

						$('#hos_exam_stat_top').html(response.hos_table_nav_top);
						$('#hos_exam_stat_content').html(response.hos_table_content);
						$('#hos_exam_stat_bottom').html(response.hos_table_nav_bottom);
						if( !from_key ){
							$('#total_count').html(response.total.total_count+' 人');
							$('#cserum_count').html(response.total.cserum_count+' 人');
							$('#cplasma_count').html(response.total.cplasma_count+' 人');
							$('#brtr_count').html(response.total.brtr_count+' 人');
							$('#age_count1').html(response.total.age_count1+' 人');
							$('#age_count2').html(response.total.age_count2+' 人');
							$('#result_count').html(response.total.total_count);
						}
					}
				);
			}

			if( $.inArray( model, ['y3','y5'] ) != '-1'  ) {
				$.post(
					ajaxurl, 
					{
						'action': 'hospital-track-four-ajax',
						'hos_action': $('#hos_action').val(),
						'model': $('#model').val(),
						'hos_type': $('#hos_type').val(),
						'page_per_num': $('#page_per_num').val(),
						'current_page': current_page,
						'extend_type' : $('.extend_type:checked').val()
					}, 
					function(response){
						response = $.parseJSON(response);
						$('#hos_table_nav_top').html( response.hos_table_nav_top );
						$('#hos_table_content').html( response.hos_table_content );
						$('#hos_table_nav_bottom').html( response.hos_table_nav_bottom );
					}
				);
			}

			if( $.inArray( group, ['pregnant', 'childbirth_status', 'pregnant_middle', 'health_manage']) != '-1' ){

				$.post(
					ajaxurl,
					{
						'action':'hospital-pregnant-ajax',
						'current_page':current_page,
						'page_per_num':$('#page_per_num').val(),
						'group' :group,

					},
					function (response){
						response = $.parseJSON(response);
						$('#hos_table_nav_top').html( response.hos_table_nav_top );
						$('#hos_table_content').html( response.hos_table_content );
						$('#hos_table_nav_bottom').html( response.hos_table_nav_bottom );
						$("#group").val(group);
					}
				);
			}

			if( 'pregnant_b' == group){
		
				$.post(
					ajaxurl,
					{
						'action':'hospital-pregnant-b-ajax',
						'current_page':current_page,
						'page_per_num':$('#page_per_num').val(),
						'pregnant_b_no1'		: $('#pregnant_b_no1').val(),
						'pregnant_b_ult_12wk' 	: $('#pregnant_b_ult_12wk').val(),
						'pregnant_b_ult_16wk' 	: $('#pregnant_b_ult_16wk').val(),
						'pregnant_b_ult_24wk' 	: $('#pregnant_b_ult_24wk').val(),
						'pregnant_b_ult_32wk' 	: $('#pregnant_b_ult_32wk').val(),
						'pregnant_b_ult_37wk' 	: $('#pregnant_b_ult_37wk').val(),
					},
					function(response){
						response = $.parseJSON(response);
						$('#hos_table_nav_top').html( response.hos_table_nav_top );
						$('#the-list').html( response.hos_table_content );
						$('#hos_table_nav_bottom').html( response.hos_table_nav_bottom );
						$("#group").val(group);
					}
				);
			}

		}

		//检查上传文件合法
		function check_file_valid( filename ) {
			var filename2 = filename.split('\\');
			filename = filename2[filename2.length-1];

			if( filename.length < 1 ){
				sweetAlert("提醒", "没有选中任何文件", "error");
				return false;
			}

			var filename_ex = filename.split('.');
			filename_ex = filename_ex[filename_ex.length-1];

			if( $.inArray( filename_ex, ['xls','xlsx','csv'] ) == '-1' ){
				sweetAlert("提醒", "文件格式不正确", "error");
				return false;
			}

			return filename;
		}

		$(document).on('click', '.health_search', function(){
			reload_list_content(1);
		});
		
		$(document).on('keyup  ', '#pregnant_b_no1', function(){
			reload_list_content(1);
		});

		//加载datepicker公共函数
		function load_date_text_input() {
			//所有选择日期
			$('.datepicker').datepicker({
				showOn: "button",
	      		buttonText: "<i class='fa fa-calendar calendar-icon'></i>",
	      		numberOfMonths:1,
				showButtonPanel:true,
				dateFormat: 'yy-mm-dd',//日期格式  
	            closeText:"清除",//关闭选择框的按钮名称
	            showMonthAfterYear:true,//是否把月放在年的后面  
	            changeMonth: true,
	            changeYear: true,
	            monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
	            monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
	            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],  
	            dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],  
	            dayNamesMin: ['日','一','二','三','四','五','六'],
	            currentText:"当前日期",
	            onClose: function (dateText, inst) {
					if ($(window.event.srcElement).hasClass('ui-datepicker-close'))
					{
					   document.getElementById(this.id).value = '';
					}
				}
			});
		}

		//datepicker的选择当前日期
		$.datepicker._gotoToday = function(id) {
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
		}

		load_date_text_input();

		$('.exec_search').click(function(e){
			var start = $('#min_track_date').val();
			var end = $('#max_track_date').val();

			start = new Date(start);
	    	end = new Date(end);
	    	if (end < start) {
	    		sweetAlert("提醒", "结束日期不能小于开始日期！", "error");
	            return;
	        }

	        reload_list_content(1);
		});

		$('.track_result_import').on('click',function(e){
			e.preventDefault();
			$('#track_import').click();
		});

		//产前队列随访结果导入
		$('#track_import').on('change', function(){
			var filename = check_file_valid( $(this).val() );

			if( filename === false )
				return;

			$('#track_import').wrap("<form id='ajaxupload' method='post' enctype='multipart/form-data' style='display:none;'></form>");

            $("#ajaxupload").ajaxSubmit({
                dataType: 'text',
                type: 'post',
        		url: ajaxurl,
        		data: {
	                'action': 'hospital-pre-birth-track-result-import-ajax',
	                'model': $('#model').val(),
	                'hos_action': $('#hos_action').val()

	            },
                success: function (response) {
                    $('#track_import').unwrap();

                    response = $.parseJSON(response);
                    if( response.error.length > 0 ){
                    	hos_notify( '上传失败', response.error, 'error');
                    }else{
                    	hos_notify( '文件 ('+filename+') 上传成功', response.error, 'success');
                    }
           
					reload_list_content( $('#current_page').val() );
                },
            });
        });

		$(".health_manage_result_import").on('click', function(e){
			e.preventDefault();
			$("#health_manage_import").click();
		});

		$("#health_manage_import").on('change', function(){
			var filename = check_file_valid( $(this).val() );
			if(filename === false)
				return;
			$("#health_manage_import").wrap("<form id='ajaxupload' method='post' enctype='multipart/form-data' style='display:none;'></form>");
			$("#ajaxupload").ajaxSubmit({
				dataType:'text',
				type:'post',
				url:ajaxurl,
				data:{
					'action':'hospital-pre-birth-health-manage-import-ajax',
				},
				success: function( response){
					$("#health_manage_import").unwrap();
					response = $.parseJSON(response);
					if( response.error.length > 0){
						hos_notify('上传失败', response.error, 'error');
					}else{
                    	hos_notify( '文件 ('+filename+') 上传成功', response.error, 'success');
                    }
                    reload_list_content( $('#current_page').val() );
				}
			});
		});
		//导入功能,链到一个页面
		$('.exec_export').click(function(){
			var linkto = $(this).attr('data-href');
			var para_arr = ['hos_action','model','min_mon_date','max_mon_date','min_track_date','max_mon_date','tab_no', 'group', 'pregnant_b_ult_12wk', 'pregnant_b_no1' ,'pregnant_b_ult_16wk','pregnant_b_ult_24wk','pregnant_b_ult_32wk','pregnant_b_ult_37wk'];
			
			$.each( para_arr, function(i, n){ 
				if( $('#'+n).length > 0 && $('#'+n).val() ){
					var value = $('#'+n).val();
					linkto = linkto+'&'+n+'='+value;
				}
			});
 			window.open(linkto);
		});

		//首页跳转
		$(document).on('click','#first_page',function(){
			if( !$(this).hasClass('btn-primary') ){
				hos_notify( '', '已经是首页', 'notice');

				return;
			}

			reload_list_content(1);
		});

		//上一页跳转
		$(document).on('click','#prev_page',function(){
			if( !$(this).hasClass('btn-primary') ){
				hos_notify( '', '已经是首页', 'notice');

				return;
			}
			var current_page = parseInt( $('#current_page').val() );
			if( current_page > 1 )
				current_page = current_page - 1;
			reload_list_content( current_page );
		});

		//下一页跳转
		$(document).on('click','#next_page',function(){
			if( !$(this).hasClass('btn-primary') ){
				hos_notify( '', '已经是尾页', 'notice');

				return;
			}
			var current_page = parseInt( $('#current_page').val() );
			var total_page = parseInt( $('#total_page').val() );
			if( current_page<total_page)
				current_page = current_page + 1;

			reload_list_content( current_page );
		});

		//尾页跳转
		$(document).on('click','#last_page',function(){
			if( !$(this).hasClass('btn-primary') ){
				hos_notify( '', '已经是尾页', 'notice');

				return;
			}
			var current_page = $('#total_page').val();
			reload_list_content( current_page );
		});

		$(document).on('click','#search_list',function(){
			var current_page = $(this).prev().find('#current_page').val();
			reload_list_content( current_page );
		});

		$(document).on('click','#page_per_num',function(){
			reload_list_content(1);
		});
		
		$(document).on('change','#tab_no',function(){
			reload_list_content(1);
		});

		

		
		
		//产前队列现场随访选择随访类型,1月..
		$('.hos_pre_birth_edit_label').on('change',function(){
			$('#model').val( $("input[name='model']:checked").val() );

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-pre-birth-edit-list-ajax',
					'hos_action': $('#hos_action').val(),
					'model': $("input[name='model']:checked").val(),
				}, 
				function(response){
					response = $.parseJSON(response);
					$('#table_list').html( response.hos_table_list );
					$('#hos_table_nav_top').html( response.hos_table_nav_top );
					$('#hos_table_content').html( response.hos_table_content );
					$('#hos_table_nav_bottom').html( response.hos_table_nav_bottom );
				}
			);
		});

		//产前队列选择表,改变
		$(document).on('change','#tab_no_edit',function(){
			reload_list_content(1);
		});
		
		//产前队列现场预约编辑
		$(document).on('click','.pre_book_item_edit',function(){
			var pid = $(this).attr('data-id');
			var model = $('#model').val();
			var title;

			switch (model) {
				case 'm1':
					title = '现场随访-1月';
					break;
				case 'm6':
					title = '现场随访-6月';
					break;
				case 'y1':
					title = '现场随访-1岁';
					break;
				case 'y2':
					title = '现场随访-2岁';
					break;
			}

			$('#myModalLabel').html(title);
			$('#hos_edit_id').val(pid);
			$('#hos_edit_no1').html( $('#no1_'+pid).html() );
			$('#hos_edit_name').html( $('#name_'+pid).html() );
			$('#hos_edit_pphone').val( $('#pphone_'+pid).html() );
			$('#hos_edit_hphone').val( $('#hphone_'+pid).html() );
			$('#hos_edit_telre1').val( $('#telre1_'+pid).html() );
			$('#hos_edit_telre2').val( $('#telre2_'+pid).html() );
			$('#hos_edit_telre3').val( $('#telre3_'+pid).html() );

			if( $('#telre1_'+pid).html().length < 1 ){
				$('#hos_edit_telre1').val(0);
				$('#hos_edit_telre1_remark').hide();
			}
			else{
				$('#hos_edit_telre1').val( $('#telre1_'+pid).attr('data-val') );
				if( $('#telre1_'+pid).attr('data-val') == 100 ){
					$('#hos_edit_telre1_remark').show().val( $('#telre1_'+pid).html() );
				}else{
					$('#hos_edit_telre1_remark').hide();
				}
			}

			if( $('#telre2_'+pid).html().length < 1 ){
				$('#hos_edit_telre2').val(0);
				$('#hos_edit_telre2_remark').hide();
			}
			else{
				$('#hos_edit_telre2').val( $('#telre2_'+pid).attr('data-val') );
				if( $('#telre2_'+pid).attr('data-val') == 100 ){
					$('#hos_edit_telre2_remark').show().val( $('#telre2_'+pid).html() );
				}else{
					$('#hos_edit_telre2_remark').hide();
				}
			}

			if( $('#telre3_'+pid).html().length < 1 ){
				$('#hos_edit_telre3').val(0);
				$('#hos_edit_telre3_remark').hide();
			}
			else{
				$('#hos_edit_telre3').val( $('#telre3_'+pid).attr('data-val') );
				if( $('#telre3_'+pid).attr('data-val') == 100 ){
					$('#hos_edit_telre3_remark').show().val( $('#telre3_'+pid).html() );
				}else{
					$('#hos_edit_telre3_remark').hide();
				}
			}

			if( $('#telname_'+pid).val().length < 1 )
				$('#hos_edit_telname').val( $('#user_name').val() );
			else
				$('#hos_edit_telname').val( $('#telname_'+pid).val() );

			$('#modal_hos_edit').modal('show');
		});

		//产前队列现场预约录入
		$('#hos_edit_save').on('click',function(){
			$('#modal_hos_edit').modal('hide');
			var id = $('#hos_edit_id').val();
			var telre1 = $('#hos_edit_telre1').val();
			var telre2 = $('#hos_edit_telre2').val();
			var telre3 = $('#hos_edit_telre3').val();

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-pre-birth-edit-save-ajax',
					'hos_action': $('#hos_action').val(),
					'model': $("input[name='model']:checked").val(),
					'id' : id,
					'pphone' : $('#hos_edit_pphone').val(),
					'hphone' : $('#hos_edit_hphone').val(),
					'telre1' : telre1,
					'telre1_remark' : $('#hos_edit_telre1_remark').val(),
					'telre2' : telre2,
					'telre2_remark' : $('#hos_edit_telre2_remark').val(),
					'telre3' : telre3,
					'telre3_remark' : $('#hos_edit_telre3_remark').val(),
					'telname' : $('#hos_edit_telname').val(),
				}, 
				function(response){
					response = $.parseJSON(response);

					hos_notify( '操作成功', $('#no1_'+id).html()+'已录入', 'success');
					reload_list_content($('#current_page').val());
				}
			);
		});

		$('#hos_edit_telre1').on('change',function(){
			if($(this).val() == 100)
				$(this).next().val('').show();
			else
				$(this).next().hide();
		});

		$('#hos_edit_telre2').on('change',function(){
			if($(this).val() == 100)
				$(this).next().val('').show();
			else
				$(this).next().hide();
		});

		$('#hos_edit_telre3').on('change',function(){
			if($(this).val() == 100)
				$(this).next().val('').show();
			else
				$(this).next().hide();
		});

		//产前队列电话随访结果编辑
		$(document).on('click','.pre_tel_item_edit',function(){
			var pid = $(this).attr('data-id');
			var model = $('#model').val();
			var title;

			switch (model) {
				case 'm1':
					title = '电话随访录入-1月';
					break;
				case 'm6':
					title = '电话随访录入-6月';
					break;
				case 'y1':
					title = '电话随访录入-1年';
					break;
				case 'y2':
					title = '电话随访录入-2年';
					break;
			}

			$('#myModalLabel').html(title);
			$('#hos_edit_id').val(pid);
			$('#hos_edit_no1').html( $('#no1_'+pid).html() );
			$('#hos_edit_name').html( $('#name_'+pid).html() );
			$('#hos_edit_pphone').val( $('#pphone_'+pid).html() );
			$('#hos_edit_hphone').val( $('#hphone_'+pid).html() );

			var telques_value = $('#telques_'+pid).attr('data-val');

			if( telques_value != 1 )
				telques_value = 0;

			$('#hos_edit_telques[value="'+telques_value+'"]').prop('checked',true);

			if( telques_value == 1 )
				$('#hos_edit_telquesre_html').hide();
			else
				$('#hos_edit_telquesre_html').show();

			var telquesre_value = $('#telquesre_'+pid).attr('data-val');
			
			$('#hos_edit_telquesre').removeAttr('checked');
			$('#hos_edit_telquesre[value="'+telquesre_value+'"]').prop('checked',true);

			$('#modal_hos_edit').modal('show');
		});

		//产前队列电话随访结果录入
		$('#tel_list_save').on('click',function(){
			$('#modal_hos_edit').modal('hide');
			var id = $('#hos_edit_id').val();

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-pre-birth-edit-save-ajax',
					'hos_action': $('#hos_action').val(),
					'model': $("input[name='model']:checked").val(),
					'id' : id,
					'pphone' : $('#hos_edit_pphone').val(),
					'hphone' : $('#hos_edit_hphone').val(),
					'telques' : $('#hos_edit_telques:checked').val(),
					'telquesre' : $('#hos_edit_telquesre:checked').val(),
				}, 
				function(response){
					response = $.parseJSON(response);
					hos_notify( '操作成功', $('#no1_'+id).html()+'已录入', 'success');

					reload_list_content( $('#current_page').val() );
				}
			);
		});

		$(document).on('click', '.pre_pregnant_item_edit', function(){
        	var pid = $(this).attr('data-id');
        	$.post(
        		ajaxurl,
        		{
        			'action' : 'hospital-pre-birth-pregnant-edit-ajax',
        			'id' : pid,
        		},
        		function(response){
        			response = $.parseJSON(response);
        			$('#pregnant_viewer_form').html(response);
        			$('#hos_pregnant_lmp').datepicker({
						showOn: "button",
			      		buttonText: "<i class='fa fa-calendar calendar-icon'></i>",
			      		numberOfMonths:1,
						showButtonPanel:true,
						dateFormat: 'yy-mm-dd',//日期格式  
			            closeText:"清除",//关闭选择框的按钮名称
			            showMonthAfterYear:true,//是否把月放在年的后面  
			            changeMonth: true,
			            changeYear: true,
			            monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
			            monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
			            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],  
			            dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],  
			            dayNamesMin: ['日','一','二','三','四','五','六'],
			            currentText:"当前日期",
			            yearRange: "1900:2036",
			            onClose: function (dateText, inst) {
							if ($(window.event.srcElement).hasClass('ui-datepicker-close'))
							{
							   document.getElementById(this.id).value = '';
							}
						}
					});
        			$('#modal_hos_pregnant').modal('show');
			
        		}
        	);
        });

		$("#hos_pregnant_save").on('click', function(){
			$("#modal_hos_pregnant").modal('hide');
			var id = $('#hos_pregnant_id').val();
			var hos_pregnant_no1 = $('#hos_pregnant_no1').val();
			var hos_pregnant_name = $("#hos_pregnant_name").val();
			var hos_pregnant_lmp = $("#hos_pregnant_lmp").val();
			var hos_pregnant_pphone = $("#hos_pregnant_pphone").val();
			var hos_pregnant_hphone = $("#hos_pregnant_hphone").val();
			var hos_pregnant_serumt1 = $('input:radio[name="hos_pregnant_serumt1"]:checked').val();
			var hos_pregnant_plasma_bcellt1 = $('input:radio[name="hos_pregnant_plasma_bcellt1"]:checked').val();
			var hos_pregnant_urinet1 = $('input:radio[name="hos_pregnant_urinet1"]:checked').val();
			var hos_pregnant_pablood = $('input:radio[name="hos_pregnant_pablood"]:checked').val();
			var hos_pregnant_serumt2 = $('input:radio[name="hos_pregnant_serumt2"]:checked').val();
			var hos_pregnant_urinet2 = $('input:radio[name="hos_pregnant_urinet2"]:checked').val();
			var hos_pregnant_urinet3_ent1 = $('input:radio[name="hos_pregnant_urinet3_ent1"]:checked').val();
			var hos_pregnant_fut2 = $("#hos_pregnant_fut2").val();
			var hos_pregnant_fut2rem = $("#hos_pregnant_fut2rem").val();
			var hos_pregnant_fut2er = $("#hos_pregnant_fut2er").val();
			var current_page = $("#current_page").val();
			$.post(
				ajaxurl,
				{
					'action':'hospital-pre-birth-pregnant-save-ajax',
					'id' : id,
					'no1' : hos_pregnant_no1,
					// 'no1'] : 'hos_pregnant_status']
					'name' : hos_pregnant_name,
					'lmp' : hos_pregnant_lmp,
					'tel1' : hos_pregnant_pphone,
					'tel2' : hos_pregnant_hphone,
					'serumt1' : hos_pregnant_serumt1,
					'plasma_bcellt1' : hos_pregnant_plasma_bcellt1,
					'urinet1' : hos_pregnant_urinet1,
					'pablood' : hos_pregnant_pablood,
					'serumt2' : hos_pregnant_serumt2,
					'urinet2' : hos_pregnant_urinet2,
					'urinet3_ent1' : hos_pregnant_urinet3_ent1,
					'fut2' : hos_pregnant_fut2,
					'fut2rem' : hos_pregnant_fut2rem,
					'fut2er' : hos_pregnant_fut2er,
				},
				function (response){
					reload_list_content(current_page);
					hos_notify( '操作成功' , $('#no1_'+id).html() + '已录入', 'success');
					
				}
			);

		});

		$(document).on('click', '.pre_childbirth_status_item_edit',function(){
			var id = $(this).attr("data-id");
			$.post(
        		ajaxurl,
        		{
        			'action' : 'hospital-pre-birth-childbirth-status-edit-ajax',
        			'id' : id,
        		},
        		function(response){
        			response = $.parseJSON(response);
        			$('#childbirth_status_viewer_form').html(response);
        				$('.hos_childbirth_status_time').datepicker({
						showOn: "button",
			      		buttonText: "<i class='fa fa-calendar calendar-icon'></i>",
			      		numberOfMonths:1,
						showButtonPanel:true,
						dateFormat: 'yy-mm-dd',//日期格式  
			            closeText:"清除",//关闭选择框的按钮名称
			            showMonthAfterYear:true,//是否把月放在年的后面  
			            changeMonth: true,
			            changeYear: true,
			            monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
			            monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
			            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],  
			            dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],  
			            dayNamesMin: ['日','一','二','三','四','五','六'],
			            currentText:"当前日期",
			            onClose: function (dateText, inst) {
							if ($(window.event.srcElement).hasClass('ui-datepicker-close'))
							{
							   document.getElementById(this.id).value = '';
							}
						}
					});
        			$('#modal_hos_childbirth_status').modal('show');
			
        		}
        	);
		});

		$("#hos_childbirth_status_save").on('click', function(){
			$("#modal_hos_childbirth_status").modal('hide');
			var id = $('#hos_childbirth_status_id').val();
			var current_page = $("#current_page").val();
			$.post(
				ajaxurl,
				{
					'action': 'hospital-pre-birth-childbirth-status-save-ajax',
					'id':id,
					'form_data':$("#childbirth_status_viewer_form").serializeArray(),
					'current_page':current_page,
				},
				function( response){
					reload_list_content(current_page);
					hos_notify( '操作成功' , $('#no1_'+id).html() + '已录入', 'success');
				}
			);

		});

		$(document).on('click', '.pre_pregnant_middle_item_edit', function(){
			var id = $(this).attr("data-id");
			$.post(
				ajaxurl,
				{
					'action':'hospital-pre-birth-pregnant-middle-edit-ajax',
					'id' :id,
				},
				function(response){
					response = $.parseJSON(response);
					$('#pregnant_middle_viewer_form').html(response);
					$('#modal_hos_pregnant_middle').modal('show');
				}
			);
		});

		$("#hos_pregnant_middle_save").on('click',function(){
			$('#modal_hos_pregnant_middle').modal('hide');
			var id = $('#hos_pregnant_middle_id').val();
			var current_page = $("#current_page").val();
			$.post(
				ajaxurl,
				{
					'action': 'hospital-pre-birth-pregnant-middle-save-ajax',
					'id':id,
					'form_data':$("#pregnant_middle_viewer_form").serializeArray(),
					'current_page':current_page,
				},
				function( response){
					reload_list_content(current_page);
					hos_notify( '操作成功' , $('#no1_'+id).html() + '已录入', 'success');
				}
			);
		});

		$(document).on('click', '.pre_health_manage_item_edit', function(){
			var id = $(this).attr("data-id");
			$.post(
				ajaxurl,
				{
					'action':'hospital-pre-birth-health-manage-edit-ajax',
					'id' :id,
				},
				function(response){
					response = $.parseJSON(response);
					$('#health_manage_viewer_form').html(response);
					$('#modal_hos_health_manage').modal('show');
				}
			);
		});

		$("#hos_health_manage_save").click(function(){
			$('#modal_hos_health_manage').modal('hide');
			var id = $('#hos_health_manage_id').val();
			var current_page = $("#current_page").val();
			$.post(
				ajaxurl,
				{
					'action': 'hospital-pre-birth-health-manage-save-ajax',
					'id':id,
					'form_data':$("#health_manage_viewer_form").serializeArray(),
					'current_page':current_page,
				},
				function( response){
					reload_list_content(current_page);
					hos_notify( '操作成功' , $('#no1_'+id).html() + '已录入', 'success');
				}
			);
		});
		//产前队列现场结果编辑
		$(document).on('click','.pre_result_item_edit',function(){
			var id = $(this).attr('data-id');
			var model = $("input[name='model']:checked").val();
			var hos_action = $('#hos_action').val();

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-pre-birth-get-track-result-ajax',
					'id':id,
					'hos_action': hos_action,
					'model': model,
				}, 
				function(response){
					response = $.parseJSON(response);
					$('#track_form').html(response);
					load_date_text_input();

					$('#modal_hos_edit').modal('show');
				}
			);
		});

		$('#edit_track_no1').on('keyup',function(){
			reload_list_content(1);
		});
		
		//产前队列现场结果录入
		$('#track_result_save').on('click',function(){
			$('#modal_hos_edit').modal('hide');
			var id = $('#hos_edit_id').val();

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-pre-birth-edit-save-ajax',
					'hos_action': $('#hos_action').val(),
					'model': $("input[name='model']:checked").val(),
					'id' : id,
					'form_data' : $('#track_form').serializeArray()
				}, 
				function(response){
					hos_notify( '操作成功', $('#no1_'+id).html()+'已录入', 'success');
				}
			);
		});

		$(document).on('change','#hos_edit_telques',function(){
			var val = $("#hos_edit_telques:checked").val();

			if( val == 1 )
				$('#hos_edit_telquesre_html').hide();
			else
				$('#hos_edit_telquesre_html').show();
		});

		


		$('#ex_import_submit').on('click',function(e){
			e.preventDefault();
			$('#ex_import').click();
		});

		//入园收样导入
		$('#ex_import').on('change', function(){
			var filename = check_file_valid( $(this).val() );

			if( filename === false )
				return;

			$('#ex_import').wrap("<form id='ajaxupload1' method='post' enctype='multipart/form-data' style='display:none;'></form>");

            $("#ajaxupload1").ajaxSubmit({
                dataType: 'text',
                type: 'post',
        		url: ajaxurl,
        		data: {
	                'action': 'hospital-exam-upload-ajax',
	                'hos_type': $('#hos_type').val(),

	            },
                success: function (response) {
                    $('#ex_import').unwrap();

                    response = $.parseJSON(response);
                    if( response.error.length > 0 ){
                    	hos_notify( '上传失败', response.error, 'error');
                    }else{
                    	hos_notify( '文件 ('+filename+') 上传成功', response.error, 'success');
						$('#hos_four_exam_content').html(response.replace_content);
                    }
                },
            });
			
        });

		$('.exam_search').click(function(){
			reload_list_content(1);
		});

		$('.ex_search_button').click(function(){
			if( $('#ex_search_text').val().length < 1 )
				return;
			reload_list_content(1,'key_word');
		});

		//入园体检导出功能,链到一个页面
		$('.exam_export').click(function(){
			var linkto = $(this).attr('data-href');
			var para_arr = ['hos_action','model','hos_type','min_exam_date','max_exam_date','hos_exam_status','hos_exam_meu','hos_exam_blood_type','hos_exam_bloodqu','hos_exam_brtr','hos_exam_altr'];
			
			$.each( para_arr, function(i, n){ 
				if( $('#'+n).length > 0 && $('#'+n).val() ){
					var value = $('#'+n).val();
					linkto = linkto+'&'+n+'='+value;
				}
			});

 			window.open(linkto);
		});

		$("input[name='import_action']").on('change',function(){
			var i = $(this).val();
			$('#hos_four_track_content').html( $('#'+i+'_table').html() );
		});

		$('#four_import_submit').on('click',function(e){
			e.preventDefault();
			$('#four_import').click();
		});

		//出生队列随访导入
		$('#four_import').on('change',function(){
			var filename = check_file_valid( $(this).val() );

			if( filename === false )
				return;

			$('#four_import').wrap("<form id='ajaxupload' method='post' enctype='multipart/form-data'></form>");

            $("#ajaxupload").ajaxSubmit({
                dataType: 'text',
                type: 'post',
        		url: ajaxurl,
        		data: {
	                'action': 'hospital-four-track-upload-ajax',
	                'hos_type': $('#hos_type').val(),
	                'model': $('#model').val(),
	                'import_action': $("input[name='import_action']:checked").val()

	            },
                success: function (response) {
                    $('#four_import').unwrap();

                    response = $.parseJSON(response);
                    if( response.error.length > 0 ){
                    	hos_notify( '上传失败', response.error, 'error');
                    }else{
                    	hos_notify( '文件 ('+filename+') 上传成功', response.error, 'success');
						$('#hos_four_track_content').html(response.replace_content);
                    }
                },
            });
        });

		//出生队列3-6现场结果编辑
		$(document).on('click','.in_track_item_edit',function(){
			var id = $(this).attr('data-id');
			var no1 = $(this).attr('data-no1');
			var no2 = $(this).attr('data-no2');
			var model = $("#model").val();
			var hos_action = $('#hos_action').val();
			var hos_type = $('#hos_type').val();

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-four-track-edit-ajax',
					'id':id,
					'no1':no1,
					'no2':no2,
					'hos_action': hos_action,
					'hos_type': hos_type,
					'model': model,
				}, 
				function(response){
					response = $.parseJSON(response);
					$('#track_form').html(response.content);
					$('#myModalLabel').html(response.title);
					load_date_text_input();
					
					$('#modal_hos_edit').modal('show');
				}
			);
		});

		//出生队列导出功能,链到一个页面
		$('.four_track_export').click(function(){
			var linkto = $(this).attr('data-href');
			var para_arr = ['hos_action','model','hos_type','group'];
			
			$.each( para_arr, function(i, n){ 
				if( $('#'+n).length > 0 && $('#'+n).val() ){
					var value = $('#'+n).val();
					linkto = linkto+'&'+n+'='+value;
				}
			});

			if($('.extend_type').length > 0)
				linkto = linkto+'&extend_type='+$('.extend_type:checked').val();
			//console.log(linkto);
 			window.open(linkto);
		});

		$('.extend_type').on('change',function(){
			reload_list_content(1);
		});

		$('#four_list_import_submit').on('click',function(e){
			e.preventDefault();
			$('#four_list_import').click();
		});

		$('#four_list_import').on('change',function(){
			var filename = check_file_valid( $(this).val() );

			if( filename === false )
				return;

			$('#four_list_import').wrap("<form id='ajaxupload' method='post' enctype='multipart/form-data'></form>");

            $("#ajaxupload").ajaxSubmit({
                dataType: 'text',
                type: 'post',
        		url: ajaxurl,
        		data: {
	                'action': 'hospital-four-track-import-ajax',
	                'hos_type': $('#hos_type').val(),
	                'model': $('#model').val(),
	                'hos_action': $('#hos_action').val(),
	            },
                success: function (response) {
                    $('#four_list_import').unwrap();

                    response = $.parseJSON(response);
                    if( response.error.length > 0 ){
                    	hos_notify( '上传失败', response.error, 'error');
                    }else{
                    	hos_notify( '文件 ('+filename+') 上传成功', response.error, 'success');
						reload_list_content($('#current_page').val());
                    }
                },
            });
        });


		$(document).on('click','.four_track_item_edit',function(){
			var id = $(this).attr('data-id');
			var no1 = $(this).attr('data-no1');
			var no2 = $(this).attr('data-no2');
			var model = $("#model").val();
			var hos_action = $('#hos_action').val();
			var hos_type = $('#hos_type').val();

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-four-track-edit-ajax',
					'id':id,
					'no1':no1,
					'no2':no2,
					'hos_action': hos_action,
					'hos_type': hos_type,
					'model': model,
				}, 
				function(response){
					response = $.parseJSON(response);
					$('#track_form').html(response.content);
					$('#myModalLabel').html(response.title);
					load_date_text_input();
					
					$('#modal_hos_edit').modal('show');
				}
			);
		});

		//3-4岁随访录入
		$('#four_track_edit_save').on('click',function(){
			$('#modal_hos_edit').modal('hide');

			$.post(
				ajaxurl, 
				{
					'action': 'hospital-four-track-edit-save-ajax',
					'hos_action': $('#hos_action').val(),
					'hos_type': $('#hos_type').val(),
					'model': $("#model").val(),
					'form_data' : $('#track_form').serializeArray()
				}, 
				function(response){
					hos_notify( '操作成功', response+'已录入', 'success');
					reload_list_content($('#current_page').val());
				}
			);
		});

	}); 
})(jQuery);



// jQuery(document).ready(function(){

// 	jQuery( ".dialog" ).dialog({
// 		autoOpen: false,
// 		show: {
// 			//effect: "blind",
// 			duration: 600
// 		},
// 		hide: {
// 			//effect: "explode",
// 			duration: 600
// 		},
// 		width: '300',
// 		minWidth : '200',
// 		resizable : true,
// 		buttons: {
// 		    "提交": function() {
// 		      	var key = jQuery(this).attr('key');
// 		      	var act = jQuery(this).attr('act');
// 		      	if( 'info' == act ){
// 		      		jQuery.post(
// 						ajaxurl, 
// 						{
// 							'data': jQuery('#hos_dialog_info_'+key).serializeArray(),
// 							'action': 'hospital-track-info-ajax'
// 						}, 
// 						function(response){
// 							jQuery("#dialog_info_"+key).dialog( "close" );
// 						}
// 					);
// 		      	}else if( 'tel' == act ){
// 		      		jQuery.post(
// 						ajaxurl, 
// 						{
// 							'data': jQuery('#hos_dialog_tel_'+key).serializeArray(),
// 							'action': 'hospital-track-tel-ajax'
// 						}, 
// 						function(response){
// 							jQuery("#dialog_tel_"+key).dialog( "close" );
// 						}
// 					);
// 		      	}
// 		    } ,
// 		    "取消": function() {
// 		      	jQuery(this).dialog( "close" );
// 		    }
// 		}
//     });
    
 	

// 	jQuery('.track_b').click(function(e){
// 		var region = jQuery(this).attr('data-region');
// 		var key = jQuery(this).attr('data-key');
// 		jQuery('.track_button'+key).css('border-color','#ccc');
// 		jQuery(this).css('border-color','#23282d');

// 		jQuery('.hos_check'+key).hide();
// 		jQuery('.hos_check_tr'+key+'_group'+region).show();
// 	});

// 	jQuery('.list_ignore').click(function(e){
// 		e.preventDefault();
// 		var obj = jQuery(this);
// 		var ignore = obj.attr('data-ignore');
// 		if( 1 == ignore ){
// 			var con = confirm('是否重新提醒');
// 		}else{
// 			var con = confirm('是否不再提醒');
// 		}
// 		if(con){
// 			jQuery.post(
// 				ajaxurl, 
// 				{
// 					'ignore': ignore,
// 					'type'  : obj.attr('data-type'),
// 					'tab_no': obj.attr('data-table_no'),
// 					'region': obj.attr('data-region'),
// 					'action': 'hospital-track-ignore-ajax'
// 				}, 
// 				function(response){
// 					if( 1 == response ){
// 						obj.html('重新提醒');
// 						obj.attr('data-ignore',1);
// 					}
// 					else{
// 						obj.html('不再提醒');
// 						obj.attr('data-ignore',0);
// 					}
// 				}
// 			);
// 		}
// 	});

// 	jQuery('.track_check').click(function(e){
// 		var obj = jQuery(this);
// 		var is_checked = obj.is(':checked') ? 1 : 0;
// 		jQuery.post(
// 			ajaxurl, 
// 			{
// 				'meta_value': is_checked,
// 				'meta_key': obj.attr('data-option'),
// 				'post_id': obj.attr('data-id'),
// 				'action': 'hospital-single-option-save-ajax'
// 			}, 
// 			function(response){
				
// 			}
// 		);
// 	});

// 	jQuery('.hos_track').click(function(e){
// 		//e.preventDefault();
// 		var hkey = jQuery(this).attr('hkey');
// 		var title = jQuery( "#dialog_info_"+hkey ).attr('data-title');
// 		if( jQuery( "#dialog_info_"+hkey ).dialog( "isOpen" ) ){
// 			jQuery( "#dialog_info_"+hkey ).dialog( "close" );
// 		}
// 		else{
// 			jQuery( ".dialog" ).dialog( "close" );
// 			jQuery( "#dialog_info_"+hkey ).dialog('option', 'title', title );
// 			jQuery( "#dialog_info_"+hkey ).dialog( "open" );
// 		}
// 	});

// 	jQuery('.hos_tel').click(function(e){
// 		//e.preventDefault();
// 		var hkey = jQuery(this).attr('hkey');
// 		var title = jQuery( "#dialog_tel_"+hkey ).attr('data-title');
// 		if( jQuery( "#dialog_tel_"+hkey ).dialog( "isOpen" ) ){
// 			jQuery( "#dialog_tel_"+hkey ).dialog( "close" );
// 		}
// 		else{
// 			jQuery( ".dialog" ).dialog( "close" );
// 			jQuery( "#dialog_tel_"+hkey ).dialog('option', 'title', title );
// 			jQuery( "#dialog_tel_"+hkey ).dialog( "open" );
// 		}
// 	});

// 	jQuery('#hos_search').click(function(e){
// 		e.preventDefault();
// 		var linkto = jQuery(this).prop('href');
// 		var region = jQuery('#region').val();
// 		var table_no = jQuery('#table_no').val();
// 		var page = 'tel_list';
// 		linkto = linkto+'&page='+page+'&region='+region+'&table_no='+table_no;
		
// 		window.open(linkto);
// 	});

// 	jQuery.datepicker.regional["zh-CN"] = { closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年" }
// 	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['zh-CN']);

// 	var device_type = jQuery('.add_data_button').attr('data-type');
// 	if( 'mobile' != device_type ){
// 		jQuery('.start_date').datepicker({ 
// 			altField: 'this',
// 			altFormat: 'yy-mm-dd',
// 			showOtherMonths: true,
// 			selectOtherMonths: true ,
// 			changeMonth:true,
// 			changeYear:true,
// 		});

// 		jQuery('.end_date').datepicker({ 
// 			altField: 'this',
// 			altFormat: 'yy-mm-dd',
// 			showOtherMonths: true,
// 			selectOtherMonths: true,
// 			changeMonth:true,
// 			changeYear:true,
// 		});
// 	}
	
// 	jQuery('.add_data_button').click(function(e){
// 		e.preventDefault();
// 		if( 'mobile' == device_type ){
// 			var html = '<div><p>开始日期:&nbsp;&nbsp;<input type="date" class="start_date" name="start_date[]" size="10" /></p>';
// 			html += '<p>结束日期:&nbsp;&nbsp;<input type="date" class="end_date" name="end_date[]" size="10" />&nbsp;&nbsp;<a class="button delete_button" data-type="mobile">删除</a></p></div>';
// 			jQuery('#mobile_date').before(html);
// 		}else{
// 			var html = '<p>开始日期:&nbsp;&nbsp;<input type="text" class="start_date" name="start_date[]" size="10">';
// 			html += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结束日期&nbsp;&nbsp;<input type="text" class="end_date" name="end_date[]" size="10" />&nbsp;&nbsp;<a class="button delete_button" data-type="pc">删除</a></p>';
// 			jQuery('.hos_stat_table').before(html);
// 		}

// 		jQuery('.delete_button').click(function(e){
// 			if( 'mobile' == device_type ){
// 				jQuery(this).parent().parent().remove();
// 			}else{
// 				jQuery(this).parent().remove();
// 			}
// 		});

// 		if( 'mobile' != device_type ){
// 			jQuery('.start_date').datepicker({ 
// 				altField: 'this',
// 				altFormat: 'yy-mm-dd',
// 				showOtherMonths: true,
// 				selectOtherMonths: true,
// 				changeMonth:true,
// 				changeYear:true,
// 			});

// 			jQuery('.end_date').datepicker({ 
// 				altField: 'this',
// 				altFormat: 'yy-mm-dd',
// 				showOtherMonths: true,
// 				selectOtherMonths: true,
// 				changeMonth:true,
// 				changeYear:true,
// 			});
// 		}
// 	});


// 	jQuery('.stat_button').click(function(e){
// 		var error = 0;
// 		jQuery('.start_date').each(function(){
// 			var start = jQuery(this).val();
// 			if( 'mobile' != device_type ){
// 				var end = jQuery(this).next().val();
// 			}else{
// 				var end = jQuery(this).parent().next().find('.end_date').val();
// 			}

// 			if( start.length == 0 && end.length == 0 ){
// 				alert('开始日期和结束日期不能同时为空!');
// 				error = 1;
// 				return false;
// 			}else{
// 				start = new Date(start);
// 	        	end = new Date(end);
// 	        	if (end < start) {
// 	                alert('结束日期不能小于开始日期！');
// 	                error = 1;
// 	                return false;
// 	            }
// 			}

// 			var date_limit = jQuery('#date_limit').val();
// 			if( date_limit != Math.abs( parseInt(date_limit) ) && date_limit.length != 0 ){
// 				alert('请输入一个有效天数!');
// 				error = 1;
// 				return false;
// 			}
// 		});

// 		if( !error ){
// 			jQuery.post(
// 				ajaxurl, 
// 				{
// 					'data': jQuery('#stat_form').serializeArray(),
// 					'action': 'hospital-stat-result-ajax'
// 				}, 
// 				function(response){
// 					jQuery('#stat_result').html(response);
// 					var swiper_search = new Swiper('.swiper-container-search', {
// 				        nextButton: '.swiper-button-next-search',
// 				        prevButton: '.swiper-button-prev-search',
// 				    });
// 				}
// 			);
// 		}
  		
// 	});

// 	jQuery('.stat_option').click(function(e){
// 		var type = jQuery(this).attr('data-type');
// 		var val = 0;
//   		jQuery('.stat_checkbox_'+type).each(function(){
//   			if( jQuery(this).is(':checked') ){
//   				val = 1;
//   				return false;
//   			}
//   		});
//   		jQuery('#has_option'+type).val(val);
// 	});

// 	jQuery('.remark_modify').click(function(){
// 		var obj = jQuery(this).prev(); 
// 		var txt = obj.html(); 
// 		var input = jQuery("<textarea>"+txt+"</textarea>");
// 		var region = obj.attr('data-region');

// 		obj.html(input);
// 		input.click(function() { return false; }); 
// 		//获取焦点 
// 		input.val('').focus().val( txt );; 
// 		//文本框失去焦点后提交内容，重新变为文本 
// 		input.blur(function() { 
// 			var newtxt = jQuery(this).val(); 
// 			//判断文本有没有修改 
// 			if ( newtxt != txt ) { 
// 				jQuery.post(
// 					ajaxurl,
// 					{
// 						'meta_value': newtxt,
// 						'meta_key': 'tel_remark'+region,
// 						'post_id' : obj.attr('data-id'),
// 						'action': 'hospital-single-option-save-ajax'
// 					}, 
// 					function(response){
// 						//浮动保存成功提示
// 					}
// 				);
// 			}
// 			obj.html(newtxt);
// 		});
// 	});

// 	jQuery('.tel_remark_mobile').click(function(){
// 		var obj = jQuery(this); 
// 		var post_id = obj.attr('data-id');

// 		jQuery('#remark_tr'+post_id).toggle();
// 		jQuery('#mobile_remark'+post_id).val('').focus().val( jQuery('#mobile_remark'+post_id).html() );

// 	});

// 	jQuery('.remark_button').click(function(){
// 		var obj = jQuery(this);
// 		var post_id = obj.attr('data-id');
// 		var region = obj.attr('data-region');

// 		jQuery.post(
// 			ajaxurl,
// 			{
// 				'meta_value': jQuery('#mobile_remark'+post_id).val(),
// 				'meta_key': 'tel_remark'+region,
// 				'post_id' : post_id,
// 				'action': 'hospital-single-option-save-ajax'
// 			}, 
// 			function(response){
// 				jQuery('#remark_tr'+post_id).hide();
// 			}
// 		);
// 	});


// 	jQuery('#hos_table1').each(function() {
// 		if(jQuery(this).find('thead').length > 0 && jQuery(this).find('th').length > 0) {
// 			// Clone <thead>
// 			var jQueryw	   = jQuery(window),
// 				jQueryt	   = jQuery(this),
// 				jQuerythead = jQueryt.find('thead').clone(),
// 				jQuerycol   = jQueryt.find('thead, tbody').clone();

// 			// Add class, remove margins, reset width and wrap table
// 			jQueryt
// 			.addClass('sticky-enabled')
// 			.css({
// 				margin: 0,
// 				width: '100%'
// 			}).wrap('<div class="sticky-wrap" />');

// 			if(jQueryt.hasClass('overflow-y')) jQueryt.removeClass('overflow-y').parent().addClass('overflow-y');

// 			// Create new sticky table head (basic)
// 			jQueryt.after('<table class="sticky-thead widefat  fixed " />');

// 			// If <tbody> contains <th>, then we create sticky column and intersect (advanced)
// 			if(jQueryt.find('tbody th').length > 0) {
// 				jQueryt.after('<table class="sticky-col" /><table class="sticky-intersect" />');
// 			}

// 			// Create shorthand for things
// 			var jQuerystickyHead  = jQuery(this).siblings('.sticky-thead'),
// 				jQuerystickyCol   = jQuery(this).siblings('.sticky-col'),
// 				jQuerystickyInsct = jQuery(this).siblings('.sticky-intersect'),
// 				jQuerystickyWrap  = jQuery(this).parent('.sticky-wrap');

// 			jQuerystickyHead.append(jQuerythead);

// 			jQuerystickyCol
// 			.append(jQuerycol)
// 				.find('thead th:gt(0)').remove()
// 				.end()
// 				.find('tbody td').remove();

// 			jQuerystickyInsct.html('<thead><tr><th>'+jQueryt.find('thead th:first-child').html()+'</th></tr></thead>');
			
// 			// Set widths
// 			var setWidths = function () {
// 					jQueryt
// 					.find('thead th').each(function (i) {
// 						jQuerystickyHead.find('th').eq(i).width(jQuery(this).width());
// 					})
// 					.end()
// 					.find('tr').each(function (i) {
// 						jQuerystickyCol.find('tr').eq(i).height(jQuery(this).height());
// 					});

// 					// Set width of sticky table head
// 					jQuerystickyHead.width(jQueryt.width());

// 					// Set width of sticky table col
// 					jQuerystickyCol.find('th').add(jQuerystickyInsct.find('th')).width(jQueryt.find('thead th').width())
// 				},
// 				repositionStickyHead = function () {
// 					// Return value of calculated allowance
// 					var allowance = calcAllowance();
				
// 					// Check if wrapper parent is overflowing along the y-axis
// 					if(jQueryt.height() > jQuerystickyWrap.height()) {
// 						// If it is overflowing (advanced layout)
// 						// Position sticky header based on wrapper scrollTop()
// 						if(jQuerystickyWrap.scrollTop() > 0) {
// 							// When top of wrapping parent is out of view
// 							jQuerystickyHead.add(jQuerystickyInsct).css({
// 								opacity: 1,
// 								top: jQuerystickyWrap.scrollTop()
// 							});
// 						} else {
// 							// When top of wrapping parent is in view
// 							jQuerystickyHead.add(jQuerystickyInsct).css({
// 								opacity: 0,
// 								top: 0
// 							});
// 						}
// 					} else {
// 						// If it is not overflowing (basic layout)
// 						// Position sticky header based on viewport scrollTop
// 						if(jQueryw.scrollTop() > jQueryt.offset().top && jQueryw.scrollTop() < jQueryt.offset().top + jQueryt.outerHeight() - allowance) {
// 							// When top of viewport is in the table itself
// 							jQuerystickyHead.add(jQuerystickyInsct).css({
// 								opacity: 1,
// 								top: jQueryw.scrollTop() - jQueryt.offset().top +30
// 							});
// 						} else {
// 							// When top of viewport is above or below table
// 							jQuerystickyHead.add(jQuerystickyInsct).css({
// 								opacity: 0,
// 								top: 0
// 							});
// 						}
// 					}
// 				},
// 				repositionStickyCol = function () {
// 					if(jQuerystickyWrap.scrollLeft() > 0) {
// 						// When left of wrapping parent is out of view
// 						jQuerystickyCol.add(jQuerystickyInsct).css({
// 							opacity: 1,
// 							left: jQuerystickyWrap.scrollLeft()
// 						});
// 					} else {
// 						// When left of wrapping parent is in view
// 						jQuerystickyCol
// 						.css({ opacity: 0 })
// 						.add(jQuerystickyInsct).css({ left: 0 });
// 					}
// 				},
// 				calcAllowance = function () {
// 					var a = 0;
// 					// Calculate allowance
// 					jQueryt.find('tbody tr:lt(3)').each(function () {
// 						a += jQuery(this).height();
// 					});
					
// 					// Set fail safe limit (last three row might be too tall)
// 					// Set arbitrary limit at 0.25 of viewport height, or you can use an arbitrary pixel value
// 					if(a > jQueryw.height()*0.25) {
// 						a = jQueryw.height()*0.25;
// 					}
					
// 					// Add the height of sticky header
// 					a += jQuerystickyHead.height();
// 					return a;
// 				};

// 			setWidths();

// 			jQueryt.parent('.sticky-wrap').scroll(jQuery.throttle(250, function() {
// 				repositionStickyHead();
// 				repositionStickyCol();
// 			}));

// 			jQueryw
// 			.load(setWidths)
// 			.resize(jQuery.debounce(250, function () {
// 				setWidths();
// 				repositionStickyHead();
// 				repositionStickyCol();
// 			}))
// 			.scroll(jQuery.throttle(250, repositionStickyHead));
// 		}
// 	});

// 	var swiper1 = new Swiper('.swiper-container1', {
// 				        nextButton: '.swiper-button-next1',
// 				        prevButton: '.swiper-button-prev1',
// 				    });

// 	var swiper2 = new Swiper('.swiper-container2', {
// 				        nextButton: '.swiper-button-next2',
// 				        prevButton: '.swiper-button-prev2',
// 				    });

// 	var swiper3 = new Swiper('.swiper-container3', {
// 				        nextButton: '.swiper-button-next3',
// 				        prevButton: '.swiper-button-prev3',
// 				    });
	
// });

(function(b, c) {
    var $ = b.jQuery || b.Cowboy || (b.Cowboy = {}),
        a;
    $.throttle = a = function(e, f, j, i) {
        var h, d = 0;
        if (typeof f !== "boolean") {
            i = j;
            j = f;
            f = c
        }

        function g() {
            var o = this,
                m = +new Date() - d,
                n = arguments;

            function l() {
                d = +new Date();
                j.apply(o, n)
            }

            function k() {
                h = c
            }
            if (i && !h) {
                l()
            }
            h && clearTimeout(h);
            if (i === c && m > e) {
                l()
            } else {
                if (f !== true) {
                    h = setTimeout(i ? k : l, i === c ? e - m : e)
                }
            }
        }
        if ($.guid) {
            g.guid = j.guid = j.guid || $.guid++
        }
        return g
    };
    $.debounce = function(d, e, f) {
        return f === c ? a(d, e, false) : a(d, f, e !== false)
    }
})(this);