$(function() {
	
	// define pager options
	var pagerOptions = {
		// target the pager markup - see the HTML block below
		container : $(".pager"),
		// output string - default is '{page}/{totalPages}'; possible variables:
		// {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
		output : '{startRow} - {endRow} / {filteredRows} ({totalRows})',
		// if true, the table will remain the same height no matter how many
		// records are displayed. The space is made up by an empty
		// table row set to a height to compensate; default is false
		fixedHeight : true,
		// remove rows from the table to speed up the sort of large tables.
		// setting this to false, only hides the non-visible rows; needed if you
		// plan to add/remove rows with the pager enabled.
		removeRows : false,
		// go to page selector - select dropdown that sets the current page
		cssGoto : '.gotoPage',
		// apply disabled classname to the pager arrows when the rows at either extreme is visible - default is true
		updateArrows: true,

		// starting page of the pager (zero based index)
		page: 0,

		// Number of visible rows - default is 10
		size: 50,

		// css class names of pager arrows
		cssNext: '.next', // next page arrow
		cssPrev: '.prev', // previous page arrow
		cssFirst: '.first', // go to first page arrow
		cssLast: '.last', // go to last page arrow
		cssGoto: '.gotoPage', // select dropdown to allow choosing a page

		cssPageDisplay: '.pagedisplay', // location of where the "output" is displayed
		cssPageSize: '.pagesize', // page size selector - select dropdown that sets the "size" option

		// class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
		cssDisabled: 'disabled' // Note there is no period "." in front of this class name
	};

	// Initialize tablesorter
	// ***********************
	$("#ranking").tablesorter({
		showProcessing: true,
		theme : 'default',
		sortList : [ [ 0, 0 ] ],
		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
		widthFixed : true,
		widgets : [ 'zebra', 'filter' ]
	});

	// initialize the pager plugin
	// ****************************
	.tablesorterPager(pagerOptions);


	// Disable / Enable
	// **************
	$('.toggle').click(function() {
		var mode = /Disable/.test($(this).text());
		$('table').trigger((mode ? 'disable' : 'enable') + '.pager');
		$(this).text((mode ? 'Enable' : 'Disable') + 'Pager');
	});
	$('table').bind('pagerChange', function() {
		// pager automatically enables when table is sorted.
		$('.toggle').text('Disable');
	});
});