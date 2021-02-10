/**
 *	Custom jQuery Scripts
 *	
 *	Developed by: Austin Crane	
 *	Designed by: Austin Crane
 *  Modified by: Lisa DeBona
 *  Date Modified: 07.28.20
 */

jQuery(document).ready(function ($) {

    $("#site-navigation a").each(function(){
        var target = $(this);
        var txt = $(this).text().replace(/\s+/g, " ").trim();
        if(txt=='Jobs') {
            if(typeof jobsCount!="undefined" && jobsCount!=null) {
                var countTxt = '<span class="menu-counter menu-badge">'+jobsCount+'</span>';
                target.append(countTxt);
            }
            
        }
        if(txt=='Events') {
            if(typeof eventsCount!="undefined" && eventsCount!=null) {
                var countTxt = '<span class="menu-counter menu-badge">'+eventsCount+'</span>';
                target.append(countTxt);
            }
        }
    });
	

    // if( $("#primary-menu a").length>0 ) {
    //     $("#primary-menu a").each(function(){
    //         var parent = $(this).parent();
    //         var str = $(this).text().replace(/\s/g,'-').toLowerCase();
    //         if(str=='donate') {
    //             $(this).addClass("redbutton");
    //             parent.addClass("donate-btn");
    //         }
    //     });
    // }
    

	/*
	*
	*	Current Page Active
	*
	------------------------------------*/
	$("[href]").each(function() {
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
	});


    /* Stick To Right Posts */
    adjust_right_side_post_images();
    $(window).on("resize",function(){
        adjust_right_side_post_images();
    });

    function adjust_right_side_post_images() {
        if( $(".stickRight .story-block").length > 0 ) {
            $(".stickRight .story-block").each(function(){
                var target = $(this);
                var photoHeight = $(this).find(".photo").height();
                var descHeight = $(this).find(".desc").height();
                if(descHeight>photoHeight) {
                    target.addClass("photoAbsolute");
                }
            });
        }
    }

	

	/*
	*
	*   Mobile Nav
	*
	------------------------------------*/
	$('.burger, .overlay').click(function(){
		$('.burger').toggleClass('clicked');
		$('.overlay').toggleClass('show');
		$('nav').toggleClass('show');
		$('body').toggleClass('overflow');
	});
	$('nav.mobilemenu li').click(function() {
		$('nav.mobilemenu ul.dropdown').removeClass('active');
		$(this).find('ul.dropdown').toggleClass('active');
	});
	/*
        FAQ dropdowns
	__________________________________________
	*/
	$('.question').click(function() {
	 
	    $(this).next('.answer').slideToggle(500);
	    $(this).toggleClass('close');
	    $(this).find('.plus-minus-toggle').toggleClass('collapsed');
	    $(this).parent().toggleClass('active');
	 
	});

	/*
	*
	*	Responsive iFrames
	*
	------------------------------------*/
	var $all_oembed_videos = $("iframe[src*='youtube']");
	
	$all_oembed_videos.each(function() {
	
		$(this).removeAttr('height').removeAttr('width').wrap( "<div class='embed-container'></div>" );
 	
 	});
	
	/*
	*
	*	Flexslider
	*
	------------------------------------*/
	$('.flexslider').flexslider({
		animation: "slide",
	}); // end register flexslider
	
	/*
	*
	*	Colorbox
	*
	------------------------------------*/
	$('a.popup').colorbox({
		width: '80%', 
		inline: true
		// height: '80%'
	});
	
	/*
	*
	*	Isotope with Images Loaded
	*
	------------------------------------*/
	var $container = $('#container').imagesLoaded( function() {
  	$container.isotope({
    // options
	 itemSelector: '.item',
		  masonry: {
			gutter: 15
			}
 		 });
	});


    /*
    *   Slider
    */

	
	/*
	*
	*	Equal Heights Divs
	*
	------------------------------------*/
	$('.js-blocks').matchHeight();

	/*
	*
	*	Navigation Jobs Count

		local = 58030
		site = 58030
	*
	------------------------------------*/

    
    var $img_width = $('section.c-sponsor-block').width();
    if( $img_width < 700) {
        $('.c-sponsor-block__image').css('height', '170px');
    }
    
	/*
	*
	*	Video
	*
	------------------------------------*/
	var $video_wrapper = $('.template-video .video-holder .video-wrapper');
    if($video_wrapper.length>0){
        var $window = $(window);
        var $video_holder = $('.template-video .video-holder');
        var $site_nav = $('#site-navigation');
        function video_check(){
            var anchor = $video_holder.offset().top;
            var offset_y = 10;
            var offset_x = 10;
            if($site_nav.length>0){
                offset_y = offset_y + $site_nav.height();
            }
            if($window.scrollTop()>anchor && window.innerWidth > 600){
                $video_wrapper.css({
                    position:'fixed',
                    top: offset_y + "px",
                    right: offset_x+"px",
                    width: '350px',
                    height: '196px'
                });
            } else {
                $video_wrapper.css({
                    position:'',
                    top: '',
                    right: '',
                    width: '',
                    height: ''
                });
            }
        }
        video_check();
        $window.on("scroll",video_check);
        $window.on("resize",video_check);
    }

	/*
	*
	*	Popular Posts
	*
	------------------------------------*/
	(function(){
		//this is for wp most popular posts compatability since they don't run title through appropriate filters
		$('.small-post .small-post-content h2').each(function(i,el){
			var $el = $(el);
			var regex = new RegExp('<i\\sclass="fa\\sfa-play-circle-o"></i>');
			if(regex.test($el.text())){
				$el.text($el.text().replace(regex,""));
				$el.append($('<i class="fa fa-play-circle-o"></i>'));
			}
		});
	})();

	// not working below...

	/*
	*
	*	JObs Banner
	*
	------------------------------------*/
	$('.jobs-banner >.row-2 .find').click(function(){
		$('.jobs-banner >.row-3 form >.row-1 input').eq(0).focus();
	});

	//ajaxLock is just a flag to prevent double clicks and spamming.
    /*
	var ajaxLock = false;

	var postOffset = parseInt(jQuery( '#offset' ).text());
	//Change that to your right site url unless you've already set global ajaxURL
	var ajaxURL = bellaajaxurl.url;
	function ajax_next_event() {
		if( ! ajaxLock && postOffset != NaN) {
			ajaxLock = true;
			
			//Parameters you want to pass to query
			ajaxData = {};
			ajaxData.post_offset= postOffset;
			ajaxData.action = 'bella_ajax_next_event';
			if(bellaajaxurl.date!=null){
				ajaxData.date = bellaajaxurl.date;
			}
			if(bellaajaxurl.category!=null){
				ajaxData.category =bellaajaxurl.category;
			}
			if(bellaajaxurl.search!=null){
				ajaxData.search = bellaajaxurl.search;
			}
			if(bellaajaxurl.tax!=null){
				ajaxData.tax = bellaajaxurl.tax;
			}
			if(bellaajaxurl.term!=null){
				ajaxData.term = bellaajaxurl.term;
			}

			//Ajax call itself
			jQuery.ajax({
				type: 'post',
				url:  ajaxURL,
				data: ajaxData,
				dataType: 'json',

				//Ajax call is successful
				success: function ( response ) {
					if(parseInt(response[1])!==0){
						$els = $(response[0]).filter('.tile');
						$els.css("opacity","0");
						$tracking.append($els);
						setTimeout(function(){
							$('.bottom-blocks').matchHeight();
							$('.blocks').matchHeight();
							$els.css("opacity","");
						},200);
						postOffset+=parseInt(response[1]);
						ajaxLock = false;
					}
				},

				//Ajax call is not successful, still remove lock in order to try again
				error: function (err) {
					ajaxLock = false;
				}
			});
		}
	}
    */

	var $window = $(window);
	var $document = $(document);
	var $tracking = $('.tracking');
	
	if($tracking.length>0){

		$window.scroll(function(){
			var top = $tracking.offset().top;
			var height = $tracking.height();
			var w_height = $window.height();
			var d_scroll = $document.scrollTop();
			if(w_height+d_scroll+500>height+top){
				ajax_next_event();
			}
		});
	}

    /*
    *   Category Counter Jobs
    */

    $(document).on('click', '.qcity-load-more:not(.loading)', function(){

        var that    = $(this);
        var page    = $(this).attr('data-page');
        var perPage = $(this).attr('data-perpage');
        var newPage = parseInt(page) + 1;
        var action  = $(this).attr('data-action');
        var basepoint = $(this).attr('data-basepoint');
        var newBasepoint = basepoint + perPage;
        var postID = $(this).attr('data-except');
        var excludeCat = ( typeof $(this).attr('data-excludecat')!='undefined' || $(this).attr('data-excludecat')!=null ) ? $(this).attr('data-excludecat'):'';

        that.addClass('loading').find('.load-text').hide();        
        that.find('.load-icon').show();
        that.attr('data-page', newPage);

        $.ajax({
            url: ajaxURL,
            type: 'post',
            data: {
                page: page,
                action: action,
                basepoint: basepoint,
                postID: postID,
                perPage: perPage,
                exclude_cat:excludeCat
            },
            success: function(response){
                
                if( response == 0){
                    $('.qcity-news-container').append('<p>No more post to load!</p>');
                    that.hide();
                } else {
    
                    that.data('basepoint', newBasepoint);
                    $('.qcity-news-container').slideDown(2000).append(response);

                    setTimeout(function(){
                        that.removeClass('loading');
                        that.find('.load-text').show();
                        that.find('.load-icon').hide();
                    }, 500);

                }
                
            }, 
            error: function(response){
            }
        });

    });

    /* Load More Events */
    $(document).on('click', '#load-more-action', function(e){
        e.preventDefault();
        var next_page = $(this).attr("data-next-page");
        var next_page_next = parseInt(next_page) + 1;
        var total_pages = $(this).attr("data-total-pages");
        var permalink = $(this).attr("data-permalink");
        var next_page_link = permalink + '?pg=' + next_page;
        if(next_page<=total_pages) {
            $("#more-posts-hidden").load(next_page_link + " .more-events-posts", function(){
                
                $("#more-posts-hidden .more-events-posts .story-block").each(function(){
                    $(this).addClass("animated fadeIn").appendTo(".qcity-news-container .more-events-posts");
                });

            });
            if(next_page==total_pages) {
                $("#load-more-action .load-text").hide();
                $("#load-more-action .load-icon").show();
                setTimeout(function(){
                    $("#more-bottom-button").html('<div style="text-align:center;color:#969696;font-size:12px;">No more post to load!</div>');
                },600);
            } else {
                $("#load-more-action .load-text").hide();
                $("#load-more-action .load-icon").show();
                setTimeout(function(){

                    $("#load-more-action .load-text").removeAttr('style');
                    $("#load-more-action .load-icon").removeAttr('style');

                },600);
            }
        }
        $(this).attr("data-next-page",next_page_next);
    });


    /*
    *   Load MOre Sidebar
    */

    // sort_trending_posts();
    // function sort_trending_posts() {
    //     if( $(".sb-trending-posts").length>0 ) {
    //         $('.sb-trending-posts article').sort(function(a, b) {
    //           return parseInt(a.id) - parseInt(b.id);
    //         }).each(function() {
    //           var elem = $(this);
    //           elem.remove();
    //           $(elem).prependTo(".sb-trending-posts .sidebar-container");
    //         });
    //     }
    // }
    

    $(document).on('click', '.qcity-sidebar-load-more:not(.loading)', function(){

        var that    = $(this);
        var page    = $(this).data('page');
        var newPage = page + 1;
        var action  = $(this).data('action');
        var qp      = $(this).data('qp');
        var postid  = $(this).data('postid');
        var is_trending = ( typeof $(this).attr('data-trending')!='undefined' && $(this).attr('data-trending') ) ? true : false;
        //var ajaxUrl = that.data('url');

        that.addClass('loading').find('.load-text').hide();        
        that.find('.load-icon').show();

        $.ajax({
            url: ajaxURL,
            type: 'post',
            data: {
                page: page,
                action: action,
                qp: qp,
                postid: postid,
                trending: is_trending
            },
            success: function(response){

                if( response == 0){
                    $('.sidebar-container').append('<p>No more post to load!</p>');
                    that.hide();
                } else {
                    that.data('page', newPage);
                    $('.sidebar-container').slideDown(2000).append(response);

                    setTimeout(function(){
                        that.removeClass('loading');
                        that.find('.load-text').show();
                        that.find('.load-icon').hide();
                    }, 500);

                }
                
            }, 
            complete: function(){
                // var prevGroup = 'page'+page;
                // var prev = '.sb-trending-posts [data-group="'+prevGroup+'"]';
                // var prevLast = $(prev).last();
                // $('.sb-trending-posts [data-group="page'+newPage+'"]').sort(function(a, b) {
                //   return parseInt(a.id) - parseInt(b.id);
                // }).each(function() {
                //   var elem = $(this);
                //   elem.remove();
                //   $(elem).insertAfter(prevLast);
                // });
            },
            error: function(response){
            }
        });

    });

    /*
    *   Business Directory load more footer section
    */

       $(document).on('click', '.qcity-business-directory-load-more:not(.loading)', function(){

        var that    = $(this);
        var page    = $(this).data('page');
        var newPage = page + 1;
        var action  = $(this).data('action');
        //var container = $(this).data('')
        //var ajaxUrl = that.data('url');

        that.addClass('loading').find('.load-text').hide();        
        that.find('.load-icon').show();

        $.ajax({
            url: ajaxURL,
            type: 'post',
            data: {
                page: page,
                action: action
            },
            success: function(response){

                if( response == 0){
                    $('.business-directory-table').append('<p>No more post to load!</p>');
                    that.hide();
                } else {

                    that.data('page', newPage);
                    $('.business-directory-table').slideDown(2000).append(response);

                    setTimeout(function(){
                        that.removeClass('loading');
                        that.find('.load-text').show();
                        that.find('.load-icon').hide();
                    }, 500);

                }
                
            }, 
            error: function(response){
            }
        });

    });


   /*
   *    Church Listing Search
   */

   var searchRequest = null;

    $(function () {
        var minlength = 3;

        $("#form_search").submit(function ( event ) {
            event.preventDefault();
            var that    = $('.searchfield'),
            value       = $('.searchfield').val();
            var action  = 'qcity_church_search';
            var post_type = $('.post_type').val();

            $('.qcity-sponsored-container').hide();
            $('.listing_initial').hide();
            $('.listing_search').show();
            $('.listing_search_result').html('<a class="red"><span class="load-icon"><i class="fas fa-sync-alt spin"></i></span></a>');
            

            if (value.length >= minlength ) {
                if (searchRequest != null) 
                    searchRequest.abort();
                    searchRequest = $.ajax({
                    type: "GET",
                    url: ajaxURL,
                    data: {
                        'search_keyword' : value,
                        'action': action,
                        'post_type': post_type
                    },                    
                    success: function( response ){         
                        $('.listing_search_result span.load-icon').hide();              
                        if ( response != 0 ) {
                            $('.listing_search_result').html(response);
                        } else {
                            message = '<h4>'+ value + ' not found! </h4>';
                            $('.listing_search_result').html(message);
                        }
                    },
                    error: function( response ) {
                        $('.listing_search_result span.load-icon').hide();
                        message = '<h4>Oops! Something went wrong. Please try again later. </h4>';
                        $('.listing_search_result').html(message);
                    }
                });
            }
        });

        /* New Search Functionality (Adde by Lisa) */
        $("#form_search_event").submit(function(e) {
            e.preventDefault();
            var keywordStr = $(this).find("input.searchfield").val().replace(/\s+/g,'').trim();
            var keyword = $(this).find("input.searchfield").val().replace(/\s+/g,' ').trim();
            var timeout = 500;
            if(keywordStr) {
                $("#more-bottom-button").hide();
                var form_data = $(this).serialize();
                    form_data += '&action=my_ajax_search_event';
                    $.ajax({
                        type : "get",
                        dataType : "json",
                        url : ajaxURL,
                        data : form_data,
                        beforeSend:function(){
                            $("#processing-data").show();
                            $("#sponsored-events-section").remove();
                            $(".more-happenings-section").hide();
                            $("body").addClass("events-search-result");
                            $("#search-result-pagination").hide();
                        },
                        success: function(obj) {
                            setTimeout(function(){
                                var res = obj.result;
                                var baseURL = '';
                                if(res.posts) {
                                    $(".more-happenings-section h1.dark-gray").html('Search Result For: <em style="color:#f1d14b">'+keyword+'</em>');
                                    $(".events.more-events-posts").html(res.posts);
                                    $("#sponsored-events-section").remove();
                                    $(".more-happenings-section").show().addClass("animated fadeIn");
                                    $("#search-result-pagination").show().addClass("animated fadeIn");
                                    setTimeout(function(){
                                        $("#search-result-pagination").html(res.paginate);
                                    },200);
                                } else {
                                    //var message = '<h4 style="margin:30px 0 50px;font-size:25px;">Unfortunately, there are no results for your query. <a href="'+currentURL+'">&larr;Go back</a></h4>';
                                    var message = '<h4 style="margin:30px 0 50px;font-size:25px;">Unfortunately, there are no results for your query.</h4>';
                                    $("#page-events-container").html(message);
                                }
                            },timeout);
                        },
                        complete: function(){
                            setTimeout(function(){
                                $("#processing-data").hide();
                            },timeout);
                        }
                    });
            } else {
                if( typeof currentURL!='undefined' ) {
                    window.location.href = currentURL;
                }
            }
        });

        /* Load More Button from Search Result */
        $(document).on("click","#load-more-result-btn",function(e){
            e.preventDefault();
            var button = $(this);
            var timeout = 500;
            var form_data = {
                'action':'my_ajax_search_event',
                'srch': button.attr("data-keyword"),
                'type': button.attr("data-type"),
                'current_page': button.attr("data-next-page"),
                'base_url': button.attr("data-permalink"),
                'more_button':1
            }
            $("#more-bottom-button").hide();
            $.ajax({
                type : "get",
                dataType : "json",
                url : ajaxURL,
                data : form_data,
                beforeSend:function(){
                    $("#more-posts-hidden").html("");
                    button.addClass("loading");
                },
                success: function(obj) {
                    setTimeout(function(){
                        var res = obj.result;
                        var baseURL = '';
                        if(res.posts) {
                            $("#more-posts-hidden").html(res.posts);
                            $("#more-posts-hidden").find(".story-block").each(function(){
                                $(this).appendTo(".events.more-events-posts").addClass("animated fadeIn");
                            });
                            setTimeout(function(){
                                $("#search-result-pagination").html(res.paginate);
                            },200);
                        } 
                    },timeout);
                },
                complete: function(){
                    setTimeout(function(){
                        button.removeClass("loading");
                    },timeout);
                }
            });
        });
    });

    // Adding search function in burger
    $('#menu-burger-submenu').append('<li><div class="qcity_search_holder"><input type="text" class="qcity_search_class" name="qcity_search" id="qcity_search" placeholder="Search"><div class="qcity_search_icon"><span ><i class="fas fa-search"></i></span></div></div></li>');
        
    $('.qcity_search_icon').on('click', function(){
        var search_word = $('#qcity_search').val();
        if( search_word != '' ){
            window.location.href = '/?s=' + search_word + '&pg=1&perpage=20';
        }
        
    });

    $('#qcity_search').keypress(function(e){
        if( e.which == 13 ){            
            $('.qcity_search_icon').click();
        }
    });


    /*
    *   Comments Section
    */

    $('.click_class').on('click', function(){
        // $('.comments-trigger').hide();
        // $('.comments-block').show();
        // $(".comment-form-comment textarea#comment").focus();
        $('.comments-trigger').hide();
        $("#comments .comment-respond").show();
    });

    if( $("form#commentform").length > 0 ) {
        if( typeof params.recaptcha !=='undefined' ) {
            var error = params.recaptcha;
            var message = '';
            if(error=='invalid') {
                message = '<div id="commentFormResponse">Invalid reCAPTCHA. Please try again.</div>';
            }
            else if(error=='empty') {
                message = '<div id="commentFormResponse">Please enter reCAPTCHA to prove you\'re a human.</div>';
            }
            $(message).prependTo("form#commentform");
            history.replaceState('', document.title, currentURL);
        }
    }

    /*
    *   Stripe Payment Section
    */

    $('span.pay_amount').on('click', function(){
        $('section.gravityform').show();
    });

	/*
	*
	*	Wow Animation
	*
	------------------------------------*/
	new WOW().init();

    
    /* Homepage Big Photo */
    // change_text_height_top_blog();
    // $(window).on("resize",function(){
    //     change_text_height_top_blog();
    // });
    // function change_text_height_top_blog() {
    //     var screenWidth = $(window).width();
    //     if( $(".stickRight .story-block").length > 0 ) {
    //         if( $(".stickLeft .bigPhoto").length > 0 ) {
    //             if( screenWidth > 1000 ) {
    //                 var bigPhotoHeight = $(".stickLeft .bigPhoto").height();
    //                 var stickLeftHeight = $(".stickLeft").height();
    //                 var textHeight = stickLeftHeight - bigPhotoHeight;
    //                 $(".stickLeft .info").css("height",textHeight+"px");
    //                 $(".stickLeft .info").addClass('absolute');
    //             } else {
    //                 $(".stickLeft .info").css("height","auto");
    //                 $(".stickLeft .info").removeClass('absolute');
    //             }
    //         }
    //     }
    // }

    function js_get_start_date() {
      var d = new Date();
      var mo = d.getMonth() + 1;
      var month = (mo.toString().length < 2 ? "0"+mo.toString() : mo);
      var day = (d.getDate().toString().length < 2 ? "0"+d.getDate().toString() :d.getDate());
      var year = d.getFullYear();
      var dateNow = year+month+day;
      return dateNow;
    }


    function js_get_date_range(numDays) {
        var d = new Date();
        var mo = d.getMonth() + 1;
        var month = (mo.toString().length < 2 ? "0"+mo.toString() : mo);
        var day = (d.getDate().toString().length < 2 ? "0"+d.getDate().toString() :d.getDate());
        var year = d.getFullYear();
        var sec = d.getSeconds();
        var min = d.getMinutes();
        var hour = d.getHours();
        var time = hour+'-'+min;
        var date_range = [];
        for(i=0; i<=numDays ;i++) {
            var nth = day + i;
            var nthdate = year+month+nth;
            date_range.push(nthdate);
        }
        var date_string = date_range.join();
        return date_string;
    }

    /* Uncomment to Delete Cookies */
    //Cookies.remove('qcitysubcribedaterange');
    
    /* Temporarily hide subscription pop-up on desktop when user close it. 
     * Display it back after 2 days. 
    */ 
    //var dateNow = js_get_start_date();
    var dateNow = $("body").attr("data-today");
    var dateRange = $("body").attr("data-range");
    var cookieDates = ( typeof Cookies.get('qcitysubcribedaterange')!="undefined" ) ? Cookies.get('qcitysubcribedaterange') : '';
    if(cookieDates) {
        var arr_dates = cookieDates.split(",");
        if($.inArray(dateNow, arr_dates) !== -1) { /* Do not show signup box */
            $("body").addClass("hide-signup-desktop");
        } else {
            /* Show only on News post */
            if( $("body").hasClass("single-post") ) {
                $("body").addClass("show-signup-desktop");
            } else {
                $("body").addClass("hide-signup-desktop");
            }
        }
    } else {
        /* Show only on News post */
        if( $("body").hasClass("single-post") ) {
            $("body").addClass("show-signup-desktop");
        } else {
            $("body").addClass("hide-signup-desktop");
        }
    }

    /* If signup box (desktop) is closed */
    $(document).on("click",".oakland-lightbox",function(e){
        var container = $("#oakland-optin");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            set_cookies_subscription_popup();
            $("body").removeClass("show-signup-desktop");
            $("body").addClass("hide-signup-desktop");
        }
    });

    $(document).on("click","#oakland-optin .oakland-close",function(e){
        set_cookies_subscription_popup();
        $("body").removeClass("show-signup-desktop");
        $("body").addClass("hide-signup-desktop");
    });


    /* Uncomment to Delete Cookies */
    //Cookies.remove('qcitysubcribeview2');

    /* Mobile Subscription */
    var cookieMobileSubscribe = ( typeof Cookies.get('qcitysubcribeview2')!="undefined" ) ? Cookies.get('qcitysubcribeview2') : '';
    if( $("#mobileSignUpBox").length > 0 ) {
        if(cookieMobileSubscribe) {
            
            var arr_dates_mob = cookieMobileSubscribe.split(",");
            if($.inArray(dateNow, arr_dates_mob) !== -1) { /* Do not show signup box */
                document.getElementById("mobileSignUpBox").style.display = "none";
                $("#mobileSignUpBox").remove();
            } else {
                document.getElementById("mobileSignUpBox").style.display = "block";
                $("#mobileSignUpBox").addClass("animated fadeIn");
            }

        } else {
            document.getElementById("mobileSignUpBox").style.display = "block";
            $("#mobileSignUpBox").addClass("animated fadeIn");
        }
    }


    $("#closeSubscribe, .signUpBtn").on("click",function(e){
        e.preventDefault();
        //Cookies.set('qcitysubcribeview',dateNow);
        var res = Cookies.set('qcitysubcribeview2',dateNow);
        $(".mobileSubscribe").remove();
        if( $(this).hasClass('signUpBtn') ) {
            window.location.href = $(this).attr("href");
        }
    });


    /* Set cookies when subscription is closed (DESKTOP) */
    function set_cookies_subscription_popup() {
        Cookies.set('qcitysubcribedaterange',dateRange);
    }


    // /* Move `Sponsored By` Box on top of `Sponsored Content` box (bottom page) */
    // if( $(".sponsoredInfoBox").length>0 && $(".sponsored-by").length > 0 ) {
    //     $(".sponsored-by").appendTo(".sponsoredInfoBox");
    // }

    /* Sponsored Content - Tool tip */
    if( $(".whatIsThisTxt").length > 0 && $("#sponsorToolTip").length > 0 ) {
        $(".whatisThis").hover(
            function(){
                $(this).next(".whatIsThisTxt").addClass("show");
            }, function(){
                $(this).next(".whatIsThisTxt").removeClass("show");
            }
        );
    }

    /* Mobile Header - Newsletter Button */
    // adjust_newsLetter_button();
    // $(window).on('resize orientationchange',function(){
    //     adjust_newsLetter_button();
    // });

    // function adjust_newsLetter_button() {
    //     var mobileWidth = $(window).width();
    //     if(mobileWidth<481) {
    //         var mw = mobileWidth - 112;
    //         var logoWidth = $("#masthead .logo img").width();
    //         var newsBtnWidth =  mw - logoWidth;
    //         $(".newsletter-link").css("width",newsBtnWidth+"px");
    //     } else {
    //         $(".newsletter-link").css("width","");
    //     }
    // }

    $(".share-link #sharerLink").on("click",function(e){
        e.preventDefault();
        $(".share-link .share").slideToggle();
    });

    /* Related Posts */
    if( $(".rp4wp-posts-list li.rp4wp-col").length>0 ) {
        $(".rp4wp-posts-list li.rp4wp-col").each(function(){
            var placeholder = assetsDIR + "video-helper.png";
            var imgSrc = $(this).find(".rp4wp_component_image img.wp-post-image").attr("src");
            var newImage = '<img src="'+placeholder+'" alt="" aria-hidden="true" class="prplaceholder" />';
            // $(this).find(".rp4wp_component_image a").attr("style","background-image:url('"+imgSrc+"')")
            var imgThumbURL = '';
            if( $(this).find("img.wp-post-image").length>0 ) {
                var srcset = '';
                if( typeof $(this).find("img.wp-post-image").attr("srcset")!='undefined' ) {
                    srcset = $(this).find("img.wp-post-image").attr("srcset");
                    if(srcset) {
                        var srcparts = srcset.split(",");
                        if( srcparts && typeof srcparts[2]!='undefined' ) {
                            var image_file_src = srcparts[2];
                            var matches = image_file_src.match(/\bhttps?:\/\/\S+/gi);
                            if( typeof matches[0]!='undefined' ) {
                                imgThumbURL = matches[0];
                            }
                        }
                    }
                }
                else if( typeof $(this).find("img.wp-post-image").attr("data-src")!='undefined' ) {
                    imgThumbURL = $(this).find("img.wp-post-image").attr("data-src");
                }
            }
            if(imgThumbURL) {
                $(this).find(".rp4wp_component_image a").attr("style","background-image:url('"+imgThumbURL+"')");
            }
            $(this).find(".rp4wp_component_image a").append(newImage);

        });
    }

    /* inline subscription form */

    $(document).on("click",".ctct-form-custom .ctct-form-field",function(){
        $(this).addClass('input-focus');
        $(this).find("input").focus();
    });

    var stickySidebarEl = $(".stickySidebar");
    var offset = stickySidebarEl.offset();

    var sidebarFloat = document.querySelector('#sidebar-single-post');
    var footer = document.querySelector('#beforeFooter');

    function checkOffset() {
      function getRectTop(el){
        var rect = el.getBoundingClientRect();
        return rect.top;
      }
      

      if((getRectTop(sidebarFloat) + document.body.scrollTop) + sidebarFloat.offsetHeight >= (getRectTop(footer) + document.body.scrollTop) - 10)
        sidebarFloat.style.position = 'absolute';
      if(document.body.scrollTop + window.innerHeight < (getRectTop(footer) + document.body.scrollTop))
        sidebarFloat.style.position = 'fixed'; // restore when you scroll up      
      //sidebarFloat.innerHTML = document.body.scrollTop + window.innerHeight;
      //var offset = document.body.scrollTop + window.innerHeight;
      getSidebarHelperSize();    
    }

    getSidebarHelperSize();  

    if( $('#sidebar-single-post').length>0 ) {

        document.addEventListener("scroll", function(){
            checkOffset();
            if ($(window).scrollTop() > offset.top) {
                $('#sidebar-single-post').addClass('fixed');
            } else {
                $('#sidebar-single-post').removeClass('fixed');
            }
        });

        if ($(window).scrollTop() > offset.top) {
            checkOffset();
            $('#sidebar-single-post').addClass('fixed');
        } else {
            $('#sidebar-single-post').removeClass('fixed');
        }

    }

    if( $("#sidebar-single-post").length>0 ) {
        var helperWidth = $(".stickySidebar .helper").width();
        $("#sidebar-single-post").attr('data-width',helperWidth+"px");
    }

    $(window).resize(function() {  
        getSidebarHelperSize();    
    });

    function getSidebarHelperSize() {
        if( $("#sidebar-single-post").length>0 ) {
            var helperWidth = $(".stickySidebar .helper").width();
            $("#sidebar-single-post").css('width',helperWidth+"px");
            var sbHeight = $("#sidebar-single-post").height();
            var windowHeight = $(window).height();
            if(sbHeight>windowHeight) {
                $("#singleSidebar").addClass("not-fixed");
            } 
        }
    }


    stickySidebar(stickySidebarEl);
    $(window).on("resize",function() {  
        stickySidebar(stickySidebarEl);
     });


    function stickySidebar(elem) {
        var screenHeight = $(window).height();
        var height = elem.height();
        var half = Math.round(height/3);
        var sidebarHeight = height + half;
        var width = elem.width();
        if(sidebarHeight>screenHeight) {
            $(".stickySidebar").addClass('overflow');
        } else {
            $(".stickySidebar").removeClass('overflow');
        }
    }


    $(window).on("scroll resize",function(){
        if( $("#shareThisPost").length>0 ) {
            inViewport(".entry-footer");
        }
    });

    function inViewport(elem){
        $(elem).each(function(){
            var divPos = $(this).offset().top,
            topOfWindow = $(window).scrollTop();
            if( divPos < topOfWindow+400 ){
                $("body").addClass('social-media-sticky');
                $("#shareThisPost").addClass('animated fadeIn fixed');
            } else {
                $("body").removeClass('social-media-sticky');
                $("#shareThisPost").removeClass('fadeIn fixed');
            }
        });
    }


    /* Content that will be visible only on mobile (SINGLE POST) */
    moveElementsOnMobile();
    $(window).on("resize",function(){
        moveElementsOnMobile();
    });
    function moveElementsOnMobile() {
        var screenWidth = $(window).width();
        if(screenWidth<821) {
            // Trending
            if( $("body.single-post #singleSidebar .trending-sidebar-wrap").length>0 ) {
                if( $("#trendingBlock").text()=='' ) {
                    $("body.single-post #singleSidebar").clone().appendTo("#trendingBlock");
                }
            }
            // Sponsored Content
            if( $("body.single-post .c-sponsor-block").length>0 ) {
                if( $("#sponsoredContentBlock").text()=='' ) {
                    $("body.single-post .c-sponsor-block").clone().appendTo("#sponsoredContentBlock");
                }
            }
            // Related Articles
            if( $("body.single-post .rp4wp-related-post").length>0 ) {
                if( $("#relatedArticlesBlock").text()=='' ) {
                    $("body.single-post .rp4wp-related-post").clone().appendTo("#relatedArticlesBlock");
                }
            }
            // West Side Connect 
            if( $("body.single-post #beforeFooter").length>0 ) {
                if( $("#westSideConnectBlock").text()=='' ) {
                    $("body.single-post #beforeFooter").clone().appendTo("#westSideConnectBlock");
                }
            }
            // Sponsored By
            if( $("body.single-post .sponsoredDataDiv").length>0 ) {
                if( $("#sponsoredByBlock .sponsoredDataDiv").text()=='' ) {
                    $("body.single-post .sponsoredDataDiv").appendTo("#sponsoredByBlock");
                }
            }

            /* HOME TOP SECTION - FOR MOBILE ONLY */
            if( $(".form-subscribe-blue").length>0 ) {
                $(".form-subscribe-blue").appendTo("#emailBlockMobileView");
            }
            if( $(".more-news-commentaries").length>0 ) {
                $(".more-news-commentaries").insertAfter(".right.stickRight");
            }

            if( $(".ads-home").length>0 ) {
                $(".ads-home").insertBefore('section.home-bottom');
            }

            // if( $(".home-sb-subscribe-form").length>0 ) {
            //     $(".home-sb-subscribe-form").insertAfter('section.home-bottom');
            //     $(".home-sb-subscribe-form").addClass('mobile');
            // }

            // if( $(".sidebar-sponsored-posts").length>0 ) {
            //     $(".sidebar-sponsored-posts").insertBefore('.twocol.qcity-news-container');
            // }

            if( $("#sponsoredPostDiv").length>0 ) {
                $("#sponsoredPostDiv").insertAfter('.home-instagram-feeds');
            }


        } else {
            $("#mobileBlocks #trendingBlock").html("");
            $("#mobileBlocks #sponsoredContentBlock").html("");
            $("#mobileBlocks #relatedArticlesBlock").html("");
            $("#mobileBlocks #westSideConnectBlock").html("");
            var ssp_width = $("#sidebar-single-post").attr("data-width");
            $("#sidebar-single-post").css("width","263px");

            /* HOME TOP SECTION - FOR MOBILE ONLY */
            if( $("#emailBlockMobileView .form-subscribe-blue").length>0 ) {
                $("#emailBlockMobileView .form-subscribe-blue").prependTo(".stickRight.right");
            }
            if( $(".left.stickLeft .more-news-commentaries").length==0 ) {
                $(".more-news-commentaries").appendTo(".left.stickLeft");
            }

            if( $(".ads-home").length>0 ) {
                //$(".ads-home").insertBefore('.twocol.qcity-news-container');
                $(".ads-home").insertBefore('.newsHomeV2 .mobile-version');
            }

            // if( $(".home-sb-subscribe-form").length>0 ) {
            //     $(".home-sb-subscribe-form").appendTo('#home-sb-form');
            //     $(".home-sb-subscribe-form").removeClass('mobile');
            // }

            // if( $(".sidebar-sponsored-posts").length>0 ) {
            //     $(".sidebar-sponsored-posts").insertBefore('#home-sb-form');
            // }

            if( $("#sponsoredPostDiv").length>0 ) {
                $("#sponsoredPostDiv").insertBefore('#sponsoredPostDivider');
            }

            // Sponsored By
            if( $("#sponsoredByBlock .sponsoredDataDiv").length>0 ) {
                $("body.single-post .sponsoredDataDiv").prependTo("body.single-post .entry-footer");
            }
        }
    }

    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 50) {
            $('body').addClass('scrolled');
        } else {
            $('body').removeClass('scrolled');
        }
    });

    // $('#widget-trending-post').stickySidebar({
    //     sidebarTopMargin: 20,
    //     footerThreshold: 100
    // });
    sticky_trending_posts_static();
    sticky_trending_posts();
    $(window).on('scroll resize', function () {
        sticky_trending_posts();
    });

    
    function sticky_trending_posts() {
        var ts = $("#widget-trending-post").width();
        var logoHeight = $("div.logo img").height();
        var adminbar = ( $("#wpadminbar").length>0 ) ? $("#wpadminbar").height() : 0;
        var offset = logoHeight+adminbar+30;
        var divHeight = $("#widget-singleSidebar").height() + offset;
        var windowHeight = $(window).height();

        if($('body').hasClass('scrolled')) {
            $("body.single #widget-singleSidebar").css("top",offset+"px");
        } else {
            $("body.single #widget-singleSidebar").css("top","0");
        }
        $("body.single #widget-trending-post").css("width",ts+"px");
    }
    function sticky_trending_posts_static() {
        var ts = $("#widget-trending-post").width();
        var logoHeight = $("div.logo img").height();
        var adminbar = ( $("#wpadminbar").length>0 ) ? $("#wpadminbar").height() : 0;
        var offset = logoHeight+adminbar+30;
        var divHeight = $("#widget-singleSidebar").height() + offset;
        var windowHeight = $(window).height();
        if(divHeight>windowHeight) {
            var wh = Math.round(windowHeight/1.5);
            $("body.single #widget-trending-post").css("height",wh+"px");
            $("body.single #widget-trending-post").addClass('overflow');
        } else {
            $("body.single #widget-trending-post").css("height","auto");
            $("body.single #widget-trending-post").removeClass('overflow');
        }
    }
    
    /* Header RED BUTTON */
    // if( $(".headRedButton").length>0 ) {
    //     var customLink = $(".headRedButton").html();
    //     var customMenuLink = '<li class="menu-item red-button-link">'+customLink+'</li>';
    //     if($("ul#primary-menu li.red-button-link").length==0) {
    //         $("ul#primary-menu").append(customMenuLink);
    //     }
    // }


});// END #####################################    END