!(function ($) {
	"use strict";

	/* Toggle submenu align */
	function WoozioSubmenuAuto() {
		if ($('.bt-site-header .bt-container').length > 0) {
			var container = $('.bt-site-header .bt-container'),
				containerInfo = { left: container.offset().left, width: container.innerWidth() },
				contLeftPos = containerInfo.left,
				contRightPos = containerInfo.left + containerInfo.width;

			$('.children, .sub-menu').each(function () {
				var submenuInfo = { left: $(this).offset().left, width: $(this).innerWidth() },
					smLeftPos = submenuInfo.left,
					smRightPos = submenuInfo.left + submenuInfo.width;

				if (smLeftPos <= contLeftPos) {
					$(this).addClass('bt-align-left');
				}

				if (smRightPos >= contRightPos) {
					$(this).addClass('bt-align-right');
				}

			});
		}
	}

	/* Toggle menu mobile */
	function WoozioToggleMenuMobile() {
		$('.bt-site-header .bt-menu-toggle').on('click', function (e) {
			e.preventDefault();

			if ($(this).hasClass('bt-menu-open')) {
				$(this).addClass('bt-is-hidden');
				$('.bt-site-header .bt-primary-menu').addClass('bt-is-active');
			} else {
				$('.bt-menu-open').removeClass('bt-is-hidden');
				$('.bt-site-header .bt-primary-menu').removeClass('bt-is-active');
			}
		});
	}

	/* Toggle sub menu mobile */
	function WoozioToggleSubMenuMobile() {
		var hasChildren = $('.bt-site-header .page_item_has_children, .bt-site-header .menu-item-has-children');

		hasChildren.each(function () {
			var $btnToggle = $('<div class="bt-toggle-icon"></div>');

			$(this).append($btnToggle);

			$btnToggle.on('click', function (e) {
				e.preventDefault();
				$(this).toggleClass('bt-is-active');
				$(this).parent().children('ul').toggle();
			});
		});
	}
	/* Shop */
	function WoozioImageZoomable() {
		if ($('.bt-gallery-zoomable').length == 0) {
			return
		}

		const zoomables = $('.bt-gallery-zoomable .zoomable');
		for (const el of zoomables) {
			new Zoomable(el);
		}
	}

	function WoozioGalleryLightbox() {
		if ($('.bt-gallery-lightbox').length == 0) {
			return
		}

		$('.bt-gallery-lightbox').magnificPopup({
			delegate: 'img',
			type: 'image',
			removalDelay: 500,
			callbacks: {
				beforeOpen: function () {
					this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
					this.st.mainClass = 'mfp-with-zoom mfp-img-mobile';
				},
				elementParse: function (item) { item.src = item.el.attr('src'); },
			},
			image: {
				verticalFit: true
			},
			gallery: {
				enabled: true,
				navigateByImgClick: true,
			},
			zoom: {
				enabled: true,
				duration: 300,
				easing: 'ease-in-out',
				opener: function(openerElement) {
					return openerElement.is('img') ? openerElement : openerElement.find('img');
				}
			}
		});
	}

	function WoozioSliderThumbs() {
		if ($('.woocommerce-product-gallery__slider').length == 0) {
			return
		}

		var thumbDirection = 'horizontal';
		if ($('.bt-left-thumbnail').length > 0 || $('.bt-right-thumbnail').length > 0) {
			thumbDirection = 'vertical';
		}

		var galleryThumbs = new Swiper('.woocommerce-product-gallery__slider-thumbs', {
			direction: thumbDirection,
			spaceBetween: 10,
			autoHeight: true,
			loop: false,
			freeMode: true,
			loopedSlides: 5,
			watchSlidesVisibility: true,
			watchSlidesProgress: true,
			breakpoints: {
				0: {
					slidesPerView: 'vertical' == thumbDirection ? 'auto' : 3,
				},
				480: {
					slidesPerView: 'vertical' == thumbDirection ? 'auto' : 4,
				},
				768: {
					slidesPerView: 'vertical' == thumbDirection ? 'auto' : 5,
				},
				992: {
					slidesPerView: 'vertical' == thumbDirection ? 'auto' : 4,
				},
				1200: {
					slidesPerView: 'vertical' == thumbDirection ? 'auto' : 5,
				}
			}
		});
		var galleryTop = new Swiper('.woocommerce-product-gallery__slider', {
			spaceBetween: 20,
			loop: false,
			loopedSlides: 5,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			thumbs: {
				swiper: galleryThumbs,
			},
		});
	}

	function WoozioGallerySlider() {
		if ($('.bt-gallery-slider-product').length == 0) {
			return
		}

		var gallerySlider = new Swiper('.bt-gallery-slider-product', {
			spaceBetween: 20,
			loop: false,
			loopedSlides: 5,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			breakpoints: {
				0: {
					slidesPerView: 2,
				},
				768: {
					slidesPerView: 3,
				}
			}
		});
	}

	function WoozioShop() {
		if ($('.single-product div.images').length > 0) {
			WoozioImageZoomable();
			WoozioGalleryLightbox();
			WoozioSliderThumbs();
			WoozioGallerySlider();
			if ($('.bt-gallery-grid-products').length > 0) {
				var items = $('.bt-gallery-grid-products').data('items'),
					shown = $('.bt-gallery-grid-products').data('shown');
				$('.bt-gallery-grid-product__item:lt(' + shown + ')').addClass('show');
				if (shown < items) {
					$('.bt-gallery-grid-products .bt-show-more').show();
				} else {
					$('.bt-gallery-grid-products .bt-show-more').hide();
				}
				$('.bt-gallery-grid-products .bt-show-more').on('click', function () {
					items = $('.bt-gallery-grid-products').data('items');
					shown = $('.bt-gallery-grid-product__item.show').length + 2;
					if (shown < items) {
						$('.bt-gallery-grid-product__item:lt(' + shown + ')').addClass('show');
					} else {
						$('.bt-gallery-grid-product__item:lt(' + items + ')').addClass('show');
						$('.bt-gallery-grid-products .bt-show-more').hide();
					}
				});
			}
		}

		if ($('.quantity input').length > 0) {
			/* Plus Qty */
			$(document).on('click', '.qty-plus', function () {
				var parent = $(this).parent();
				$('input.qty', parent).val(parseInt($('input.qty', parent).val()) + 1);
				$('input.qty', parent).trigger('change');
			});
			/* Minus Qty */
			$(document).on('click', '.qty-minus', function () {
				var parent = $(this).parent();
				if (parseInt($('input.qty', parent).val()) > 1) {
					$('input.qty', parent).val(parseInt($('input.qty', parent).val()) - 1);
					$('input.qty', parent).trigger('change');
				}
			});
		}

		if ($('.variations_form').length > 0) {
			$(document).on('click', '.bt-attributes-wrap .bt-js-item', function () {
				var valueItem = $(this).data('value');
				var attributesItem = $(this).closest('.bt-attributes--item');
				var attributeName = attributesItem.data('attribute-name');
				attributesItem.find('.bt-js-item').removeClass('active'); // Remove active class only from items in the same attribute group
				$(this).addClass('active'); // Add active class to clicked item
				var nameItem = (attributeName == 'pa_color') ? $(this).find('label').text() : $(this).text();
				attributesItem.find('.bt-result').text(nameItem);
				$(this).closest('.variations_form').find('select#' + attributeName).val(valueItem).trigger('change');
				var gallerylayout = '';
				if ($('.bt-gallery-slider-container').length > 0 || $('.bt-gallery-slider-fullwidth').length > 0) {
					gallerylayout = 'gallery-slider';
				} else if ($('.bt-gallery-one-column').length > 0 || $('.bt-gallery-two-column').length > 0 || $('.bt-gallery-stacked').length > 0) {
					gallerylayout = 'gallery-grid';
				} else {
					gallerylayout = 'slider-thumb';
				}
				// fix add cart slider elementor
				if ($(this).closest('.swiper-slide-duplicate').length > 0) {
					if (typeof $.fn.wc_variation_form !== 'undefined') {
						$(this).closest('.variations_form').wc_variation_form();
					}
				}

				$(this).closest('.variations_form').on('show_variation', function(event, variation) {
					var variationId = variation.variation_id;
					if (variationId && variationId !== '0') {
						$('.bt-button-buy-now a').removeClass('disabled').attr('data-variation', variationId);
						if ($('.bt-product-add-to-cart-variable').length > 0) {
							var $addToCartBtn = $(this).closest('.bt-product-add-to-cart-variable').find('.bt-js-add-to-cart-variable');
							$addToCartBtn.removeClass('disabled').attr('data-variation', variationId);
							
							// Handle quantity controls - remove old handler first
							$(this).closest('.variations_form').find('.quantity .qty').off('change.updateQuantity').on('change.updateQuantity', function () {
								var newQuantity = parseInt($(this).val()) || 1;
								$addToCartBtn.attr('data-product-quantity', newQuantity);
							});
						}
						// Load gallery
						var param_ajax = {
							action: 'woozio_load_product_gallery',
							gallery_layout: gallerylayout,
							variation_id: variationId
						};
						
						$.ajax({
							type: 'POST',
							dataType: 'json',
							url: AJ_Options.ajax_url,
							data: param_ajax,
							beforeSend: function () {
								// Show loading skeleton
								let skeletonHtml = '';
								if (gallerylayout == 'gallery-slider') {
									skeletonHtml = `
										<div class="bt-skeleton-gallery">
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
										</div>`;
								} else if (gallerylayout == 'gallery-grid') {
									skeletonHtml = `
										<div class="bt-skeleton-gallery">
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
											<div class="bt-skeleton-main-image">
												<div class="bt-skeleton-thumb"></div>
											</div>
										</div>`;
								} else {
									skeletonHtml = `
										<div class="bt-skeleton-gallery">
												<div class="bt-skeleton-main-image">
													<div class="bt-skeleton-thumb"></div>
												</div>
												<div class="bt-skeleton-thumbnails">
													<div class="bt-skeleton-thumbnails--inner">
														<div class="bt-skeleton-thumb"></div>
														<div class="bt-skeleton-thumb"></div>
														<div class="bt-skeleton-thumb"></div>
														<div class="bt-skeleton-thumb"></div>
														<div class="bt-skeleton-thumb"></div>
														<div class="bt-skeleton-thumb"></div>
													</div>
												</div>
										</div>`;
								}
								// Remove existing gallery
								$('.woocommerce-product-gallery, .bt-gallery-grid-products, .bt-gallery-slider-products').addClass('loading');
								$('.woocommerce-product-gallery, .bt-gallery-grid-products, .bt-gallery-slider-products').prepend(skeletonHtml);

								$('.bt-attributes-wrap .bt-js-item').addClass('disable');
							},
							success: function (response) {
								if (response.success) {
									if (gallerylayout == 'gallery-slider') {
										setTimeout(function () {
											$('.bt-gallery-slider-products').html(response.data['gallery-slider']);
											WoozioImageZoomable();
											WoozioGalleryLightbox();
											WoozioGallerySlider();

											setTimeout(function () {
												$('.bt-skeleton-gallery').remove();
												$('.bt-gallery-slider-products').removeClass('loading');
											}, 200);
										}, 300);
									} else if (gallerylayout == 'gallery-grid') {
										setTimeout(function () {
											$('.bt-gallery-grid-products').data('items', response.data['itemgallery']);
											$('.bt-gallery-grid-product').html(response.data['gallery-grid']);

											WoozioImageZoomable();

											var items = $('.bt-gallery-grid-products').data('items'),
												shown = $('.bt-gallery-grid-products').data('shown');
											$('.bt-gallery-grid-product__item:lt(' + shown + ')').addClass('show');
											if (shown < items) {
												$('.bt-gallery-grid-products .bt-show-more').show();
											} else {
												$('.bt-gallery-grid-products .bt-show-more').hide();
											}

											setTimeout(function () {
												$('.bt-skeleton-gallery').remove();
												$('.bt-gallery-grid-products').removeClass('loading');
											}, 200);
										}, 300);
									} else {
										if (response.data['itemgallery'] > 1) {
											$('.woocommerce-product-gallery__wrapper').addClass('bt-has-slide-thumbs');
											$('.bt-skeleton-gallery .bt-skeleton-thumbnails').show();
										} else {
											$('.woocommerce-product-gallery__wrapper').removeClass('bt-has-slide-thumbs');
											$('.bt-skeleton-gallery .bt-skeleton-thumbnails').hide();
										}
										setTimeout(function () {
											$('.woocommerce-product-gallery__wrapper').html(response.data['slider-thumb']);
											WoozioImageZoomable();
											WoozioGalleryLightbox();
											WoozioSliderThumbs();
											
											setTimeout(function () {
												$('.woocommerce-product-gallery').removeClass('loading');
												$('.bt-skeleton-gallery').remove();
											}, 200);
										}, 300);
									}
									$('.bt-attributes-wrap .bt-js-item').removeClass('disable');
								}
							},
							error: function (xhr, status, error) {
								console.log('Error loading gallery:', error);
								$('.woocommerce-product-gallery').removeClass('loading');
								$('.bt-attributes-wrap .bt-js-item').removeClass('disable');
							}
						});
						//Get variation price from data-product_variations
						var $form = $(this).closest('.variations_form');
						var variations = $form.data('product_variations');
						if (variations) {
							// Find matching variation by ID
							var variation = variations.find(function(v) {
								return v.variation_id === variationId;
							});

							if (variation && variation.display_price) {
								// Format price with currency symbol
								var formattedPrice = '<span class="bt-price-add-cart"> - ' + 
									variation.price_html + '</span>';
									
								// Update add to cart button text
								$form.find(".single_add_to_cart_button")
									.html("Add to cart" + formattedPrice);
							}
						}
					} else {
						$('.bt-button-buy-now a').addClass('disabled').removeAttr('data-variation');
						if ($('.bt-product-add-to-cart-variable').length > 0) {
							$(this).closest('.bt-product-add-to-cart-variable').find('.bt-js-add-to-cart-variable').addClass('disabled').removeAttr('data-variation');
						}
					}
				});

				$('.bt-attributes-wrap .bt-js-item').each(function () {
					var valueItem = $(this).data('value');
					var attributesItem = $(this).closest('.bt-attributes--item');
					var attributeName = attributesItem.data('attribute-name');
					var options = $(this).closest('.variations_form').find('select#' + attributeName + ' option');
					var optionExists = false;
					options.each(function () {
						if ($(this).val() == valueItem) {
							optionExists = true;
							return false; // break the loop
						}
					});
					if (!optionExists) {
						$(this).addClass('disabled');
					} else {
						$(this).removeClass('disabled');
					}
				});
			});
		}

		if ($('.bt-js-open-popup-link').length > 0) {
			$('.bt-js-open-popup-link').magnificPopup({
				type: 'inline',
				midClick: true,
				mainClass: 'mfp-fade'
			});
		}

		$('.bt-copy-btn').on('click', function (e) {
			e.preventDefault();
			var $button = $(this),
				$buttonurl = $(this).closest('form').find('#bt-product-share-url');
			if (navigator.clipboard) {
				navigator.clipboard.writeText($buttonurl.val()).then(() => {
					$buttonurl.select();
					$button.text($button.data('copied'));
					setTimeout(function () {
						$button.text($button.data('copy'))
					}, 1000);
				}, () => {
					return prompt("Copy to clipboard: Ctrl+C, Enter", $buttonurl.value);
				});
			} else {
				$buttonurl.select();
				document.execCommand('copy');
				$button.text($button.data('copied'));
				setTimeout(function () {
					$button.text($button.data('copy'))
				}, 1000);
			}
		});
	}
	/* load Shop Quick View */
	function WoozioLoadShopQuickView() {
		if ($('.bt-quickview-product').length > 0) {
			WoozioImageZoomable();
			WoozioGalleryLightbox();
			WoozioSliderThumbs();
		}
		// check button add to cart 
		if ($('.bt-quickview-product .grouped_form').length > 0) {
			const $addToCartBtn = $('.bt-quickview-product .grouped_form .single_add_to_cart_button');
			$addToCartBtn.addClass('disabled');
		}
		if ($('.quantity input').length > 0) {
			/* Plus Qty */
			$(document).on('click', '.qty-plus', function () {
				var parent = $(this).parent();
				$('input.qty', parent).val(parseInt($('input.qty', parent).val()) + 1);
				$('input.qty', parent).trigger('change');
			});
			/* Minus Qty */
			$(document).on('click', '.qty-minus', function () {
				var parent = $(this).parent();
				if (parseInt($('input.qty', parent).val()) > 1) {
					$('input.qty', parent).val(parseInt($('input.qty', parent).val()) - 1);
					$('input.qty', parent).trigger('change');
				}
			});
		}
	}
	
	/* Validation form comment */
	function WoozioCommentValidation() {
		if ($('#bt_comment_form').length) {
			jQuery('#bt_comment_form').validate({
				rules: {
					author: {
						required: true,
						minlength: 2
					},
					email: {
						required: true,
						email: true
					},
					comment: {
						required: true,
						minlength: 20
					}
				},
				errorElement: "div",
				errorPlacement: function (error, element) {
					element.after(error);
				}
			});
		}
		// Check if the form reviews product
		if ($('#commentform').length) {
			jQuery('#commentform').validate({
				rules: {
					author: {
						required: true,
						minlength: 2
					},
					email: {
						required: true,
						email: true
					},
					comment: {
						required: true,
						minlength: 20
					}
				},
				errorElement: "div",
				errorPlacement: function (error, element) {
					element.after(error);
				}
			});
		}
	}
	/* Product Toast */
	function LoadWoozioProductToast() {
		if (AJ_Options.wishlist_toast || AJ_Options.compare_toast || AJ_Options.cart_toast) {
			$('body').append('<div class="bt-toast"></div>');
		}
	}
	function WoozioshowToast(idproduct, tools = 'wishlist', status = 'add') {
		if ($(window).width() > 1024) { // Only run for screens wider than 1024px
			// ajax load product toast
			var param_ajax = {
				action: 'woozio_load_product_toast',
				idproduct: idproduct,
				status: status,
				tools: tools
			};
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				beforeSend: function () {
				},
				success: function (response) {
					if (response.success) {
						// Append and show new toast
						$('.bt-toast').append(response.data['toast']);
						const $newToast = $('.bt-toast .bt-product-toast').last();
						setTimeout(() => {
							$newToast.addClass('show');
						}, 100);
						// Handle close button click
						$newToast.find('.bt-product-toast--close').on('click', function () {
							removeToast($newToast);
						});
						let toastTimeout;

						function startRemovalTimer($toast) {
							toastTimeout = setTimeout(() => {
								removeToast($toast);
							}, 3000);
						}

						// Handle hover events
						$newToast.hover(
							function () {
								// On mouse enter, clear the timeout
								clearTimeout(toastTimeout);
							},
							function () {
								// On mouse leave, start a new timeout
								startRemovalTimer($(this));
							}
						);

						// Start initial removal timer
						startRemovalTimer($newToast);

						function removeToast($toast) {
							$toast.addClass('remove-visibility');

							// Remove toast element after animation
							setTimeout(() => {
								$toast.addClass('remove-height');
								setTimeout(() => {
									$toast.remove();
								}, 300);
							}, 300);
						}
					}
				},
				error: function (xhr, status, error) {
					console.error('Ajax request failed:', {
						status: status,
						error: error,
						response: xhr.responseText
					});
				}
			});
		}
	}
	/* Product wishlist */
	function WoozioProductWishlist() {
		if ($('.bt-product-wishlist-btn').length > 0) {
			$(document).on('click', '.bt-product-wishlist-btn', function (e) {
				e.preventDefault();

				var post_id = $(this).data('id').toString(),
					wishlist_local = window.localStorage.getItem('productwishlistlocal');
				if (!wishlist_local) {
					window.localStorage.setItem('productwishlistlocal', post_id);
					wishlist_local = window.localStorage.getItem('productwishlistlocal');
					var wishlist_count = wishlist_local ? wishlist_local.split(',').length : 0;
					$('.bt-mini-wishlist .wishlist_total').html(wishlist_count);
					$(this).addClass('loading');
					$(this).removeClass('no-added');
					setTimeout(function () {
						$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').addClass('added');
						$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').removeClass('loading');
						$('.bt-product-wishlist-btn[data-id="' + post_id + '"] .tooltip').text("Remove Wishlist");
					}, 200);
					if (AJ_Options.wishlist_toast) {
						WoozioshowToast(post_id, 'wishlist', 'add');
					}

				} else {
					var wishlist_arr = wishlist_local.split(',');

					if (wishlist_arr.includes(post_id)) {
						var index = wishlist_arr.indexOf(post_id);
						if (index > -1) {
							wishlist_arr.splice(index, 1);
						}
						window.localStorage.setItem('productwishlistlocal', wishlist_arr);
						wishlist_local = window.localStorage.getItem('productwishlistlocal');
						var wishlist_count = wishlist_local ? wishlist_local.split(',').length : 0;
						$('.bt-mini-wishlist .wishlist_total').html(wishlist_count);
						$(this).addClass('loading');
						$(this).removeClass('added');
						setTimeout(function () {
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').addClass('no-added');
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').removeClass('loading');
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"] .tooltip').text("Add To Wishlist");
						}, 200);
						if (AJ_Options.wishlist_toast) {
							WoozioshowToast(post_id, 'wishlist', 'remove');
						}
					} else {
						window.localStorage.setItem('productwishlistlocal', wishlist_local + ',' + post_id);
						wishlist_local = window.localStorage.getItem('productwishlistlocal');
						var wishlist_count = wishlist_local ? wishlist_local.split(',').length : 0;
						$('.bt-mini-wishlist .wishlist_total').html(wishlist_count);
						$(this).addClass('loading');
						$(this).removeClass('no-added');
						setTimeout(function () {
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').addClass('added');
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').removeClass('loading');
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"] .tooltip').text("Remove Wishlist");
						}, 200);
						if (AJ_Options.wishlist_toast) {
							WoozioshowToast(post_id, 'wishlist', 'add');
						}
					}
				}
			});
		}

		if ($('.elementor-widget-bt-product-wishlist').length > 0) {
			$(document).on('click', '.bt-product-remove-wishlist', function (e) {
				e.preventDefault();
				$(this).addClass('deleting');
				var product_id = $(this).data('id').toString(),
					wishlist_str = $('.bt-productwishlistlocal').val(),
					wishlist_arr = wishlist_str.split(','),
					index = wishlist_arr.indexOf(product_id);

				if (index > -1) {
					wishlist_arr.splice(index, 1);
				}

				wishlist_str = wishlist_arr.toString();
				$('.bt-productwishlistlocal').val(wishlist_str);
				window.localStorage.setItem('productwishlistlocal', wishlist_str);
				WoozioShareLocalStorage(wishlist_str);

				$(this).closest('.bt-product-item').remove();
				let currentCount = $('.bt-product-item').length;
				$('.bt-mini-wishlist .wishlist_total').html(currentCount);
				if (currentCount == 0) {
					$('.bt-products-wishlist-form').submit();
				}
			});

			// Ajax wishlist
			$('.bt-products-wishlist-form').submit(function () {

				var param_ajax = {
					action: 'woozio_products_wishlist',
					productwishlist_data: $('.bt-productwishlistlocal').val()
				};

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: AJ_Options.ajax_url,
					data: param_ajax,
					context: this,
					beforeSend: function () {
					},
					success: function (response) {
						if (response.success) {
							$('.bt-product-list').html(response.data['items']).fadeIn('slow');
							$('.bt-mini-wishlist .wishlist_total').html(response.data['count']);
						} else {
							console.log('error');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('The following error occured: ' + textStatus, errorThrown);
					}
				});

				return false;
			});
		}
	}
	/* Product Wishlist Load */
	function WoozioProductWishlistLoad() {
		var wishlist_local = window.localStorage.getItem('productwishlistlocal');
		if (!wishlist_local) {
			return;
		}
		if ($('.elementor-widget-bt-product-wishlist').length > 0) {
			WoozioShareLocalStorage(wishlist_local);
			$('.bt-productwishlistlocal').val(wishlist_local);
			var param_ajax = {
				action: 'woozio_products_wishlist',
				productwishlist_data: wishlist_local
			};

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				context: this,
				beforeSend: function () {
					//$('.bt-table--body').addClass('loading');
					let skeletonHtml = '';
					for (let i = 0; i < 5; i++) {
						skeletonHtml += `
							<div class="bt-table--row bt-product-item bt-skeleton-item">
								<div class="bt-table--col bt-product-remove">
									<div class="bt-skeleton-circle"></div>
								</div>
								<div class="bt-table--col bt-product-thumb">
									<div class="bt-skeleton-image"></div>
								</div>
								<div class="bt-table--col bt-product-title">
									<div class="bt-skeleton-text"></div>
								</div>
								<div class="bt-table--col bt-product-price">
									<div class="bt-skeleton-text"></div>
								</div>
								<div class="bt-table--col bt-product-stock">
									<div class="bt-skeleton-text"></div>
								</div>
								<div class="bt-table--col bt-product-add-to-cart">
									<div class="bt-skeleton-button"></div>
								</div>
							</div>`;
					}
					$('.bt-product-list').html(skeletonHtml);
				},
				success: function (response) {
					if (response.success) {
						$('.bt-product-list').html(response.data['items']).fadeIn('slow');
						$('.bt-mini-wishlist .wishlist_total').html(response.data['count']);
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		}
	}
	/* Product content compare scroll  */
	function WoozioCompareContentScroll() {
		const $compareTable = $('.bt-table--body');
		if ($compareTable.length) {
			let isDown = false;
			let startX, scrollLeft;

			$($compareTable).on("mousedown", function (e) {
				isDown = true;
				startX = e.pageX - $(this).offset().left;
				scrollLeft = $(this).scrollLeft();
				$(this).css({
					"cursor": "grabbing",
				});
			});

			$(document).on("mouseup", function () {
				isDown = false;
				$($compareTable).css({
					"cursor": "grab",
				});
			});

			$($compareTable).on("mousemove", function (e) {
				if (!isDown) return;
				e.preventDefault();
				let x = e.pageX - $(this).offset().left;
				let walk = (x - startX) * 1.5;
				$(this).scrollLeft(scrollLeft - walk);
			});

			// Add momentum scrolling
			$($compareTable).on("mouseleave", function () {
				if (isDown && typeof walk !== 'undefined') {
					let velocity = walk;
					let deceleration = 0.95;
					let momentum = setInterval(function () {
						velocity *= deceleration;
						$($compareTable).scrollLeft($($compareTable).scrollLeft() + velocity);
						if (Math.abs(velocity) < 0.5) {
							clearInterval(momentum);
						}
					}, 10);
				}
			});

			// Prevent text selection on mouseup outside the element
			$(document).on("selectstart", function (e) {
				if (isDown) {
					e.preventDefault();
					return false;
				}
			});
		}
	}
	/* Product compare */
	function WoozioProductCompare() {
		function showComparePopup() {
			$('.bt-popup-compare').addClass('active');
			$('.bt-compare-body').addClass('show');
			$('body').css({
				'overflow': 'hidden',
				'padding-right': `${window.innerWidth - $(window).width()}px` // Prevent layout shift
			}); // Disable body scroll
		}
		function removeComparePopup() {
			$('.bt-compare-body').removeClass('show');
			setTimeout(function () {
				$('.bt-popup-compare').removeClass('active');
			}, 300);
			$('body').css({
				'overflow': 'auto', // Restore body scroll
				'padding-right': '0' // Reset padding-right
			});
		}
		if ($('.bt-product-compare-btn').length > 0) {
			$('body').append('<div class="bt-popup-compare"><div class="bt-compare-overlay"></div><div class="bt-compare-close"></div><div class="bt-compare-body"><div class="bt-loading-wave"></div><div class="bt-compare-load"></div></div></div>').fadeIn('slow');

			$(document).on('click', '.bt-product-compare-btn', function (e) {
				e.preventDefault();
				$(this).find('.tooltip').remove();
				var post_id = $(this).data('id').toString(),
					compare_local = window.localStorage.getItem('productcomparelocal');
				if (!compare_local) {
					window.localStorage.setItem('productcomparelocal', post_id);
					compare_local = window.localStorage.getItem('productcomparelocal');
					$(this).addClass('loading');
					$(this).removeClass('no-added');
					if (AJ_Options.compare_toast) {
						WoozioshowToast(post_id, 'compare', 'add');
					}
				} else {
					var compare_arr = compare_local.split(',');
					if (!compare_arr.includes(post_id)) {
						window.localStorage.setItem('productcomparelocal', compare_local + ',' + post_id);
						compare_local = window.localStorage.getItem('productcomparelocal');
						$(this).addClass('loading');
						$(this).removeClass('no-added');
						if (AJ_Options.compare_toast) {
							WoozioshowToast(post_id, 'compare', 'add');
						}
					}
				}
				var param_ajax = {
					action: 'woozio_products_compare',
					compare_data: compare_local,
				};
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: AJ_Options.ajax_url,
					data: param_ajax,
					beforeSend: function () {

					},
					success: function (response) {
						if (response.success) {
							$('.bt-product-compare-btn[data-id="' + post_id + '"]').removeClass('loading');
							$('.bt-product-compare-btn[data-id="' + post_id + '"]').addClass('added');
							showComparePopup();
							$('.bt-popup-compare .bt-compare-load').html(response.data['product']).fadeIn('slow');
							WoozioCompareContentScroll();
							// close popup quick view
							if ($('.bt-popup-quick-view').hasClass('active')) {
								$('.bt-quick-view-body').removeClass('show');
								setTimeout(function () {
									$('.bt-popup-quick-view').removeClass('active');
								}, 300);
							}
						} else {
							console.log('error');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('The following error occured: ' + textStatus, errorThrown);
					}
				});
			});
			$(document).on('click', '.bt-popup-compare .bt-compare-overlay', function () {
				removeComparePopup();
			});
			$(document).on('click', '.bt-popup-compare .bt-compare-close', function () {
				if (!$('.bt-popup-compare').hasClass('bt-compare-elwwg')) {
					removeComparePopup();
				}
			});
			$(document).on('keydown', function (e) {
				if (e.key === 'Escape') {
					removeComparePopup();
				}
			});
		}
		$(document).on('click', '.bt-product-add-compare .bt-cover-image', function () {
			$('.bt-compare-body').removeClass('show');
			setTimeout(function () {
				if (!$('.bt-popup-compare').hasClass('bt-compare-elwwg')) {
					$('.bt-popup-compare').removeClass('active');
					$('body').css({
						'overflow': 'auto', // Restore body scroll
						'padding-right': '0' // Reset padding-right
					});
				} else {
					window.location.href = AJ_Options.shop;
				}
			}, 300);
		});

		$(document).on('click', '.bt-remove-item', function (e) {
			e.preventDefault();
			var compare_local = window.localStorage.getItem('productcomparelocal');
			var product_id = $(this).data('id').toString(),
				compare_arr = compare_local.split(','),
				index = compare_arr.indexOf(product_id);

			if (index > -1) {
				compare_arr.splice(index, 1);
			}
			window.localStorage.setItem('productcomparelocal', compare_arr);
			compare_local = window.localStorage.getItem('productcomparelocal');
			if ($('.bt-popup-compare').hasClass('bt-compare-elwwg')) {
				WoozioShareLocalStorage(compare_local);
			}
			$('.bt-product-compare-btn[data-id="' + product_id + '"]').addClass('no-added');
			$('.bt-product-compare-btn[data-id="' + product_id + '"]').removeClass('added');
			if (!$('.bt-popup-compare').hasClass('bt-compare-elwwg')) {
				if (!compare_local || compare_local === '') {
					removeComparePopup();
					$('.bt-compare-body').removeClass('loading');
					return;
				}
			}
			$(this).closest('.bt-table--row').remove();
			let itemCompareCount = $('.bt-table-compare .bt-table--row').length;
			if (itemCompareCount == 5) {
				$('.bt-table-compare .bt-table--row.bt-product-add-compare').first().addClass('active');
			} else if (itemCompareCount == 4) {
				$('.bt-table-compare .bt-table--row.bt-product-add-compare').slice(0, 2).addClass('active');
			} else if (itemCompareCount == 3) {
				$('.bt-table-compare .bt-table--row.bt-product-add-compare').slice(0, 3).addClass('active');
			}
		});
	}
	/* Product Compare Load */
	function WoozioProductCompareLoad() {
		var compare_local = window.localStorage.getItem('productcomparelocal');
		if (!compare_local) {
			return;
		}
		if ($('.elementor-widget-bt-product-compare').length > 0) {
			WoozioShareLocalStorage(compare_local);
			var param_ajax = {
				action: 'woozio_products_compare',
				compare_data: compare_local
			};
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				beforeSend: function () {
					$('.bt-compare-body').addClass('loading');
				},
				success: function (response) {
					if (response.success) {
						$('.bt-popup-compare .bt-compare-load').html(response.data['product']).fadeIn('slow');
						WoozioCompareContentScroll();
						$('.bt-compare-body').removeClass('loading');
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		}
	}
	/* Product Quick View */
	function WoozioProductQuickView() {
		if ($('.bt-product-quick-view-btn').length > 0) {
			$('body').append('<div class="bt-popup-quick-view"><div class="bt-quick-view-overlay"></div><div class="bt-quick-view-close"></div><div class="bt-quick-view-body"><div class="bt-quick-view-load"></div></div></div>');
			function showQuickViewPopup() {
				$('.bt-popup-quick-view').addClass('active');
				$('.bt-quick-view-body').addClass('show');
				$('body').css({
					'overflow': 'hidden',
					'padding-right': `${window.innerWidth - $(window).width()}px` // Prevent layout shift
				}); // Disable body scroll
			}
			function removeQuickViewPopup() {
				$('.bt-quick-view-body').removeClass('show');
				setTimeout(function () {
					$('.bt-popup-quick-view').removeClass('active');
				}, 300);
				$('body').css({
					'overflow': 'auto', // Restore body scroll
					'padding-right': '0' // Reset padding-right
				});
			}
			$(document).on('click', '.bt-product-quick-view-btn', function (e) {
				e.preventDefault();
				var productid = $(this).data('id');
				$(this).find('.tooltip').remove();
				$(this).addClass('loading');
				var param_ajax = {
					action: 'woozio_products_quick_view',
					productid: productid,
				};
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: AJ_Options.ajax_url,
					data: param_ajax,
					beforeSend: function () {

					},
					success: function (response) {
						if (response.success) {
							$('.bt-product-quick-view-btn').removeClass('loading');
							showQuickViewPopup();
							$('.bt-popup-quick-view .bt-quick-view-load').html(response.data['product']).fadeIn('slow');
							WoozioLoadShopQuickView();
							WoozioProductButtonStatus();
							if (typeof $.fn.wc_variation_form !== 'undefined') {
								$('.bt-quickview-product .variations_form').wc_variation_form();
							}
						} else {
							console.log('error');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('The following error occured: ' + textStatus, errorThrown);
					}
				});

			});
			$(document).on('click', '.bt-popup-quick-view .bt-quick-view-overlay', function () {
				removeQuickViewPopup();
			});
			$(document).on('click', '.bt-popup-quick-view .bt-quick-view-close', function () {
				removeQuickViewPopup();
			});
			$(document).on('keydown', function (e) {
				if (e.key === 'Escape') {
					removeQuickViewPopup();
				}
			});
		}

	}
	/* load Show filter Tag */
	function WoozioLoadFilterTagProduct() {
		if ($('body').hasClass('archive') && $('body').hasClass('post-type-archive-product')) {
			const url = new URL(window.location.href);
			const params = new URLSearchParams(url.search);
			params.delete('current_page');
			params.delete('sort_order');
			params.delete('view_type');
			params.delete('search_keyword');
			// Clean up URL params by removing empty values
			for (const [key, value] of params.entries()) {
				if (!value) {
					params.delete(key);
				}
			}
			const hasValidParams = params.size > 0;
			if (hasValidParams) {
				const tagsContainer = $('.bt-list-tag-filter').addClass('active');
				tagsContainer.find('.bt-reset-filter-product-btn').removeClass('disable');
				tagsContainer.children().not('.bt-reset-filter-product-btn').remove();

				let minPrice = '', maxPrice = '';
				const svgElement = `<svg class="bt-close" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
					<path d="M12 4L4 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M4 4L12 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
				  </svg>`;
				const svgStar = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
				  <path d="M14.6431 7.17815L11.8306 9.60502L12.6875 13.2344C12.7347 13.4314 12.7226 13.638 12.6525 13.8281C12.5824 14.0182 12.4575 14.1833 12.2937 14.3025C12.1298 14.4217 11.9343 14.4896 11.7319 14.4977C11.5294 14.5059 11.3291 14.4538 11.1562 14.3481L7.99996 12.4056L4.84184 14.3481C4.66898 14.4532 4.4689 14.5048 4.2668 14.4963C4.06469 14.4879 3.8696 14.4199 3.70609 14.3008C3.54257 14.1817 3.41795 14.0169 3.3479 13.8272C3.27786 13.6374 3.26553 13.4312 3.31246 13.2344L4.17246 9.60502L1.35996 7.17815C1.20702 7.04597 1.09641 6.87166 1.04195 6.67699C0.987486 6.48232 0.99158 6.27592 1.05372 6.08356C1.11586 5.89121 1.23329 5.72142 1.39135 5.59541C1.54941 5.4694 1.7411 5.39274 1.94246 5.37502L5.62996 5.07752L7.05246 1.63502C7.12946 1.44741 7.26051 1.28693 7.42894 1.17398C7.59738 1.06104 7.7956 1.00073 7.9984 1.00073C8.2012 1.00073 8.39942 1.06104 8.56785 1.17398C8.73629 1.28693 8.86734 1.44741 8.94434 1.63502L10.3662 5.07752L14.0537 5.37502C14.2555 5.39209 14.4477 5.46831 14.6064 5.59415C14.765 5.71999 14.883 5.88984 14.9455 6.08243C15.008 6.27502 15.0123 6.48178 14.9579 6.6768C14.9034 6.87183 14.7926 7.04644 14.6393 7.17877L14.6431 7.17815Z" fill="currentColor"></path>
				</svg>`;

				params.forEach((value, key) => {
					const tags = value.split(/[,; ]+/); // Split value by comma, semicolon, or space
					tags.forEach(tag => {
						const tagElement = $(`<span class="bt-filter-tag" data-name="${key}" data-slug="${tag.trim()}"></span>`); // Updated to use tag.trim() for data-slug

						if (key == 'product_cat' && tag != '') {
							const matchingLink = $(`.bt-form-field[data-name="${key}"] label`).filter(function () {
								return $(this).data('slug') === tag.trim();
							});
							if (matchingLink.length) {
								const nameTag = matchingLink.text().trim();
								tagElement.text(nameTag).append(svgElement);
							}
							tagsContainer.append(tagElement);
						} else if (key == 'min_price') {
							minPrice = tag.trim();
						} else if (key == 'max_price') {
							maxPrice = tag.trim();
						} else if (key == 'product_rating') {
							const product_rating = tag.trim();
							//	console.log(product_rating);
							tagElement.text(product_rating).append(svgStar).append(svgElement).addClass('bt-rating-tag');;
							tagsContainer.append(tagElement);
						} else {
							const matchingLink = $(`.bt-form-field[data-name="${key}"] .bt-field-list a`).filter(function () {
								return $(this).data('slug') === tag.trim();
							});
							if (matchingLink.length) {
								if (key == 'pa_color') {
									const colortag = matchingLink.prop('outerHTML');
									//	console.log(colortag);
									tagElement.html(colortag).append(svgElement).addClass('bt-color-tag');
								} else {
									const textTag = matchingLink.text().trim();
									const nameTag = textTag.replace(/\(\d+\)$/g, '').trim();
									tagElement.text(nameTag).append(svgElement);
								}
								tagsContainer.append(tagElement);
							} else {
								if (tag != '') {
									const titleCase = tag.split('-')
										.map(word => word.charAt(0).toUpperCase() + word.slice(1))
										.join(' ');
									tagElement.text(titleCase).append(svgElement);
									tagsContainer.append(tagElement);
								}
							}
						}
					});
				});

				if (minPrice && maxPrice) {
					if (minPrice && maxPrice) {
						const currency = $('.bt-field-price .bt-field-min-price .bt-currency').text().trim();
						const priceElement = $(`<span class="bt-filter-tag bt-tag-price" data-name="product_price">${currency}${minPrice} - ${currency}${maxPrice}</span>`).append(svgElement);
						tagsContainer.append(priceElement);
					}
				}
			} else {
				$('.bt-list-tag-filter').removeClass('active');
				$('.bt-list-tag-filter').children().not('.bt-reset-filter-product-btn').remove();
			}
		}
	}
	/* Product Filter */
	function WoozioProductsFilter() {
		if (!$('body').hasClass('post-type-archive-product')) {
			return;
		}

		// Search by keywords
		$('.bt-product-filter-form .bt-field-type-search input').on('keyup', function (e) {
			if (e.key === 'Enter' || e.keyCode === 13) {
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			}
		});

		$('.bt-product-filter-form  .bt-field-type-search a').on('click', function (e) {
			e.preventDefault();
			$('.bt-product-filter-form .bt-product-current-page').val('');
			$('.bt-product-filter-form').submit();
		});

		// View type
		$(document).on('click', '.bt-view-type', function (e) {
			e.preventDefault();

			var view_type = $(this).data('view');

			if ('list' == view_type) {
				$('.bt-product-filter-form .bt-product-view-type').val(view_type);
				$('.bt-product-layout').attr('data-view', view_type);
			} else {
				$('.bt-product-filter-form .bt-product-view-type').val('');
				$('.bt-product-layout').attr('data-view', '');
			}

			$('.bt-view-type').removeClass('active');
			$(this).addClass('active');
			//	$('.bt-product-filter-form').submit();
			var param_str = '',
				param_out = [],
				param_in = $('.bt-product-filter-form').serialize().split('&');

			param_in.forEach(function (param) {
				var param_val = param.split('=')[1];
				if ('' !== param_val) {
					param_out.push(param);
				}
			});

			if (0 < param_out.length) {
				param_str = param_out.join('&');
			}

			if ('' !== param_str) {
				window.history.replaceState(null, null, `?${param_str}`);
				$(this).find('.bt-reset-filter-product-btn').removeClass('disable');
			} else {
				window.history.replaceState(null, null, window.location.pathname);
				$(this).find('.bt-reset-filter-product-btn').addClass('disable');
			}
		});
		//Sort order
		$('.bt-product-sort-block select').select2({
			dropdownParent: $('.bt-product-sort-block'),
			minimumResultsForSearch: Infinity
		});
		$('.bt-product-sort-block select').on('change', function () {
			var sort_val = $(this).val();

			$('.bt-product-filter-form .bt-product-sort-order').val(sort_val);
			$('.bt-product-filter-form').submit();
		});


		// Pagination
		$('.bt-product-pagination-wrap').on('click', '.bt-product-pagination a', function (e) {
			e.preventDefault();

			var current_page = $(this).data('page');

			if (1 < current_page) {
				$('.bt-product-filter-form .bt-product-current-page').val(current_page);
			} else {
				$('.bt-product-filter-form .bt-product-current-page').val('');
			}

			$('.bt-product-filter-form').submit();
		});

		// Filter Slider
		if ($('#bt-price-slider').length > 0) {
			const priceSlider = document.getElementById('bt-price-slider');

			var rangeMin = $('#bt-price-slider').data('range-min'),
				rangeMax = $('#bt-price-slider').data('range-max'),
				startMin = $('#bt-price-slider').data('start-min'),
				startMax = $('#bt-price-slider').data('start-max');
			noUiSlider.create(priceSlider, {
				start: [startMin, startMax],
				connect: true,
				range: {
					'min': rangeMin,
					'max': rangeMax
				},
				step: 1
			});

			const minPriceValue = $('#bt-min-price');
			const maxPriceValue = $('#bt-max-price');
			let timeout;
			priceSlider.noUiSlider.on('change', function (values, handle) {
				minPriceValue.val(parseInt(values[0]));
				maxPriceValue.val(parseInt(values[1]));
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
			minPriceValue.on('input', function () {
				clearTimeout(timeout);
				timeout = setTimeout(function () {
					let minValue = parseInt(minPriceValue.val());
					const maxValue = parseInt(maxPriceValue.val());

					if (!isNaN(minValue)) {
						if (minValue > maxValue) {
							minValue = maxValue;
							minPriceValue.val(minValue);
						} else if (minValue < rangeMin) {
							minValue = rangeMin;
							minPriceValue.val(minValue);
						}
						priceSlider.noUiSlider.set([minValue, null]);
						if (!isNaN(maxValue)) {
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						} else {
							maxPriceValue.val(rangeMax);
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						}
					}
				}, 500);
			});

			maxPriceValue.on('input', function () {
				clearTimeout(timeout);
				timeout = setTimeout(function () {
					const minValue = parseInt(minPriceValue.val());
					let maxValue = parseInt(maxPriceValue.val());

					if (!isNaN(maxValue)) {
						if (maxValue < minValue) {
							maxValue = minValue;
							maxPriceValue.val(maxValue);
						} else if (maxValue > rangeMax) {
							maxValue = rangeMax;
							maxPriceValue.val(maxValue);
						}
						priceSlider.noUiSlider.set([null, maxValue]);
						if (!isNaN(minValue)) {
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						} else {
							minPriceValue.val(rangeMin);
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						}
					}
				}, 500);
			});
		}

		//Filter single tax
		if ($('.bt-field-type-radio').length > 0) {
			$('.bt-field-type-radio input').on('change', function () {
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
		}

		//Filter multiple tax
		if ($('.bt-field-type-multi').length > 0) {
			$('.bt-field-type-multi a').on('click', function (e) {
				e.preventDefault();

				if ($(this).parent().hasClass('checked')) {
					$(this).parent().removeClass('checked');
				} else {
					$(this).parent().addClass('checked');
				}

				var value_arr = [];

				$(this).parents('.bt-form-field').find('.bt-field-item').each(function () {
					if ($(this).hasClass('checked')) {
						value_arr.push($(this).children().data('slug'));
					}
				});
				$(this).parents('.bt-form-field').find('input').val(value_arr.toString());
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
		}
		//Filter rating
		if ($('.bt-field-type-rating ').length > 0) {
			$('.bt-field-type-rating input').on('change', function () {
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
		}
		// Filter Product Tag remove
		$(document).on('click', '.bt-filter-tag .bt-close', function (e) {
			e.preventDefault();
			var tagSlug = $(this).parent().data('slug');
			var tagName = $(this).parent().data('name');
			if (tagName == 'product_cat' || tagName == 'product_rating') {
				$(`.bt-product-filter-form .bt-form-field[data-name="${tagName}"] input[type="radio"]`).prop('checked', false);
			} else {
				if (typeof tagSlug !== 'undefined' && tagSlug !== null && tagSlug !== '') {
					var currentValue = $(`.bt-product-filter-form .bt-form-field[data-name="${tagName}"] input`).val();
					var valuesArray = currentValue.split(',');
					var updatedValues = valuesArray.filter(value => value !== tagSlug);
					$(`.bt-product-filter-form .bt-form-field[data-name="${tagName}"] input`).val(updatedValues.join(','));
					$(`.bt-product-filter-form .bt-form-field[data-name="${tagName}"] .bt-field-item a[data-slug="${tagSlug}"]`).parent().removeClass('checked');
				} else {
					$(`.bt-product-filter-form .bt-form-field[data-name="${tagName}"] input`).val('');
					$(`.bt-product-filter-form .bt-form-field[data-name="${tagName}"] .bt-field-item`).removeClass('checked');
				}
			}
			$('.bt-product-filter-form').submit();
		});
		// Filter reset
		if (window.location.href.includes('?')) {
			$('.bt-product-filter-form .bt-reset-filter-product-btn').removeClass('disable');
		}

		$('.bt-reset-filter-product-btn').on('click', function (e) {
			e.preventDefault();

			if ($(this).hasClass('disable')) {
				return;
			}
			$('.bt-list-tag-filter').removeClass('active');
			$('.bt-list-tag-filter').children().not('.bt-reset-filter-product-btn').remove();
			window.history.replaceState(null, null, window.location.pathname);
			$('.bt-product-filter-form input').not('[type="radio"]').val('');
			$('.bt-product-filter-form input[type="radio"]').prop('checked', false);
			$('.bt-product-filter-form .bt-field-item').removeClass('checked');
			$('.bt-product-filter-form select').select2().val('').trigger('change');
			$(this).addClass('disable')

			$('.bt-product-filter-form').submit();
		});
		// Ajax filter
		$('.bt-product-filter-form').submit(function () {
			var param_str = '',
				param_out = [],
				param_in = $(this).serialize().split('&');

			var param_ajax = {
				action: 'woozio_products_filter',
			};

			param_in.forEach(function (param) {
				var param_key = param.split('=')[0],
					param_val = param.split('=')[1];

				if ('' !== param_val) {
					param_out.push(param);
					param_ajax[param_key] = param_val.replace(/%2C/g, ',');
				}
			});

			if (0 < param_out.length) {
				param_str = param_out.join('&');
			}

			if ('' !== param_str) {
				window.history.replaceState(null, null, `?${param_str}`);
				$(this).find('.bt-reset-filter-product-btn').removeClass('disable');
			} else {
				window.history.replaceState(null, null, window.location.pathname);
				$(this).find('.bt-reset-filter-product-btn').addClass('disable');
			}
			WoozioLoadFilterTagProduct();
			// console.log(param_ajax);

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				context: this,
				beforeSend: function () {
					document.querySelector('.bt-filter-scroll-pos').scrollIntoView({
						behavior: 'smooth'
					});

					// Show loading skeleton for 6 product items
					let skeletonHtml = '';
					for (let i = 0; i < 12; i++) {
						skeletonHtml += `
							<div class="bt-product-skeleton product">
								<div class="bt-skeleton-thumbnail"></div>
								<div class="bt-skeleton-content">
									<div class="bt-skeleton-title"></div>
									<div class="bt-skeleton-price"></div>
									<div class="bt-skeleton-rating"></div>
									<div class="bt-skeleton-description"></div>
									<div class="bt-skeleton-action"></div>
								</div>
							</div>
						`;
					}
					$('.bt-product-layout .woocommerce-loop-products').html(skeletonHtml).fadeIn('fast');
					$('.bt-product-pagination-wrap').fadeOut('fast');
				},
				success: function (response) {
					//console.log(response);
					if (response.success) {

						setTimeout(function () {
							$('.bt-results-count').html(response.data['results']).fadeIn('slow');
							$('.bt-product-results-btn').html(response.data['button-results']).fadeIn('slow');
							$('.bt-product-layout .woocommerce-loop-products').html(response.data['items']).fadeIn('slow');
							$('.bt-product-pagination-wrap').html(response.data['pagination']).fadeIn('slow');
							//	$('.bt-product-layout').removeClass('loading');
							WoozioProductButtonStatus();
						}, 500);
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});

			return false;
		});
	}
	/* Product Button toggle Filter*/
	function WoozioProductFilterToggle() {
		if ($('.bt-product-filter-toggle').length > 0) {
			$('.bt-product-filter-toggle').on('click', function () {
				const $mainContent = $(this).parents('.bt-main-content');
				$mainContent.find('.bt-products-sidebar').addClass('active');
				$mainContent.find('.bt-products-dropdown').toggleClass('active');
				$mainContent.find('.bt-template-nosidebar-dropdown').addClass('active');
			});
			$('.bt-popup-overlay, .bt-form-button .bt-close-btn, .bt-form-button-results .bt-product-results-btn').on('click', function (e) {
				e.preventDefault();
				const $mainContent = $(this).parents('.bt-main-content');
				$mainContent.find('.bt-products-sidebar, .bt-products-dropdown, .bt-template-nosidebar-dropdown').removeClass('active');
			});
		}
	}
	function WoozioAttachTooltip(targetSelector, tooltipText) {
		var timeout;

		function hideTooltip(element) {
			timeout = setTimeout(function () {
				element.find('.tooltip').fadeOut(200, function () {
					$(this).remove();
				});
			}, 200);
		}

		$(document).on('mouseenter', targetSelector, function () {
			clearTimeout(timeout);
			if (!$(this).find('.tooltip').length) {
				var tooltip = $('<span class="tooltip"></span>').text(tooltipText);
				$(this).append(tooltip);
				tooltip.fadeIn(200);
			}
		});

		$(document).on('mouseleave', targetSelector, function () {
			hideTooltip($(this));
		});
	}
	function WoozioAttachTooltips() {
		WoozioAttachTooltip('.bt-product-wishlist-btn.no-added', 'Add to Wishlist');
		WoozioAttachTooltip('.bt-product-wishlist-btn.added', 'Remove Wishlist');
		WoozioAttachTooltip('.bt-product-compare-btn.no-added', 'Add to Compare');
		WoozioAttachTooltip('.bt-product-compare-btn.added', 'View Compare');
		WoozioAttachTooltip('.bt-product-quick-view-btn', 'Quick View');
	}
	function WoozioUpdateMiniCart() {
		var timeout;
		$(document.body).on('change input', 'input.qty', function () {
			if (timeout !== undefined) clearTimeout(timeout);
			timeout = setTimeout(function () {
				$('[name=update_cart]').trigger('click');
			}, 500);
		});
		if (typeof wc_cart_fragments_params !== 'undefined') {
			$('form.woocommerce-cart-form').on('submit', function (event) {
				event.preventDefault();
				var $form = $(this);
				$.ajax({
					url: $form.attr('action'),
					type: 'POST',
					data: $form.serialize(),
					success: function () {
						$.ajax({
							url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
							type: 'POST',
							success: function (response) {
								if (response && response.fragments) {
									$.each(response.fragments, function (key, value) {
										$(key).replaceWith(value);
									});
								}
							},
							error: function () {
								console.error('Failed to update mini cart.');
							}
						});
					},
					error: function () {
						console.error('Failed to submit the cart form.');
					}
				});
			});
		}
	}
	function WoozioProgressCart() {
		if ($('.bt-progress-container-cart').length > 0) {
			let targetWidth = $(".bt-progress-bar").data("width");
			let currentWidth = 0;
			var interval = setInterval(function () {
				if (currentWidth >= targetWidth) {
					clearInterval(interval);
				} else {
					currentWidth++;
					$(".bt-progress-bar").css("width", currentWidth + "%");
				}
			}, 30);
		}
	}
	function WoozioFreeShippingMessage() {
		$.ajax({
			url: AJ_Options.ajax_url,
			type: 'POST',
			data: {
				action: 'woozio_get_free_shipping',
			},
			success: function (response) {
				if (response.success) {
					$(".bt-progress-bar").css("width", response.data['percentage'] + "%");
					$('#bt-free-shipping-message').html(response.data['message']);
				}
			},
		});
	}
	function WoozioCountdownCart() {
		if ($('.bt-time-promotion').length > 0) {
			var countdownElement = $('#countdown');
			var originalTime = countdownElement.data('time');
			var time = originalTime.split(':');
			var minutes = parseInt(time[0], 10);
			var seconds = parseInt(time[1], 10);
			var interval = setInterval(function () {
				if (seconds === 0 && minutes === 0) {
					countdownElement.text("00:00");
					countdownElement.data('time', originalTime);
					time = originalTime.split(':');
					minutes = parseInt(time[0], 10);
					seconds = parseInt(time[1], 10);
				} else {
					if (seconds === 0) {
						minutes--;
						seconds = 59;
					} else {
						seconds--;
					}
					countdownElement.text(formatTime(minutes, seconds));
					countdownElement.data('time', formatTime(minutes, seconds));
				}
			}, 1000);

			function formatTime(minutes, seconds) {
				return (minutes < 10 ? '0' + minutes : minutes) + ':' + (seconds < 10 ? '0' + seconds : seconds);
			}
		}
	}

	function WoozioMegaMenu() {
		/* mega menu add class custom */
		jQuery('.bt-mega-menu-sub').each(function () {
			jQuery(this).parent().parent().addClass('bt-submenu-content');
		});
		/* mega menu fix hover category*/
		if (!$('body').hasClass('elementor-editor-active')) {
			$(document).on({
				mouseenter: function () {
					// Remove no-hover class when hovering in
					$('.bt-megamenu-shop-category').removeClass('bt-no-hover');
				},
				mouseleave: function () {
					// Add no-hover class when hovering out
					$('.bt-megamenu-shop-category').addClass('bt-no-hover');
				}
			}, '.bt-megamenu-shop-category.e-active .e-n-menu-title, .bt-megamenu-shop-category.e-active .e-n-menu-content,.bt-mega-menu.bt-main > .elementor-widget-container > .e-n-menu > .e-n-menu-wrapper > .e-n-menu-heading > li.e-n-menu-item:first-child > .e-n-menu-title');

			$('.bt-mega-menu > .elementor-widget-container > .e-n-menu > .e-n-menu-wrapper > .e-n-menu-heading > li.e-n-menu-item').on('mousemove', function (e) {
				const target = $(e.target);
				if (!target.closest('.bt-mega-menu-submega-content').length && target.closest('.bt-mega-menu-submega').length) {
					$(this).find('.bt-mega-menu-submega').hide();
				}
			});
		}
	}
	function WoozioBuyNow() {
		$(document).on('click', '.bt-button-buy-now a', function (e) {
			e.preventDefault();
			if ($(this).hasClass('disabled')) {
				alert('Please select some product options before adding this product to your cart.');
				return;
			}
			var product_id_grouped = $(this).data('grouped');
			if (product_id_grouped) {
				product_id_grouped = product_id_grouped.toString();
				var param_ajax = {
					action: 'woozio_products_buy_now',
					product_id_grouped: product_id_grouped
				};
			} else {
				var product_id = $(this).data('id').toString();
				var variation_id = $(this).data('variation');
				var quantity = $('input[name="quantity"]').val();
				var param_ajax = {
					action: 'woozio_products_buy_now',
					product_id: product_id,
					quantity: quantity
				};

				// Add variation_id to AJAX data if it exists
				if (variation_id) {
					param_ajax.variation_id = variation_id;
				}
			}
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				beforeSend: function () {

				},
				success: function (response) {
					if (response.success) {
						window.location.href = response.data['redirect_url'];
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		});
	}
	function WoozioReviewPopup() {
		$(document).on('click', '.bt-action-review', function (e) {
			e.preventDefault();
			$('.bt-form-review-popup').addClass('active');
		});

		$(document).on('click', '.bt-form-review-popup .bt-review-overlay, .bt-form-review-popup .bt-review-close', function () {
			$('.bt-form-review-popup').removeClass('active');
		});

		$(document).on('keydown', function (e) {
			if (e.key === 'Escape') {
				$('.bt-form-review-popup').removeClass('active');
			}
		});
	}
	function WoozioAddSelect2GravityForm() {
		$('.gform_wrapper').each(function () {
			const $self = $(this);
			$($self).find('select').each(function () {
				const placeholder = $(this).find('option.gf_placeholder').text();
				const select2Options = {
					dropdownParent: $($self),
					minimumResultsForSearch: Infinity,
				};
				if (placeholder) {
					select2Options.placeholder = placeholder;
				}
				$(this).select2(select2Options);
			});
		});
	}
	function WoozioHookGravityFormEvents() {
		$(document).on('submit', '.gform_wrapper form', function (e) {
			let $form = $(this);
			let $submitButton = $form.find('input[type="submit"], button[type="submit"]');
			$form.addClass('loading');
			$submitButton.prop('disabled', true).addClass('loading');
		});
		$(document).on('gform_post_render', function (event, formId) {
			WoozioAddSelect2GravityForm();
		});
	}
	function WoozioProductButtonStatus() {
		var productCompare = localStorage.getItem('productcomparelocal');
		var productCompareArray = productCompare ? productCompare.split(',') : [];
		var productWishlist = localStorage.getItem('productwishlistlocal');
		var productWishlistArray = productWishlist ? productWishlist.split(',') : [];
		var wishlist_count = productWishlist ? productWishlist.split(',').length : 0;
		$('.bt-mini-wishlist .wishlist_total').html(wishlist_count);
		$('.bt-product-compare-btn').each(function () {
			var productId = $(this).data('id');
			if (productCompareArray.includes(productId.toString())) {
				$(this).addClass('added').removeClass('no-added');
			} else {
				$(this).addClass('no-added').removeClass('added');
			}
		});
		$('.bt-product-wishlist-btn').each(function () {
			var productId = $(this).data('id');
			if (productWishlistArray.includes(productId.toString())) {
				$(this).addClass('added').removeClass('no-added');
			} else {
				$(this).addClass('no-added').removeClass('added');
			}
		});
	}
	/* Copyright Current Year */
	function WoozioCopyrightCurrentYear() {
		var searchTerm = '{Year}',
			replaceWith = new Date().getFullYear();

		$('.bt-elwg-site-copyright').each(function () {
			this.innerHTML = this.innerHTML.replace(searchTerm, replaceWith);
		});
	}
	/* share button wishlist page and compare page */
	function WoozioShareLocalStorage(datashare = []) {
		if (!datashare) {
			const url = new URL(window.location.href);
			url.searchParams.delete('datashare');
			window.history.pushState(null, '', url.toString());
			return;
		}
		const url = new URL(window.location.href);
		url.searchParams.set('datashare', datashare);
		window.history.pushState(null, '', url.toString());
		$('.bt-post-share a').each(function () {
			var currentHref = $(this).attr('href');
			// Handle both Facebook and Pinterest share links
			if (currentHref.includes('facebook.com/sharer')) {
				var newHref = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href);
			} else if (currentHref.includes('pinterest.com/pin')) {
				var newHref = 'https://pinterest.com/pin/create/button/?url=' + encodeURIComponent(window.location.href);
			} else {
				var newHref = currentHref.replace(/url=[^&]+/, 'url=' + encodeURIComponent(window.location.href));
			}
			$(this).attr('href', newHref);
		});
	}
	/* backtotop */
	function WoozioBackToTop() {
		const $backToTop = $('.bt-back-to-top');
		if ($backToTop.length > 0) {
			$(window).on('scroll', function () {
				if ($(this).scrollTop() > 300) {
					$backToTop.addClass('show');
				} else {
					$backToTop.removeClass('show');
				}
			});

			$backToTop.on('click', function (e) {
				e.preventDefault();
				$('html, body').animate({ scrollTop: 0 }, 500);
			});
		}
	}
	/* addToRecentlyViewed */
	function WoozioAddToRecentlyViewed(productId) {
		// Check if not single product page, return early
		if (!$('body').hasClass('single-product')) {
			return;
		}
		const MAX_PRODUCTS = 5;
		// Get product ID from URL
		const bodyClasses = document.body.className;
		const match = bodyClasses.match(/postid-(\d+)/);

		if (match && match[1]) {
			var productId = parseInt(match[1]);
		}
		if (productId) {
			var recentlyViewed = localStorage.getItem('recentlyViewed') || '[]';
			recentlyViewed = JSON.parse(recentlyViewed);
			// Remove the product if it's already in the list
			recentlyViewed = recentlyViewed.filter(id => id !== productId);
			// Add the product to the beginning of the list
			recentlyViewed.unshift(productId);
			// Limit the list to the last 5 products
			if (recentlyViewed.length > MAX_PRODUCTS) {
				recentlyViewed = recentlyViewed.slice(0, MAX_PRODUCTS);
			}
			localStorage.setItem('recentlyViewed', JSON.stringify(recentlyViewed));
		}
	}
	/* loadRecentlyViewedProducts */
	function WoozioLoadRecentlyViewedProducts() {
		const recentlyViewed = localStorage.getItem('recentlyViewed') || '[]';
		const parsedRecentlyViewed = JSON.parse(recentlyViewed);
		$('.bt-related-tab-heading .bt-tab-title').addClass('bt-heading-related');
		if (parsedRecentlyViewed.length > 1) {
			$('.bt-related-tab-heading .bt-tab-title').removeClass('bt-heading-related');
			$('.bt-related-tab-heading .bt-tab-title.recently-viewed').show();

			$.ajax({
				url: AJ_Options.ajax_url,
				type: 'POST',
				data: {
					action: 'load_recently_viewed',
					recently_viewed: parsedRecentlyViewed
				},
				success: function (response) {
					if (response.success) {
						$('.recently-viewed-products').html(response.data);
					}
				}
			})
			const $tabTitles = $('.bt-tab-title');
			const $tabPanes = $('.bt-tab-pane');

			// Tab switching functionality
			$tabTitles.on('click', function () {
				const $this = $(this);
				const tabId = $this.data('tab');

				// Update active states
				$tabTitles.removeClass('active');
				$this.addClass('active');

				$tabPanes.removeClass('active');
				$(`[data-tab-content="${tabId}"]`).addClass('active');
			});

		}
	}
	/* Countdown product sale  */
	function WoozioCountdownProductSale() {
		if ($('.bt-countdown-product-sale').length > 0) {
			var idproduct = $('.bt-countdown-product-sale .bt-countdown-product-js').data('idproduct');
			const countDown = $('.bt-countdown-product-sale .bt-countdown-product-js[data-idproduct="' + idproduct + '"]');

			const countDownDate = new Date(countDown.data('time')).getTime();
			//	console.log(countDownDate);
			if (isNaN(countDownDate)) {
				console.error('Invalid countdown date');
				return;
			}
			const timer = setInterval(() => {
				const now = new Date().getTime();
				const distance = countDownDate - now;

				if (distance < 0) {
					clearInterval(timer);
					countDown.html('<div class="bt-countdown-expired">EXPIRED</div>');
					window.location.reload();
					return;
				}

				const days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
				const hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
				const mins = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
				const secs = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');

				countDown.find('.bt-countdown-days').text(days);
				countDown.find('.bt-countdown-hours').text(hours);
				countDown.find('.bt-countdown-mins').text(mins);
				countDown.find('.bt-countdown-secs').text(secs);
			}, 1000);
			// progress bar countdown product sale
			let progressWidth = $(".bt-progress-bar-sold").data("width");
			let currentWidth = 0;
			var interval = setInterval(function () {
				if (currentWidth >= progressWidth) {
					clearInterval(interval);
				} else {
					currentWidth++;
					$(".bt-progress-bar-sold").css("width", currentWidth + "%");
				}
			}, 30);
		}
	}
	// js Customize WooCommerce product toggle on single product page
	function WoozioCustomizeProductToggle() {
		// Check if the product toggle exist
		if ($('.bt-product-toggle-js').length > 0) {
			$('.bt-product-toggle-js .bt-item-title').on('click', function (e) {
				e.preventDefault();
				if ($(this).hasClass('active')) {
					$(this).parent().find('.bt-item-content').slideUp();
					$(this).removeClass('active');
				} else {
					$('.bt-product-toggle-js .bt-item-content').slideUp();
					$('.bt-product-toggle-js .bt-item-title').removeClass('active');
					$(this).parent().find('.bt-item-content').slideDown();
					$(this).addClass('active');
				}
			});
		}
	}
	// checkbox customize grouped product
	function WoozioCustomizeGroupedProduct() {
		// reset input quantity value
		$('.bt-product-grouped-js .quantity input[type="number"]').val(0);
		// check button add to cart 
		const $addToCartBtn = $('.grouped_form .single_add_to_cart_button');
		$addToCartBtn.addClass('disabled');
		$(document).on('click', '.grouped_form .single_add_to_cart_button.disabled', function (e) {
			e.preventDefault();
			alert('Please select some product options before adding this product to your cart.');
			return;
		});
		// Check if the checkbox is checked
		$(document).on('change', '.bt-product-grouped-js input[type="checkbox"]', function () {
			var parent = $(this).parents('.woocommerce-grouped-product-list-item');
			var quantityInput = parent.find('.quantity input');
			var currentQuantity = parseInt(quantityInput.val()) || 0;
			if ($(this).is(':checked')) {
				if (currentQuantity === 0) {
					quantityInput.val(1).trigger('change');
				}
			} else {
				quantityInput.val(0).trigger('change');
			}
		});
		$(document).on('change', '.bt-product-grouped-js .quantity input', function () {
			var parent = $(this).parents('.woocommerce-grouped-product-list-item');
			var checkbox = parent.find('input[type="checkbox"]');
			var quantity = parseInt($(this).val()) || 0;

			if (quantity > 0) {
				checkbox.prop('checked', true);
			} else {
				checkbox.prop('checked', false);
			}
			// Get all checked checkboxes and store their values
			// Get checked products and their quantities
			var productGrouped = [];
			let totalPrice = 0;
			let regularTotalPrice = 0;
			let currencySymbol = '$';
			$('.bt-product-grouped-js input[type="checkbox"]:checked').each(function () {
				const $checkbox = $(this);
				const $item = $checkbox.closest('.woocommerce-grouped-product-list-item');
				const $quantity = $item.find('.quantity input');

				// Add product to grouped array
				productGrouped.push($checkbox.val() + ':' + $quantity.val());

				// Calculate total price of checked products
				currencySymbol = $item.find('.woocommerce-Price-currencySymbol').first().text() || '$';
				const $priceElement = $item.find('.woocommerce-Price-amount');
				let price;

				// Get regular price
				const regularPrice = parseFloat($priceElement.first().text().replace(/[^0-9.-]+/g, ''));

				// Get sale price if exists
				if ($item.find('ins').length) {
					price = parseFloat($item.find('ins .woocommerce-Price-amount').text().replace(/[^0-9.-]+/g, ''));
				} else {
					price = regularPrice;
				}

				const quantity = parseInt($quantity.val()) || 0;
				totalPrice += price * quantity;
				regularTotalPrice += regularPrice * quantity;
			});

			// Update price display
			if (totalPrice < regularTotalPrice) {
				$('.bt-price').html(`<del>${currencySymbol}${regularTotalPrice.toFixed(2)}</del> ${currencySymbol}${totalPrice.toFixed(2)}`);
			} else {
				$('.bt-price').text(currencySymbol + totalPrice.toFixed(2));
			}
			// Update buy now button state

			const $buyNowBtn = $('.bt-button-buy-now a');

			if (productGrouped.length) {
				$buyNowBtn
					.attr('data-grouped', productGrouped.join(','))
					.removeClass('disabled');
				$addToCartBtn.removeClass('disabled');
				$('.bt-quickview-product .grouped_form .single_add_to_cart_button').removeClass('disabled');
				$('.bt-total-price ').addClass('active');
			} else {
				$buyNowBtn.addClass('disabled');
				$addToCartBtn.addClass('disabled');
				$('.bt-quickview-product .grouped_form .single_add_to_cart_button').addClass('disabled');
				$('.bt-total-price ').removeClass('active');

			}
		});
	}

	/* popup newsletter */
	function WoozioPopupNewsletter() {
		// Check if newsletter popup exists
		if ($('#bt-newsletter-popup').length > 0) {
			if ($('.bt-thankyou-newsletter').length) {
				// Check if URL has confirmed parameter
				const urlParams = new URLSearchParams(window.location.search);
				if (urlParams.get('nm') === 'confirmed') {
					localStorage.setItem('newsletterpopup', 'true');
				}
				return;
			}
			const newsletter_local = window.localStorage.getItem('newsletterpopup');

			if (!newsletter_local) {
				const popup = $('#bt-newsletter-popup');
				let hasShown = false;

				// Show popup when scrolling down 300px
				$(window).scroll(function () {
					if (!hasShown && $(this).scrollTop() > 300) {
						hasShown = true;
						const scrollbarWidth = window.innerWidth - $(window).width();

						popup.fadeIn();
						$('body').css({
							'overflow': 'hidden',
							'padding-right': scrollbarWidth + 'px' // Prevent layout shift
						});
					}
				});

				// Helper function to close popup
				const closePopup = () => {
					popup.fadeOut();
					localStorage.setItem('newsletterpopup', 'true');
					$('body').css({
						'overflow': 'auto',
						'padding-right': '0'
					});
				};

				// Close popup events
				$(document).on('click', '#bt-newsletter-popup .bt-close-popup', closePopup);
				$(document).on('click', '#bt-newsletter-popup .bt-newsletter-overlay', closePopup);
				$(document).on('keydown', function (e) {
					if (e.key === 'Escape') {
						closePopup();
					}
				});
			}
		}
	}
	function WoozioProductColorVariationsLoadImage() {
		if ($('.bt-product-add-to-cart-variable').length > 0) {
			$(document).on('click', '.bt-product-add-to-cart-variable .bt-value-color .bt-item-color', function (e) {
				var valueColor = $(this).data('value');
				const productContainer = $(this).closest('.woocommerce-loop-product');
				const colorVariationsContainer = $(this).closest('.bt-product-add-to-cart-variable');
				const colorVariations = colorVariationsContainer.data('color-variations');
				
				// Check if colorVariations exists and is an object
				if (colorVariations && typeof colorVariations === 'object') {
					// Find the matching color variation
					const matchingColor = Object.keys(colorVariations).find(colorSlug => {
						return colorSlug === valueColor;
					});

					if (matchingColor && colorVariations[matchingColor]) {
						const colorData = colorVariations[matchingColor];
						productContainer.find('.bt-product-images-wrapper').html(colorData.variable_image_html);

					} else {
						console.log('No matching color found for:', valueColor);
					}
				} else {
					console.log('Color variations data not found or invalid');
				}
			});
		}
	}
	/* Get width body */
	function WoozioUpdateBodyWidthVariable() {
		var widthBody = $(window).width();
		$('.bt-col-container-left').css('--width-body', widthBody + 'px');
		$('.bt-col-container-right').css('--width-body', widthBody + 'px');
	}
	/* add to cart ajax product variable */
	function WoozioAddToCartVariable() {
		$(document).on('click', '.bt-js-add-to-cart-variable', function (e) {
			e.preventDefault();
			
			var $button = $(this);
			var $form = $button.closest('.variations_form');
			
			if ($button.hasClass('disabled')) {
				alert('Please select some product options before adding this product to your cart.');
				return;
			}
			
			$button.addClass('loading');
			
			// Get the latest values from the variations form
			var product_id = $button.data('product-id').toString();
			var variation_id = $form.find('input.variation_id').val();
			var quantity = $form.find('.quantity .qty').val() || 1;

			var param_ajax = {
				action: 'woozio_products_add_to_cart_variable',
				product_id: product_id,
				quantity: quantity
			};

			// Add variation_id to AJAX data if it exists
			if (variation_id) {
				param_ajax.variation_id = variation_id;
			}
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				beforeSend: function () {

				},
				success: function (response) {
					if (response.success) {
						$('.bt-js-add-to-cart-variable').removeClass('loading');
						if (variation_id) {
							WoozioshowToast(variation_id, 'cart', 'add');
						}else{
							WoozioshowToast(product_id, 'cart', 'add');
						}
						// Update mini cart after successful add to cart
						$.ajax({
							url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
							type: 'POST',
							success: function (response) {
								if (response && response.fragments) {
									$.each(response.fragments, function (key, value) {
										$(key).replaceWith(value);
									});
								}
							},
							error: function () {
								console.error('Failed to update mini cart.');
							}
						});
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		});
	}
	jQuery(document).ready(function ($) {
		WoozioSubmenuAuto();
		WoozioToggleMenuMobile();
		WoozioToggleSubMenuMobile();
		WoozioShop();
		WoozioCommentValidation();
		LoadWoozioProductToast();
		WoozioProductCompare();
		WoozioProductCompareLoad();
		WoozioProductWishlist();
		WoozioProductWishlistLoad();
		WoozioProductQuickView();
		WoozioProductsFilter();
		WoozioProductFilterToggle();
		WoozioAttachTooltips();
		WoozioUpdateMiniCart();
		WoozioProgressCart();
		WoozioCountdownCart();
		WoozioMegaMenu();
		WoozioBuyNow();
		WoozioReviewPopup();
		WoozioHookGravityFormEvents();
		WoozioProductButtonStatus();
		WoozioCopyrightCurrentYear();
		WoozioCompareContentScroll();
		WoozioBackToTop();
		WoozioLoadFilterTagProduct();
		WoozioAddToRecentlyViewed();
		WoozioLoadRecentlyViewedProducts();
		WoozioCountdownProductSale();
		WoozioCustomizeProductToggle();
		WoozioCustomizeGroupedProduct();
		WoozioPopupNewsletter();
		WoozioProductColorVariationsLoadImage();
		WoozioUpdateBodyWidthVariable();
		WoozioAddToCartVariable();
	});
	$(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button) {
		// Only show toast if not in Elementor editor
		WoozioFreeShippingMessage();
		if (!$('body').hasClass('elementor-editor-active')) {
			// Get product ID from button that triggered the event
			var productId = null;
			if ($button && $button.data('product_id')) {
				productId = $button.data('product_id');
			}
			if (productId) {
				if (AJ_Options.cart_toast) {
					WoozioshowToast(productId, 'cart', 'add');
				}
			}
		}
	});
	$(document).on('removed_from_cart', function () {
		WoozioFreeShippingMessage();
	});

	jQuery(window).on('resize', function () {
		WoozioSubmenuAuto();
		WoozioUpdateBodyWidthVariable();
	});
	$(document.body).on('updated_cart_totals', function () {
		WoozioFreeShippingMessage();
	});
	/* Check if cart is empty */
	$(document.body).on('wc_fragments_loaded wc_fragments_refreshed', function () {
		const cartCount = parseInt($('.bt-mini-cart .cart_total').text());
		if (cartCount === 0) {
			$(".bt-mini-cart-sidebar .bt-progress-content").addClass("bt-hide");
		} else {
			$(".bt-mini-cart-sidebar .bt-progress-content").removeClass("bt-hide");
		}
	});
	jQuery(window).on('scroll', function () {

	});
})(jQuery);
