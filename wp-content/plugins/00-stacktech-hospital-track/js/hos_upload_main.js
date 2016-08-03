/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    var uploader = $("#fileupload");

    uploader.fileupload({
        // Uncomment the following to send cross-domain cookies:
        url: ajaxurl,
        autoUpload: true,
        dataType: 'json',
        formData: {
            action: $('#ajax_action').val(),
            hos_type: $('#hos_type').val(),
            hos_action: $('#hos_action').val(),
            model: $('#model').val(),
        }
    });  
    
});
