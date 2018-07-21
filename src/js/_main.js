/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */
(function ($) {
  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function () {
        // JavaScript to be fired on all pages
        function loadEmoji() {
          $.getJSON("https://slack.com/api/emoji.list?token=xoxp-3921626273-114425188213-401339713923-9e939340d027352b450c1e6fdf421ce6",
            function (data) {
              //                                     console.log(data.emoji);
              Object.keys(data.emoji).forEach(function (k) {
                $('span.emoji').each(function () {
                  //                                            var userEmoji = $(this).children('span.emoji');
                  var text = $(this).text().replace(':', '').replace(':', '');
                  if (text == k) {
                    $(this).html('<img src="' + data.emoji[k] + '">');
                  } else {
                    $(this).emoji();
                  }
                })
              });
            });
        }
        $(document).ready(function () {
          loadEmoji();
        })
        $('.fade').slick({
          //                    draggable: false,
          arrows: false,
          dots: false,
          fade: true,
          speed: 900,
          infinite: true,
          touchThreshold: 100,
          autoplay: true,
          autoplaySpeed: 10000,
          pauseOnHover: false,
        });
        jQuery('.fade').on('afterChange', function (event, slick, currentSlide, nextSlide) {
          var hasVideo = $('.slick-active').hasClass('video');
          var video = $('.slick-active.video').find('video');
          var speed = $('.slick-active').attr('data-attr');
          var VideoFull = $('.slick-active.video').hasClass('video-full');
          if (hasVideo == true) {
            console.log('has video');
            var slickSlide = $('.slick-active').attr('data-slick-index');
          }
          if (slick.currentSlide == slickSlide) {
            console.log('slide video start');
            $(video).get(0).play();
            if (VideoFull == true) {
              console.log('slide playing full video');

              setTimeout(function () {
                slick.slickPause();
              }, 500);
              $(video).on('ended', function () {
                slick.slickNext();
              })
            } else {
              setTimeout(function () {
                slick.slickPause();
                console.log('slide will change at: ' + speed);
              }, 500);
              setTimeout(function () {
                slick.slickNext();
              }, speed);
            }
          } else {
              console.log('slide will change at: ' + speed);

              setTimeout(function () {
                slick.slickNext();
              }, speed);

          }
        });
        $('.fade').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
          console.log(nextSlide);
          if ($('.slide[data-slick-index="' + nextSlide + '"]').attr('id') == 'flight-status') {}
          if ($('.slide[data-slick-index="' + nextSlide + '"]').hasClass('board')) {
            $('.flapper').remove();
            $('.slide[data-slick-index="' + nextSlide + '"] input.hero.title').each(function () {
              var text = $(this).attr('data-src');
              var spaceCount = text.length;
              console.log(spaceCount)
              var options = {
                align: 'left',
                width: spaceCount,
                timing: 100
              }
              $(this).flapper(options).val(text).change();
            });
            $('.slide[data-slick-index="' + nextSlide + '"] input.hero.time').each(function () {
              var text = $(this).attr('data-src');
              var spaceCount = text.length;
              console.log(spaceCount)
              var options = {
                align: 'right',
                width: spaceCount,
                timing: 100
              }
              $(this).flapper(options).val(text).change();
            });
          }
        });
        $('.welcome #content h1.xl').fitText(2);
        $('.welcome #content h1.large').fitText(2.5);
        $('.welcome #content h1.medium').fitText(4);
        $('.welcome #content .xl p').fitText(3);
        $('.welcome #content .large p').fitText(4);
        $('.welcome #content .medium p').fitText(9);
      },
      finalize: function () {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    }, // Home page
    'home': {
      init: function () {
        // JavaScript to be fired on the home page
      },
      finalize: function () {
        // JavaScript to be fired on the home page, after the init JS
      }
    }, // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function () {
        // JavaScript to be fired on the about us page
      }
    }
  };
  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function (func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';
      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function () {
      // Fire common init JS
      UTIL.fire('common');
      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function (i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });
      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };
  // Load Events
  $(document).ready(UTIL.loadEvents);
})(jQuery); // Fully reference jQuery after this point.
