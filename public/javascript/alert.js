$(function() {
    var alert = $("div.alert[auto-close]");
    alert.each(function() {
      var that = $(this);
      var timePeriod = that.attr("auto-close");
      setTimeout(function() {
        that.alert("close");
      }, timePeriod);      
    });
  });

/*function replace() {
window.location.replace("https://localhost/blogpost/index.php?p=contact/index/");
};
window.onload = () => {
  let button = document.querySelectorAll(".btn");
  button.addEventListener("click", replace);
};*/
/*$( ".btn" ).click(function() {
  window.location.replace("https://localhost/blogpost/index.php?");
});*/
/*function sendMail(){
  let xmlhttp = new XMLHttpRequest;
  xmlhttp.open("GET", "https://localhost/blogpost/index.php?p=contact/sendMail/");
  xmlhttp.send();
}
/*var jqxhr = $.post( "https://localhost/blogpost/index.php?p=contact/send/", function() {
  window.location.replace("https://localhost/blogpost/index.php?p=contact");
})*/
/*window.onload = () => {
  let buttons = document.querySelectorAll(".btn");
  for(let button of buttons){
      button.addEventListener("click", sendMail);
  }
};*/