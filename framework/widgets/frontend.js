(function ($) {
	/**
	   * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	**/
	/* Check Background Light or Dark */
	function WoozioCheckBgLightDark() {
		if ($('.js-check-bg-color').length > 0) {
			$(".js-check-bg-color").each(function () {
				let $el = $(this);
				let bg = $el.css("background-color");
				let rgb = bg.match(/\d+/g);
				if (!rgb) return;

				let r = parseInt(rgb[0]),
					g = parseInt(rgb[1]),
					b = parseInt(rgb[2]);

				let yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;

				$el.removeClass("bt-bg-light bt-bg-dark")
					.addClass(yiq >= 128 ? "bt-bg-light" : "bt-bg-dark");
			});
		}
	}

	/* Submenu toggle */
	const SubmenuToggleHandler = function ($scope, $) {
		var hasChildren = $scope.find('.menu-item-has-children');

		hasChildren.each(function () {
			var $btnToggle = $('<span class="bt-toggle-icon"></span>');

			$(this).append($btnToggle);

			$btnToggle.on('click', function (e) {
				e.preventDefault();

				if ($(this).parent().hasClass('bt-is-active')) {
					$(this).parent().removeClass('bt-is-active');
					$(this).parent().children('ul').slideUp();
				} else {
					$(this).parent().addClass('bt-is-active');
					$(this).parent().children('ul').slideDown();
					$(this).parent().siblings().removeClass('bt-is-active').children('ul').slideUp();
					$(this).parent().siblings().find('li').removeClass('bt-is-active').children('ul').slideUp();
				}
			});
		});
	}

	const FaqHandler = function ($scope, $) {
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
	const BtAccordionHandler = function ($scope, $) {
		const $accordionTitle = $scope.find('.bt-accordion-title');
		if ($accordionTitle.length > 0) {
			$accordionTitle.on('click', function (e) {
				e.preventDefault();
				const $currentItem = $(this);
				const $content = $currentItem.parent().find('.bt-accordion-content');

				if ($currentItem.hasClass('active')) {
					$content.slideUp();
					$currentItem.removeClass('active');
				} else {
					// Close other accordion items (single accordion behavior)
					$scope.find('.bt-accordion-title.active').removeClass('active');
					$scope.find('.bt-accordion-content').slideUp();

					// Open current item
					$content.slideDown();
					$currentItem.addClass('active');
				}
			});
		}
	};
	const SearchProductHandler = function ($scope, $) {
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

	function WoozioshowToast(idproduct, tools = 'cart', status = 'add') {
		if ($(window).width() > 1024) { // Only run for screens wider than 1024px
			// ajax load product toast
			var toastTimeshow;
			if (tools === 'wishlist' && AJ_Options.wishlist_toast_time) {
				toastTimeshow = AJ_Options.wishlist_toast_time;
			} else if (tools === 'compare' && AJ_Options.compare_toast_time) {
				toastTimeshow = AJ_Options.compare_toast_time;
			} else if (tools === 'cart' && AJ_Options.cart_toast_time) {
				toastTimeshow = AJ_Options.cart_toast_time;
			} else {
				toastTimeshow = 3000; // Default fallback time
			}
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
							}, toastTimeshow);
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

	const TestimonialsStaggeredSliderHandler = function ($scope) {
		const $TestimonialsStaggeredSlider = $scope.find('.js-data-testimonials-staggered-slider');
		if ($TestimonialsStaggeredSlider.length > 0) {
			const $sliderSettings = $TestimonialsStaggeredSlider.data('slider-settings') || {};
			// Initialize the testimonials staggered slider
			const staggeredSlider = new Swiper($TestimonialsStaggeredSlider.find('.js-testimonials-staggered-slider')[0], {
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
				$TestimonialsStaggeredSlider.find('.js-testimonials-staggered-slider')[0].addEventListener('mouseenter', () => {
					staggeredSlider.autoplay.stop();
				});

				$TestimonialsStaggeredSlider.find('.js-testimonials-staggered-slider')[0].addEventListener('mouseleave', () => {
					staggeredSlider.autoplay.start();
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
		const $sidebar = $('.bt-mini-cart-sidebar');

		// Toggle mini cart
		$miniCart.find('.js-cart-sidebar').on('click', function (e) {
			e.preventDefault();
			$sidebar.addClass('active'); // Add active class to sidebar
			const scrollbarWidth = window.innerWidth - $(window).width();
			$('body').css({
				'overflow': 'hidden',
				'padding-right': scrollbarWidth + 'px' // Prevent layout shift
			});
		});

		// Close mini cart when clicking overlay or close button
		$sidebar.find('.bt-mini-cart-sidebar-overlay, .bt-mini-cart-close').on('click', function () {
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
	const SwitcherHandler = function ($scope, $) {
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

	const TitleNavWithSliderHandler = function ($scope) {
		const $TitleNavWithSlider = $scope.find('.bt-elwg-title-nav-with-slider--default');
		const $navContent = $TitleNavWithSlider.find('.js-title-nav-content');

		if ($navContent.length > 0) {
			const $sliderSettings = $TitleNavWithSlider.data('slider-settings');
			const sliderSpeed = $sliderSettings.speed || 1000;
			const autoplay = $sliderSettings.autoplay || false;
			const autoplayDelay = $sliderSettings.autoplay_delay || 3000;

			let titleNavContentSwiper;

			function initSlider() {
				if (titleNavContentSwiper) {
					titleNavContentSwiper.destroy();
				}

				titleNavContentSwiper = new Swiper($navContent[0], {
					direction: 'horizontal',
					slidesPerView: 1,
					spaceBetween: 15,
					loop: true,
					speed: sliderSpeed,
					centeredSlides: false,
					autoplay: autoplay ? {
						delay: autoplayDelay,
						disableOnInteraction: false
					} : false,
					allowTouchMove: true,
					effect: 'slide',
					mousewheel: {
						enabled: true,
						forceToAxis: true,
					},
					breakpoints: {
						768: {
							spaceBetween: 20,
							loop: true,
						},
						1025: {
							direction: 'vertical',
							spaceBetween: 20,
							loop: false,
						},
						1367: {
							direction: 'vertical',
							spaceBetween: 30,
							loop: false,
						}
					}
				});

				// Handle navigation item click
				$TitleNavWithSlider.find('.bt-nav-item').on('click', function () {
					const clickedIndex = parseInt($(this).data('index'));

					// Update active navigation item
					$TitleNavWithSlider.find('.bt-nav-item').removeClass('active');
					$(this).addClass('active');

					// Slide to corresponding content
					titleNavContentSwiper.slideTo(clickedIndex);
				});

				// Update navigation when slider changes
				titleNavContentSwiper.on('slideChange', function () {
					const activeIndex = this.activeIndex;

					// Update navigation
					$TitleNavWithSlider.find('.bt-nav-item').removeClass('active');
					$TitleNavWithSlider.find('.bt-nav-item[data-index="' + activeIndex + '"]').addClass('active');
				});

				// Pause autoplay on hover if autoplay is enabled
				if (autoplay) {
					$navContent[0].addEventListener('mouseenter', () => {
						if (titleNavContentSwiper.autoplay) {
							titleNavContentSwiper.autoplay.stop();
						}
					});

					$navContent[0].addEventListener('mouseleave', () => {
						if (titleNavContentSwiper.autoplay) {
							titleNavContentSwiper.autoplay.start();
						}
					});
				}
				// Trigger click on second nav item programmatically for desktop
				if (window.innerWidth > 1024) {
					setTimeout(() => {
						$TitleNavWithSlider.find('.bt-nav-item').eq(1).trigger('click');
					}, 100);
				}
			}

			// Initialize slider
			initSlider();

			// Reinitialize on window resize
			let resizeTimer;
			window.addEventListener('resize', () => {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(() => {
					initSlider();
				}, 250);
			});
		}
	};

	const CollectionBannerHandler = function ($scope, $) {
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
			// video hover
			$bannerProductSlider.find('.bt-banner-product-slider--item').each(function () {
				const $item = $(this);
				const $video = $item.find('.bt-hover-video');
				const $coverImage = $item.find('.bt-cover-image img');

				if ($item.hasClass('bt-video-hover-enable') && $video.length) {
					$item.on('mouseenter', function () {
						$coverImage.css('opacity', '0');
						$video[0].play();
					});

					$item.on('mouseleave', function () {
						$coverImage.css('opacity', '1');
						$video[0].pause();
					});

					$item.on('click', function () {
						if ($video[0].paused) {
							$video[0].play();
						} else {
							$video[0].pause();
						}
					});
				}
			});
		}
	};

	const OffersSliderHandler = function ($scope) {
		const $offersSlider = $scope.find('.bt-elwg-offers-slider');

		if ($offersSlider.length > 0) {
			const $sliderSettings = $offersSlider.data('slider-settings');
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
					nextEl: $offersSlider.find('.bt-button-next')[0],
					prevEl: $offersSlider.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $offersSlider.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: $sliderSettings.breakpoints
			};

			const swiper = new Swiper($offersSlider.find('.swiper')[0], swiperOptions);

			if ($sliderSettings.autoplay) {
				$offersSlider.find('.swiper')[0].addEventListener('mouseenter', () => {
					swiper.autoplay.stop();
				});

				$offersSlider.find('.swiper')[0].addEventListener('mouseleave', () => {
					swiper.autoplay.start();
				});
			}
		}
	};

	const TextSliderHandler = function ($scope, $) {
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
		const $productShowcase = $scope.find('.js-product-showcase');
		if ($productShowcase.length > 0) {
			$productShowcase.find('.js-check-bg-color').each(function () {
				let $el = $(this);
				let bg = $el.css("background-color");
				let rgb = bg.match(/\d+/g);
				if (!rgb) return;

				let r = parseInt(rgb[0]),
					g = parseInt(rgb[1]),
					b = parseInt(rgb[2]);

				let yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;

				$el.removeClass("bt-bg-light bt-bg-dark")
					.addClass(yiq >= 128 ? "bt-bg-light" : "bt-bg-dark");
			});
		}
	}
	const ProductShowcaseStyle2Handler = function ($scope) {
		// Initialize Gallery Slider for Layout 01
		const $layoutWidget = $scope.find('.bt-layout-layout-01');
		if ($layoutWidget.length > 0) {
			const $gallerySlider = $layoutWidget.find('.woocommerce-product-gallery__slider');
			if ($gallerySlider.length > 0) {
				// Determine thumb direction based on layout class
				var thumbDirection = 'horizontal';
				if ($layoutWidget.find('.bt-left-thumbnail').length > 0 || $layoutWidget.find('.bt-right-thumbnail').length > 0) {
					thumbDirection = 'vertical';
				}

				// Initialize thumbnail slider
				var galleryThumbs = new Swiper($layoutWidget.find('.woocommerce-product-gallery__slider-thumbs')[0], {
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

				// Initialize main gallery slider
				var galleryTop = new Swiper($gallerySlider[0], {
					spaceBetween: 20,
					loop: false,
					loopedSlides: 5,
					navigation: {
						nextEl: $layoutWidget.find('.swiper-button-next')[0],
						prevEl: $layoutWidget.find('.swiper-button-prev')[0],
					},
					thumbs: {
						swiper: galleryThumbs,
					},
				});
			}
		}
	}
	function WoozioProductHotspotAddSetCart($container) {
		const $variationForm = $container.find('.variations_form');
		const $productItems = $container.find('.bt-hotspot-product-list__item');
		if ($variationForm.length > 0) {
			$variationForm.find('.bt-attributes--item').each(function () {
				$(this).closest('.variations_form').off('show_variation.woozioloaditem').on('show_variation.woozioloaditem', function (event, variation) {
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
								if ($ItemProduct.find(".bt-price").length > 0) {
									$ItemProduct.find(".bt-price").html(variation.price_html);
								} else {
									$ItemProduct.find(".woocommerce-loop-product__infor .price").html(variation.price_html);
								}
							}
						}
					}
				});
			});
		}
		// Function to update variation_id in data-ids for a given product
		function updateHotspotProductVariationId(productItem, variationId, $container) {

			const $productItem = productItem;
			const $productId = $productItem.data('product-id');

			const $product_currencySymbol = $productItem.data('product-currency');
			const $variationForm = $productItem.find('.variations_form');
			if (typeof variationId === 'undefined' || !variationId || typeof variationId === 'object') {
				variationId = parseInt($variationForm.find('input.variation_id').val(), 10) || 0;
			}
			const $addSetToCartBtn = $container.find('.bt-button-add-set-to-cart');
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
				let hasInvalidVariation = false;

				idsArr = idsArr.map(item => {
					if (item.product_id == $productId) {
						if (item.variation_id != variationId && variationId !== 0) {
							item.variation_id = variationId;
							updated = true;
						}
					}

					// Check if this is a variable product that needs a variation selected
					const $form = $container.find(`.variations_form[data-product_id="${item.product_id}"]`);
					if ($form.length && (!item.variation_id || item.variation_id === 0 || item.variation_id === null)) {
						hasInvalidVariation = true;
					}

					// Get price for each variation
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
						const $productItemId = $container.find(`.bt-hotspot-product-list__item[data-product-id="${item.product_id}"]`);
						if ($productItemId.length) {
							const simplePrice = $productItemId.data('product-single-price');
							if (simplePrice) {
								totalPrice += parseFloat(simplePrice);
							}
						}
					}
					return item;
				});

				// Update button state based on variations
				if (hasInvalidVariation) {
					$addSetToCartBtn.addClass('disabled');
				} else {
					$addSetToCartBtn.removeClass('disabled');
				}

				if (updated) {
					$addSetToCartBtn.attr('data-ids', JSON.stringify(idsArr));
				}
				// update total price
				totalPrice = totalPrice.toFixed(2);
				$addSetToCartBtn.find('.bt-btn-price').html(' - ' + $product_currencySymbol + totalPrice);
			}
		}

		// Initial update on load
		$productItems.each(function () {
			updateHotspotProductVariationId($(this), null, $container);
		});
		// Update on variation change
		$variationForm.find('select').on('change', function () {
			var $form = $(this).closest('.variations_form')
			$form.off('show_variation.wooziochangeitem').on('show_variation.wooziochangeitem', function (event, variation) {
				var variationId = variation.variation_id;
				if (variationId && variationId !== '0') {
					var $ItemProduct = $form.closest('.bt-hotspot-product-list__item');
					updateHotspotProductVariationId($ItemProduct, variationId, $container);
					var variations = $form.data('product_variations');
					if (variations) {
						var variation = variations.find(function (v) {
							return v.variation_id === variationId;
						});
						if (variation && variation.price_html) {
							// Update price display
							if ($ItemProduct.find(".bt-price").length > 0) {
								$ItemProduct.find(".bt-price").html(variation.price_html);
							} else {
								$ItemProduct.find(".woocommerce-loop-product__infor .price").html(variation.price_html);
							}
						}
					}
				}

			});
		});
		/* ajax add to cart */
		$container.find('.bt-button-add-set-to-cart').on('click', function (e) {
			e.preventDefault();
			const $this = $(this);
			if ($this.hasClass('disabled')) {
				return;
			}
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
			if ($(window).width() <= 1023) {
				$('.bt-mini-cart-sidebar').addClass('active');
				const scrollbarWidth = window.innerWidth - $(window).width();
				$('body').css({
					'overflow': 'hidden',
					'padding-right': scrollbarWidth + 'px' // Prevent layout shift
				});
			}
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
	// Product list hotspot
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
			WoozioProductHotspotAddSetCart($hotspotProductNormal);
		}
	}
	const ProductSliderBottomHotspotHandler = function ($scope) {
		const $productSliderBottomHotspot = $scope.find('.bt-elwg-product-slider-bottom-hotspot--default');
		if ($productSliderBottomHotspot.length > 0) {
			// Get width of container
			const $itemdesktop = $productSliderBottomHotspot.width() < 1560 ? 3 : 4;
			// Get slider direction from data attribute
			const sliderDirection = $productSliderBottomHotspot.data('slider-direction') || 'ltr';
			const isRTL = sliderDirection === 'rtl';

			const swiperOptions = {
				slidesPerView: 1,
				loop: false,
				spaceBetween: 30,
				speed: 1000,
				autoplay: false,
				rtl: isRTL,
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
						slidesPerView: $itemdesktop,
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
			WoozioProductHotspotAddSetCart($productSliderBottomHotspot);
		}
	};
	const ProductTooltipHotspotHandler = function ($scope) {
		const $HotspotProduct = $scope.find('.bt-elwg-product-tooltip-hotspot--default');
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
				$HotspotProduct.toggleClass('bt-hotspot-product-mobile', $point.parent().width() < 600);
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
				loop: false,
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
		WoozioProductHotspotAddSetCart($HotspotProduct);

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

	const ProductNavImageHandler = function ($scope) {
		const $productNavImage = $scope.find('.bt-elwg-product-nav-image--default');
		if ($productNavImage.length > 0) {
			const $productNavImageTabs = $productNavImage.find('.bt-product-nav-image--tabs');
			const $productNavImageThumb = $productNavImage.find('.bt-product-nav-image--thumb');
			$productNavImageTabs.find('.bt-product-nav-image--tab-item').on('click', function () {
				const $this = $(this);
				const index = $this.data('index');
				$productNavImageTabs.find('.bt-product-nav-image--tab-item').removeClass('active');
				$this.addClass('active');
				$productNavImageThumb.find('.bt-product-nav-image--thumb-item').removeClass('active');
				$productNavImageThumb.find('.bt-product-nav-image--thumb-item').eq(index).addClass('active');
			});
		}
	}
	const BrandSliderHandler = function ($scope) {
		const $brandSlider = $scope.find('.bt-elwg-brand-slider--default');

		if ($brandSlider.length > 0 && $brandSlider.hasClass('bt-elwg-brand-slider--slider')) {
			const $sliderSettings = $brandSlider.data('slider-settings');
			const $sliderContinuous = $brandSlider.data('slider-continuous');
			if ($sliderContinuous) {
				$sliderSettings.speed = $sliderContinuous.speed;
				$sliderSettings.direction = $sliderContinuous.direction;
				var $swiper = new Swiper($brandSlider.find('.swiper')[0], {
					slidesPerView: 'auto',
					loop: true,
					spaceBetween: 0,
					speed: $sliderContinuous.speed,
					freeMode: true,
					allowTouchMove: true,
					autoplay:
					{
						delay: 0,
						reverseDirection: $sliderContinuous.direction == 'rtl' ? true : false,
						disableOnInteraction: false,
					}
				});
			} else {
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
						nextEl: $brandSlider.find('.bt-button-next')[0],
						prevEl: $brandSlider.find('.bt-button-prev')[0],
					},
					pagination: {
						el: $brandSlider.find('.bt-swiper-pagination')[0],
						clickable: true,
						type: 'bullets',
						renderBullet: function (index, className) {
							return '<span class="' + className + '"></span>';
						},
					},
					breakpoints: $sliderSettings.breakpoints
				};

				const swiper = new Swiper($brandSlider.find('.swiper')[0], swiperOptions);

				if ($sliderSettings.autoplay) {
					$brandSlider.find('.swiper')[0].addEventListener('mouseenter', () => {
						swiper.autoplay.stop();
					});

					$brandSlider.find('.swiper')[0].addEventListener('mouseleave', () => {
						swiper.autoplay.start();
					});
				}
			}
		}
	};
	const VerticalBannerSliderHandler = function ($scope) {
		const $verticalBannerSlider = $scope.find('.bt-vertical-banner-slider');
		if ($verticalBannerSlider.length > 0) {
			const $backgrounds = $verticalBannerSlider.find('.bt-banner-background');
			const $headings = $verticalBannerSlider.find('.bt-banner-heading');
			const $autoplay = $verticalBannerSlider.data('autoplay');
			const $autoplaySpeed = $verticalBannerSlider.data('autoplay-speed');
			const $autoplayOnlyMobile = $verticalBannerSlider.data('autoplay-only-mobile');
			let currentActive = 0;

			function setActiveItem(index) {
				// Remove active class from all backgrounds and headings
				$backgrounds.removeClass('active');
				$headings.removeClass('active');

				// Add active class to target items
				$backgrounds.eq(index).addClass('active');
				$headings.eq(index).addClass('active');
				currentActive = index;
			}

			// Hover event on headings to change active banner
			$headings.on('mouseenter', function () {
				const index = $(this).index();
				setActiveItem(index);
			});

			// Set first item as active by default
			setActiveItem(0);
			// Auto rotate for mobile screens
			function autoRotateBanners() {
				const totalBanners = $backgrounds.length;
				setActiveItem((currentActive + 1) % totalBanners);
			}

			// Check screen width and start/stop auto rotation 
			function handleAutoRotation() {
				// Clear any existing interval
				const existingInterval = $verticalBannerSlider.data('autoRotateInterval');
				if (existingInterval) {
					clearInterval(existingInterval);
					$verticalBannerSlider.data('autoRotateInterval', null);
				}

				// Only run autoplay if enabled
				if ($autoplay === 'yes') {
					const isMobile = window.innerWidth <= 767;
					const shouldAutoplay = $autoplayOnlyMobile === 'yes' ? isMobile : true;

					if (shouldAutoplay) {
						// Start auto rotation with configured speed
						const autoRotateInterval = setInterval(autoRotateBanners, $autoplaySpeed);
						$verticalBannerSlider.data('autoRotateInterval', autoRotateInterval);

						// Stop autoplay on heading hover
						$headings.off('mouseenter.autoplay').on('mouseenter.autoplay', function () {
							const currentInterval = $verticalBannerSlider.data('autoRotateInterval');
							if (currentInterval) {
								clearInterval(currentInterval);
								$verticalBannerSlider.data('autoRotateInterval', null);
							}
						});

						// Resume autoplay when mouse leaves headings
						$headings.off('mouseleave.autoplay').on('mouseleave.autoplay', function () {
							const newInterval = setInterval(autoRotateBanners, $autoplaySpeed);
							$verticalBannerSlider.data('autoRotateInterval', newInterval);
						});
					}
				}
			}

			// Initial check
			handleAutoRotation();

			// Check on resize
			$(window).off('resize.bannerSlider').on('resize.bannerSlider', handleAutoRotation);
		}
	}
	/* Bundle Save Handler */
	const BundleSaveHandler = function ($scope) {
		const $bundleSave = $scope.find('.bt-bundle-save');
		if ($bundleSave.length === 0) return;

		// Update data-ids and subtotal on load
		updateBundleDataIds($bundleSave);

		// Add More Button - Show modal
		$bundleSave.find('.bt-bundle-save--add-more-btn').on('click', function (e) {
			e.preventDefault();
			showModal($bundleSave);
		});

		// Modal Close
		$bundleSave.find('.bt-modal-close, .bt-modal-overlay').on('click', function (e) {
			e.preventDefault();
			$bundleSave.find('.bt-bundle-save--modal').fadeOut();
		});

		// Add product from modal
		$bundleSave.on('click', '.bt-modal-add-product', function (e) {
			e.preventDefault();
			const $button = $(this);
			const productId = $button.data('product-id');
			addProductToBundle($bundleSave, productId, $button);
		});

		// Remove product
		$bundleSave.on('click', '.bt-product-remove', function (e) {
			e.preventDefault();
			const $item = $(this).closest('.bt-bundle-product--item');
			$item.fadeOut(300, function () {
				$(this).remove();
				updateBundleDataIds($bundleSave);
			});
		});

		// Add all to cart
		$bundleSave.find('.bt-bundle-save--add-cart-btn').on('click', function (e) {
			e.preventDefault();
			addBundleToCart($bundleSave, $(this));
		});

		function showModal($widget) {
			const $modal = $widget.find('.bt-bundle-save--modal');
			const $modalBody = $modal.find('.bt-modal-body');
			const $productsContainer = $widget.find('.bt-bundle-save--products');

			const bundleProducts = $productsContainer.data('bundle-products') || [];
			const currentProducts = [];

			// Collect all current product/variation IDs
			$widget.find('.bt-bundle-product--item').each(function () {
				const variationId = parseInt($(this).data('variation-id'));
				const productId = parseInt($(this).data('product-id'));

				// If it's a variation, use variation ID, otherwise use product ID
				const itemId = variationId > 0 ? variationId : productId;
				currentProducts.push(itemId);
			});

			// Filter out products that are already in the bundle
			const availableProducts = bundleProducts.filter(function (id) {
				return currentProducts.indexOf(parseInt(id)) === -1;
			});

			if (availableProducts.length === 0) {
				$modalBody.html('<p class="bt-no-products">All products are already added to the bundle.</p>');
			} else {
				loadAvailableProducts($widget, availableProducts, $modalBody);
			}

			$modal.fadeIn();
		}

		function loadAvailableProducts($widget, productIds, $container) {
			$container.html('<p class="bt-loading">Loading products...</p>');

			$.ajax({
				url: AJ_Options.ajax_url,
				type: 'POST',
				data: {
					action: 'woozio_get_bundle_products',
					product_ids: productIds
				},
				success: function (response) {
					if (response.success && response.data.html) {
						$container.html(response.data.html);
					} else {
						$container.html('<p class="bt-error">Failed to load products.</p>');
					}
				},
				error: function () {
					$container.html('<p class="bt-error">Failed to load products.</p>');
				}
			});
		}

		function addProductToBundle($widget, productId, $button) {
			const $productsContainer = $widget.find('.bt-bundle-save--products');
			$button.prop('disabled', true).text('Adding...');

			$.ajax({
				url: AJ_Options.ajax_url,
				type: 'POST',
				data: {
					action: 'woozio_get_bundle_product_item',
					product_id: productId
				},
				success: function (response) {
					if (response.success && response.data.html) {
						$productsContainer.append(response.data.html);
						updateBundleDataIds($widget);

						// Remove this product item from modal
						$button.closest('.bt-modal-product--item').fadeOut(300, function () {
							$(this).remove();

							// Check if any products left in modal
							const $modal = $widget.find('.bt-bundle-save--modal');
							const $modalBody = $modal.find('.bt-modal-body');
							const remainingProducts = $modalBody.find('.bt-modal-product--item').length;

							if (remainingProducts === 0) {
								$modalBody.html('<p class="bt-no-products">All products are already added to the bundle.</p>');
								setTimeout(function () {
									$modal.fadeOut();
								}, 1000);
							}
						});
					} else {
						$button.prop('disabled', false).text('Add');
					}
				},
				error: function () {
					$button.prop('disabled', false).text('Add');
				}
			});
		}

		function updateBundleDataIds($widget) {
			const $productsContainer = $widget.find('.bt-bundle-save--products');
			const $addCartBtn = $widget.find('.bt-bundle-save--add-cart-btn');
			const idsArr = [];
			let subtotal = 0;
			let regularTotal = 0;
			let productCount = 0;

			$widget.find('.bt-bundle-product--item').each(function () {
				const $item = $(this);
				const productId = parseInt($item.data('product-id'));
				const variationId = parseInt($item.data('variation-id')) || 0;
				const price = parseFloat($item.data('price')) || 0;
				const regularPrice = parseFloat($item.data('regular-price')) || price;

				idsArr.push({
					product_id: productId,
					variation_id: variationId
				});

				subtotal += price;
				regularTotal += regularPrice;
				productCount++;
			});

			// Calculate savings
			const savings = regularTotal - subtotal;
			const savingsPercent = regularTotal > 0 ? ((savings / regularTotal) * 100) : 0;

			// Update progress bar
			const $progressBar = $widget.find('.bt-progress-fill');
			const $discountText = $widget.find('.bt-discount-text');

			if ($progressBar.length) {
				$progressBar.css('width', savingsPercent.toFixed(0) + '%');
			}

			if ($discountText.length) {
				let template = $discountText.data('template');
				if (!template) {
					return;
				}
				let text = template;

				// Replace placeholders with styled spans
				text = text.replace(/\{count\}/g, '<span>' + productCount + '</span>');
				text = text.replace(/\{discount\}/g, '<span>' + savingsPercent.toFixed(0) + '%</span>');

				$discountText.html(text);
			}

			// Update button data-ids
			$addCartBtn.attr('data-ids', JSON.stringify(idsArr));
			// Check if no products 
			if (idsArr.length === 0) {
				$addCartBtn.prop('disabled', true);
			} else {
				$addCartBtn.prop('disabled', false);
			}

			// Update subtotal display
			const currencySymbol = AJ_Options.currency_symbol || '$';
			const formattedSubtotal = currencySymbol + subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
			$widget.find('.bt-subtotal-amount').text(formattedSubtotal);
		}

		function addBundleToCart($widget, $button) {
			let productIds = $button.data('ids');

			if (typeof productIds === 'string') {
				try {
					productIds = JSON.parse(productIds);
				} catch (e) {
					console.error('Invalid data-ids JSON', e);
					productIds = [];
				}
			}
			if ($button.hasClass('bt-view-cart')) {
				window.location.href = AJ_Options.cart;
				return;
			}
			if (!Array.isArray(productIds) || productIds.length === 0) {
				alert('Please add products to the bundle first.');
				return;
			}

			const originalText = $button.text();
			$button.prop('disabled', true).addClass('loading');

			// Show toast for each product

			productIds.forEach((item, idx) => {

				const productId = item.variation_id && item.variation_id !== 0 ? item.variation_id : item.product_id;
				setTimeout(() => {
					WoozioshowToast(productId, 'cart', 'add');
				}, idx * 300);
			});
			if ($(window).width() <= 1023) {
				$('.bt-mini-cart-sidebar').addClass('active');
				const scrollbarWidth = window.innerWidth - $(window).width();
				$('body').css({
					'overflow': 'hidden',
					'padding-right': scrollbarWidth + 'px' // Prevent layout shift
				});
			}
			$.ajax({
				url: AJ_Options.ajax_url,
				type: 'POST',
				data: {
					action: 'woozio_add_multiple_to_cart_variable',
					product_ids: productIds
				},
				success: function (response) {
					$button.removeClass('loading');
					if (response.success) {
						$(document.body).trigger('updated_wc_div');
						WoozioFreeShippingMessage();
						$button.text('View Cart').prop('disabled', false).addClass('bt-view-cart');

					} else {
						alert('Failed to add products to cart.');
						$button.prop('disabled', false).text(originalText);
					}
				},
				error: function () {
					$button.removeClass('loading');
					alert('Failed to add products to cart.');
					$button.prop('disabled', false).text(originalText);
				}
			});
		}
	};

	const OrderTrackingHandler = function ($scope, $) {
		const $form = $scope.find('.bt-order-tracking-form');
		const $message = $scope.find('.bt-order-tracking-message');
		const $result = $scope.find('.bt-order-tracking-result');

		if ($form.length) {
			$form.on('submit', function (e) {
				e.preventDefault();

				const orderId = $form.find('input[name="order_id"]').val();
				const billingEmail = $form.find('input[name="billing_email"]').val();
				const $button = $form.find('button[type="submit"]');

				// Reset messages
				$message.html('').removeClass('error success');
				$result.hide();

				// Disable button
				$button.prop('disabled', true).text($button.data('loading-text') || 'Loading...');

				// AJAX request
				$.ajax({
					url: AJ_Options.ajax_url,
					type: 'POST',
					data: {
						action: 'woozio_track_order',
						order_id: orderId,
						billing_email: billingEmail,
						nonce: AJ_Options.order_tracking_nonce
					},
					success: function (response) {
						$button.prop('disabled', false).text($button.data('original-text') || 'Track');

						if (response.success) {
							$message.html(response.data.message).addClass('success');
							if (response.data.html) {
								$result.html(response.data.html).slideDown(400, function() {
									// Smooth scroll to result
									$('html, body').animate({
										scrollTop: $result.offset().top - 100
									}, 600);
								});
								
								// Initialize tabs
								initOrderTrackingTabs($result);
							}
						} else {
							$message.html(response.data.message).addClass('error');
						}
					},
					error: function () {
						$button.prop('disabled', false).text($button.data('original-text') || 'Track');
						$message.html('An error occurred. Please try again.').addClass('error');
					}
				});
			});

			// Store original button text
			const $button = $form.find('button[type="submit"]');
			$button.data('original-text', $button.text());
			$button.data('loading-text', 'Loading...');
		}

		// Initialize tabs functionality
		function initOrderTrackingTabs($container) {
			const $tabBtns = $container.find('.bt-tab-btn');
			const $tabContents = $container.find('.bt-tab-content');

			$tabBtns.on('click', function() {
				const targetTab = $(this).data('tab');
				
				// Update buttons
				$tabBtns.removeClass('active');
				$(this).addClass('active');
				
				// Update content
				$tabContents.removeClass('active');
				$container.find('#' + targetTab + '-tab').addClass('active');
			});
		}
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-mobile-menu.default', SubmenuToggleHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-list-faq.default', FaqHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-accordion.default', BtAccordionHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-search-product.default', SearchProductHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-heading-animation.default', headingAnimationHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-instagram-posts.default', InstagramPostsHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-banner-product-slider.default', BannerProductSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-offers-slider.default', OffersSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-tooltip-hotspot.default', ProductTooltipHotspotHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-testimonial.default', ProductTestimonialHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-testimonial-slider.default', TestimonialSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-testimonials-staggered-slider.default', TestimonialsStaggeredSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-countdown.default', countDownHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-site-notification.default', NotificationSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-mini-cart.default', MiniCartHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-currency-switcher.default', SwitcherHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-language-switcher.default', SwitcherHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-accordion-with-product-slider.default', AccordionWithProductSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-title-nav-with-slider.default', TitleNavWithSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-collection-banner.default', CollectionBannerHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-overlay-hotspot.default', ProductHotspotOverlayHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-text-slider.default', TextSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-showcase.default', ProductShowcaseHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-showcase-style-1.default', ProductShowcaseHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-showcase-style-2.default', ProductShowcaseHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-showcase-style-2.default', ProductShowcaseStyle2Handler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-list-hotspot.default', ProductListHotspotHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-store-locations-slider.default', StoreLocationsHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-slider-bottom-hotspot.default', ProductSliderBottomHotspotHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-nav-image.default', ProductNavImageHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-brand-slider.default', BrandSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-vertical-banner-slider.default', VerticalBannerSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-bundle-save.default', BundleSaveHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-order-tracking.default', OrderTrackingHandler);
	});

})(jQuery);
