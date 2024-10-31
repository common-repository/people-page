jQuery(document).ready(function() {
  jQuery(function() {
	  jQuery('#peopleOn, #peopleOff').sortable({
		  connectWith: '.connectedSortable',
		  receive: function(event, ui) {
			  ui.item.children('input').prop('disabled', !ui.item.children('input').prop('disabled'));
			  if (jQuery('#peopleOff .header').length == 0){
				  jQuery('#peopleOff').prepend('<li>x<input type=\"text\" name=\"people[]\" class=\"header\" value=\"Heading\" disabled /></li>');
			  }
		  }
	  })//.disableSelection();
  });
  
  // AJAX SAVE
  jQuery('#people_ajax').click(function(event){
	  event.preventDefault();
	  var peoplearray = [];
	  jQuery('#peopleOn input').each(function(){
		  peoplearray.push(jQuery(this).val());
	  });
	  
	  var data = {
		  postid: jQuery(this).data('postid'),
		  action: 'people_save',
		  peeps: peoplearray,
		  _wpnonce: jQuery(this).data('nonce')
	  };
	  jQuery.post(ajaxurl, data, function(response) {
		  jQuery('#saved').show().delay(1000).fadeOut();
	  });
  });
  
});