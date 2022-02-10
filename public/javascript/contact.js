$(function() {
    var alert = $('div.alert[auto-close]');
    alert.each(function() {
      var that = $(this);
      var time_period = that.attr('auto-close');
      setTimeout(function() {
        that.alert('close');
      }, time_period);
    });
  });
