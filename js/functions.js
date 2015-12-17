/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */

 var boron = {};

jQuery(window).load(function() {

});

jQuery(document).ready(function($) {
	if ( jQuery('.content-wrapper article').length ) {
		var $isotope = jQuery('.content-wrapper');

		$isotope.imagesLoaded( function(){
			$isotope.isotope({
				itemSelector: 'article',
				transitionDuration: 0
			});
		});
	};

	function boron_getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++)
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam)
			{
				return sParameterName[1];
			}
		}
	}

	function get_rest_filters() {
		var filters = 'per_page=' + WP_API_Settings.posts_per_page + '&page=' + jQuery('#current-post-page').val();
		if ( jQuery('body').hasClass('home blog') ) {
			// filters = '';
		} else if ( jQuery('body').hasClass('archive category') ) {
			var category = jQuery('body').attr("class").match(/category-[\w-]*\b/);
				category = category['0'].split('-');
			filters += '&filter[category_name]=' + category['1'];
		} else if ( jQuery('body').hasClass('search search-results') ) {
			filters += '&filter[s]=' + boron_getUrlParameter('s');
		} else if ( jQuery('body').hasClass('archive author') ) {
			var author = jQuery('body').attr("class").match(/author-[\w-]*\b/g);
				author = author['1'].split('-');
			filters += '&filter[author]=' + author['1'];
		} else if ( jQuery('body').hasClass('archive tag') ) {
			var tag = jQuery('body').attr("class").match(/tag-[\w-]*\b/g);
				tag = tag['0'].split('-');
			filters += '&filter[tag]=' + tag['1'];
		} else if ( jQuery('body').hasClass('archive date') ) {
			if ( WP_API_Settings.dates.year != '' ) {
				filters += '&filter[year]=' + WP_API_Settings.dates.year;
			};
			if ( WP_API_Settings.dates.monthnum != '' ) {
				filters += '&filter[monthnum]=' + WP_API_Settings.dates.monthnum;
			};
			if ( WP_API_Settings.dates.day != '' ) {
				filters += '&filter[day]=' + WP_API_Settings.dates.day;
			};
		} else if ( jQuery('body').hasClass('archive tax-post_format') ) {
			// var post_format = jQuery('body').attr("class").match(/term-[\w-]*\b/g);
			// 	post_format = post_format['1	'].split('-');
			// filters += '&filter[tax_query]=' + WP_API_Settings.post_tax;
		}

		return '?'+filters;
	}

	function boron_load_posts() {
		if ( jQuery('#current-post-page').val() == -1 || jQuery('body').hasClass('pull-content-to-side') || jQuery('body').hasClass('prevent-loading') ) {
			return;
		};
		jQuery('.posts-loading').addClass('start');
		jQuery.ajax( {
			url: WP_API_Settings.root + 'wp/v2/posts' + get_rest_filters(),
			method: 'GET',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', WP_API_Settings.nonce );
				jQuery('body').addClass('prevent-loading');
			}
		} ).done( function ( response ) {
			var $new_posts = '';
			var $new_images = '';
			jQuery('.posts-loading').removeClass('start');
			jQuery.each(response, function() {
				var current_post = jQuery(this);
				current_post = current_post['0'];

				$new_posts += '<article id="post-' + current_post.id + '" class="' + current_post.boron_extra.post_classes + ' post-new-item"><header class="entry-header simple"><div class="single-image-container">';
				if ( current_post.boron_extra.image_src != null ) {
					$new_posts += '<img src="' + current_post.boron_extra.image_src + '" class="single-post-image" alt="Post with image">';
				} else {
					$new_posts += '<span class="post-no-image"></span>';
				}
				$new_posts += '<div class="single-post-meta">' + current_post.boron_extra.tag_list + '<span class="single-post-date icon-clock">' + current_post.boron_extra.date_ago + '</span><span class="single-post-like icon-chat">' + current_post.boron_extra.comments + '</span></div></div><input type="hidden" class="single-post-url" value="' + current_post.link + '"></header><script type="text/template" class="single-post-template">' + current_post.boron_extra.post_template + '</script><script type="text/template" class="single-post-side-template">' + current_post.boron_extra.post_side_template + '</script></article>';
			});

			// Preload images
			jQuery('#img-preloader').append($new_posts);

			jQuery.each(jQuery($new_posts), function() {
				var current_new_post = jQuery(this);
				current_new_post = current_new_post['0'];
				if ( jQuery(current_new_post).find('.post-no-image').length ) {
					$isotope.isotope( 'insert', jQuery(current_new_post) );
					jQuery(current_new_post).addClass('position');
				};
			});

			jQuery('#img-preloader').imagesLoaded().progress( function( imgLoad, image ) {
				var loaded_element = jQuery(image.img).parent().parent().parent();
				$isotope.isotope( 'insert', jQuery(loaded_element) );
				jQuery(loaded_element).addClass('position');
			});

			jQuery('#current-post-page').val(parseInt(jQuery('#current-post-page').val())+1);
			
			if ( response.length < WP_API_Settings.posts_per_page ) {
				jQuery('#current-post-page').val('-1');
				jQuery('#no-more-posts').addClass('show');
			}

			jQuery('body').removeClass('prevent-loading');
		} );
	}

	if ( jQuery('.content-wrapper').length ) {
		$(window).bind('mousewheel', function(event) {
			if ( event.originalEvent.wheelDelta <= 0 ) {
				if($(window).scrollTop() + $(window).height() >= $(document).height()) {
					boron_load_posts();
				}
			};
		});
	};

	setTimeout(function() {
		if ( jQuery('.content-wrapper').height() < jQuery(window).height()) {
			boron_load_posts();
		};
	}, 500);

	if ( jQuery('body').hasClass('single') ) {
		if ( WP_API_Settings.post_comments == 'side' ) {
			jQuery('.single-post-side-comments').html(jQuery('.single-post-inner').find('.comments-container').html());
			jQuery('.single-post-inner').find('.comments-container').html('');
		}
	};

	jQuery(document).on('click', '.content-wrapper article', function() {
		// Set old url
		var old_href = window.location.href;
		jQuery('#current-page-url').val( old_href );

		// Change URL
		var href = jQuery(this).find('.single-post-url').val();
		history.pushState({page: href}, '', href);

		// Add post content
		var inner_content = jQuery(this).find('.single-post-template').html();
		inner_content.replace('&lt;', '<').replace('&gt;', '>');
		jQuery('.single-post-inner').html( inner_content );

		// Check comment location
		if ( WP_API_Settings.post_comments == 'side' ) {
			jQuery('.single-post-side-comments').html(jQuery('.single-post-inner').find('.comments-container').html());
			jQuery('.single-post-inner').find('.comments-container').html('');
		}

		jQuery('.single-post-side').html( jQuery(this).find('.single-post-side-template').html() );

		jQuery('body').addClass('pull-content-to-side');
		
		window.scrollTo(0,0);

		setTimeout(function() {
			jQuery('body').addClass('pull-content-to-side-ended');
		}, 800);
	});

	jQuery(document).on('click', '.single-post-close, .site-small-logo', function() {
		if ( jQuery('body').hasClass('show-search') ) {
			jQuery('body').removeClass('show-search');
		};
		if ( typeof jQuery('#current-page-url').val() == 'undefined') {
			window.location.href = WP_API_Settings.home_url;
		} else {
			var href = jQuery('#current-page-url').val();
			history.pushState({page: href}, '', href);

			jQuery('body').addClass('pull-content-back pull-content-back-started prevent-loading');

			setTimeout(function() {
				jQuery('body').removeClass('pull-content-to-side pull-content-back pull-content-to-side-ended pull-content-back-started');
				jQuery('.single-post-inner, .single-post-side').html('');
			}, 800);

			setTimeout(function() {
				jQuery('body').removeClass('prevent-loading');
			}, 1500)
		}
	});

	jQuery(document).on('click', '.side-comments', function() {
		if ( WP_API_Settings.post_comments == 'side' ) {
			jQuery('body').addClass('show-side-comments');
		} else if ( WP_API_Settings.post_comments == 'bottom' ) {
			$('body,html').animate({
				scrollTop: jQuery('.comments-container').offset().top
			}, 800);
			return false;
		}
	});

	jQuery(document).on('click', '.comment-reply-link', function(e) {
		if ( WP_API_Settings.post_comments != 'side' ) {
			window.scrollTo(0, jQuery('.comment #respond').offset().top);
		}
	});

	jQuery(document).on('click', '.show-side-comments .single-post-container', function(e) {
		var target = $(e.target);
		if ( !target.is('.single-post-side-comments') && !target.is('input') && !target.is('textarea') && !target.is('.comment-reply-link') && !target.is('.cancel-comment-reply-link') ) {
			jQuery('body').removeClass('show-side-comments');
		};
	});

	jQuery(document).on('click', '#main-content .category-link a', function(e) {
		e.preventDefault();
	});

	jQuery(document).on('click', '.main-search', function() {
		jQuery('body').addClass('show-search');
		setTimeout(function() {
			jQuery('.main-search-form .search-field').focus();
		}, 100)
	});

	jQuery(document).on('click', '.main-search-form', function(e) {
		var target = $(e.target);
		if ( !target.is('input') && ( !target.is('input') && !target.is('textarea') ) ) {
			jQuery('body').removeClass('show-search');
		};
	});
});

( function( $ ) {
	var body    = $( 'body' ),
		_window = $( window );

	$('.scroll-to-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	jQuery(document).scroll(function() {
		if ( jQuery(document).scrollTop() >= 200 ) {
			jQuery('.site-header').addClass('scrolled');
		} else {
			jQuery('.site-header').removeClass('scrolled');
		}
	});

	// Enable menu toggle for small screens.
	( function() {
		var nav = $( '#primary-navigation' ), button, menu;
		if ( ! nav ) {
			return;
		}

		button = nav.find( '.menu-toggle' );
		if ( ! button ) {
			return;
		}

		// Hide button if menu is missing or empty.
		menu = nav.find( '.nav-menu' );
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		$( '.menu-toggle' ).on( 'click.boron', function() {
			nav.toggleClass( 'toggled-on' );
		} );
	} )();

	/*
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange.boron', function() {
		var element = document.getElementById( location.hash.substring( 1 ) );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();

			// Repositions the window on jump-to-anchor to account for header height.
			window.scrollBy( 0, -80 );
		}
	} );

	$( function() {

		/*
		 * Fixed header for large screen.
		 * If the header becomes more than 48px tall, unfix the header.
		 *
		 * The callback on the scroll event is only added if there is a header
		 * image and we are not on mobile.
		 */
		if ( _window.width() > 781 ) {
			var mastheadHeight = $( '#masthead' ).height(),
				toolbarOffset, mastheadOffset;

			if ( mastheadHeight > 48 ) {
				body.removeClass( 'masthead-fixed' );
			}

			if ( body.is( '.header-image' ) ) {
				toolbarOffset  = body.is( '.admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
				mastheadOffset = $( '#masthead' ).offset().top - toolbarOffset;

				_window.on( 'scroll.boron', function() {
					if ( ( window.scrollY > mastheadOffset ) && ( mastheadHeight < 49 ) ) {
						body.addClass( 'masthead-fixed' );
					} else {
						body.removeClass( 'masthead-fixed' );
					}
				} );
			}
		}

		// Focus styles for menus.
		$( '.primary-navigation, .secondary-navigation' ).find( 'a' ).on( 'focus.boron blur.boron', function() {
			$( this ).parents().toggleClass( 'focus' );
		} );
	} );
} )( jQuery );

/*------------------------------------------------------------
 * FUNCTION: Scroll Page Back to Top
 * Used for ajax navigation scroll position reset
 *------------------------------------------------------------*/

function scrollPageToTop(){
	// Height hack for mobile/tablet
	jQuery('body').css('height', 'auto');
	jQuery("html, body").animate({ scrollTop: 0 }, "slow");

	// if( boron.device != 'desktop' ){
		// jQuery('body').scrollTop(0);
	// }else{
	//  jQuery('.content-wrapper').scrollTop(0);
	// }

	jQuery('body').css('height', '');
}

(function() {

	// detect if IE : from http://stackoverflow.com/a/16657946      
	var ie = (function(){
		var undef,rv = -1; // Return value assumes failure.
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf('MSIE ');
		var trident = ua.indexOf('Trident/');

		if (msie > 0) {
			// IE 10 or older => return version number
			rv = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
		} else if (trident > 0) {
			// IE 11 (or newer) => return version number
			var rvNum = ua.indexOf('rv:');
			rv = parseInt(ua.substring(rvNum + 3, ua.indexOf('.', rvNum)), 10);
		}

		return ((rv > -1) ? rv : undef);
	}());


	// disable/enable scroll (mousewheel and keys) from http://stackoverflow.com/a/4770179                  
	// left: 37, up: 38, right: 39, down: 40,
	// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
	var keys = [37, 38, 39, 40], wheelIter = 0;

	function preventDefault(e) {
		e = e || window.event;
		if (e.preventDefault)
		e.preventDefault();
		e.returnValue = false;  
	}

	function keydown(e) {
		for (var i = keys.length; i--;) {
			if (e.keyCode === keys[i]) {
				preventDefault(e);
				return;
			}
		}
	}

	function touchmove(e) {
		preventDefault(e);
	}

	function wheel(e) {
		// for IE 
		//if( ie ) {
			//preventDefault(e);
		//}
	}

	function disable_scroll() {
		window.onmousewheel = document.onmousewheel = wheel;
		document.onkeydown = keydown;
		document.body.ontouchmove = touchmove;
	}

	function enable_scroll() {
		window.onmousewheel = document.onmousewheel = document.onkeydown = document.body.ontouchmove = null;  
	}

	var docElem = window.document.documentElement,
		scrollVal,
		isRevealed, 
		noscroll, 
		isAnimating;

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	function scrollPage() {
		scrollVal = scrollY();
		
		if( noscroll && !ie ) {
			if( scrollVal < 0 ) return false;
			// keep it that way
			window.scrollTo( 0, 0 );
		}

		if( jQuery('body').hasClass( 'notrans' ) ) {
			jQuery('body').removeClass( 'notrans' );
			return false;
		}

		if( isAnimating ) {
			return false;
		}
		
		if( scrollVal <= 0 && isRevealed ) {
			toggle(0);
		}
		else if( scrollVal > 0 && !isRevealed ){
			toggle(1);
		}
	}

	function toggle( reveal ) {
		isAnimating = true;
		
		if( reveal ) {
			jQuery('body').addClass( 'modify' );
		}
		else {
			noscroll = true;
			disable_scroll();
			jQuery('body').removeClass( 'modify' );
		}

		// simulating the end of the transition:
		setTimeout( function() {
			isRevealed = !isRevealed;
			isAnimating = false;
			if( reveal ) {
				noscroll = false;
				enable_scroll();
			}
		}, 600 );
	}

	if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

		// refreshing the page...
		var pageScroll = scrollY();
		noscroll = pageScroll === 0;

		disable_scroll();

		if( pageScroll ) {
			isRevealed = true;
			jQuery('body').addClass( 'notrans' );
			jQuery('body').addClass( 'modify' );
		}

		
	} else if ( jQuery('body').hasClass('single-post') && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		jQuery('body').addClass( 'notrans' );
		jQuery('body').addClass( 'modify' );
	}
	
})();
