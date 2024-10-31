jQuery(document).ready(function($) {
	
  $('#pp-photo-preview img[src=""]').hide();

  $('#pp-photo-select').click(function(e) {
	  var pp_select_photo = $(this);
	  e.preventDefault();
		
		var insertImage = wp.media.controller.Library.extend({
			defaults :  _.defaults({
				id: 'insert-image',
				title: 'Select User Image',
				// allowLocalEdits: true,
				displaySettings: true,
				// displayUserSettings: true,
				multiple: false,
				type: 'image' // audio, video, application/pdf, ... etc
			}, wp.media.controller.Library.prototype.defaults )
		});
		
		//Setup media frame
		var frame = wp.media({
			button: { text : 'Select' },
			state: 'insert-image',
			states: [ new insertImage() ]
		});
		
		
		frame.on( 'select',function() {
			var state = frame.state('insert-image');
			var selection = state.get('selection');
		
			if ( !selection ) return;

			selection.each(function( attachment ) {
				
				var display = state.display(attachment).toJSON();
				var img_info = attachment.toJSON();
				var selected_img = wp.media.string.props(display, img_info);
				var img_src = selected_img['src'];
				
				pp_select_photo.parent().next().children('#pp-photo').val( img_src );
				pp_select_photo.parent().next().children('#pp-photo-preview').children('img').attr( 'src', img_src );		
				pp_select_photo.parent().next().children('#pp-photo-preview').children('img').css( 'display', 'inline-block' );
	
			});
		});
		
		//reset selection in popup, when open the popup
		frame.on('open',function() {
			var selection = frame.state('insert-image').get('selection');
		
			//remove all the selection first
			selection.each(function(image) {
				var attachment = wp.media.attachment( image.attributes.id );
				attachment.fetch();
				selection.remove( attachment ? [ attachment ] : [] );
			});
			
		});
		
		//now open the popup
		frame.open();
  });

});