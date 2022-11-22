function vibe_carousel(){
    if (typeof Flickity=='function') {
      let galleryitems = document.querySelectorAll('.image_slider');
      let imageflicks = [];
      for (var i = galleryitems.length - 1; i >= 0; i--) {
      	imageflicks[i] = new Flickity( items[i], {
		  // options
		  	draggable: true,
		  	freeScroll: false,
			contain: true,
			prevNextButtons: true,
			pageDots: true,
			wrapAround: true,
			cellAlign: 'center',
		});
      }

      let items = document.querySelectorAll('.vibe_carousel.flexslider');
      let flicks = [];
      for (var i = items.length - 1; i >= 0; i--) {
      	var dnav = parseInt(items[i].getAttribute('data-directionnav'));
	    if (typeof dnav === typeof undefined || dnav === false) {
	        dnav = 1;
	    }
	    if(items[i].classList.contains('woocommerce')){

	    	flicks[i] = new Flickity( items[i].querySelector('.slides'), {
			  // options
			  	draggable: true,
			  	freeScroll: false,
				contain: true,
				prevNextButtons: true,
				pageDots: false,
				wrapAround: false,
				groupCells:1,
				cellAlign: 'left',
			});
	    }else{
	    	flicks[i] = new Flickity( items[i].querySelector('.slides'), {
			  // options
			  	draggable: true,
			  	freeScroll: false,
				contain: true,
				prevNextButtons: dnav,
				pageDots: parseInt(items[i].getAttribute('data-controlnav')),
				wrapAround: true,
				groupCells:parseInt(items[i].getAttribute('data-block-move')),
				cellAlign: (parseInt(items[i].getAttribute('data-rtl'))?'right':'left'),
				autoPlay: (parseInt(items[i].getAttribute('data-autoslide')))?3000:false,
			});
	    }

      }


      /*$('.vibe_carousel.flexslider').each(function(){
        var $this = $(this);
        if($(this).find('.slides').length){
          var dnav = parseInt($this.attr('data-directionnav'));
          if (typeof dnav === typeof undefined || dnav === false) {
              dnav = 1;
          }
          $this.flexslider({
            animation: "slide",
            rtl: false,
            controlNav: parseInt($this.attr('data-controlnav')),
            directionNav: dnav,
            animationLoop: parseInt($this.attr('data-autoslide')),
            slideshow: parseInt($this.attr('data-autoslide')),
            itemWidth:parseInt($this.attr('data-block-width')),
            itemMargin:30,
            minItems:parseInt($this.attr('data-block-min')),
            maxItems:parseInt($this.attr('data-block-max')),
            prevText: "<i class='icon-arrow-1-left'></i>",
            nextText: "<i class='icon-arrow-1-right'></i>",
            move:parseInt($this.attr('data-block-move')),
            start: function(slider){
              $(slider).removeClass('loading');
            },
            before:function(slider){
              console.log(slider);
            },
            after: function(slider){
              console.log(slider);
            }
          });
        }
        if($this.hasClass('woocommerce')){
          $this.flexslider({
            selector: ".products > li",
            animation: "slide",
            rtl: false,
            controlNav: false,
            directionNav: true,
            animationLoop: false,
            slideshow: false,
            minItems:1,
            maxItems:1,
            itemWidth:100,
            itemMargin:0,
            prevText: "<i class='icon-arrow-1-left'></i>",
            nextText: "<i class='icon-arrow-1-right'></i>",
          });
        }
       
      });*/
    }
  }
jQuery(document).ready(function ($) {
 
  vibe_carousel();
  if ( window.elementorFrontend && window.elementorFrontend.hooks && window.elementorFrontend.hooks.hasOwnProperty('addAction')) {
    window.elementorFrontend.hooks.addAction( 'init', function() {
        vibe_carousel();
    });
    window.elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
        vibe_carousel();
    });
  }
});