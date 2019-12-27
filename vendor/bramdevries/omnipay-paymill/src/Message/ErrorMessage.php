<?php

namespace Omnipay\Paymill\Message;

class ErrorMessage
{
	/**
	 * @var array
	 */
	protected static $errors = array(
		'40001' => 'RESPONSE_DATA_INVALID',
		'40100' => 'RESPONSE_DATA_CARD',
		'40101' => 'RESPONSE_DATA_CARD_CVV',
		'40102' => 'RESPONSE_DATA_CARD_EXPIRED',
		'40103' => 'RESPONSE_DATA_CARD_LIMIT_EXCEEDED',
		'40104' => 'RESPONSE_DATA_CARD_INVALID',
		'40105' => 'RESPONSE_DATA_CARD_EXPIRY_DATE',
		'40106' => 'RESPONSE_DATA_CARD_BRAND',
		'40201' => 'RESPONSE_DATA_ACCOUNT_COMBINATION',
		'40202' => 'RESPONSE_DATA_ACCOUNT_AUTH_FAILED',
		'40301' => 'RESPONSE_DATA_3D_AMOUNT_CURRENCY_MISMATCH',
		'40401' => 'RESPONSE_DATA_INPUT_AMOUNT_TOO_LOW',
		'40402' => 'RESPONSE_DATA_INPUT_USAGE_TOO_LONG',
		'40403' => 'RESPONSE_DATA_INPUT_CURRENCY_NOT_ALLOWED',
		'50001' => 'RESPONSE_BACKEND_BLACKLISTED',
		'50002' => 'RESPONSE_BACKEND_IP_BLACKLISTED',
		'50102' => 'RESPONSE_BACKEND_CARD_DENIED',
		'50103' => 'RESPONSE_BACKEND_CARD_MANIPULATION',
		'50104' => 'RESPONSE_BACKEND_CARD_RESTRICTED',
		'50105' => 'RESPONSE_BACKEND_CARD_CONFIGURATION',
		'50201' => 'RESPONSE_BACKEND_ACCOUNT_BLACKLISTED',
		'50300' => 'RESPONSE_BACKEND_3D',
		'50502' => 'RESPONSE_BACKEND_TIMEOUT_RISK',
		'50501' => 'RESPONSE_BACKEND_TIMEOUT_ACQUIRER',
		'50600' => 'RESPONSE_BACKEND_TRANSACTION_DUPLICATE',
	);

	/**
	 * @param $code
	 *
	 * @return string
	 */
	public static function getErrorForCode($code)
	{
		return isset(self::$errors[$code]) ? self::$errors[$code] : 'UNKNOWN_ERROR';
	}
} 