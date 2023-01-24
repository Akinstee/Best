jQuery(document).ready(function($){

	
	$('#fund-1-table').DataTable({
		"processing":true,
       //"serverSide":true,
		ajax: {url: '/wp-json/best/rest-ajax', dataSrc:""},
			columns: [
                { data: 'Id' },
                { data: 'SchemeId' },
                { data: 'ReportDate' },
                { data: 'Value' },
			]
    });
});