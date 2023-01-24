jQuery(document).ready(function($){
	$('#fund_table').DataTable({
		"processing":true,
       //"serverSide":true,
		ajax: {
            url: '/wp-json/best/rest-ajax', 
            dataSrc:''
        },
        columns: [
                { data: 'Id' },
                { data: 'SchemeId' },
                { data: 'ReportDate' },
                { data: 'Value' },
			]
    });
});