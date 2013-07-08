$(function() {

	// call the tablesorter plugin
	$("#submissions").tablesorter({
		showProcessing : true,
		theme : 'default',
		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add
		// the bootstrap icon!
		widthFixed : true,
		widgets : [ 'zebra' ],
		// sort on the first column and third column in ascending order
		sortList : [ [ 0, 0 ] ]
	});

});