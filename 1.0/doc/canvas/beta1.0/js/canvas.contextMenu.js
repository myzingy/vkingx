/**
*	右键菜单
**/
if(jQuery)( function() {
	$.extend($.fn, {
		contextMenu: function(e,o, callback) {
			// Defaults
			if( o.menu == undefined ) return false;
			if( o.inSpeed == undefined ) o.inSpeed = 150;
			if( o.outSpeed == undefined ) o.outSpeed = 75;
			// 0 needs to be -1 for expected results (no fade)
			if( o.inSpeed == 0 ) o.inSpeed = -1;
			if( o.outSpeed == 0 ) o.outSpeed = -1;
			var hideflag=false;
			var el = $(this);
			var offset = $(el).offset();
			// Add contextMenu class
			$('#' + o.menu).addClass('contextMenu');
			e.stopPropagation();
			
			$(".contextMenu").hide();
			var menu = $('#' + o.menu);
			if( $(el).hasClass('disabled') ) return false;
						
			// Detect mouse position
			var d = getMouseXY(e);
			var x = d.x;
			var y = d.y;
						
			// Show the menu
			
			$(menu).css({ top: y, left: x }).fadeIn(o.inSpeed);
			
			$(menu).mouseout(function(){
				//alert(this.id);
				hideflag=true;
				setTimeout(function(){if(hideflag)$(menu).fadeOut(o.outSpeed);},1000);
			}).mouseover(function(){
				hideflag=false;
			});
			
			// Hover events
			$(menu).find('A').mouseover( function() {
				$(menu).find('LI.hover').removeClass('hover');
				$(this).parent().addClass('hover');
			}).mouseout( function() {
				$(menu).find('LI.hover').removeClass('hover');
			});
						
						
						
			$('#' + o.menu).find('A').unbind('click');
			$('#' + o.menu).find('LI:not(.disabled) A').click( function() {
				//$(document).unbind('click').unbind('keypress');
				$(".contextMenu").hide();
				// Callback
				if( callback ) callback( $(this).attr('href').substr(1), $(el), {x: x - offset.left, y: y - offset.top, docX: x, docY: y} );
				return false;
			});
						

		
			
			// Disable text selection
			if( $.browser.mozilla ) {
				$('#' + o.menu).each( function() { $(this).css({ 'MozUserSelect' : 'none' }); });
			} else if( $.browser.msie ) {
				$('#' + o.menu).each( function() { $(this).bind('selectstart.disableTextSelect', function() { return false; }); });
			} else {
				$('#' + o.menu).each(function() { $(this).bind('mousedown.disableTextSelect', function() { return false; }); });
			}
			// Disable browser context menu (requires both selectors to work in IE/Safari + FF/Chrome)
			$(el).add($('UL.contextMenu')).bind('contextmenu', function() { return false; });
			
			return $(this);
		}
		
	});
})(jQuery);