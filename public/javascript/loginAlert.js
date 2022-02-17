$(function() {
    var alert = $("div.alert-danger[auto-close]");
    alert.each(function() {
      var that = $(this);
      var timePeriod = that.attr("auto-close");
      setTimeout(function() {
        that.alert("close");
      }, timePeriod);      
    });
  });

