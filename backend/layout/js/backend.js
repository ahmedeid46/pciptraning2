$(function(){
  'use strict';
  // hide placeholder on from focus
  $('[placeholder]').focus(function(){
    $(this).attr('data-text', $(this).attr('placeholder'));
    $(this).attr('placeholder','');
  }).blur(function() {
    $(this).attr('placeholder', $(this).attr('data-text'));
  });


  // add asterisk on requird field
  $('input').each(function(){
    if($(this).attr('required') === 'required'){
      $(this).after('<span class=\'asterisk\'>*</span>');
    }
  });

  //confirmation message on button
   $('.confirm').click(function(){
    return confirm( 'are you sure ?' );
  });
   //details view option
  $('.cont h3').click(function () {
    $(this).next('.full-view').fadeToggle(200);

  });
  // how view the category
  $('.option span').click(function () {
    $(this).addClass('active').siblings('span').removeClass('active');
    if($(this).data('view')=='full'){
      $('.cont .full-view').fadeIn(200);

    }else{
      $('.cont .full-view').fadeOut(200);

    }
  });


});
