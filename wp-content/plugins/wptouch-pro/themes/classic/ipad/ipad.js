/* WPtouch Pro Classic iPad JS */
/* This file holds all the default jQuery & Ajax functions for the classic theme on iPad */
/* Description: JavaScript for the Classic theme on iPad */
/* Required jQuery version: 1.6.1+ */

var touchJS = jQuery.noConflict();
var WPtouchWebApp = window.navigator.standalone;
/* For debugging Web-App mode in a browser */
//var WPtouchWebApp = true;

/* see http://cubiq.org/add-to-home-screen for additional options */
var addToHomeConfig = {
	animationIn: 'bubble',
	animationOut: 'bubble',
	startDelay: 800,			// milliseconds
	lifespan: 1000*60, 		// milliseconds  (set to: 30 secs)
	expire: 60*24*30,				// minutes (set to: 30 days)
	bottomOffset: 12,
	touchIcon: true,
	arrow: true,
	message: WPtouch.add2home_message
};

/* Try to get out of frames! */
if ( top.location!= self.location ) { 
	top.location = self.location.href
}

/* If it's iPad, use touchstart, in desktop browser, use click (touchstart/end is faster on iOS) */
if ( navigator.platform == 'iPad' && typeof orientation != 'undefined' ) { 
	var touchStartOrClick = 'touchstart'; 
	var touchEndOrClick = 'touchend'; 
} else {
	var touchStartOrClick = 'click'; 
	var touchEndOrClick = 'click'; 
};

function doClassiciPadReady() {

	/* Prevent default touchmove function, ready for iScrolls */	
	document.addEventListener( 'touchmove', function( e ){ e.preventDefault(); } );
	
	/* Add the orientation event listener */	
	window.addEventListener( 'orientationchange', function() {
		classicUpdateOrientation();
	});
	
	/* Setup iScrolls */

	classicMainScroll = new iScroll( 'iscroll-wrapper', { bounceLock: true, hScrollbar: false } );

	if ( touchJS( '#pages-wrapper' ).length ) {
		classicPagesScroll = new iScroll( 'pages-wrapper' );
	} else { 
		classicPagesScroll = classicMainScroll; 
	}

	if ( touchJS( '#recent-wrapper' ).length ) {
		classicRecentScroll = new iScroll( 'recent-wrapper' );
	} else { 
		classicRecentScroll = classicMainScroll; 
	}

	if ( touchJS( '#popular-wrapper' ).length ) {
		classicPopularScroll = new iScroll( 'popular-wrapper' );
	} else { 
		classicPopularScroll = classicMainScroll; 
	}

	if ( touchJS( '#tags-wrapper' ).length ) {
		classicTagsScroll = new iScroll( 'tags-wrapper' );
	} else { 
		classicTagsScroll = classicMainScroll; 
	}

	if ( touchJS( '#cats-wrapper' ).length ) {
		classicCatsScroll = new iScroll( 'cats-wrapper' );
	} else { 
		classicCatsScroll = classicMainScroll; 
	}
	
	if ( touchJS( '#flickr-wrapper' ).length ) {
		classicFlickrScroll = new iScroll( 'flickr-wrapper' );
	} else { 
		classicFlickrScroll = classicMainScroll; 
	}

	if ( touchJS( '#wordtwit-wrapper' ).length ) {
		classicWordTwitScroll = new iScroll( 'wordtwit-wrapper' );
	} else { 
		classicWordTwitScroll = classicMainScroll; 
	}


	/* Menubar Button Left Popover Triggers */	
	touchJS( '.head-left div.menubar-button' ).bind( touchEndOrClick, function() {
		var popoverName = '#pop-' + touchJS( this ).attr( 'id' );
		var linkOffset = touchJS( this ).offset();
		touchJS( popoverName ).css({
			top: linkOffset.top + 32 + 'px',
			left: linkOffset.left + 'px'
		}).popOverToggle();
		touchJS( popoverName + ' .menu-pointer-arrow' ).css( 'left', '20px' );
		headerDismissSpan();
		setTimeout( function () { allScrollsRefresh(); }, 0 );
	});
	
	/* Menubar Button Right Popover Triggers */	
	touchJS( '.head-right div.menubar-button' ).bind( touchEndOrClick, function() {
		var popoverName = '#pop-' + touchJS( this ).attr( 'id' );
		var linkOffset = touchJS( this ).offset();
		touchJS( popoverName ).css({
			top: linkOffset.top + 32 + 'px',
			left: linkOffset.left - touchJS( popoverName ).width() / 1.45
		}).popOverToggle();
		touchJS( popoverName + ' .menu-pointer-arrow' ).css( 'right', '58px' );
		headerDismissSpan();
		setTimeout( function () { allScrollsRefresh(); }, 0 );
	});
	
	/*  Tap the menubar to scroll the main content to top */	
	touchJS( '.head-center h1' ).bind( touchStartOrClick, function() {
		classicMainScroll.scrollTo( 0, 0, 500 );
	});

	/*  Menubar Blog PopOver Inner Tabs */
	touchJS( function() {
	    var tabContainers = touchJS( '#pop-blog > div' );

	    touchJS( 'ul.menu-tabs a' ).bind( touchStartOrClick, function( e ) {
	        tabContainers.hide().filter( this.rel ).show();
	    	touchJS( 'ul.menu-tabs a' ).removeClass( 'selected' );
	   		touchJS( this ).addClass( 'selected' );
			e.preventDefault();
			setTimeout( function() { 
				allScrollsRefresh();
			}, 0 );
	  	  }).filter( ':first' ).trigger( touchStartOrClick );
	});

	/* Add highlights to a popover and menu links when clicked */
		touchJS( '#popovers-container .pop-inner li a, #pages-wrapper a' ).live( 'click', function() {
			touchJS( this ).parent().toggleClass( 'highlight' );
		});

	/* Live .active styling to mimic default iOS functionality */
	var touchDivs = '.button, .content a, .title-area a, #switch a, .footer a';
		touchJS( touchDivs ).live( touchStartOrClick, function() {
			touchJS( this ).toggleClass( 'active' );
		}).live( touchEndOrClick, function() {
			touchJS( this ).toggleClass( 'active' );
		});
	
	/* Page menu: Hide the Child ULs */
	touchJS( '#pages-wrapper' ).find( 'li.has_children ul' ).hide();

	/* Page menu: Filter parent link href's and make them toggles for thier children */
	touchJS( '#pages-wrapper ul li.has_children > a' ).unbind( 'click' ).bind( 'click', function() {
		touchJS( this ).next().webkitSlideToggle( 350 );
		touchJS( this ).toggleClass( 'arrow-toggle' );
		touchJS( this ).parent().toggleClass( 'open-tree' );
		setTimeout( function () { classicPagesScroll.refresh(); }, 0 );
		return false;
	});
	
	/* Single post page share popover */	
	touchJS( 'a.share-post' ).unbind( 'click' ).bind( touchStartOrClick, function() {
		var linkOffset = touchJS( this ).offset();
			touchJS( '#share-popover' ).css({
				top: linkOffset.top - 260 + 'px',
				left: linkOffset.left -	 25 + 'px',
				}).fadeIn( 350 );
		headerDismissSpan();
		return false;
	});
	
	/*  On single posts and pages, move the comments to the fly-in box */
	touchJS( '#respond' ).detach().appendTo( '.comment-reply-box .pop-inner' ).css( 'display', 'block' );
	touchJS( '#share-placeholder' ).detach().appendTo( '#share-popover' ).css( 'display', 'block' );

	touchJS( 'a.leave-a-comment, .comments-close-button' ).unbind( 'click' ).bind( touchEndOrClick, function() {
		touchJS( '.comment-reply-box' ).toggleClass( 'fly-in' ).flyInToggle();
		touchJS( 'input#comment_parent' ).val( '0' );
		touchJS( '#box-head h3' ).html( WPtouch.leave_a_comment );
		touchJS( '#peek' ).hide();
		touchJS( '#container1 textarea' ).removeClass( 'reply' );
		touchJS('#commentform input, #commentform textarea').blur();
		return false;
	});

	/* Peek in Comment Reply */
	touchJS( '#peek' ).unbind( 'click' ).bind( touchEndOrClick, function() {
		touchJS( '#container1' ).toggleClass( 'slide' );
		touchJS( '#container2' ).toggleClass( 'slide2' );
		return false;
	});
  
	/* Log In To Comment Trigger */
  	touchJS( 'a.comment-reply-login, a.reply-to-comment' ).bind( touchStartOrClick, function() {
		touchJS( '#account.menubar-button' ).trigger( touchEndOrClick );
		return false;
	});	

	/* Try to make imgs and captions nicer in posts (images and caption larger than 350px get aligncenter)  */	
	if ( touchJS( '.post' ).length ) {
		touchJS( '.content img, .content .wp-caption' ).each( function() {
			if ( touchJS( this ).width() >= 350 ) {
				touchJS( this ).addClass( 'aligncenter' );
			}
		});
	}

	/*  Make sure the menubar stays present when form textareas are out of focus */	
	touchJS( 'textarea, form#prowl-direct-message input, form#loginform input' ).bind( 'blur', function() {
		scrollTo( 0, 0, 100 );		
	});
	
///* Refresh the Scrolling Div after Disqus loads ( if present *** EXPERIMENTAL *** ) */
//	if ( jQuery( 'body.disqus' ).length ) {
//		var disqusLoader = setInterval( function() {
//			if ( jQuery( '#disqus_thread' ).html().length ) {
//				clearInterval( disqusLoader );
//				classicMainScroll.refresh();
//			}
//		}, 1000 );
//    }
	
	/* Instapaper Share Hookup */
	touchJS( 'li#instapaper a' ).click( function() {
		var userName = prompt( WPtouch.instapaper_username, '' );
		if ( userName ) {
			var passWord = prompt( WPtouch.instapaper_password, '' );
			if ( !passWord ) {
				passWord = 'default';	
			}
			
			var ajaxParams = {
				url: document.location.href,
				username: userName,
				password: passWord,
				title: document.title
			};
			
			WPtouchAjax( 'instapaper', ajaxParams, function( result ) {
				if ( result == '1' ) {
					alert( WPtouch.instapaper_saved );
					touchJS( '#share-popover' ).fadeOut( 350 );
				} else {
					alert( WPtouch.instapaper_try_again );
				}
			});
		}
		return false;
	});

	/* Ajaxify commentform */
	var postURL = document.location;
	var CommentFormOptions = {
		beforeSubmit: function() {
			touchJS( '#container1' ).append( '<div id="comment-spinner"></div>' );
		},
		success: function() {
			touchJS( 'ol.commentlist' ).load( postURL + ' ol.commentlist > li', function(){ 
				touchJS( '#comment-spinner' ).remove();
				touchJS( '#container1 textarea' ).addClass( 'success' );			
				commentReplyLinks();
			});
			setTimeout( function () { 
				touchJS( '.comments-close-button' ).trigger( touchEndOrClick );
				touchJS( '#container1 textarea' ).removeClass( 'success' );
				classicMainScroll.refresh();
			}, 1500 );
		},
		error: function() {
		touchJS( '#comment-spinner' ).remove();
			touchJS( '#container1 textarea' ).addClass( 'error' );
			touchJS( '#container1' ).prepend( '<div id="error-text">' + WPtouch.comment_failure +'</div>' );
			setTimeout( function () { 
				touchJS( '#container1 textarea' ).removeClass( 'error' );
				touchJS( '#error-text' ).remove();
			}, 3000 );
		},
		resetForm: true,
		timeout:   7500
	} 	//end options
	
	
	if ( touchJS.isFunction( jQuery.fn.ajaxForm ) ) {
		touchJS( '#commentform' ).ajaxForm( CommentFormOptions );
	}

	/* Ajaxify Prowl Form */
	var prowlFormOptions = {
		beforeSubmit: function formValidate( formData, jqForm, options ) { 
				for ( var i=0; i < formData.length; i++ ) { 
					if ( !formData[i].value ) { 
						alert( WPtouch.validation_message ); 
						return false; 
					}
				touchJS( '#prowl-direct-message p' ).prepend( '<div id="prowl-spinner"></div>' );
				}
		},
		success: function() {
		touchJS( '#prowl-spinner' ).remove();
			touchJS( '#prowl-direct-message textarea' ).addClass( 'success' );
			setTimeout( function () { 
				touchJS( '#message' ).trigger( touchEndOrClick );
				touchJS( '#prowl-direct-message textarea' ).removeClass( 'success' );
			}, 1000 );
		},
		error: function() {
		touchJS( '#prowl-spinner' ).remove();
			touchJS( '#prowl-direct-message textarea' ).addClass( 'error' );
			touchJS( '#prowl-direct-message' ).prepend( '<div id="prowl-error-text">' + WPtouch.prowl_failure +'</div>' );
			setTimeout( function () { 
				touchJS( '#prowl-direct-message textarea' ).removeClass( 'error' );
				touchJS( '#prowl-error-text' ).remove();
			}, 4000 );
		},
		resetForm: true,
		timeout:   3500
	} 	//end options
	
	if ( touchJS.isFunction( jQuery.fn.ajaxForm ) ) {
		touchJS( '#prowl-direct-message' ).ajaxForm( prowlFormOptions );
	}

	/* Refresh the main iScroll when all page items are loaded */	
	touchJS( window ).load( function() {  
		var welcomeMessage = touchJS( '#welcome-message' ).length;		
		var formHeight = touchJS( 'div.comment-reply-box' ).height() + 150;
		touchJS( 'div.comment-reply-box' ).css('-webkit-transform', 'translateY(-'+formHeight+'px) rotate(-10deg)' ).show();
		if ( welcomeMessage ) { 
			touchJS( '#welcome-message' ).show();
		}
		setTimeout( function () { touchJS( '#iscroll-wrapper' ).addClass('in-display'); classicMainScroll.refresh(); }, 0 );
	});
	
	/* Set tabindex automagically */
	jQuery( function(){
	var tabindex = 1;
		jQuery( 'input, select, textarea' ).each( function() {
			if ( this.type != "hidden" ) {
				var inputToTab = jQuery( this );
				inputToTab.attr( 'tabindex', tabindex );
				tabindex++;
			}
		});
	});
	
	/* New Toggle Switch JS */
	var switchURL = jQuery( '#switch a' ).attr( 'href' );
	jQuery( '#switch :checkbox' ).iphoneStyle( { checkedLabel: WPtouch.toggle_on, uncheckedLabel: WPtouch.toggle_off } ).bind( 'change', function(){ 
		if ( !jQuery( this ).attr( 'checked' ) ) { window.location = switchURL } 
	});

	/* Functions to run onReady */
	classicUpdateOrientation();
	loadMoreEntries();
	commentReplyLinks();
	loadMoreComments();
	welcomeMessage();
	webAppLinks();
	webAppOnly();

}
/* End Document Ready */

function allScrollsRefresh() {
	classicMainScroll.refresh(); 
	classicPagesScroll.refresh(); 
	classicRecentScroll.refresh();
	classicPopularScroll.refresh();
	classicTagsScroll.refresh();
	classicCatsScroll.refresh();
	classicFlickrScroll.refresh();
	classicWordTwitScroll.refresh();
}

/* Detect orientation and do some Voodoo with the menubar's menu */
function classicUpdateOrientation() {	
	var windowHeight = touchJS( window ).height() - 44;
	var imageHeight = touchJS( '#logo-area' ).height();
	var menuHeight = windowHeight - imageHeight;
	var orientationCookie = WPtouchReadCookie( 'wptouch-ipad-orientation' );
	switch( window.orientation ) {
		// Portrait
		case 0:
		case 180:
			touchJS( 'body' ).removeClass( 'landscape' ).addClass( 'portrait' );
			touchJS( '#iscroll-wrapper' ).css( 'height', windowHeight );
			touchJS( '#main-menu #pages-wrapper' ).detach().appendTo( '#pop-menu .pop-inner' ).css( 'height', 'auto' ).css( 'max-height', '500px' );
			touchJS( '.popover.open' ).each( function() { touchJS( this ).removeClass( 'open' ).hide(); });
			WPtouchCreateCookie( 'wptouch-ipad-orientation', 'portrait', 365 );
		break;
		// Landscape & Browsers
		case 90:
		case -90:
		default:
			touchJS( 'body' ).removeClass( 'portrait' ).addClass( 'landscape' );
			touchJS( '#iscroll-wrapper' ).css( 'height', windowHeight );
			touchJS( '#pages-wrapper' ).detach().appendTo( '#main-menu' ).css( 'height', menuHeight ).css( 'max-height', 'none' );
			touchJS( '.popover.open' ).each( function() { touchJS( this ).removeClass( 'open' ).hide(); });
			WPtouchCreateCookie( 'wptouch-ipad-orientation', 'landscape', 365 );
	}
	setTimeout( function () { allScrollsRefresh(); }, 750 );
}

/* Create a dismiss span that will reverse open popovers when triggered */
function headerDismissSpan() {
	if ( !touchJS( '#dismiss-underlay' ).length ) {
		touchJS( 'body' ).append( '<span id="dismiss-underlay"></span>' );
		touchJS( '#dismiss-underlay' ).bind( touchStartOrClick, function( e ) {
			touchJS( '#popovers-container .popover.open' ).removeClass( 'open' ).fadeOut( 350 );
			touchJS( '#share-popover' ).fadeOut( 350 );
			touchJS( this ).remove();
			return false;
		});
	} else {
		touchJS( '#dismiss-underlay' ).remove();	
	}
}

function loadMoreEntries() {
	var loadMoreLink = touchJS( 'a.load-more-link' );
	var ajaxDiv = '.ajax-page-target';
	if ( loadMoreLink.length ) {
		loadMoreLink.unbind( 'click' ).live( 'click', function() {
			touchJS( 'a.load-more-link span' ).addClass( 'ajax-spinner' );
			var loadMoreURL = touchJS( this ).attr( 'href' );
			touchJS( '#content' ).append( "<div class='ajax-page-target'></div>" );
			touchJS( ajaxDiv ).hide().load( loadMoreURL + ' #content .post, #content .load-more-link', function() {
				touchJS( this ).replaceWith( touchJS( this ).html() );	
				touchJS( 'span.ajax-spinner' ).parent().fadeOut( 350 );
		 		setTimeout( function() { classicMainScroll.refresh(); }, 0 );
				webAppLinks();
			});
			return false;
		});	
	}	
}

/* Load More Comments */
function loadMoreComments() {
	var loadMoreLink = touchJS( 'ol.commentlist li.load-more-comments-link a' );
	if ( loadMoreLink.length ) {
		loadMoreLink.unbind( 'click' ).live( 'click', function() {
			var loadMoreURL = touchJS( this ).attr( 'href' );
			touchJS( this ).addClass( 'ajax-spinner' ).delay( 1250 ).fadeOut( 350 );
			touchJS( 'ol.commentlist' ).append( "<div class='ajax-page-target'></div>" );

			touchJS( 'div.ajax-page-target' ).hide().load( loadMoreURL + ' ol.commentlist > li', function() {
				touchJS( this ).replaceWith( touchJS( this ).html() );	
				commentReplyLinks();
		 		setTimeout( function() { classicMainScroll.refresh(); }, 0 );
				webAppLinks();
			});
			return false;
		});	
	}	
}

/* Comment Reply Gravy - Not perfect yet  */	
function commentReplyLinks() {
	touchJS( '.commentlist a.comment-reply-link' ).unbind( 'click' ).bind( touchStartOrClick, function() {
		var CommentID = touchJS( this ).closest( 'li.comment' ).attr( 'ID' );
		var CommenterName = touchJS( '#' + CommentID ).find( 'span.fn:first' ).text();
		var PostID = touchJS( '.commentlist ol' ).attr( 'ID' );
		var CommentContent = touchJS( 'li#' + CommentID + ' .comment-content:first > p' ).text();

		touchJS( '.comment-reply-box' ).addClass( 'fly-in' ).flyInToggle();		
		touchJS( '#box-head h3' ).html( WPtouch.leave_a_reply + ' <span>' + CommenterName + '</span>');
		touchJS( '#peek' ).show();
		touchJS( 'input#comment_parent' ).val( CommentID );
		touchJS( '#container1 textarea' ).addClass( 'reply' );
		touchJS( '#container2' ).html( CommentContent );
		return false;
	});
}

function webAppLinks() {
	if ( WPtouchWebApp ) {
		// The New Sauce ( Nobody makes tasty gravy like mom )		
		// bind to all links, except UI controls and such
		var webAppLinks = touchJS( 'a' ).not( 
			'.no-ajax, .email a, .button, .comment-buttons a, ul.menu-tabs a, #pages-wrapper .has_children > a, a.load-more-link, .load-more-comments-link a, #share-popover a' 
		);

 		webAppLinks.each( function(){
			var targetUrl = touchJS( this ).attr( 'href' ); 		
			var targetLink = touchJS( this ); 		
			var localDomain = location.protocol + '//' + location.hostname;

			// link is local, but set to be non-mobile
			if ( typeof wptouch_ignored_urls != 'undefined' ) {
			   touchJS.each( wptouch_ignored_urls, function( i, value ) {
			       if ( targetUrl.match( value ) ) {
						targetLink.addClass( 'ignored' );
			       }
			   });
			}

		   // filetypes, images class name additions
	       if ( targetUrl.match( ( /[^\s]+(\.(pdf|numbers|pages|xls|xlsx|doc|docx|zip|tar|gz|csv|txt))$/i ) ) ) {
				targetLink.addClass( 'external' );
	       } else if ( targetUrl.match( ( /[^\s]+(\.(jpg|jpeg|gif|png|bmp|tiff))$/i ) ) ) {
				targetLink.addClass( 'img-link' );
	       }

			touchJS( targetLink ).unbind( 'click' ).bind( 'click', function( e ) {
				// is this an external link? Confirm to leave WAM
				if ( touchJS( targetLink ).hasClass( 'external' ) || touchJS( targetLink ).parent( 'li' ).hasClass( 'external' ) ) {
			       	confirmForExternal = confirm( WPtouch.external_link_text + ' \n' + WPtouch.open_browser_text );
					if ( confirmForExternal ) {
						return true;
					} else {			
						e.preventDefault();
						e.stopImmediatePropagation();
					}
				// prevent images with links to larger ones from opening in web-app mode
				} else if ( touchJS( targetLink ).hasClass( 'img-link' ) ) {
					return false;

				// local http link or no http present: 
				} else if ( targetUrl.match( localDomain ) || !targetUrl.match( 'http://' ) ) {
					// make sure it's not in the ignored list first
					if ( touchJS( targetLink ).hasClass( 'ignored' ) || touchJS( targetLink ).parent( 'li' ).hasClass( 'ignored' ) ) {
				       	confirmForExternal = confirm( WPtouch.wptouch_ignored_text + ' \n' + WPtouch.open_browser_text );
							if ( confirmForExternal ) {
								return true;	
							} else {
								e.preventDefault();
								e.stopImmediatePropagation();						
							}
					// okay, it's passed the tests, this is a local link, fire WAM
					} else {
						WPtouchCreateCookie( 'wptouch-load-last-url', targetUrl, 365 );
						window.location = targetUrl;  
						e.preventDefault();
					} 
				// not local, not ignored, doesn't have no-ajax but it's got an external http domain url
				} else {
			       	confirmForExternal = confirm( WPtouch.external_link_text + ' \n' + WPtouch.open_browser_text );
					if ( confirmForExternal ) {
						return true;
					} else {			
						e.preventDefault();
						e.stopImmediatePropagation();
					}					
				}
			}); /* end click bindings */
		}); /* end .each loop */
	}
}

function webAppOnly() {
	if ( WPtouchWebApp ) {
		var persistenceOn = touchJS( 'body.loadsaved' ).length;
		touchJS( 'body' ).addClass( 'web-app' );
		touchJS( '#account-link-area, #switch, #welcome-message' ).remove();
		if ( !persistenceOn ) {
			WPtouchEraseCookie( 'wptouch-load-last-url' );
		}
		/* prevent images with links to larger ones from opening in web-app mode */
		touchJS( '.post a' ).has( 'img' ).each( function(){ 
			touchJS( this ).click( function( e ) {
		  		var imgURL = touchJS( this ).attr( 'href' );
				if ( imgURL.match( '.jpg' ) || imgURL.match( '.png' ) || imgURL.match( '.jpeg' ) || imgURL.match( '.gif' ) ) {
					e.preventDefault();
					e.stopImmediatePropagation();
				}
			});
		});
	}
}

function welcomeMessage() {
	if ( !WPtouchWebApp ) {	
		touchJS( 'a#close-msg' ).unbind( 'click' ).bind( 'click', function() {
			WPtouchCreateCookie( 'wptouch_welcome', '1', 365 );
			touchJS( '#welcome-message' ).fadeOut( 350 );
			return false;
		});
	}
}

//	if ( window.history.length < 2 ) {
//		touchJS( '#back, #forward' ).hide();
//	} else if ( window.history.length = 2 ) {
//		touchJS( '#forward' ).hide();
//	} else {	
//		touchJS( '#back, #forward' ).show();
//	}
//	  
//	  touchJS( '#back, #forward' ).bind( touchEndOrClick, function( e ) {
//		if ( touchJS( this ).attr( 'ID' ) == 'back' ) {
//		  	history.back();
//	  	} else {
//		  	history.forward();	  	
//	  	}
//	  });

/* New jQuery function popOverToggle() for popover windows */
touchJS.fn.popOverToggle = function() { 
	if ( !this.hasClass( 'open' ) ) {
		this.show().addClass( 'open' );
	} else {
		this.removeClass( 'open' ).fadeOut( 350 );
	}
}

/* New jQuery function flyInToggle() for Message/Comment Windows */
touchJS.fn.flyInToggle = function() { 
	var boxHeight = this.height() + 150;
	if ( this.hasClass( 'fly-in' ) ) {
		this.css('-webkit-transform', 'translateY(0px) rotate(0deg)' ).css( 'opacity', '1' ).css( '-webkit-transition', '350ms' );
	} else {
		this.css('-webkit-transform', 'translateY(-'+boxHeight+'px) rotate(-10deg)' ).css( 'opacity', '.5' ).css( '-webkit-transition', '350ms' );
	}
}

/* New jQuery function webkitSlideToggle() */
touchJS.fn.webkitSlideToggle = function() { 
	if ( !this.hasClass( 'slide-in' ) ) {
		this.show().addClass( 'slide-in' );
	} else {
		this.slideUp( 350 ).removeClass( 'slide-in' );
	}
}

/* Cookie Functions */

function WPtouchCreateCookie( name, value, days ) {
	if ( days ) {
		var date = new Date();
		date.setTime( date.getTime() + ( days*24*60*60*1000 ) );
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path="+WPtouch.siteurl;
}

function WPtouchReadCookie( name ) {
	var nameEQ = name + "=";
	var ca = document.cookie.split( ';' );
	for( var i=0;i < ca.length;i++ ) {
		var c = ca[i];
		while ( c.charAt(0)==' ' ) c = c.substring( 1, c.length );
		if ( c.indexOf( nameEQ ) == 0 ) return c.substring( nameEQ.length, c.length );
	}
	return null;
}

function WPtouchEraseCookie( name ) {
	WPtouchCreateCookie( name,"",-1 );
}

touchJS( document ).ready( function() { doClassiciPadReady(); } );

/*!
// iPhone-style Checkboxes jQuery plugin
// Copyright Thomas Reynolds, licensed GPL & MIT
*/
(function(b,a){b[a]=function(d,c){this.$elem=b(d);var e=this;b.each(c,function(f,g){e[f]=g});this.wrapCheckboxWithDivs();this.attachEvents();this.disableTextSelection();if(this.resizeHandle){this.optionallyResize("handle")}if(this.resizeContainer){this.optionallyResize("container")}this.initialPosition()};b.extend(b[a].prototype,{wrapCheckboxWithDivs:function(){this.$elem.wrap('<div class="'+this.containerClass+'" />');this.container=this.$elem.parent();this.offLabel=b('<label class="'+this.labelOffClass+'"><span>'+this.uncheckedLabel+"</span></label>").appendTo(this.container);this.offSpan=this.offLabel.children("span");this.onLabel=b('<label class="'+this.labelOnClass+'"><span>'+this.checkedLabel+"</span></label>").appendTo(this.container);this.onSpan=this.onLabel.children("span");this.handle=b('<div class="'+this.handleClass+'"><div class="'+this.handleRightClass+'"><div class="'+this.handleCenterClass+'" /></div></div>').appendTo(this.container)},disableTextSelection:function(){if(!b.browser.msie){return}b.each([this.handle,this.offLabel,this.onLabel,this.container],function(){b(this).attr("unselectable","on")})},optionallyResize:function(f){var c=this.onLabel.width(),d=this.offLabel.width();if(f=="container"){var e=(c>d)?c:d;e+=this.handle.width()+15}else{var e=(c<d)?c:d}this[f].css({width:e})},attachEvents:function(){var c=this;this.container.bind("mousedown touchstart",function(e){e.preventDefault();if(c.$elem.is(":disabled")){return}var d=e.pageX||e.originalEvent.changedTouches[0].pageX;b[a].currentlyClicking=c.handle;b[a].dragStartPosition=d;b[a].handleLeftOffset=parseInt(c.handle.css("left"),10)||0;b[a].dragStartedOn=c.$elem}).bind("iPhoneDrag",function(e,d){e.preventDefault();if(c.$elem.is(":disabled")){return}if(c.$elem!=b[a].dragStartedOn){return}var f=(d+b[a].handleLeftOffset-b[a].dragStartPosition)/c.rightSide;if(f<0){f=0}if(f>1){f=1}c.handle.css({left:f*c.rightSide});c.onLabel.css({width:f*c.rightSide+4});c.offSpan.css({marginRight:-f*c.rightSide});c.onSpan.css({marginLeft:-(1-f)*c.rightSide})}).bind("iPhoneDragEnd",function(f,d){if(c.$elem.is(":disabled")){return}var e;if(b[a].dragging){var g=(d-b[a].dragStartPosition)/c.rightSide;e=(g<0)?Math.abs(g)<0.5:g>=0.5}else{e=!c.$elem.attr("checked")}c.$elem.attr("checked",e);b[a].currentlyClicking=null;b[a].dragging=null;c.$elem.change()});this.$elem.change(function(){if(c.$elem.is(":disabled")){c.container.addClass(c.disabledClass);return false}else{c.container.removeClass(c.disabledClass)}var d=c.$elem.attr("checked")?c.rightSide:0;c.handle.animate({left:d},c.duration);c.onLabel.animate({width:d+4},c.duration);c.offSpan.animate({marginRight:-d},c.duration);c.onSpan.animate({marginLeft:d-c.rightSide},c.duration)})},initialPosition:function(){this.offLabel.css({width:this.container.width()-5});var c=(b.browser.msie&&b.browser.version<7)?3:6;this.rightSide=this.container.width()-this.handle.width()-c;if(this.$elem.is(":checked")){this.handle.css({left:this.rightSide});this.onLabel.css({width:this.rightSide+4});this.offSpan.css({marginRight:-this.rightSide})}else{this.onLabel.css({width:0});this.onSpan.css({marginLeft:-this.rightSide})}if(this.$elem.is(":disabled")){this.container.addClass(this.disabledClass)}}});b.fn[a]=function(c){var e=this.filter(":checkbox");if(!e.length){return this}var d=b.extend({},b[a].defaults,c);e.each(function(){b(this).data(a,new b[a](this,d))});if(!b[a].initComplete){b(document).bind("mousemove touchmove",function(g){if(!b[a].currentlyClicking){return}g.preventDefault();var f=g.pageX||g.originalEvent.changedTouches[0].pageX;if(!b[a].dragging&&(Math.abs(b[a].dragStartPosition-f)>d.dragThreshold)){b[a].dragging=true}b(g.target).trigger("iPhoneDrag",[f])}).bind("mouseup touchend",function(g){if(!b[a].currentlyClicking){return}g.preventDefault();var f=g.pageX||g.originalEvent.changedTouches[0].pageX;b(b[a].currentlyClicking).trigger("iPhoneDragEnd",[f])});b[a].initComplete=true}return this};b[a].defaults={duration:200,checkedLabel:"ON",uncheckedLabel:"OFF",resizeHandle:true,resizeContainer:true,disabledClass:"iPhoneCheckDisabled",containerClass:"iPhoneCheckContainer",labelOnClass:"iPhoneCheckLabelOn",labelOffClass:"iPhoneCheckLabelOff",handleClass:"iPhoneCheckHandle",handleCenterClass:"iPhoneCheckHandleCenter",handleRightClass:"iPhoneCheckHandleRight",dragThreshold:5}})(jQuery,"iphoneStyle");