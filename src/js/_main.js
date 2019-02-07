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
 * ========================================================================
 **/
(function ($) {
  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function () {
        // JavaScript to be fired on all pages
        //        var emojiText = require("emoji-text");



        var updateTimer = setInterval(updateACF, 60000);
        var runningClockTimer = setInterval(runningClock, 1000);

        var mode = Cookies.get('mode');
        var upcoming = Cookies.get('meeting_upcoming');

        function Get(yourUrl) {
          var Httpreq = new XMLHttpRequest(); // a new request
          Httpreq.open("GET", yourUrl, false);
          Httpreq.send(null);
          return Httpreq.responseText;
        }

        var index;
        var json_obj = JSON.parse(Get('https://slack.com/api/emoji.list?token=xoxp-3921626273-114425188213-401339713923-9e939340d027352b450c1e6fdf421ce6'));
        var emojis = json_obj.emoji;

        function loadEmoji() {
          Object.keys(emojis).forEach(function (k) {
            $('span.emoji').each(function () {
              //                                            var userEmoji = $(this).children('span.emoji');
              var text = $(this).text().replace(':', '').replace(':', '');
              if (text == k) {
                $(this).html('<img src="' + emojis[k] + '">');
              } else {
                $(this).emoji();
              }
            })
          });
        }


        var options
        var meetingOut
        var meetings

        function updateACF() {

          options = JSON.parse(Get('/wp-json/acf/v2/options/client_schedule'));
          meetingOut = Cookies.get('leaving_meeting');
          meetings = options.client_schedule;
          console.log('Watching Schedule...')
        }

        function runningClock(){


            if(meetings == false) {


              Cookies.set('no meetings', 'yes');

            }
            if (meetings != false) {
              console.log(meetings);
              console.log('Meeting upcoming:' + options.client_schedule[0].client_time);

              Cookies.remove('no meetings');
              var meetingTime = options.client_schedule[0].client_time;
              var time = moment(meetingTime, 'H:mm:ss');
              var timePlus = moment(meetingTime, 'H:mm:ss');
              var meetingMinus15 = time.subtract(15, 'minutes');
              var meetingPlus15 = timePlus.add(15, 'minutes');
              var meetingMinus15 = moment(meetingMinus15).format('H:mm:ss');
              var meetingPlus15 = moment(meetingPlus15).format('H:mm:ss');



              function checkTime(i) {
                if (i < 10) {
                  i = "0" + i;
                }
                return i;
              }
              function startTime() {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                // add a zero in front of numbers<10
                m = checkTime(m);
                s = checkTime(s);
                var currentTime = h + ":" + m + ":" + s;



//                var currentTime = moment(currentTime, 'h:mm:ss');
                console.log('Current Time: ' + currentTime);
                console.log('15 Min prior to meeting: ' + meetingMinus15);
                console.log('15 Min after meeting: ' + meetingPlus15);

                if ((meetingMinus15 < currentTime) && (currentTime < meetingPlus15) || (meetingMinus15 == currentTime)) {
                  //                        jQuery('.welcome').addClass('transition-to');
                  if(mode == 'slides') {
//                  alert('JS: welcome mode time');
                  Cookies.remove('leaving_meeting');
                  Cookies.set('mode', 'welcome');
                  Cookies.set('entering_meeting', 'yes');
//                  alert('15 minutes before meeting');
                  clearInterval(updateTimer);
                  clearInterval(runningClockTimer);
                  location.reload();

                  }

                } else if ((currentTime == meetingPlus15) || (currentTime > meetingPlus15)) {
                if(mode != 'slides') {
//                  alert('JS: slide mode time');
                  Cookies.remove('entering_meeting');
                  Cookies.set('mode', 'slides');
                  Cookies.set('leaving_meeting', 'yes');
//                  alert('15 after before meeting');
                  clearInterval(updateTimer);
                  clearInterval(runningClockTimer);
                  location.reload();
                }
                } else if(currentTime < meetingMinus15 && mode != 'slides'){
                  Cookies.remove('entering_meeting');
                  Cookies.remove('leaving_meeting');
                  Cookies.set('mode', 'slides');
//                  alert('switching back to slide mode');
                  clearInterval(updateTimer);
                  clearInterval(runningClockTimer);
                  location.reload();
                }

//                else if(currentTime < meetingPlus15 && mode == 'slides') {
//
//                  console.log('welcome mode time');
//                  Cookies.remove('leaving_meeting');
//                  Cookies.set('mode', 'welcome');
//                  Cookies.set('entering_meeting', 'yes');
////                  alert('15 minutes before meeting');
//                  clearInterval(updateTimer);
//                  location.reload();
//
//                }
              }
              startTime();
            } else {


              var restartTime = '8:00:00';

              function checkTime(i) {
                if (i < 10) {
                  i = "0" + i;
                }
                return i;
              }
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                // add a zero in front of numbers<10
                m = checkTime(m);
                s = checkTime(s);
                var currentTime = h + ":" + m + ":" + s;

                console.log(currentTime);

                if (currentTime == restartTime ) {

                  Cookies.remove('no meetings');
                  location.reload();


                }

            }

        }
        function watchSlack() {
          var meetingIn = Cookies.get('entering_meeting');
          if (meetingIn != 'yes') {
            var previous = null;
            var current = null;
            setInterval(function () {
              $.getJSON("https://slack.com/api/users.list?token=xoxp-3921626273-114425188213-401339713923-9e939340d027352b450c1e6fdf421ce6&presence=true", function (json) {
                Object.keys(json.members).forEach(function (k) {
                  if (json.members[k].deleted == false && json.members[k].is_bot == false && json.members[k].name != 'slackbot') {
                    current = json.members[k].presence;
                    var member = json.members[k].name;
                    var status_text = json.members[k].profile.status_text;
                    var emoji = json.members[k].profile.status_emoji;
                    var current_emoji = $('.members-status li[id="' + member + '"] .emoji').attr('data-src');
                    var status = $('.members-status li[id="' + member + '"]').attr('class');
                    if (((previous != current) || (status != current))) {
                      if (status_text == '') {
                        $('.members-status li[id="' + member + '"] .user-status').text(current);
                      } else {
                        $('.members-status li[id="' + member + '"] .user-status').text(status_text);
                        console.log('Updating ' + member + ' Status: ' + status_text + '(' + current + ')');


                      }
                      $('.members-status li[id="' + member + '"]').attr('class', current);
                      $('.members-status li[id="' + member + '"] > span').attr('class', current);

                      console.log('Updating ' + member + ' Status: ' + current);
                      //                      var emojiText = emojiText.convert(emoji, {
                      //                                      delimiter: ':'
                      //                                      });
                      var emojiReplaced;
                      //
                      if (current_emoji != emoji) {
                        Object.keys(emojis).forEach(function (k) {
                          if (k == emoji.replace(':', '').replace(':', '')) {
                            $('.members-status li[id="' + member + '"] .emoji').html('<img src="' + emojis[k] + '">');
                            emojiReplaced = true;
                          }
                        });
                        if (emojiReplaced != true) {
                          $('.members-status li[id="' + member + '"] .emoji').text(emoji);
                          $('.members-status li[id="' + member + '"] .emoji').emoji();
                        }
                        $('.members-status li[id="' + member + '"] .emoji').attr('data-src', emoji);
                      }
                    }
                    status = current;
                  }
                  //                      previous = presence;
                });
                console.timeEnd('Slack Update');
                console.time('Slack Update');
              });
            }, 1 * 60 * 1000);
          }
        }


        updateACF()
        var updateTimer = setInterval(updateACF, 60000);

        $(window).load(function () {


          var runningClockTimer = setInterval(runningClock, 1000);

//          console.time('Slack Update');
          loadEmoji();
          watchSlack();

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
            var slickSlide = $('.slick-active').attr('data-slick-index');
            console.log('has video');
          }
          var i, output = "";
          // Start timing now
          console.time('Slide Timer');
          for (i = 1; i <= 1e6; i++)
            output += i;
          if (slick.currentSlide == slickSlide) {
            console.log('slide video start');
            $(video).get(0).play();
            if (VideoFull == true) {
              console.log('slide playing full video');
              setTimeout(function () {
                slick.slickPause();
              }, 500);
              $(video).on('ended', function () {
                $(video).get(0).pause();
                console.timeEnd('Slide Timer');
                slick.slickNext();
              });
            } else {
              setTimeout(function () {
                slick.slickPause();
                console.log('slide will change in: ' + speed / 1000 + 's');
              }, 500);
              setTimeout(function () {
                console.timeEnd('Slide Timer');
                $(video).get(0).pause();
                slick.slickNext();
              }, speed);
            }
          } else {
            console.log('slide will change in: ' + speed / 1000 + 's');
            setTimeout(function () {
              console.timeEnd('Slide Timer');
              slick.slickNext();
            }, speed);
          }
        });
        $('.fade').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
          console.log(nextSlide);
          if ($('.slide[data-slick-index="' + nextSlide + '"]').attr('id') == 'flight-status') {}
          if ($('.slide[data-slick-index="' + nextSlide + '"]').hasClass('board')) {
            $('.flapper').remove();
//             $('.slide[data-slick-index="' + nextSlide + '"] input.hero.title').each(function () {
//               var text = $(this).attr('data-src');
//               var spaceCount = text.length;

//               if(spaceCount > 35){

//                 spaceCount = 35;
//               }
//               console.log(spaceCount);

//               var timing = Math.floor(Math.random() * 300) + 100;
//               var options = {
//                 align: 'left',
//                 width: spaceCount,
//                 timing: timing
// //                timing: timing
//               }
//               $(this).flapper(options).val(text).change();
//             });
            $('.slide[data-slick-index="' + nextSlide + '"] input.hero.time').each(function () {
              var text = $(this).attr('data-src');
              var spaceCount = text.length;
              console.log(spaceCount)
              var timing = Math.floor(Math.random() * 300) + 100;

              var options = {
                align: 'right',
                width: spaceCount,
                timing: 1000
//                timing: timing
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
