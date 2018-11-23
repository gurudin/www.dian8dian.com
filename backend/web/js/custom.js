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

var date = {
  /**
   * 
   * @param {string} format 'Y-m-d H:m:s'
   * @param {int} timestamp
   * 
   * @return {string} format time
   */
  format: function(format, timestamp) {
    timestamp = timestamp.toString().length == 10 ? timestamp * 1000 : timestamp;
    let date = new Date(timestamp);
    let retDate = '';

    for (let i = 0; i < format.length; i++) {
      retDate += this.getFormat(format[i], date);
    }

    return retDate;
  },
  getFormat: function (str, date) {
    if (str == 'Y') {
      return date.getFullYear();
    }
    if (str == 'y') {
      return date.getFullYear().toString().substring(2);
    }
    if (str == 'M') {
      return date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
    }
    if (str == 'D' || str == 'd') {
      return date.getDate();
    }
    if (str == 'H' || str == 'h') {
      return date.getHours();
    }
    if (str == 'm') {
      return date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
    }
    if (str == 'S' || str == 's') {
      return date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds();
    }

    return str;
  }
};

/**
 * 
 * @param {string} currentUrl
 * @param {object} params
 * 
 * @return {string} url
 */
var getUrl = function(currentUrl, params) {
  var url = new URL(currentUrl);
  
  for (const key in params) {
    if (params[key] != '') {
      url.searchParams.append(key, params[key]);
    }
  }

  return url.href;
}
