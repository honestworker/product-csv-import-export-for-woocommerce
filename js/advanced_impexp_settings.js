// 
var product_cats;
var import_cats;

jQuery( '#prod-advanced-save-settings-upload-file' ).click(function() {
	jQuery( '#prod-advanced-save-settings-upload-file' ).attr('disabled', true);
	var use_ftp = jQuery("#pro_use_ftps").prop("checked") ? 1 : 0;
	jQuery.ajax({
		url :                           xa_prod_piep_test_ftp.admin_ajax_url,
		type:                           'POST',
		data : {
				action:		            'advanced-save-settings-upload-file',
				ftp_host:	            jQuery('#pro_ftp_server').val(),
				ftp_port:	            jQuery('#pro_ftp_port').val(),
				ftp_userid:	            jQuery('#pro_ftp_user').val(),
				ftp_password:	        jQuery('#pro_ftp_password').val(),
				use_ftps:	            use_ftp,
				server_path:       		jQuery('#pro_server_path').val(),
				delimiter:	            jQuery('#pro_delimiter').val(),
				images_path:			jQuery('#pro_images_path').val(),
				main_category:	        jQuery('#pro_main_category').val(),
				sub_category:	        jQuery('#pro_sub_category').val()
		},
		success : function(response) {
			var result = JSON.parse(response);
			jQuery( '#prod-advanced-save-settings-upload-file' ).attr('disabled', false);
			if (typeof result['cats_html'] !== 'undefined') {
				jQuery( '#prod-advaced-import-result' )[0].innerHTML = result['cats_html'];
				
				jQuery( '.prod_map_main_category' ).change(function() {
					for ( var i = 0, len1 = this.options.length; i < len1; i++ ) {
						opt = this.options[i];
						if ( opt.selected === true ) {
							var changed = 0;
							for ( var j = 0, len2 = product_cats.length; j < len2; j++) {
								if ( product_cats[j].name == opt.value ) {
									changed = 1;
									var sub_category_html = "<option value=\"\" data-id=\"0\">Unspecified</option>";
									if (typeof product_cats[j].sub_cats != 'undefined' && product_cats[j].sub_cats) {
										for ( var k = 0, len3 = product_cats[j].sub_cats.length; k < len3; k++) {
											sub_category_html = sub_category_html + "<option value=\"" + product_cats[j].sub_cats[k].name + "\" data-id=\"" + product_cats[j].sub_cats[k].term_id + "\">" + product_cats[j].sub_cats[k].name + "</option>";
										}
									}
									this.parentNode.nextSibling.firstChild.innerHTML = sub_category_html;
								}
							}
							if (!changed) {
								this.parentNode.nextSibling.firstChild.innerHTML = "<option value=\"\" data-id=\"0\">Unspecified</option>";
							}
						}
					}
				});
				
				jQuery( '#prod-advanced-import-file' ).click(function() {
					jQuery( '#prod-advanced-import-file' ).attr('disabled', true);
					var mapping_category = [];
					var import_main_category = [];
					var import_sub_category = [];
					var main_category = "";
					jQuery( '#prod-advaced-import-result' ).find( 'tr' ).each( function() {
						if (this.childNodes[3].childNodes[0].value) {
							mapping_category.push(this.childNodes[2].childNodes[0].value + ' > '  + this.childNodes[3].childNodes[0].value);
						} else {
							mapping_category.push(this.childNodes[2].childNodes[0].value);
						}
						if (this.childNodes[0].textContent) {
							main_category = this.childNodes[0].textContent;
						}
						import_main_category.push(main_category);
						import_sub_category.push(this.childNodes[1].textContent);
					});
					jQuery.ajax({
						url :                           xa_prod_piep_test_ftp.admin_ajax_url,
						type:                           'POST',
						data : {
								action:		            'advanced-import-file',
								mapping_category:	    mapping_category,
								main_category:		    import_main_category,
								sub_category:		    import_sub_category,
						},
						success : function(response) {
							var result = JSON.parse(response);
							jQuery( '#prod-advanced-import-file' ).attr('disabled', false);
						}
					});
				});
			} else {
				jQuery( '#prod-advaced-import-result' )[0].innerHTML = result;
			}
			if (typeof result['product_cats'] !== 'undefined') {
				product_cats = result['product_cats'];
			}
			if (typeof result['import_cats'] !== 'undefined') {
				import_cats = result['import_cats'];
			}
			jQuery( '#prod-advaced-import-result' ).removeClass( 'hidden' );
		}
    });
});
