/**
 * Your JS/CoffeeScript goes here
 * Components like custom classes are in components/
 * Third party libraries are in vendor/ or /bower_components/
 *
 * For CoffeeScript style guide please refer to
 * https://github.com/RTO-Websites/CoffeeScript-Style-Guide
 *
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */
'use strict';
var Sticky;

jQuery(function($) {
  var $stickyElementList, init, reInit, registerEvents, sticky;
  $stickyElementList = null;

  /**
   * Initialize modules/plugins/etc.
   *
   * @since 0.1.0
   * @return {void}
   */
  init = function() {
    $stickyElementList = $('[data-elebee-sticky]');
    $stickyElementList.each(function() {
      this.Sticky = new Sticky($(this));
      return this.Sticky.checkStickyness();
    });
  };

  /**
   *
   */
  reInit= function () {

    $stickyElementList = $('[data-elebee-sticky]');
    $stickyElementList.each(function() {
      $(this).css({
        position: '',
        left: '',
        width: '',
        height: ''
      });

      if($(this).data('data-stick-to-bottom')) {
        $(this).css({
          bottom: ''
        });
      }
      else {
        $(this).css({
          top: ''
        });
      }

      if(typeof $(this).data('sticky-placeholder') != 'undefined') {
        $(this).next('.sticky-placeholder').each(function () {
          $(this).remove();
        });
      }
    });

    init();
  };

  /**
   *
   */
  registerEvents = function() {
    $(window).on('resize', reInit);
    $(document).on('scroll', sticky);
    return ;
  };

  /*
   *
   */
  sticky = function(e) {
    return $stickyElementList.each(function() {
      return this.Sticky.checkStickyness();
    });
  };
  init();
  registerEvents();
  return;
});

Sticky = (function() {

  /*
   *
   */
  var data;

  data = {
    offset: 'data-sticky-offset',
    toBottom: 'data-stick-to-bottom',
    placeholder: 'data-sticky-placeholder'
  };


  /*
   *
   */

  function Sticky($element) {
    this.$window = jQuery(window);
    this.$element = $element;
    this.width = $element.outerWidth();
    this.height = $element.outerHeight();
    this.left = $element.offset().left;
    this.trigger = $element.offset().top;
    this.offset = $element.attr(data.offset);
    this.toBottom = $element.attr(data.toBottom) !== void 0;
    this.stickyClass = 'elebee-sticky';
    if (this.offset === void 0) {
      this.offset = 0;
    } else {
      this.offset = parseFloat(this.offset);
    }
    if (this.toBottom) {
      this.trigger += this.height + this.offset;
    } else {
      this.trigger -= this.offset;
    }
    this.isSticky = false;
    this.usePlaceholder = $element.attr(data.placeholder) !== void 0;
    if (this.usePlaceholder) {
      this.addPlaceholder();
      this.hidePlaceholder();
    }
    this.$element.addClass(this.stickyClass)
  }


  /*
   */

  Sticky.prototype.checkStickyness = function() {
    var triggerPos;
    triggerPos = this.$window.scrollTop();
    if (this.toBottom) {
      triggerPos += this.$window.height();
    }
    if (this.isSticky) {
      if (this.toBottom) {
        if (triggerPos > this.trigger) {
          return this.unPin();
        }
      } else {
        if (triggerPos < this.trigger) {
          return this.unPin();
        }
      }
    } else {
      if (this.toBottom) {
        if (triggerPos <= this.trigger) {
          return this.pin();
        }
      } else {
        if (triggerPos >= this.trigger) {
          return this.pin();
        }
      }
    }
  };


  /*
   */

  Sticky.prototype.pin = function() {
    var css, event;
    this.isSticky = true;
    css = {
      position: 'fixed',
      left: this.left + 'px',
      width: this.width + 'px',
      height: this.height + 'px'
    };
    if (this.toBottom) {
      css.bottom = this.offset + 'px';
    } else {
      css.top = this.offset + 'px';
    }
    this.$element.css(css);
    if (this.usePlaceholder) {
      this.showPlaceholder();
    }

    event = new Event('sticky.pined');
    this.$element.get(0).dispatchEvent(event);
  };


  /*
   */

  Sticky.prototype.unPin = function() {
    var css, event;
    this.isSticky = false;
    css = {
      position: '',
      left: '',
      width: '',
      height: '',
      bottom: '',
      top: ''
    };
    this.$element.css(css);
    if (this.usePlaceholder) {
      this.hidePlaceholder();
    }

    event = new Event('sticky.unpined');
    this.$element.get(0).dispatchEvent(event);
  };


  /*
   */

  Sticky.prototype.addPlaceholder = function() {
    var $placecolder;
    $placecolder = jQuery('<div class="sticky-placeholder"></div>');
    return this.$placeholder = $placecolder.insertAfter(this.$element).css({
      width: this.width + 'px',
      height: this.height + 'px'
    });
  };


  /*
   */

  Sticky.prototype.hidePlaceholder = function() {
    return this.$placeholder.hide();
  };


  /*
   */

  Sticky.prototype.showPlaceholder = function() {
    return this.$placeholder.show();
  };

  return Sticky;

})();

// ---
// generated by coffee-script 1.9.2