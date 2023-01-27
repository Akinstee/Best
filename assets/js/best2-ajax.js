jQuery(document).ready(function($){
	$('#Investment_table').DataTable({
		"processing":true,
		ajax: {
            url: '/wp-json/best2/rest-ajax', 
            dataSrc:''
        },
        columns: [
                {data: "ReportDate"},
                { data: 'Fund1Equities' },
                { data: 'Fund1InfrastructureFunds' },
                { data: 'Fund1MoneyMarket' },
                { data: 'Fund1StateGovBonds' },
                { data: 'Fund1TotalFGNSecurities' },
                { data: 'Fund1UnInvestedCash'},
                { data: 'Fund2CorporateBonds'},
                { data: 'Fund2Equities' },
                { data: 'Fund2InfrastructureBonds' },
                { data: 'Fund2InfrastructureFunds' },
                { data: 'Fund2MoneyMarket' },
                { data: 'Fund2StateGovBonds' },
                { data: 'Fund2TotalFGNSecurities' },
                { data: 'Fund2UnInvestedCash'},
                { data: 'Fund3CorporateBonds' },
                { data: 'Fund3Equities' },
                { data: 'Fund3InfrastructureBonds' },
                { data: 'Fund3MoneyMarket' },
                { data: 'Fund3StateGovBonds' },
                { data: 'Fund3TotalFGNSecurities' },
                { data: 'Fund3UnInvestedCash'},
                { data: 'Fund4CorporateBonds' },
                { data: 'Fund4Equities' },
                { data: 'Fund4MoneyMarket' },
                { data: 'Fund4StateGovBonds' },
                { data: 'Fund4TotalFGNSecurities' },
                { data: 'Fund4UnInvestedCash'},
                { data: 'Fund5MoneyMarket' },
                { data: 'Fund5TotalFGNSecurities' },
                { data: 'Fund5UnInvestedCash'},
                { data: 'Fund6TotalFGNSecurities' },
                { data: 'Fund6UnInvestedCash'}
                    
			]
    });
});
