jQuery(document).ready(function($){
	$('#Investment_table').DataTable({
		"processing":true,
		ajax: {
            url: '/wp-json/best2/rest-ajax', 
            dataSrc:''
        },
        columns: [
                { data: 'EQUITIES' },
                { data: 'INFRASTRUCTURE FUNDS' },
                { data: 'TOTAL FGN SECURITIES' },
                { data: 'MONEY MARKET' },
                { data: 'UNINVESTED CASH' },
			]
    });
});
