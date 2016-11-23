$(document).ready(function() {
	//$('header nav ul').superfish({delay:400,animation:{opacity:'show', height:'show'},speed:400,autoArrows:  false,dropShadows: false});
	
	$('.modal_link').click(function(event){
  	event.preventDefault();
  	$('#myModal').removeData("modal");
  	$('#myModal').load($(this).attr('href')+'?no_layout=true',function(){
  		$('#myModal').modal();
  		});
	});
	
	$('#sl_main_gallery').on('slide.bs.carousel', function(e) {
		var $nextImage = $(e.relatedTarget).find('img');
		
		$nextImage.each(function(){
			if($(this).attr('data-original')) {
    		$(this).attr('src', $(this).attr('data-original'));
    		$(this).removeAttr('data-original');
			}
  		});
	});
	
	$('#sl_main_gallery .carousel-inner .active img,#sl_main_blog img').each(function(){
  	if($(this).attr('data-original')) {
    	$(this).attr('src', $(this).attr('data-original'));
    	$(this).removeAttr('data-original');
    	}
	});
});

if(!$('#myCanvas').tagcanvas({
    outlineThickness : 1,
    maxSpeed : 0.05,
			textFont: null,
			textColour: null,
			weight: true,   
    depth : 1
  },'tags')) {
    // TagCanvas failed to load
    $('#myCanvasContainer').hide();
    $("#tags ul").css({'margin':0,'padding':0,'list-style':'none'});
    $("#tags ul li").css({'float':'left','margin':'0 10px'});     
  }
