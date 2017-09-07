!function($){
	$.fn.viewBigImage = function(callback, options){
		 var tmpDivId = "tmp_big_img";
		 var getImgDiv = function(){
			return $("#" + tmpDivId);
		};

        this.mouseenter(function(){
            getImgDiv() && getImgDiv().remove();
			var stl = $.fn.viewBigImage.defaults;
			if(options != undefined && typeof options === 'object'){
				stl = $.extend({}, $.fn.viewBigImage.defaults, options);
			}
			var top_offset  = options && options.top_offset  || 80;
			var left_offset = options && options.left_offset || 0; 
            var tdThis = this,
				offset = $(this).offset(),
				top = offset.top - (parseInt(stl.height)-$(this).outerHeight())/2 + 'px',
				left = offset.left + $(this).outerWidth() + left_offset + 'px';
				
			stl.left = left;
			stl.top = top;	
			
            var gimg = $('<div id="' + tmpDivId + '"><img width="100%" height="100%" src=' + callback($(tdThis)) + '></div>');
            gimg.appendTo('body').css(stl);

            gimg.mouseleave(function(event){
                    var evtTarget = event.relatedTarget;
                    if(evtTarget != tdThis){
                        $(this).remove();
                    }
            });

        });

        this.mouseleave(function(event){
            var x = event.pageX;
            var y = event.pageY;
            if(getImgDiv()){
                var imgDiv = getImgDiv();
                var offset = imgDiv.offset();
                var img_left = offset.left;
                var img_top = offset.top;
                var img_right = offset.left + imgDiv.outerWidth();
                var img_bottom = offset.top + imgDiv.outerHeight();
                if(x<img_left || x>img_right || y<img_top || y>img_bottom){
                    imgDiv.remove();
                }
            }
            
        });
	}
	$.fn.viewBigImage.defaults = {
		'position': 'absolute',
		'height': '180px',
		'width': '250px',
		'border': "1px solid #AAA",
    };
}(window.jQuery);