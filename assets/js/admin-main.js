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
	});

	jQuery(window).on('resize', function () {
		//  WoozioSubmenuAuto();
	});

	jQuery(window).on('scroll', function () {

	});

})(jQuery);
