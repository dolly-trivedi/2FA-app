<?php 
return [ 
    'client_id' => 'AZhp7il_IN4AfQR0We9g1ApARsUAGZhMIQQ1i-X0ZetRJp6DXyD-FwtmrZJzXcIEk16pUHgNKer5Rykj',
	'secret' => 'EGekNKJSZivNs9nPDvHBXvrldOJkcin3ThvBJMhA8z8jr5XFH5kS2eAQjfWajD3ad4PaIs8Sir8Hclbz',
    'settings' => array(
        'mode' => 'sandbox',
        'http.ConnectionTimeOut' => 1000,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'FINE'
    ),
];