$(function() {

	// call the tablesorter plugin
	if (!$("#contest > tbody").length)
		$("#contest").prepend($('<tbody>'));
	if (!$("#contest > thead").length)
		$("#contest").prepend($('<thead>'));
	$('#contest > tbody').append($('#contest tr').detach());
	$('#contest > thead').append($('#contest tr:first').detach());
	
	$("#contest").tablesorter({
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