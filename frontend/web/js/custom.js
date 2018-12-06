$(function() {
  // Search
  var search = function () {
    if ($("input[name='keywords']").val() == '') {
      return false;
    } else {
      window.location.href = '/search/' + $("input[name='keywords']").val();
    }
  }
  $("button[name='btn-keywords']").click(() => {
    search();
  });
  $("input[name='keywords']").keyup(event => {
    if (event.which == 13) {
      search();
    }
  });
});
