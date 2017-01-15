jQuery(document).ready(function($){

  /*--------------------------------------
            SCROLLING ANIMATION
  ---------------------------------------*/

  $(window).scroll(function(){
    var windowHeight = $(window).height(),
        windowTop = $(window).scrollTop(),
        windowBottom = (windowTop + windowHeight),
        scrollnimation = $('.scrollnimate');

    scrollnimation.each(function(){
      var elementHeight = $(this).height(),
          elementTop = $(this).offset().top,
          elementBottom = (elementTop + elementHeight);

      if ( windowBottom > elementTop ){
        if ($(this).hasClass('sn-fade-in')) {
          $(this).addClass('scrollnimate-fade-in');
        }
      } else {
        if ($(this).hasClass('scrollnimate-fade-in')) {
          $(this).removeClass('scrollnimate-fade-in');
        }
      }
    });

  });

  /*--------------------------------------
              OTHER ANIMATION
  ---------------------------------------*/





}); // end doc

jQuery(document).ready(function($){


  /*--------------------------------------
            MAIN IMAGE SELECT
  ---------------------------------------*/
  var currentImage = $('#panels-gallery-content .gallery-image picture'),
      selectedImage = $('#panels-gallery-content .selected-image .image'),
      pinitButton = $('#panels-gallery-content .pin-it'),
      pinitAPI = 'https://www.pinterest.com/pin/create/button/?',
      currentURL = window.location.protocol + window.location.host + window.location.pathname,
      imageSource = null;


  currentImage.first().clone().appendTo(selectedImage);
  currentImage.first().parent('div').addClass('selected');
  imageSource = window.location.protocol + window.location.host + currentImage.first().find('img').attr('srcset');
  pinitButton.attr('href', pinitAPI + 'url=' + currentURL + "&media=" + imageSource);

  if ($('#panels-gallery-content .gallery-image').length == 1) {
    $('#panels-gallery-content .gallery-image').hide();
  }
  
  currentImage.click(function(){
    var previousImage = currentImage;

    previousImage.parent('div').removeClass('selected');

    currentImage = $(this);
    currentImage.parent('div').addClass('selected');

    selectedImage.animate({
      opacity: '0',
      }, 300, function(){
        var initialHeight = selectedImage.height(),
            initalWidth = selectedImage.width();

        selectedImage.css({'width': initalWidth, 'height': initialHeight});
        selectedImage.find('picture').replaceWith(currentImage.clone());

        var nextHeight = selectedImage.find('img').height(),
            nextWidth = selectedImage.find('img').width();

        selectedImage.animate({
          width: nextWidth,
          height: nextHeight,
          opacity: '1'
        }, 300, function(){
          selectedImage.css({'width': 'auto', 'height': 'auto'});
          imageSource = selectedImage.find('img').attr('srcset');
          pinitButton.attr('href', pinitAPI + 'url=' + currentURL + "&media=" + imageSource);
        });
    });
  });



  /*--------------------------------------
         ADD IMAGE TO PIN IT BUTTONS
  ---------------------------------------*/

  // Get image src, replace pin it href




});

jQuery(document).ready(function($){



  // SHOW COOKIE POPUP ... Mmm... cookies...
  $(window).load(function(){
    window.setTimeout(function(){
      popup('show');
    }, 2000);

    function popup(hideOrShow) {
      var policyURL = window.location.protocol + '://' + window.location.host,
          cookiePopup = '<div id="cookie-popup">' +
                          '<span>This website, like most, uses cookies. Read the <a href="' + policyURL + '/privacy-policy">privacy policy</a> to find out what a cookie is.</span>' +
                          '<div class="button" role="button" aria-label="dismiss">Go away, I like cookies</div>' +
                        '</div>';


      if (hideOrShow == 'show') {
        if ( localStorage.getItem('cookiePopupDismissed') == null ){
          //build the popup
          $('body').append(cookiePopup);
          window.setTimeout(function(){
            $('#cookie-popup').animate({
              'bottom': '0',
            }, 1000);
          }, 2000);
        }
      }
    }

    $('body').on('click', '#cookie-popup .button', function(){
      //Note that the user clicked the dismiss button, animate then remove the popup... forever... dun dun dun
      localStorage.setItem('cookiePopupDismissed', 1);
      $('#cookie-popup').animate({
        'bottom': '-100%',
      }, 1000, function(){
        $('#cookie-popup').remove();
      });
    });

  });






/*--------------------------------------
          EMBED SVG GRAPHICS
---------------------------------------*/
$('img').filter(function(){
  return this.src.match(/.*\.svg$/);
  }).each(function(){

  var img = $(this),
      imgURL = img.attr('src'),
      imgID = img.attr('id'),
      imgClass = img.attr('class'),
      imgAlt = img.attr('alt');

  $.get(imgURL, function(data){
    var svg = $(data).find('svg');

    // Add back ID and classes
    if (imgID) {
      svg.attr('id', imgID);
    }
    if (imgClass) {
      svg.attr('class', imgClass);
    }

    // Accessibility - add an ARIA label with the alt text
    svg.attr('role', 'img');
    svg.attr('aria-label', imgAlt);

    // Remove invalid XML tags
    svg.removeAttr('xmlns:a');

    // Optional: Strip it clean and give it a $2
    svg.removeAttr('style');
    svg.find('style').remove();

    img.replaceWith(svg);
  }, 'xml');
});

/*--------------------------------------
           SECTION SCROLLING
---------------------------------------*/

$('.scroll-down').click(function(){
  var section = $(this).parents('.section').next('.section'),
      sectop = section.offset().top;

  $("html, body").animate({ scrollTop: sectop }, 800);
});




});// end doc

jQuery(document).ready(function($){

  $(window).load(function(){

    /*--------------------------------------
                    GROUPINGS
    ---------------------------------------*/
    //check URL and fire off grouping script

    // - PORTFOLIO
    if (window.location.href.indexOf('/portfolio') > -1) {
      ga('set', 'contentGroup2', 'Portfolio');
    }
    // - PORTFOLIO CONTENT
      // see layouts gallery_content.html.twig

    // - SUPPORT
    else if (window.location.href.indexOf('/work') > -1) {
      ga('set', 'contentGroup1', 'About');
    } else if (window.location.href.indexOf('/about') > -1 ) {
      ga('set', 'contentGroup1', 'About');
    } else if (window.location.href.indexOf('/connect') > -1 ) {
      ga('set', 'contentGroup1', 'Contact');
    }

    // - HOME
      // see page--front.html.twig

  }); // end load


  /*--------------------------------------
                  GOALS
  ---------------------------------------*/
  $('input').data('ga-goal', 'submit').click(function(){
    ga('send', { 'hitType': 'pageview', 'page': '/vpv/form-submit', 'title': window.location.pathname });
  });


}); // end ready

jQuery(document).ready(function($){
  /*--------------------------------------
            NAV OPEN/CLOSE
  ---------------------------------------*/
  var navHeight = 50,
      loc = window.location.pathname;

  // If the menu state is set in desktop mode, load menu in that state
  if ($(window).width() > 1080) {
    if (localStorage.getItem('navMenuOpen') == 1) {
      $('#main-menu #branding').show().css('opacity', 1);
      $('#main-menu #nav-menu').show().css('opacity', 1);
      $('#main-menu').css('width', '100%');
      $('#main-menu .menu-button').addClass('opened');
    } else {
      $('#main-menu #branding').hide();
      $('#main-menu #nav-menu').hide();
    }
  } else {
    $('#main-menu #branding').hide();
    $('#main-menu #nav-menu').hide();
  }

  // Detect if location matches main menu items
  $('#main-menu a').each(function(){
    if (loc.indexOf($(this).attr('href')) != -1) {
      $(this).addClass('is-active');
    }
    if ( $(this).hasClass('logo') && loc != $(this).attr('href') ) { // Don't highlight logo if not on home page
      $(this).removeClass('is-active');
    }
  });


  $('#main-menu .menu-button').click(function(){
    if ($(this).hasClass('opened')) {
      openMenu('close');
      $(this).removeClass('opened')
    } else {
      openMenu('open');
      $(this).addClass('opened');
    }
  });

  function openMenu(command){
    //commands: 'open', 'close'

    if (command == 'open') {
      $('#main-menu').animate({
        width: '100%'
      }, 500, function(){
        if ($(window).width() < 1080) { // Mobile
          $('#main-menu').animate({ height: '100vh'}, 500);
        } else { // desktop
          // Store state
          localStorage.setItem('navMenuOpen', 1);
        }
        $('#nav-menu').show();
        $('#branding').show();
        window.setTimeout(function(){
          $('#nav-menu').animate({
            opacity: 1
          }, 500);
        }, 300);
        window.setTimeout(function(){
          $('#branding').animate({
            opacity: 1
          }, 500);
        }, 300);
      });

    } else if (command == 'close') {
      window.setTimeout(function(){
        $('#nav-menu').animate({
          opacity: 0
        }, 500, function(){
          $('#nav-menu').hide();
        });
      }, 300);
      window.setTimeout(function(){
        $('#branding').animate({
          opacity: 0
        }, 500, function(){
          $('#branding').hide();
        });
      }, 300);
      window.setTimeout(function(){
        if ($(window).width() < 1080) { // Mobile
          $('#main-menu').animate({
            height: navHeight
          }, 500, function(){
            $('#main-menu').animate({
              width: navHeight
            }, 500);
          });
        } else { // Desktop
          localStorage.setItem('navMenuOpen', 0);
          $('#main-menu').animate({
            width: navHeight
          }, 500);
        }
      }, 500);
    }
  }

  /*--------------------------------------
          ADD SHADOW ON SCROLL
  ---------------------------------------*/
  $(window).scroll(function(){
    if( $(window).scrollTop() > 0 ) {
      $('#main-menu').addClass('scroll');
    } else {
      $('#main-menu').removeClass('scroll');
    }
  });


  /*--------------------------------------
                  SHARE MENU
  ---------------------------------------*/
  var shareButton = $('.share-menu .share-menu-button'),
      shareExpand = $('.share-menu .share-menu-expand');

      shareButton.click(function(){
        if (!shareButton.hasClass('active')){
          shareButton.addClass('active');
          shareExpand.slideDown();
        } else {
          shareButton.removeClass('active');
          shareExpand.slideUp();
        }
      });



}); // end doc
