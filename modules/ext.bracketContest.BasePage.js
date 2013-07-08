$(function() {

	// call the tablesorter plugin
	$("#contest").tablesorter({
		showProcessing : true,
		theme : 'default',
		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add
		// the bootstrap icon!
		widthFixed : true,
		widgets : [ 'zebra' ],
		sortList : [ [ 2, 1 ], [ 0, 1 ] ],
		textExtraction: function(node) {
			var anchor = $(node).find('a').first();
			if (anchor !== undefined && anchor.attr('href') !== undefined) {
				return anchor.attr('href');
			}
			return $(node).text(); 
		} 
	});

});