jQuery(function($){
	$('#filter').submit(function(){
		var filter = $('#filter');
		
		$.ajax({
			url:filter.attr('action'),
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			beforeSend:function(xhr){
				filter.find('.apply-filter').addClass('loading');
				$('.ad-row').addClass('loading');
			},
			success:function(data){
				filter.find('.apply-filter').removeClass('loading');		
				$('.ad-row').removeClass('loading');		
				$('.ad-row').html(data); // insert data
			}
		});
		return false;
	});

	$('#clear').click(function(){
		// reset each field
		$('.price-slider input').val(''); 
		$('select[name="locationfilter"]').val(''); 
	 
		$('#filter').submit();

		// do not refresh the page after the link is clicked
		return false;
	});
});
