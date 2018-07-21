jQuery(document).ready(function($) {
$('.pop-link').click(function () {
             var poplink = $(this).attr('href');

	newwindow=window.open(poplink,'name','height=800,width=1024');
	if (window.focus) {newwindow.focus()}
	return false;
})


if ( window.location !== window.parent.location ) {		  $('body').addClass('in-iframe');	$('html').addClass('in-iframe')	} else {				}

});