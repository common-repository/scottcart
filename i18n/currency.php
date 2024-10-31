<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// currencies 
function scottcart_get_currencies() {
	$currencies = array(
		'USD'  => __( 'US Dollars', 'scottcart' ),
		'EUR'  => __( 'Euros', 'scottcart' ),
		'GBP'  => __( 'Pounds Sterling', 'scottcart' ),
		'AUD'  => __( 'Australian Dollars', 'scottcart' ),
		'BRL'  => __( 'Brazilian Real', 'scottcart' ),
		'CAD'  => __( 'Canadian Dollars', 'scottcart' ),
		'CZK'  => __( 'Czech Koruna', 'scottcart' ),
		'DKK'  => __( 'Danish Krone', 'scottcart' ),
		'HKD'  => __( 'Hong Kong Dollar', 'scottcart' ),
		'HUF'  => __( 'Hungarian Forint', 'scottcart' ),
		'ILS'  => __( 'Israeli Shekel', 'scottcart' ),
		'JPY'  => __( 'Japanese Yen', 'scottcart' ),
		'MYR'  => __( 'Malaysian Ringgits', 'scottcart' ),
		'MXN'  => __( 'Mexican Peso', 'scottcart' ),
		'NZD'  => __( 'New Zealand Dollar', 'scottcart' ),
		'NOK'  => __( 'Norwegian Krone', 'scottcart' ),
		'PHP'  => __( 'Philippine Pesos', 'scottcart' ),
		'PLN'  => __( 'Polish Zloty', 'scottcart' ),
		'SGD'  => __( 'Singapore Dollar', 'scottcart' ),
		'SEK'  => __( 'Swedish Krona', 'scottcart' ),
		'CHF'  => __( 'Swiss Franc', 'scottcart' ),
		'TWD'  => __( 'Taiwan New Dollars', 'scottcart' ),
		'THB'  => __( 'Thai Baht', 'scottcart' ),
		'INR'  => __( 'Indian Rupee', 'scottcart' ),
		'TRY'  => __( 'Turkish Lira', 'scottcart' ),
		'RIAL' => __( 'Iranian Rial', 'scottcart' ),
		'RUB'  => __( 'Russian Rubles', 'scottcart' )
	);

	return apply_filters( 'scottcart_currencies', $currencies );
}
