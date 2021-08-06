
<h1>Main Info</h1> 
<script>
    jQuery(document).ready(function($){
    "use strict";

    console.log("LOADED LWB");  

    $('.sync_linking_keywords_list_custom').click(function(){
        console.log("Do the click")
    });

    // function runAutomation();
	let count = 0;
    function clickAll(){
        console.log("clicky-click");
		checkDone();
        if($('.sync_linking_keywords_list_custom').length > 0){
            $('.sync_linking_keywords_list_custom').not('.wpil_button_is_active').not('.done-button').each(function( index ) {
                    console.log("clinking links")
					$(this).click();
                });
				// checkDone();
            }else{
				count++;
				if(count > 6){
					console.log("Nothing Here!")
					checkDone();
					window.location = window.location;
				}
			}
		
    }

	function checkDone(){
		
		if(($('.sync_linking_keywords_list_custom').length == 0) || ($('.sync_linking_keywords_list_custom').length == $('.done-button').length)){
			console.log("REFRESH");
			clearInterval(automation);

			window.location = window.location;

		}
	}

    let automation = setInterval(clickAll, 3000);



/**
 * ripped from link whisper core for a few edits
 */

$(document).on('click', '.sync_linking_keywords_list_custom', function (e) {
		e.preventDefault();

		var page = $(this).data('page');
		var links = [];
		var data = [];
		var button = $(this);
		$(this).closest('div:not(#wpil-inbound-suggestions-head-controls)').find('[wpil-link-new][type=checkbox]:checked').each(function() {
			if (page == 'inbound') {
				var item = {};
				item.id = $(this).closest('tr').find('.sentence').data('id');
				item.type = $(this).closest('tr').find('.sentence').data('type');
				item.links = [{
					'sentence': $(this).closest('tr').find('.sentence').find('[name="sentence"]').val(),
					'sentence_with_anchor': $(this).closest('tr').find('.wpil_sentence_with_anchor').html(),
					'custom_sentence': $(this).closest('tr').find('input[name="custom_sentence"]').val()
				}];

				data.push(item);
				console.log("DATA",data); 
			} else {
				if ($(this).closest('tr').find('input[type="radio"]:checked').length) {
					var id =  $(this).closest('tr').find('input[type="radio"]:checked').data('id');
					var type = $(this).closest('tr').find('input[type="radio"]:checked').data('type');
					var custom_link = $(this).closest('tr').find('input[type="radio"]:checked').data('custom');
					var post_origin = $(this).closest('tr').find('input[type="radio"]:checked').data('post-origin');
					var site_url = $(this).closest('tr').find('input[type="radio"]:checked').data('site-url');
				} else {
					var id =  $(this).closest('tr').find('.suggestion').data('id');
					var type =  $(this).closest('tr').find('.suggestion').data('type');
					var custom_link =  $(this).closest('tr').find('.suggestion').data('custom');
					var post_origin = $(this).closest('tr').find('.suggestion').data('post-origin');
					var site_url = $(this).closest('tr').find('.suggestion').data('site-url');
				}

				links.push({
					id: id,
					type: type,
					custom_link: custom_link,
					post_origin: post_origin,
					site_url: site_url,
					sentence: $(this).closest('div').find('[name="sentence"]').val(),
					sentence_with_anchor: $(this).closest('div').find('.wpil_sentence_with_anchor').html(),
					custom_sentence: $(this).closest('.sentence').find('input[name="custom_sentence"]').val()
				});
			}

		});

		if (page == 'outbound') {
			data.push({'links': links});
		}else{
			button.addClass('wpil_button_is_active');
		}

		$('.wpil_keywords_list, .tbl-link-reports .wp-list-table').addClass('ajax_loader');

		var data_post = {
			"id": $(this).data('id'),
			"type": $(this).data('type'),
			"page": $(this).data('page'),
			"action": 'wpil_save_linking_references',
			'data': data,
			'gutenberg' : $('.block-editor-page').length ? true : false
    	};
		console.log("Running process!", data_post);
		$.ajax({
			url: wpil_ajax.ajax_url,
			dataType: 'json',
			data: data_post,
			method: 'post',
			error: function (jqXHR, textStatus, errorThrown) {
                var wrapper = document.createElement('div');
                $(wrapper).append('<strong>' + textStatus + '</strong><br>');
                $(wrapper).append(jqXHR.responseText);
                wpil_swal({"title": "Error", "content": wrapper, "icon": "error"});

				$('.wpil_keywords_list, .tbl-link-reports .wp-list-table').removeClass('ajax_loader');
			},
			success: function (data) {
				if (data.err_msg) {
					wpil_swal('Error', data.err_msg, 'error');
				} else {
					if (page == 'outbound') {
						if ($('.editor-post-save-draft').length) {
							$('.editor-post-save-draft').click();
						} else if ($('#save-post').length) {
							$('#save-post').click();
						} else if ($('.editor-post-publish-button').length) {
							$('.editor-post-publish-button').click();
						} else if ($('#publish').length) {
							$('#publish').click();
						} else if ($('.edit-tag-actions').length) {
							$('.edit-tag-actions input[type="submit"]').click();
						}

						// set the flag so we know that the editor needs to be reloaded
						reloadGutenberg = true;
					} else {
						// location.reload();
					}
				}
			},
			complete: function(){
				button.removeClass('wpil_button_is_active');
				button.addClass('done-button');
				$('.wpil_keywords_list').removeClass('ajax_loader');
			}
		})
	});

});
</script>