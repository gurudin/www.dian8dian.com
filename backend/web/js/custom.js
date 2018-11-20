function initMenu(obj) {
  new Vue({
    el: '#nav',
    data() {
      return {
        navItem: obj,
        currentUri: window.location.pathname
      };
    },
    methods: {
      openChild(item) {
        if (typeof item.open == 'undefined') {
          item.open = true;
        } else {
          item.open = !item.open;
        }
      }
    },
    created() {
      var _this = this;
      this.navItem.forEach(row => {
        if (typeof row.child == 'object') {
          row.child.forEach(child => {
            if (child.href == _this.currentUri) {
              row.open = true;
            }
          });
        }
      });
    }
  });
}
(function ($) {
  /**
   * Button for loading
   * @param {string} text
   * 
   * @example $(event).loading(); / $(event).loading('reset');
   */
  var loadingDefaultValue = '';
  $.fn.loading = function (text = '') {
    text == '' ? text = 'loading...' : text;
    if (text == 'reset') {
      this.removeClass('disabled').attr('disabled', false).html(loadingDefaultValue);
    } else {
      loadingDefaultValue = this[0].innerHTML;
      this.addClass('disabled').attr('disabled', true).html(text);
      
      return this;
    }
  };
})(jQuery);
