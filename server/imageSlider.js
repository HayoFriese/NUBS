'use strict';

$(function() {
  
  var currentImage = 1;
  var autoMove;
  
  autoMove = setInterval(function scrollImage() {
    $('#scroller .images').animate({'margin-left': '-=1125px'}, 800, function() {
      currentImage++;
      if (currentImage == 3) {
        currentImage = 1;
        $('#scroller .images').css('margin-left', 0);
      }
      })
  }, 4000);
  
  $('#arrowRight').click(function() {
    clearInterval(autoMove)
    $('#scroller .images').animate({'margin-left': '-=1125px'}, 800);
      
    
  });
  
    $('#arrowLeft').click(function() {
    clearInterval(autoMove)
    $('#scroller .images').animate({'margin-left': '+=1125px'}, 800);
    
  });
  
});