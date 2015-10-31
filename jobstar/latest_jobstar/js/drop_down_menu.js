var needReload=true;
var lastWidth=$( window ).width()
	$( window ).resize(function() {
	
	  if($( window ).width()<760 && needReload){
	   parentCall=true;
		LoadArea();
		needReload=false;
	  }	 
	if($( window ).width()>=760 && !needReload)	{
	
			needReload=true;
		 	removeEvents();
			}  
	  
	});	
	$(document).ready(function(){
	
	   LoadArea();
	    if ( $(window).width() < 760) {
		needReload=false;
		}
	  });  
	  
	  
	  function removeEvents(){
	  $('#pull').off('click');
	   $('.parent').off('click');
	  $('.sub_parent').off('click');
	  
	  $(".menubox").show()
	  $(".parent").removeClass("active-li");
	  $(".childbox").removeAttr('style');
	  $(".sub_childbox").removeAttr('style');
	   var parentCall=true;
	  }
	   var parentCall=true;
	function LoadArea(){

	 if ( $(window).width() < 760) {
	    $(".menubox").hide(function(){
			$("#pull").click(function(){
			
				$(".menubox").slideToggle("slow");
				$(".parent").removeClass("active-li");		
			});		
		});	
			   
			    $(".childbox").hide();
				 $(".sub_childbox").hide();
				$(".parent").click(function(){
				   
				   if(parentCall){
				   var firstState=$(this).find(".childbox").css('display')
				   $(".childbox").hide();
				   $(".parent").removeClass("active-li");
				   
				   
				   if(firstState == 'none'){ 
					   $(this).find(".childbox").hide();
					   $(this).addClass("active-li");
					} else { 
					   $(this).find(".childbox").show();
					   $(this).removeClass("active-li");
					}

					$(this).find(".childbox").slideToggle();
}	
parentCall=true;				
				});		

			
			$(".sub_parent").click(function(){
				 var chid=$(this).find(".sub_childbox");
				 if(chid!=null){
				   var firstState=$(this).find(".sub_childbox").css('display')
				   $(".sub_childbox").hide();
				   $(".sub_parent").removeClass("active-li");
				   
				   
				   if(firstState == 'none'){ 
					   $(this).find(".sub_childbox").hide();
					   $(this).addClass("active-li");	
					    // $(this).find('span').removeClass("leftarrow");
					    //$(this).find('span').addClass("down_arrow");
					} else { 
					   $(this).find(".sub_childbox").show();
					   $(this).removeClass("active-li");
						//$(this).find('span').removeClass("down_arrow");					   
					   //$(this).find('span').addClass("leftarrow");
					}

					$(this).find(".sub_childbox").slideToggle();
					parentCall=false;	
			}					
				});					
				
				
		
	    }	  
	}