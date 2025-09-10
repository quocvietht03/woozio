(function ($) {
	/**
	   * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	**/

	/* location list toggle */
	var LocationListHandler = function ($scope, $) {
		var buttonMore = $scope.find('.bt-more-info');
		var contentList = $scope.find('.bt-location-list--content');
		if (buttonMore.length > 0) {
			buttonMore.on('click', function (e) {
				e.preventDefault();
				if ($(this).hasClass('active')) {
					$(this).parent().find('.bt-location-list--content').slideUp();
					$(this).removeClass('active');
					$(this).children('span').text('More Information');
				} else {
					contentList.slideUp();
					buttonMore.children('span').text('More Information');
					buttonMore.removeClass('active');
					$(this).parent().find('.bt-location-list--content').slideDown();
					$(this).addClass('active');
					$(this).children('span').text('Less Information');
				}
			});
		}
	};
	var FaqHandler = function ($scope, $) {
		const $titleFaq = $scope.find('.bt-item-title');
		if ($titleFaq.length > 0) {
			$titleFaq.on('click', function (e) {
				e.preventDefault();
				if ($(this).hasClass('active')) {
					$(this).parent().find('.bt-item-content').slideUp();
					$(this).removeClass('active');
				} else {
					$(this).parent().find('.bt-item-content').slideDown();
					$(this).addClass('active');
				}
			});
		}
	};
	var SearchProductHandler = function ($scope, $) {
		const $searchProduct = $scope.find('.bt-elwg-search-product');
		if ($searchProduct.length) {
			const $selectedCategory = $searchProduct.find('.bt-selected-category');
			const $categoryList = $searchProduct.find('.bt-category-list');
			const $categoryItems = $searchProduct.find('.bt-category-item');
			const $catProductInput = $searchProduct.find('input[name="product_cat"]');

			$selectedCategory.on('click', function (e) {
				e.stopPropagation();
				$categoryList.toggle();
			});

			// Handle category selection
			$categoryItems.on('click', function (e) {
				e.preventDefault();
				const $this = $(this);
				const selectedText = $this.text();
				const catSlug = $this.data('cat-slug');

				// Update selected category
				$selectedCategory.find('span').text(selectedText);
				$catProductInput.val(catSlug);

				// Update active class
				$categoryItems.removeClass('active');
				$this.addClass('active');

				// Hide dropdown
				$categoryList.hide();
			});

			// Close dropdown when clicking outside
			$(document).on('click', function () {
				$categoryList.hide();
			});
			// scroll window remove class active live search results
			$(window).scroll(function () {
				$liveSearchResults.removeClass('active');
			});
			const $liveSearch = $searchProduct.find('.bt-live-search');
			const $liveSearchResults = $searchProduct.find('.bt-live-search-results');
			const $dataSearch = $searchProduct.find('.bt-live-search-results .bt-load-data');
			let typingTimer;
			const doneTypingInterval = 500; // 0.5 second delay after typing stops

			const performSearch = function () {
				const searchTerm = $liveSearch.val().trim();
				if (searchTerm.length >= 2) {
					var param_ajax = {
						action: 'woozio_search_live',
						search_term: searchTerm,
						category_slug: $catProductInput.val()
					};
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: AJ_Options.ajax_url,
						data: param_ajax,
						context: this,
						beforeSend: function () {
							$liveSearchResults.addClass('active');
							//	$liveSearchResults.addClass('loading');
							// Show loading skeleton for 3 product items
							let skeletonHtml = '';
							for (let i = 0; i < 3; i++) {
								skeletonHtml += `
							<div class="bt-product-skeleton product">
								<div class="bt-skeleton-thumb">
									<div class="bt-skeleton-image"></div>
									<div class="bt-skeleton-content">
										<div class="bt-skeleton-title"></div>
										<div class="bt-skeleton-price"></div>
									</div>
								</div>
								<div class="bt-skeleton-add-to-cart"></div>
							</div>
						`;
							}
							$dataSearch.html(skeletonHtml);
						},
						success: function (response) {
							if (response.success) {
								setTimeout(function () {
									$dataSearch.html(response.data['items']);
								}, 300);
							} else {
								console.log('error');
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							console.log('The following error occured: ' + textStatus, errorThrown);
						}
					});
					return false;
				} else {
					$dataSearch.empty();
				}
			};

			$liveSearch.on('input', function () {
				clearTimeout(typingTimer);
				const searchTerm = $(this).val().trim();
				if (searchTerm.length >= 2) {
					typingTimer = setTimeout(performSearch, doneTypingInterval);
				} else {
					$dataSearch.empty();
					$liveSearchResults.removeClass('active');
				}
			});
			$liveSearch.on('click', function () {
				const searchTerm = $(this).val().trim();
				if (searchTerm.length >= 2) {
					$liveSearchResults.addClass('active');
					if (window.location.href.includes('search_keyword')) {
						performSearch();
					}
				}
			});
			$categoryItems.on('click', function (e) {
				e.preventDefault();
				const searchTerm = $liveSearch.val().trim();
				// If search term exists, perform search
				if (searchTerm.length >= 2) {
					performSearch();
				} else {
					$liveSearchResults.removeClass('active');
				}
			});

			// Hide results when clicking outside
			$(document).on('click', function (e) {
				if (!$(e.target).closest('.bt-live-search-results').length &&
					!$(e.target).is('.bt-live-search') &&
					!$(e.target).closest('.bt-category-item').length) {
					$liveSearchResults.removeClass('active');
				}
			});
		}
	};
	function WoozioAnimateText(selector, delayFactor = 50) {
		const $text = $(selector);
		const textContent = $text.text();
		$text.empty();

		let letterIndex = 0;

		textContent.split(" ").forEach((word) => {
			const $wordSpan = $("<span>").addClass("bt-word");

			word.split("").forEach((char) => {
				const $charSpan = $("<span>").addClass("bt-letter").text(char);
				$charSpan.css("animation-delay", `${letterIndex * delayFactor}ms`);
				$wordSpan.append($charSpan);
				letterIndex++;
			});

			$text.append($wordSpan).append(" ");
		});
	}
	function headingAnimationHandler($scope) {
		var headingAnimationContainer = $scope.find('.bt-elwg-heading-animation');
		var animationElement = headingAnimationContainer.find('.bt-heading-animation-js');
		var animationClass = headingAnimationContainer.data('animation');
		var animationDelay = headingAnimationContainer.data('delay');

		if (animationClass === 'none') {
			return;
		}
		function checkIfElementInView() {
			const windowHeight = $(window).height();
			const elementOffsetTop = animationElement.offset().top;
			const elementOffsetBottom = elementOffsetTop + animationElement.outerHeight();

			const isElementInView =
				elementOffsetTop < $(window).scrollTop() + windowHeight &&
				elementOffsetBottom > $(window).scrollTop();

			if (isElementInView) {
				if (!animationElement.hasClass('bt-animated')) {
					animationElement
						.addClass('bt-animated')
						.addClass(animationClass);
					WoozioAnimateText(animationElement, animationDelay);
				}
			}
		}
		jQuery(window).on('scroll', function () {
			checkIfElementInView();
		});
		jQuery(document).ready(function () {
			checkIfElementInView();
		});
	}

	var TiktokShopSliderHandler = function ($scope, $) {
		const $tiktokSlider = $scope.find('.bt-elwg-tiktok-shop-slider--default');
		// Get Elementor breakpoints
		const $sliderSettings = $tiktokSlider.data('slider-settings');
		//	console.log($sliderSettings.breakpoints);
		if ($tiktokSlider.length > 0) {

			const $swiper = new Swiper($tiktokSlider[0], {
				slidesPerView: $sliderSettings.slidesPerView,
				loop: $sliderSettings.loop,
				spaceBetween: $sliderSettings.spaceBetween,
				speed: $sliderSettings.speed,
				pagination: {
					el: $tiktokSlider.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				autoplay: $sliderSettings.autoplay ? {
					delay: 3000,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $tiktokSlider.find('.bt-button-next')[0],
					prevEl: $tiktokSlider.find('.bt-button-prev')[0],
				},
				breakpoints: $sliderSettings.breakpoints,
			});

			if ($sliderSettings.autoplay) {
				$tiktokSlider[0].addEventListener('mouseenter', () => {
					$swiper.autoplay.stop();
				});
				$tiktokSlider[0].addEventListener('mouseleave', () => {
					$swiper.autoplay.start();
				});
			}
			// tiktok popup video
			$tiktokSlider.find('.js-open-popup').magnificPopup({
				type: 'inline',
				preloader: false,
				removalDelay: 300,
				mainClass: 'mfp-fade',
				callbacks: {
					beforeOpen: function () {
						this.st.mainClass = this.st.el.attr('data-effect');
					},
					open: function () {
						// Initialize video elements when popup opens
						const videoPopup = this.content.find('.bt-video-wrap');
						const videoElement = videoPopup.find('video');

						if (videoElement.length > 0) {
							// Handle uploaded video
							videoElement[0].play();
						}
					},
					close: function () {
						// Pause video when popup closes
						const videoElement = this.content.find('video');
						if (videoElement.length > 0) {
							videoElement[0].pause();
							videoElement[0].currentTime = 0;
						}
					}
				}
			});
		}
	};
	function WoozioshowToast(idproduct, tools = 'cart', status = 'add') {
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
	const ProductTooltipHotspotHandler = function ($scope) {
		const $HotspotProduct = $scope.find('.bt-elwg-hotspot-product--default');
		if ($HotspotProduct.length > 0) {
			function getPositionPoint($point) {
				const pointLeft = $point.position().left;
				const pointTop = $point.position().top;
				const pointRight = $point.parent().width() - (pointLeft + $point.outerWidth());
				const pointBottom = $point.parent().height() - (pointTop + $point.outerHeight());
				const $info = $point.find('.bt-hotspot-product-info');
				const infoWidth = $info.outerWidth();
				const halfWidth = infoWidth / 2 - (window.innerWidth <= 600 ? 6 : 10);
				const infoHeight = $info.outerHeight();
				const halfHeight = infoHeight / 2 - (window.innerWidth <= 600 ? 6 : 10);
				const maxPoint = Math.max(pointLeft, pointTop, pointRight, pointBottom);
				if (maxPoint === pointRight) {
					if (infoWidth > pointRight) {
						const maxHigh = Math.max(pointTop, pointBottom);
						if (maxHigh === pointTop) {
							return 'topcenter';
						} else {
							return 'bottomcenter';
						}
					} else {
						if (halfHeight < pointTop && halfHeight < pointBottom) {
							return 'rightcenter';
						} else if (infoHeight < pointTop) {
							return 'righttop';
						} else {
							return 'rightbottom';
						}
					}
				} else if (maxPoint === pointLeft) {
					if (infoWidth > pointLeft) {
						const maxHigh = Math.max(pointTop, pointBottom);
						if (maxHigh === pointTop) {
							return 'topcenter';
						} else {
							return 'bottomcenter';
						}
					} else {
						if (halfHeight < pointTop && halfHeight < pointBottom) {
							return 'leftcenter';
						} else if (infoHeight < pointTop) {
							return 'lefttop';
						} else {
							return 'leftbottom';
						}
					}
				} else if (maxPoint === pointTop) {
					if (halfWidth < pointLeft && halfWidth < pointRight) {
						return 'topcenter';
					} else if (infoWidth < pointLeft) {
						return 'topleft';
					} else {
						return 'topright';
					}
				} else if (maxPoint === pointBottom) {
					if (halfWidth < pointLeft && halfWidth < pointRight) {
						return 'bottomcenter';
					} else if (infoWidth < pointLeft) {
						return 'bottomleft';
					} else {
						return 'bottomright';
					}
				}
			}
			function hotspotPoint() {
				$HotspotProduct.find('.bt-hotspot-point').each(function () {
					const $point = $(this);
					const $positionPoin = getPositionPoint($point);
					const $info = $point.find('.bt-hotspot-product-info');
					const containerWidth = $point.parent().width();
					let smallOffset = 5;
					let largeOffset = 15;
					if (containerWidth < 700) {
						smallOffset = 2;
						largeOffset = 8;
					}
					if ($positionPoin == 'rightcenter') {
						$info.css({
							'inset': 'auto auto auto 100%',
							'transform': `translateX(${largeOffset}px)`
						});
					} else if ($positionPoin == 'righttop') {
						$info.css({
							'inset': 'auto auto 100% 100%',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'rightbottom') {
						$info.css({
							'inset': '100% auto auto 100%',
							'transform': `translate(0, ${smallOffset}px)`
						});
					} else if ($positionPoin == 'leftcenter') {
						$info.css({
							'inset': 'auto 100% auto auto',
							'transform': `translateX(-${largeOffset}px)`
						});
					} else if ($positionPoin == 'lefttop') {
						$info.css({
							'inset': 'auto 100% 100% auto',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'leftbottom') {
						$info.css({
							'inset': '100% 100% auto auto',
							'transform': `translate(0, ${smallOffset}px)`
						});
					} else if ($positionPoin == 'topcenter') {
						$info.css({
							'inset': 'auto auto 100% auto',
							'transform': `translateY(-${largeOffset}px)`
						});
					} else if ($positionPoin == 'topleft') {
						$info.css({
							'inset': 'auto 100% 100% auto',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'topright') {
						$info.css({
							'inset': 'auto auto 100% 100%',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'bottomcenter') {
						$info.css({
							'inset': '100% auto auto auto',
							'transform': `translateY(${largeOffset}px)`
						});
					} else if ($positionPoin == 'bottomleft') {
						$info.css({
							'inset': '100% 100% auto auto',
							'transform': `translate(0, ${smallOffset}px)`
						});
					} else if ($positionPoin == 'bottomright') {
						$info.css({
							'inset': '100% auto auto 100%',
							'transform': `translate(0, ${smallOffset}px)`
						});
					}
				});
			}
			hotspotPoint();
			$(window).on('resize', function () {
				hotspotPoint();
			});
		}
		// slider hotspot
		const $Hotspotslider = $HotspotProduct.find('.bt-hotspot-slider');

		if ($Hotspotslider.length > 0) {
			const $sliderSettings = $Hotspotslider.data('slider-settings');
			const $Hotspotwrap = $Hotspotslider.find('.bt-hotspot-slider--inner');
			const $swiper = new Swiper($Hotspotwrap[0], {
				slidesPerView: 1,
				loop: $sliderSettings.loop || false,
				spaceBetween: $sliderSettings.spaceBetween.mobile,
				speed: $sliderSettings.speed,
				pagination: {
					el: $Hotspotslider.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				autoplay: $sliderSettings.autoplay ? {
					delay: 3000,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $Hotspotslider.find('.bt-button-next')[0],
					prevEl: $Hotspotslider.find('.bt-button-prev')[0],
				},

				breakpoints: {
					1560: {
						slidesPerView: 3,
						spaceBetween: $sliderSettings.spaceBetween.desktop
					},
					1025: {
						slidesPerView: 2,
						spaceBetween: $sliderSettings.spaceBetween.desktop
					},
					767: {
						slidesPerView: 3,
						spaceBetween: $sliderSettings.spaceBetween.tablet
					},
					500: {
						slidesPerView: 2,
						spaceBetween: $sliderSettings.spaceBetween.tablet
					},
				},
			});

			if ($sliderSettings.autoplay) {
				$Hotspotwrap[0].addEventListener('mouseenter', () => {
					$swiper.autoplay.stop();
				});
				$Hotspotwrap[0].addEventListener('mouseleave', () => {
					$swiper.autoplay.start();
				});
			}
		}
		// Add set to cart ajax 
		const $buttonAddToCart = $HotspotProduct.find('.bt-add-to-cart-wrapper');
		if ($buttonAddToCart.length > 0) {
			$buttonAddToCart.on('click', '.bt-add-to-cart-btn', function (e) {
				e.preventDefault();
				const $this = $(this);
				if ($this.hasClass('bt-view-cart')) {
					window.location.href = AJ_Options.cart;
					return;
				}
				const productIds = $this.find('.bt-btn-price').data('ids');
				// Loop through each product ID and show toast notification
				if (Array.isArray(productIds)) {
					productIds.forEach(productId => {
						setTimeout(() => {
							WoozioshowToast(productId, 'cart', 'add');
						}, productIds.indexOf(productId) * 300); // Add 300ms delay between each toast
					});
				}
				if (productIds.length > 0) {
					$.ajax({
						type: 'POST',
						url: AJ_Options.ajax_url,
						data: {
							action: 'woozio_add_multiple_to_cart',
							product_ids: productIds
						},
						beforeSend: function () {
							$this.addClass('loading');
						},
						success: function (response) {
							$this.removeClass('loading');
							if (response.success) {
								// Update cart count and trigger cart refresh
								$(document.body).trigger('updated_wc_div');
								WoozioFreeShippingMessage();
								$this.html('View Cart');
								$this.addClass('bt-view-cart');
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							$this.removeClass('loading');
							console.log('Error adding products to cart:', textStatus, errorThrown);
						}
					});
				}
			});
		}
	};
	const ProductTestimonialHandler = function ($scope) {
		const $ProductTestimonial = $scope.find('.bt-elwg-product-testimonial--default');
		const $testimonialContent = $ProductTestimonial.find('.js-testimonial-content');
		const $testimonialImages = $ProductTestimonial.find('.js-testimonial-images');

		if ($testimonialContent.length > 0 && $testimonialImages.length > 0) {
			const $sliderSettings = $ProductTestimonial.data('slider-settings');
			const sliderSpeed = $sliderSettings.speed || 1000;
			const autoplay = $sliderSettings.autoplay || false;
			const autoplayDelay = $sliderSettings.autoplay_delay || 3000;
			// Initialize the testimonial content slider
			const testimonialContentSwiper = new Swiper($testimonialContent[0], {
				slidesPerView: 1,
				loop: true,
				speed: sliderSpeed,
				autoplay: autoplay ? {
					delay: autoplayDelay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $ProductTestimonial.find('.bt-button-next')[0],
					prevEl: $ProductTestimonial.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $ProductTestimonial.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},

			});
			// Initialize the testimonial images slider
			const testimonialImagesSwiper = new Swiper($testimonialImages[0], {
				slidesPerView: 1,
				loop: true,
				speed: sliderSpeed,
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				allowTouchMove: false,
			});

			// Sync both sliders
			testimonialContentSwiper.controller.control = testimonialImagesSwiper;
			testimonialImagesSwiper.controller.control = testimonialContentSwiper;

			// Pause autoplay on hover if autoplay is enabled
			if (autoplay) {
				$testimonialContent[0].addEventListener('mouseenter', () => {
					testimonialContentSwiper.autoplay.stop();
				});

				$testimonialContent[0].addEventListener('mouseleave', () => {
					testimonialContentSwiper.autoplay.start();
				});
			}
		}
	};
	const TestimonialSliderHandler = function ($scope) {
		const $TestimonialSlider = $scope.find('.js-data-testimonial-slider');
		if ($TestimonialSlider.length > 0) {
			const $sliderSettings = $TestimonialSlider.data('slider-settings') || {};
			const swiper = new Swiper($TestimonialSlider.find('.js-testimonial-slider')[0], {
				slidesPerView: $sliderSettings.slidesPerView,
				spaceBetween: $sliderSettings.spaceBetween,
				loop: $sliderSettings.loop,
				speed: $sliderSettings.speed,
				autoplay: $sliderSettings.autoplay ? {
					delay: $sliderSettings.autoplay_delay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $scope.find('.bt-button-next')[0],
					prevEl: $scope.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $scope.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: $sliderSettings.breakpoints,
			});

			if ($sliderSettings.autoplay) {
				$TestimonialSlider.find('.js-testimonial-slider')[0].addEventListener('mouseenter', () => {
					swiper.autoplay.stop();
				});
				$TestimonialSlider.find('.js-testimonial-slider')[0].addEventListener('mouseleave', () => {
					swiper.autoplay.start();
				});
			}
		}
	};
	const ProductTestimonialSliderHandler = function ($scope) {
		const $ProductTestimonialSlider = $scope.find('.js-data-product-testimonial-slider');
		if ($ProductTestimonialSlider.length > 0) {
			const $sliderSettings = $ProductTestimonialSlider.data('slider-settings') || {};
			// Initialize the testimonial slider
			const testimonialSlider = new Swiper($ProductTestimonialSlider.find('.js-testimonial-slider')[0], {
				slidesPerView: $sliderSettings.slidesPerView,
				spaceBetween: $sliderSettings.spaceBetween,
				loop: $sliderSettings.loop,
				speed: $sliderSettings.speed,
				autoplay: $sliderSettings.autoplay ? {
					delay: $sliderSettings.autoplay_delay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $scope.find('.bt-button-next')[0],
					prevEl: $scope.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $scope.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: $sliderSettings.breakpoints,
			});

			// Pause autoplay on hover if autoplay is enabled
			if ($sliderSettings.autoplay) {
				$ProductTestimonialSlider.find('.js-testimonial-slider')[0].addEventListener('mouseenter', () => {
					testimonialSlider.autoplay.stop();
				});

				$ProductTestimonialSlider.find('.js-testimonial-slider')[0].addEventListener('mouseleave', () => {
					testimonialSlider.autoplay.start();
				});
			}
		}
	};

	const countDownHandler = function ($scope) {
		const countDown = $scope.find('.bt-countdown-js');
		const countDownDate = new Date(countDown.data('time')).getTime();

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
	}
	const NotificationSliderHandler = function ($scope) {
		const $notificationWrapper = $scope.find('.bt-elwg-site-notification--default ');
		const $notificationContent = $notificationWrapper.find('.js-notification-content');

		if ($notificationContent.length > 0) {
			const $sliderSettings = $notificationWrapper.data('slider-settings');
			const sliderSpeed = $sliderSettings.speed || 1000;
			const autoplay = $sliderSettings.autoplay || false;
			const autoplayDelay = $sliderSettings.autoplay_delay || 3000;
			// Initialize the notification content slider
			const notificationContentSwiper = new Swiper($notificationContent[0], {
				slidesPerView: 1,
				loop: true,
				speed: sliderSpeed,
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				autoplay: autoplay ? {
					delay: autoplayDelay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $notificationWrapper.find('.bt-site-notification--next')[0],
					prevEl: $notificationWrapper.find('.bt-site-notification--prev')[0],
				},
			});

			// Pause autoplay on hover if autoplay is enabled
			if (autoplay) {
				$notificationContent[0].addEventListener('mouseenter', () => {
					notificationContentSwiper.autoplay.stop();
				});

				$notificationContent[0].addEventListener('mouseleave', () => {
					notificationContentSwiper.autoplay.start();
				});
			}
		}
	};
	// mini cart
	const MiniCartHandler = function ($scope) {
		const $miniCart = $scope.find('.bt-elwg-mini-cart--default');
		const $sidebar = $miniCart.find('.bt-mini-cart-sidebar');

		// Toggle mini cart
		$miniCart.find('.js-cart-sidebar').on('click', function (e) {
			e.preventDefault();
			$sidebar.addClass('active');
			const scrollbarWidth = window.innerWidth - $(window).width();
			$('body').css({
				'overflow': 'hidden',
				'padding-right': scrollbarWidth + 'px' // Prevent layout shift
			});
		});

		// Close mini cart when clicking overlay or close button
		$miniCart.find('.bt-mini-cart-sidebar-overlay, .bt-mini-cart-close').on('click', function () {
			closeMiniCart();
		});

		// Close mini cart when pressing ESC key
		$(document).keyup(function (e) {
			if (e.key === "Escape") {
				closeMiniCart();
			}
		});

		// Helper function to close mini cart
		function closeMiniCart() {
			$sidebar.removeClass('active');
			$('body').css({
				'overflow': 'auto', // Restore body scroll
				'padding-right': '0' // Reset padding-right
			});
		}
	}
	// product item
	const ProductItemHandler = function ($scope) {
		const $productItem = $scope.find('.bt-elwg-product-item--default');
		if ($productItem.length > 0) {
			// layout default
			$productItem.find('.bt-attributes-default .bt-item-color').on('click', function () {
				const colorValue = $(this).data('value');
				$productItem.find('.bt-attributes-default .bt-item-color').removeClass('active');
				$(this).addClass('active');
				$productItem.find('.bt-thumb-load-default .bt-color').removeClass('active');
				$productItem.find('.bt-thumb-load-default .bt-color.' + colorValue).addClass('active');
				$productItem.find('.bt-product-item--price .bt-price').removeClass('active');
				$productItem.find('.bt-product-item--price .bt-price.' + colorValue).addClass('active');
			});
			// Check if product has variations
			const $variationForm = $productItem.find('.variations_form');
			if ($variationForm.length > 0) {

				// Product has variations
				const $variationSelects = $variationForm.find('.variations select');

				// Loop through each select element
				$variationSelects.each(function () {
					const $select = $(this);
					// Get first non-disabled option that's not empty
					const firstOption = $select.find('option:not(.disabled):not([value=""])').first().val();
					if (firstOption) {
						// Set first available option as selected
						$select.val(firstOption).trigger('change');

					} else {
						// If no valid options found, reset to empty
						$select.val('').trigger('change');
					}
				});

				// $productItem.find("form.variations_form").on("change", "input, select", function () {
				// 	var variation_id = $variationForm.find("input.variation_id").val();
				// 	if (variation_id) {
				// 		var param_ajax = {
				// 			action: 'woozio_get_variation_price',
				// 			variation_id: variation_id
				// 		};

				// 		$.ajax({
				// 			type: 'POST',
				// 			dataType: 'json',
				// 			url: AJ_Options.ajax_url,
				// 			data: param_ajax,
				// 			success: function (response) {
				// 				if (response.success) {
				// 					var price = response.data['price'];
				// 					if (price) {
				// 						$productItem.find(".bt-product-item--price").html(price);
				// 					}
				// 				}
				// 			},
				// 			error: function (xhr, status, error) {
				// 				console.log('Error getting variation price:', error);
				// 			}
				// 		});
				// 	}
				// });
				$productItem.find('.bt-attributes-wrap .bt-js-item').on('click', function () {
					var valueItem = $(this).data('value');
					var attributesItem = $(this).closest('.bt-attributes--item');
					var attributeName = attributesItem.data('attribute-name');
					attributesItem.find('.bt-js-item').removeClass('active'); // Remove active class only from items in the same attribute group
					$(this).addClass('active'); // Add active class to clicked item
					var nameItem = (attributeName == 'pa_color') ? $(this).find('label').text() : $(this).text();
					attributesItem.find('.bt-result').text(nameItem);
					$productItem.find(' select#' + attributeName).val(valueItem).trigger('change');
					/* button buy now */
					var variationId = $productItem.find('input.variation_id').val();
					if (variationId) {
						// Load gallery
						var param_ajax = {
							action: 'woozio_load_product_variation',
							variation_id: variationId
						};

						$.ajax({
							type: 'POST',
							dataType: 'json',
							url: AJ_Options.ajax_url,
							data: param_ajax,
							beforeSend: function () {
								$productItem.find('.bt-attributes-wrap .bt-js-item').addClass('disable');
							},
							success: function (response) {
								if (response.success) {
									$productItem.find('.bt-product-item--thumb').html(response.data['image-variation']);
									$productItem.find('.bt-attributes-wrap .bt-js-item').removeClass('disable');
								}
							},
							error: function (xhr, status, error) {
								console.log('Error loading gallery:', error);
								$productItem.find('.bt-attributes-wrap .bt-js-item').removeClass('disable');
							}
						});
					}
					$productItem.find('.bt-attributes-wrap .bt-js-item').each(function () {
						var valueItem = $(this).data('value');
						var attributesItem = $(this).closest('.bt-attributes--item');
						var attributeName = attributesItem.data('attribute-name');
						var options = $productItem.find(' select#' + attributeName + ' option');
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
				// Find and activate first color variation if it exists
				const $variationItemColor = $variationForm.find('.bt-value-color .bt-js-item').first();
				if ($variationItemColor.length) {
					$variationItemColor.trigger('click');
				}
			}

		}
	}

	var SwitcherHandler = function ($scope, $) {
		const $switcher = $scope.find('.js-switcher-dropdown');
		if ($switcher.length) {
			const $currentItem = $switcher.find('.bt-current-item .bt-current-item-text');
			const $dropdownItems = $switcher.find('.bt-item');
			const $dropdown = $switcher.find('.bt-has-dropdown');

			// Toggle dropdown on click
			$currentItem.parent().on('click', function (e) {
				e.preventDefault();
				$dropdown.toggleClass('active');
			});

			// Handle dropdown item click
			$dropdownItems.on('click', function (e) {
				e.preventDefault();
				const selectedText = $(this).html();

				console.log(selectedText);

				// Remove active class from all items
				$dropdownItems.removeClass('active');

				// Add active class to clicked item
				$(this).addClass('active');

				// Update current item text
				$currentItem.html(selectedText);

				// Close dropdown
				$dropdown.removeClass('active');
			});

			// Close dropdown when clicking outside
			$(document).on('click', function (e) {
				if (!$switcher.is(e.target) && $switcher.has(e.target).length === 0) {
					$dropdown.removeClass('active');
				}
			});
		}
	};

	const AccordionWithProductSliderHandler = function ($scope) {
		const $AccordionWithProductSlider = $scope.find('.bt-elwg-accordion-with-product-slider--default');
		const $accordionProducts = $AccordionWithProductSlider.find('.js-accordion-products');

		if ($accordionProducts.length > 0) {
			const $sliderSettings = $AccordionWithProductSlider.data('slider-settings');
			const sliderSpeed = $sliderSettings.speed || 1000;
			const autoplay = $sliderSettings.autoplay || false;
			const autoplayDelay = $sliderSettings.autoplay_delay || 3000;

			// Clone first slide and append to end for smooth loop effect
			const $swiperWrapper = $accordionProducts.find('.swiper-wrapper');
			const $firstSlide = $swiperWrapper.find('.swiper-slide').first();

			if ($firstSlide.length > 0) {
				// Clone first two slides and append to end for smooth loop effect
				const $secondSlide = $swiperWrapper.find('.swiper-slide').eq(1);

				const $clonedFirstSlide = $firstSlide.clone();
				$clonedFirstSlide.addClass('swiper-slide-duplicate-end'); // Add identifier class
				$swiperWrapper.append($clonedFirstSlide);

				if ($secondSlide.length > 0) {
					const $clonedSecondSlide = $secondSlide.clone();
					$clonedSecondSlide.addClass('swiper-slide-duplicate-end'); // Add identifier class
					$swiperWrapper.append($clonedSecondSlide);
				}
			}

			// Initialize the accordion products slider
			const accordionProductsSwiper = new Swiper($accordionProducts[0], {
				slidesPerView: 1,
				spaceBetween: 20,
				loop: false,
				speed: sliderSpeed,
				centeredSlides: false,
				autoplay: autoplay ? {
					delay: autoplayDelay,
					disableOnInteraction: false
				} : false,
				allowTouchMove: true,
				breakpoints: {
					767: {
						slidesPerView: 1,
						centeredSlides: false,
						spaceBetween: 20
					},
					1024: {
						slidesPerView: 1.9,
						centeredSlides: true,
						spaceBetween: 30
					}
				},
			});

			// Handle accordion navigation item click
			$AccordionWithProductSlider.find('.bt-accordion-nav-item').on('click', function () {
				const clickedIndex = parseInt($(this).data('index'));

				// Update active accordion nav item
				$AccordionWithProductSlider.find('.bt-accordion-nav-item').removeClass('active');
				$(this).addClass('active');

				// Slide to corresponding products
				accordionProductsSwiper.slideTo(clickedIndex);
			});

			// Get total number of original slides (not including cloned)
			const totalOriginalSlides = $AccordionWithProductSlider.find('.bt-accordion-nav-item').length;

			// Update accordion nav when slider changes (via navigation arrows or pagination)
			accordionProductsSwiper.on('slideChange', function () {
				const activeIndex = this.activeIndex;

				// If we're on the cloned slide (last slide), jump to first slide without stopping autoplay
				if (activeIndex >= totalOriginalSlides) {
					// Store autoplay state before reset
					const wasAutoplayRunning = this.autoplay && this.autoplay.running;

					setTimeout(() => {
						this.slideTo(0, 0); // Slide to first slide with 0 speed (no animation)

						// Restart autoplay if it was running before reset
						if (wasAutoplayRunning && autoplay) {
							setTimeout(() => {
								this.autoplay.start();
							}, 50); // Small delay to ensure slide transition is complete
						}
					}, sliderSpeed); // Wait for current transition to complete
				}

				// Update accordion nav (use modulo to handle cloned slide)
				const navIndex = activeIndex % totalOriginalSlides;
				$AccordionWithProductSlider.find('.bt-accordion-nav-item').removeClass('active');
				$AccordionWithProductSlider.find('.bt-accordion-nav-item[data-index="' + navIndex + '"]').addClass('active');
			});

			// Pause autoplay on hover if autoplay is enabled
			if (autoplay) {
				$accordionProducts[0].addEventListener('mouseenter', () => {
					if (accordionProductsSwiper.autoplay) {
						accordionProductsSwiper.autoplay.stop();
					}
				});

				$accordionProducts[0].addEventListener('mouseleave', () => {
					if (accordionProductsSwiper.autoplay) {
						accordionProductsSwiper.autoplay.start();
					}
				});
			}
		}
	};

	var CollectionBannerHandler = function ($scope, $) {
		var $collectionBanner = $scope.find('.bt-collection-banner');

		if ($collectionBanner.length) {
			// Store the index of the default active item on page load
			var $defaultActiveItem = $collectionBanner.find('.collection-item.active').first();
			var defaultActiveIndex = $defaultActiveItem.length ? $defaultActiveItem.data('index') : null;

			// Handle hover events
			$collectionBanner.find('.collection-item').on('mouseenter', function () {
				var $this = $(this);
				var $container = $this.closest('.bt-collection-banner');

				// Remove active class from all items
				$container.find('.collection-item').removeClass('active');

				// Add active class to hovered item
				$this.addClass('active');
			});

			// Optional: Reset to default active item when mouse leaves container
			$collectionBanner.on('mouseleave', function () {
				var $container = $(this);

				// Remove active class from all items
				$container.find('.collection-item').removeClass('active');

				// If we have a default active item, restore it
				if (defaultActiveIndex !== null) {
					$container.find('.collection-item[data-index="' + defaultActiveIndex + '"]').addClass('active');
				}
			});
		}
	};

	const ProductHotspotOverlayHandler = function ($scope) {
		const $productHotspotOverlay = $scope.find('.bt-elwg-product-overlay-hotspot--default');

		if ($productHotspotOverlay.length > 0) {
			const $hotspotPoints = $productHotspotOverlay.find('.bt-hotspot-point');
			const $productItems = $productHotspotOverlay.find('.bt-product-item-minimal');

			// Handle hotspot point clicks
			$hotspotPoints.on('click', function (e) {
				e.preventDefault();
				const $this = $(this);
				const productId = $this.data('product-id');

				// Remove active state from all points and products
				$hotspotPoints.removeClass('active');
				$productItems.removeClass('active');

				// Add active state to clicked point
				$this.addClass('active');

				// Show corresponding product
				$productItems.filter(`[data-product-id="${productId}"]`).addClass('active');
			});
		}
	};

	const InstagramPostsHandler = function ($scope) {
		const $instagramPosts = $scope.find('.bt-elwg-instagram-posts');

		if ($instagramPosts.length > 0 && $instagramPosts.hasClass('bt-elwg-instagram-posts--slider')) {
			const $sliderSettings = $instagramPosts.data('slider-settings');
			const swiperOptions = {
				slidesPerView: $sliderSettings.slidesPerView,
				loop: $sliderSettings.loop,
				spaceBetween: $sliderSettings.spaceBetween,
				speed: $sliderSettings.speed,
				autoplay: $sliderSettings.autoplay ? {
					delay: 3000,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $instagramPosts.find('.bt-button-next')[0],
					prevEl: $instagramPosts.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $instagramPosts.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: $sliderSettings.breakpoints
			};

			const swiper = new Swiper($instagramPosts.find('.swiper')[0], swiperOptions);

			if ($sliderSettings.autoplay) {
				$instagramPosts.find('.swiper')[0].addEventListener('mouseenter', () => {
					swiper.autoplay.stop();
				});

				$instagramPosts.find('.swiper')[0].addEventListener('mouseleave', () => {
					swiper.autoplay.start();
				});
			}
		}
	};

	const BannerProductSliderHandler = function ($scope) {
		const $bannerProductSlider = $scope.find('.bt-elwg-banner-product-slider');

		if ($bannerProductSlider.length > 0) {
			const $sliderSettings = $bannerProductSlider.data('slider-settings');
			const swiperOptions = {
				slidesPerView: $sliderSettings.slidesPerView,
				loop: $sliderSettings.loop,
				spaceBetween: $sliderSettings.spaceBetween,
				speed: $sliderSettings.speed,
				autoplay: $sliderSettings.autoplay ? {
					delay: $sliderSettings.autoplay_delay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $bannerProductSlider.find('.bt-button-next')[0],
					prevEl: $bannerProductSlider.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $bannerProductSlider.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: $sliderSettings.breakpoints
			};

			const swiper = new Swiper($bannerProductSlider.find('.swiper')[0], swiperOptions);

			if ($sliderSettings.autoplay) {
				$bannerProductSlider.find('.swiper')[0].addEventListener('mouseenter', () => {
					swiper.autoplay.stop();
				});

				$bannerProductSlider.find('.swiper')[0].addEventListener('mouseleave', () => {
					swiper.autoplay.start();
				});
			}
		}
	};
	var TextSliderHandler = function ($scope, $) {
		var $textslider = $scope.find('.bt-elwg-text-slider--default');
		if ($textslider.length > 0) {
			var $direction = $textslider.data('direction');
			var $speed = $textslider.data('speed');
			var $spaceBetween = $textslider.data('spacebetween');

			var $swiper = new Swiper($textslider[0], {
				slidesPerView: 'auto',
				loop: true,
				spaceBetween: $spaceBetween,
				speed: $speed,
				freeMode: true,
				allowTouchMove: true,
				autoplay:
				{
					delay: 0,
					reverseDirection: $direction == 'rtl' ? true : false,
					disableOnInteraction: false,
				}
			});
		}
	};
	// product showcase
	const ProductShowcaseHandler = function ($scope) {
		const $productShowcase = $scope.find('.bt-elwg-product-showcase--default');
		if ($productShowcase.length > 0) {
			const $variationForm = $productShowcase.find('.variations_form');
			if ($variationForm.length > 0) {
				$variationForm.find('.bt-attributes--item').each(function () {
					const $firstJsItem = $(this).find('.bt-js-item').first();
					if ($firstJsItem.length) {
						$firstJsItem.trigger('click');
					}
				});
			}
		}
	}
	// hotspot product normal
	const ProductListHotspotHandler = function ($scope) {
		const $hotspotProductNormal = $scope.find('.bt-elwg-product-list-hotspot--default');
		if ($hotspotProductNormal.length > 0) {
			// Handle hotspot point clicks
			const $hotspotPoints = $hotspotProductNormal.find('.bt-hotspot-point');
			const $productItems = $hotspotProductNormal.find('.bt-hotspot-product-list__item');

			$hotspotPoints.on('click', function (e) {
				e.preventDefault();
				const $this = $(this);
				const productId = $this.data('product-id');

				// Remove active state from all points and products
				$hotspotPoints.removeClass('active');
				$productItems.removeClass('active');

				// Add active state to clicked point
				$this.addClass('active');

				// Show corresponding product
				$productItems.filter(`[data-product-id="${productId}"]`).addClass('active');
			});
			const $variationForm = $hotspotProductNormal.find('.variations_form');
			if ($variationForm.length > 0) {
				$variationForm.find('.bt-attributes--item').each(function () {
					const $firstJsItem = $(this).find('.bt-js-item').first();
					if ($firstJsItem.length) {
						$firstJsItem.trigger('click');
						$(this).closest('.variations_form').on('show_variation', function (event, variation) {
							var variationId = variation.variation_id;
							if (variationId && variationId !== '0') {
								var $ItemProduct = $(this).closest('.bt-hotspot-product-list__item');
								var $form = $(this).closest('.variations_form');
								var variations = $form.data('product_variations');
								if (variations) {
									// Find matching variation by ID
									var variation = variations.find(function (v) {
										return v.variation_id === variationId;
									});

									if (variation && variation.price_html) {
										// Update price display
										$ItemProduct.find(".bt-price").html(variation.price_html);
									}
								}
							}
						});
					}
				});
			}
			// Function to update variation_id in data-ids for a given product
			function updateHotspotProductVariationId(productItem, variationId, $scope) {
				
				const $productItem = productItem;
				const $productId = $productItem.data('product-id');
				const $product_currencySymbol = $productItem.data('product-currency');
				const $variationForm = $productItem.find('.variations_form');
				if (typeof variationId === 'undefined' || !variationId || typeof variationId === 'object') {
					variationId = parseInt($variationForm.find('input.variation_id').val(), 10) || 0;
				}
				const $addSetToCartBtn = $hotspotProductNormal.find('.bt-button-add-set-to-cart');
				if ($addSetToCartBtn.length) {
					let idsData = $addSetToCartBtn.attr('data-ids');
					let idsArr = [];
					try {
						idsArr = JSON.parse(idsData);
					} catch (e) {
						console.error('Invalid data-ids JSON', e);
					}
					let updated = false;
					let totalPrice = 0;

					idsArr = idsArr.map(item => {
						if (item.product_id == $productId) {
							if (item.variation_id != variationId) {
								item.variation_id = variationId;
								updated = true;
							}
						}
						// Get price for each variation
						const $form = $scope.find(`.variations_form[data-product_id="${item.product_id}"]`);
						if ($form.length) {
							// Product has variations
							const variations = $form.data('product_variations');
							if (variations) {
								const variation = variations.find(v => v.variation_id === item.variation_id);
								if (variation && variation.display_price) {
									totalPrice += parseFloat(variation.display_price);
								}
							}
						} else {
							// Simple product - get price from data attribute
							const $productItem = $scope.find(`.bt-hotspot-product-list__item[data-product-id="${item.product_id}"]`);
							if ($productItem.length) {
								const singlePrice = $productItem.data('product-single-price');
								if (singlePrice) {
									totalPrice += parseFloat(singlePrice);
								}
							}
						}
						return item;
					});

					if (updated) {
						$addSetToCartBtn.attr('data-ids', JSON.stringify(idsArr));
					}

					// update total price
					totalPrice = totalPrice.toFixed(2);
					$addSetToCartBtn.find('.bt-btn-price').html(' - ' + $product_currencySymbol + totalPrice);
				}
			}

			// Initial update on load
			const $productItem = $hotspotProductNormal.find('.bt-hotspot-product-list__item');
			$productItem.each(function () {
				
				updateHotspotProductVariationId($(this), null, $scope);
			});
			// Update on variation change
			$variationForm.find('select').on('change', function () {
				var $form = $(this).closest('.variations_form')
				$form.on('show_variation', function (event, variation) {
					var variationId = variation.variation_id;
					if (variationId && variationId !== '0') {
						var $ItemProduct = $form.closest('.bt-hotspot-product-list__item');
						updateHotspotProductVariationId($ItemProduct, variationId, $scope);
						var variations = $form.data('product_variations');
						if (variations) {
							var variation = variations.find(function (v) {
								return v.variation_id === variationId;
							});
							if (variation && variation.price_html) {
								// Update price display
								$ItemProduct.find(".bt-price").html(variation.price_html);
							}
						}
					}

				});
			});
			/* ajax add to cart */
			$hotspotProductNormal.find('.bt-button-add-set-to-cart').on('click', function (e) {
				e.preventDefault();
				const $this = $(this);
				if ($this.hasClass('bt-view-cart')) {
					window.location.href = AJ_Options.cart;
					return;
				}
				let productIds = $this.data('ids');
				// Ensure productIds is an array of objects (for variable products)
				if (typeof productIds === 'string') {
					try {
						productIds = JSON.parse(productIds);
					} catch (e) {
						console.error('Invalid data-ids JSON', e);
						productIds = [];
					}
				}
				if (!Array.isArray(productIds)) {
					productIds = [];
				}

				// Show toast for each product (with delay)
				productIds.forEach((item, idx) => {
					const productId = item.variation_id && item.variation_id !== 0 ? item.variation_id : item.product_id;
					setTimeout(() => {
						WoozioshowToast(productId, 'cart', 'add');
					}, idx * 300);
				});

				if (productIds.length > 0) {
					$.ajax({
						type: 'POST',
						url: AJ_Options.ajax_url,
						data: {
							action: 'woozio_add_multiple_to_cart_variable',
							product_ids: productIds
						},
						beforeSend: function () {
							$this.addClass('loading');
						},
						success: function (response) {
							$this.removeClass('loading');
							if (response.success) {
								// Update cart count and trigger cart refresh
								$(document.body).trigger('updated_wc_div');
								WoozioFreeShippingMessage();
								$this.html('View Cart');
								$this.addClass('bt-view-cart');
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							$this.removeClass('loading');
							console.log('Error adding products to cart:', textStatus, errorThrown);
						}
					});
				}
			});
		}
	}
	const StoreLocationsHandler = function ($scope) {
		const $storeLocationsSlider = $scope.find('.bt-elwg-store-locations-slider');

		if ($storeLocationsSlider.length > 0) {
			const $sliderSettings = $storeLocationsSlider.data('slider-settings');
			const swiperOptions = {
				slidesPerView: $sliderSettings.slidesPerView,
				loop: $sliderSettings.loop,
				spaceBetween: $sliderSettings.spaceBetween,
				speed: $sliderSettings.speed,
				autoplay: $sliderSettings.autoplay ? {
					delay: $sliderSettings.autoplay_delay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $storeLocationsSlider.find('.bt-button-next')[0],
					prevEl: $storeLocationsSlider.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $storeLocationsSlider.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: $sliderSettings.breakpoints
			};

			const swiper = new Swiper($storeLocationsSlider.find('.swiper')[0], swiperOptions);

			if ($sliderSettings.autoplay) {
				$storeLocationsSlider.find('.swiper')[0].addEventListener('mouseenter', () => {
					swiper.autoplay.stop();
				});

				$storeLocationsSlider.find('.swiper')[0].addEventListener('mouseleave', () => {
					swiper.autoplay.start();
				});
			}
		}
	};
	const ProductSliderBottomHotspotHandler = function ($scope) {
		const $productSliderBottomHotspot = $scope.find('.bt-elwg-product-slider-bottom-hotspot--default');
		if ($productSliderBottomHotspot.length > 0) {
			const swiperOptions = {
				slidesPerView: 1,
				loop: false,
				spaceBetween: 30,
				speed: 1000,
				autoplay: false,
				navigation: {
					nextEl: $productSliderBottomHotspot.find('.bt-button-next')[0],
					prevEl: $productSliderBottomHotspot.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $productSliderBottomHotspot.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: {
					1560: {
						slidesPerView: 4,
						spaceBetween: 30
					},
					1200: {
						slidesPerView: 3,
						spaceBetween: 30
					},
					800: {
						slidesPerView: 2,
						spaceBetween: 30
					}
				}

			};

			const swiper = new Swiper($productSliderBottomHotspot.find('.swiper')[0], swiperOptions);

			// Handle hotspot point clicks
			const $hotspotPoints = $productSliderBottomHotspot.find('.bt-hotspot-point');
			const $productItems = $productSliderBottomHotspot.find('.bt-hotspot-product-list__item');

			$hotspotPoints.on('click', function (e) {
				e.preventDefault();
				const $this = $(this);
				const productId = $this.data('product-id');

				// Remove active state from all points and products
				$hotspotPoints.removeClass('active');
				$productItems.removeClass('active');

				// Add active state to clicked point
				$this.addClass('active');

				// Show corresponding product
				const $targetProduct = $productItems.filter(`[data-product-id="${productId}"]`);
				$targetProduct.addClass('active');

				// Find the slide index containing the target product
				const $targetSlide = $targetProduct.closest('.swiper-slide');
				const slideIndex = $targetSlide.index();
				// Get number of visible items
				const visibleItemsCount = swiper.params.slidesPerView;
				// Scroll to the slide if it exists
				if (slideIndex >= 0) {
					// Only scroll if target slide is outside visible range
					const currentIndex = swiper.activeIndex;
					const lastVisibleIndex = currentIndex + visibleItemsCount - 1;

					if (slideIndex < currentIndex || slideIndex > lastVisibleIndex) {
						swiper.slideTo(slideIndex);
					}
				}
			});

			const $variationForm = $productSliderBottomHotspot.find('.variations_form');
			if ($variationForm.length > 0) {
				$variationForm.find('.bt-attributes--item').each(function () {
					const $firstJsItem = $(this).find('.bt-js-item').first();
					if ($firstJsItem.length) {
						$firstJsItem.trigger('click');
						$(this).closest('.variations_form').on('show_variation', function (event, variation) {
							var variationId = variation.variation_id;
							if (variationId && variationId !== '0') {
								var $ItemProduct = $(this).closest('.bt-hotspot-product-list__item');
								var $form = $(this).closest('.variations_form');
								var variations = $form.data('product_variations');
								if (variations) {
									// Find matching variation by ID
									var variation = variations.find(function (v) {
										return v.variation_id === variationId;
									});

									if (variation && variation.price_html) {
										// Update price display
										$ItemProduct.find(".bt-price").html(variation.price_html);
									}
								}
							}
						});
					}
				});
			}
			// Function to update variation_id in data-ids for a given product
			function updateHotspotProductVariationId(productItem, variationId, $scope) {
				const $productItem = productItem;
				const $productId = $productItem.data('product-id');
				const $product_currencySymbol = $productItem.data('product-currency');
				const $variationForm = $productItem.find('.variations_form');
				if (typeof variationId === 'undefined' || !variationId || typeof variationId === 'object') {
					variationId = parseInt($variationForm.find('input.variation_id').val(), 10) || 0;
				}
				const $addSetToCartBtn = $productSliderBottomHotspot.find('.bt-button-add-set-to-cart');
				if ($addSetToCartBtn.length) {
					let idsData = $addSetToCartBtn.attr('data-ids');
					let idsArr = [];
					try {
						idsArr = JSON.parse(idsData);
					} catch (e) {
						console.error('Invalid data-ids JSON', e);
					}
					let updated = false;
					let totalPrice = 0;

					idsArr = idsArr.map(item => {
						if (item.product_id == $productId) {
							if (item.variation_id != variationId) {
								item.variation_id = variationId;
								updated = true;
							}
						}
						// Get price for each variation
						const $form = $scope.find(`.variations_form[data-product_id="${item.product_id}"]`);
						if ($form.length) {
							// Product has variations
							const variations = $form.data('product_variations');
							if (variations) {
								const variation = variations.find(v => v.variation_id === item.variation_id);
								if (variation && variation.display_price) {
									totalPrice += parseFloat(variation.display_price);
								}
							}
						} else {
							// Simple product - get price from data attribute
							const $productItem = $scope.find(`.bt-hotspot-product-list__item[data-product-id="${item.product_id}"]`);
							if ($productItem.length) {
								const singlePrice = $productItem.data('product-single-price');
								if (singlePrice) {
									totalPrice += parseFloat(singlePrice);
								}
							}
						}
						return item;
					});

					if (updated) {
						$addSetToCartBtn.attr('data-ids', JSON.stringify(idsArr));
					}

					// update total price
					totalPrice = totalPrice.toFixed(2);
					$addSetToCartBtn.find('.bt-btn-price').html(' - ' + $product_currencySymbol + totalPrice);
				}
			}

			// Initial update on load
			const $productItem = $productSliderBottomHotspot.find('.bt-hotspot-product-list__item');
			$productItem.each(function () {
				updateHotspotProductVariationId($(this), null, $scope);
			});
			// Update on variation change
			$variationForm.find('select').on('change', function () {
				var $form = $(this).closest('.variations_form')
				$form.on('show_variation', function (event, variation) {
					var variationId = variation.variation_id;
					if (variationId && variationId !== '0') {
						var $ItemProduct = $form.closest('.bt-hotspot-product-list__item');
						updateHotspotProductVariationId($ItemProduct, variationId, $scope);
						var variations = $form.data('product_variations');
						if (variations) {
							var variation = variations.find(function (v) {
								return v.variation_id === variationId;
							});
							if (variation && variation.price_html) {
								// Update price display
								$ItemProduct.find(".bt-price").html(variation.price_html);
							}
						}
					}

				});
			});

			/* ajax add to cart */
			$productSliderBottomHotspot.find('.bt-button-add-set-to-cart').on('click', function (e) {
				e.preventDefault();
				const $this = $(this);
				if ($this.hasClass('bt-view-cart')) {
					window.location.href = AJ_Options.cart;
					return;
				}
				let productIds = $this.data('ids');
				// Ensure productIds is an array of objects (for variable products)
				if (typeof productIds === 'string') {
					try {
						productIds = JSON.parse(productIds);
					} catch (e) {
						console.error('Invalid data-ids JSON', e);
						productIds = [];
					}
				}
				if (!Array.isArray(productIds)) {
					productIds = [];
				}

				// Show toast for each product (with delay)
				productIds.forEach((item, idx) => {
					const productId = item.variation_id && item.variation_id !== 0 ? item.variation_id : item.product_id;
					setTimeout(() => {
						WoozioshowToast(productId, 'cart', 'add');
					}, idx * 300);
				});

				if (productIds.length > 0) {
					$.ajax({
						type: 'POST',
						url: AJ_Options.ajax_url,
						data: {
							action: 'woozio_add_multiple_to_cart_variable',
							product_ids: productIds
						},
						beforeSend: function () {
							$this.addClass('loading');
						},
						success: function (response) {
							$this.removeClass('loading');
							if (response.success) {
								// Update cart count and trigger cart refresh
								$(document.body).trigger('updated_wc_div');
								WoozioFreeShippingMessage();
								$this.html('View Cart');
								$this.addClass('bt-view-cart');
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							$this.removeClass('loading');
							console.log('Error adding products to cart:', textStatus, errorThrown);
						}
					});
				}
			});
		}
	};
	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-location-list.default', LocationListHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-list-faq.default', FaqHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-search-product.default', SearchProductHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-heading-animation.default', headingAnimationHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-instagram-posts.default', InstagramPostsHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-banner-product-slider.default', BannerProductSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-tiktok-shop-slider.default', TiktokShopSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-tooltip-hotspot.default', ProductTooltipHotspotHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-testimonial.default', ProductTestimonialHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-testimonial-slider.default', TestimonialSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-testimonial-slider.default', ProductTestimonialSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-countdown.default', countDownHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-site-notification.default', NotificationSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-mini-cart.default', MiniCartHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-item.default', ProductItemHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-currency-switcher.default', SwitcherHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-language-switcher.default', SwitcherHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-accordion-with-product-slider.default', AccordionWithProductSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-collection-banner.default', CollectionBannerHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-overlay-hotspot.default', ProductHotspotOverlayHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-text-slider.default', TextSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-showcase.default', ProductShowcaseHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-list-hotspot.default', ProductListHotspotHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-store-locations-slider.default', StoreLocationsHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-slider-bottom-hotspot.default', ProductSliderBottomHotspotHandler);
	});

})(jQuery);
