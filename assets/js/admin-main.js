!(function ($) {
	"use strict";

	jQuery(document).ready(function ($) {
		if (!$('#woocommerce-product-data').length) {
			return;
		}
		// Add gallery images
		$(document).on('click', '.add-variation-gallery-image', function (e) {
			e.preventDefault();
			var button = $(this);
			var wrapper = button.closest('.variation-gallery-wrapper');

			var frame = wp.media({
				title: 'Add Gallery Images',
				multiple: true,
				library: { type: 'image' }
			});

			frame.on('select', function () {
				var selection = frame.state().get('selection');
				var attachmentIds = wrapper.find('.variation-gallery-ids').val();
				attachmentIds = attachmentIds ? attachmentIds.split(',') : [];

				selection.map(function (attachment) {
					attachment = attachment.toJSON();
					if (attachmentIds.indexOf(attachment.id.toString()) === -1) {
						attachmentIds.push(attachment.id);
						wrapper.find('.variation-gallery-images').append(
							'<div class="image" data-id="' + attachment.id + '">' +
							'<img src="' + attachment.sizes.thumbnail.url + '" />' +
							'<a href="#" class="delete-variation-gallery-image">×</a>' +
							'</div>'
						);
					}
				});

				wrapper.find('.variation-gallery-ids').val(attachmentIds.join(','));
				wrapper.closest('.woocommerce_variation').addClass('variation-needs-update');
			});

			frame.open();
		});

		// Remove gallery image
		$(document).on('click', '.delete-variation-gallery-image', function (e) {
			e.preventDefault();
			var wrapper = $(this).closest('.variation-gallery-wrapper');
			var image = $(this).closest('.image');
			var imageId = image.data('id');

			var attachmentIds = wrapper.find('.variation-gallery-ids').val().split(',');
			var index = attachmentIds.indexOf(imageId.toString());
			if (index > -1) {
				attachmentIds.splice(index, 1);
			}

			wrapper.find('.variation-gallery-ids').val(attachmentIds.join(','));
		wrapper.closest('.woocommerce_variation').addClass('variation-needs-update');
		image.remove();
	});

	// Media uploader for 360 GLB file
		var frame360;
		$('.upload_360_images_button').on('click', function (e) {
			e.preventDefault();

			if (frame360) {
				frame360.open();
				return;
			}

			frame360 = wp.media({
				title: 'Select GLB File for 360° View',
				button: {
					text: 'Use this file'
				},
				multiple: false,
				library: {
					type: ['model/gltf-binary', 'application/octet-stream']
				}
			});

			frame360.on('select', function () {
				var attachment = frame360.state().get('selection').first().toJSON();
				var fileId = attachment.id;
				var fileName = attachment.filename;
				var fileSize = attachment.filesizeHumanReadable;
				var fileUrl = attachment.url;

				// Update hidden input
				$('#_product_360_images').val(fileId);

				// Create preview HTML
				var previewHtml = '<div class="file-preview" style="display: flex; align-items: center; padding: 10px; background: #f5f5f5; border-radius: 4px; max-width: 400px;">';
				previewHtml += '<span class="dashicons dashicons-media-document" style="font-size: 24px; margin-right: 10px;"></span>';
				previewHtml += '<div style="flex: 1;">';
				previewHtml += '<strong>' + fileName + '</strong><br>';
				previewHtml += '<small style="color: #666;">' + fileSize + '</small>';
				previewHtml += '</div>';
				previewHtml += '<a href="' + fileUrl + '" target="_blank" class="button button-small" style="margin-left: 10px;">View</a>';
				previewHtml += '<button type="button" class="button button-small remove_360_file_button" style="margin-left: 5px; color: #b32d2e;">Remove</button>';
				previewHtml += '</div>';

				// Update or create preview container
				if ($('.product-360-preview').length) {
					$('.product-360-preview').html(previewHtml);
				} else {
					$('.product-360-fields').append('<div class="product-360-preview" style="margin-left: 150px;">' + previewHtml + '</div>');
				}
			});

			frame360.open();
		});

		// Remove 360 GLB file
		$(document).on('click', '.remove_360_file_button', function (e) {
			e.preventDefault();
			$('#_product_360_images').val('');
			$('.product-360-preview').remove();
		});
	});

	jQuery(window).on('resize', function () {

	});

	jQuery(window).on('scroll', function () {

	});

})(jQuery);
