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
  jQuery.extend({
    /**
     * Alert message
     *
     * @param {object} obj
     * 
     * @example {
     *    title: 'Error',
     *    message: 'Failed to created.',
     *    type: 'error' // danger|success
     * } 
     */
    alert: function(obj) {
      var alertType = 'alert-danger';
      if (obj.type) {
        alertType = 'alert-' + obj.type;
      }
      
      var hash = Math.random().toString(36).substr(5);
      var str = '<div id="alert-' + hash + '" class="alert ' + alertType + ' alert-dismissible fade" style="min-width: 300px; position: fixed; z-index: 1; right: 0; top: 0;">';
      if (obj.title) {
        str += '<h4 class="alert-heading">' + obj.title + '</h4>'; // title
        str += '<hr>';
      }
      str += obj.message; // message
      str += '<button type="button" class="close" id="close-' + hash + '">';
      str += '<span aria-hidden="true">&times;</span>';
      str += '</button>';
      str += '</div>';

      $('#app').prepend(str);
      $("#alert-" + hash).addClass('show');

      $("#close-" + hash).click(()=>{
        $("#alert-" + hash).remove();
      });
      
      setTimeout(() => {
        $("#alert-" + hash).removeClass('show').addClass('fade');
      }, 2500);
    }
  });
})(jQuery);
