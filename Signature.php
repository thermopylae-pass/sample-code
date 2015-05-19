<?php
/**
 * code depicts calculating a requstSignature using sha1 and base64 encoding
 */
function calculateSignature(
			$accessId,
			$accessKey,
			$merchantId,
			$description,
			$currency,
			$amount,
			$merchantReference,
			$paymentType = null,
			$timeZone = null,
			$recurrenceStartDate = null,
			$recurrenceEndDate = null,
			$recurrenceFrequency = null,
			$recurrenceFrequencyUnit = null,
			$recurrenceFrequencyUnitType = null,
			$recurrenceRecurringAmount = null,
			$recurrenceAutomaticCapture = null,
			$paymentDataVerificationStatus = null,
			$paymentDataCustomerCustomerId = null,
			$paymentDataCustomerExternalId = null,
			$paymentDataCustomerName = null,
			$paymentDataCustomerSsn = null,
			$paymentDataCustomerDriverLicenseNumber = null,
			$paymentDataCustomerDriverLicenseState = null,
			$paymentDataCustomerAddressAddress1 = null,
			$paymentDataCustomerAddressAddress2 = null,
			$paymentDataCustomerAddressCity = null,
			$paymentDataCustomerAddressState = null,
			$paymentDataCustomerAddressZip = null,
			$paymentDataCustomerAddressCountry = null,
			$paymentDataCustomerPhone = null,
			$paymentDataCustomerEmail = null,
			$paymentDataRequiresCustomerDataValidation = null
			) {

	// The signature is created from a query string, where order and presence matters.
	// We use an array to store the values.
	$queryArr = array(
		'accessId' => $accessId,
		'merchantId' => $merchantId,
		'description' => $description,
		'currency' => $currency,
		'amount' => $amount,
		'merchantReference' => $merchantReference
	);

	if (!empty($paymentType)) $queryArr['paymentType'] = $paymentType;
	if (!empty($timeZone)) $queryArr['timeZone'] = $timeZone;

	// Recurrence Information
	if (!empty($recurrenceStartDate)) $queryArr['recurrence.startDate'] = $recurrenceStartDate;
	if (!empty($recurrenceEndDate)) $queryArr['recurrence.endDate'] = $recurrenceEndDate;
	if (!empty($recurrenceFrequency)) $queryArr['recurrence.frequency'] = $recurrenceFrequency;
	if (!empty($recurrenceFrequencyUnit)) $queryArr['recurrence.frequencyUnit'] = $recurrenceFrequencyUnit;
	if (!empty($recurrenceFrequencyUnitType)) $queryArr['recurrence.frequencyUnitType'] = $recurrenceFrequencyUnitType;
	if (!empty($recurrenceRecurringAmount)) $queryArr['recurrence.recurringAmount'] = $recurrenceRecurringAmount;
	if (!empty($recurrenceAutomaticCapture)) $queryArr['recurrence.automaticCapture'] = $recurrenceAutomaticCapture;

	// Verification Status
	if (!empty($paymentDataVerificationStatus)) $queryArr['verificationStatus'] = $paymentDataVerificationStatus;

	// Customer Information
	if (!empty($paymentDataCustomerCustomerId)) $queryArr['customer.customerId'] = $paymentDataCustomerCustomerId;
	if (!empty($paymentDataCustomerExternalId)) $queryArr['customer.externalId'] = $paymentDataCustomerExternalId;
	if (!empty($paymentDataCustomerName)) $queryArr['customer.name'] = $paymentDataCustomerName;
	if (!empty($paymentDataCustomerSsn)) $queryArr['customer.ssn'] = $paymentDataCustomerSsn;
	if (!empty($paymentDataCustomerDriverLicenseNumber)) $queryArr['customer.driverLicense.numbe'] = $paymentDataCustomerDriverLicenseNumber;
	if (!empty($paymentDataCustomerDriverLicenseState)) $queryArr['customer.driverLicense.state'] = $paymentDataCustomerDriverLicenseState;
	if (!empty($paymentDataCustomerAddressAddress1)) $queryArr['customer.address.address1'] = $paymentDataCustomerAddressAddress1;
	if (!empty($paymentDataCustomerAddressAddress2)) $queryArr['customer.address.address2'] = $paymentDataCustomerAddressAddress2;
	if (!empty($paymentDataCustomerAddressCity)) $queryArr['customer.address.city'] = $paymentDataCustomerAddressCity;
	if (!empty($paymentDataCustomerAddressState)) $queryArr['customer.address.state'] = $paymentDataCustomerAddressState;
	if (!empty($paymentDataCustomerAddressZip)) $queryArr['customer.address.zip'] = $paymentDataCustomerAddressZip;
	if (!empty($paymentDataCustomerAddressCountry)) $queryArr['customer.address.country'] = $paymentDataCustomerAddressCountry;
	if (!empty($paymentDataCustomerPhone)) $queryArr['customer.phone'] = $paymentDataCustomerPhone;
	if (!empty($paymentDataCustomerEmail)) $queryArr['customer.email'] = $paymentDataCustomerEmail;
	if (!empty($paymentDataRequiresCustomerDataValidation)) $queryArr['requiresCustomerDataValidation'] = $paymentDataRequiresCustomerDataValidation;


	// Convert the array into HTTP query string
	$query = http_build_query($queryArr, '', '&');
	
	// The signature shouldn't have escaped characters and http_build_query() automatically encodes them. We have to decode them before using.
	$query = urldecode($query);

	// The signature itself is a binary SHA1 HMAC hash, encoded in BASE64. Remember to tell to hash_hmac() to return binary values.
	return base64_encode(hash_hmac('sha1', $query, $accessKey, true));
}

echo "\nSample Instant Signature: ";
echo calculateSignature(
		"1111111111111111111",	// accessId
		"2222222222222222222",	// accessKey
		"99999999",			// merchantId
		"description",			// description
		"USD",					// currency
		"10.00",				// amount
		"REF");					// merchantReference
echo "\nExcepted: FSn9DejCsAVHXjrYnOLaGqCd6DQ=";

echo "\n\nSample Recurring Signature: ";
echo calculateSignature(
		"1111111111111111111",	// accessId
		"2222222222222222222",	// accessKey
		"99999999",			// merchantId
		"description",			// description
		"USD",					// currency
		"30.00",				// amount
		"REF",					// merchantReference
		"Recurring",			// paymentType
		null,					// timeZone
		"1356998400000",		// recurrenceStartDate (01/01/2013)
		"1362096000000",		// recurrenceEndDate (03/01/2013)
		"3",					// recurrenceFrequency
		"1",					// recurrenceFrequencyUnit
		"Month",				// recurrenceFrequencyUnitType
		"10.00",				// recurrenceRecurringAmount
		"true");				// recurrenceAutomaticCapture
echo "\nExcepted: FWptWsJINK36i+78RUEOYPwYs08=";

echo "\n\nSample Customer Information Signature: ";
echo calculateSignature(
		"1111111111111111111",	// accessId
		"2222222222222222222",	// accessKey
		"99999999",			// merchantId
		"description",			// description
		"USD",					// currency
		"10.00",				// amount
		"REF",                  // merchantReference
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		"Sample Customer",      // Customer Name
		null,
		null,
		null,
		"123 Sample St",        // Customer Address 1
		null,
		"Sample City",          // Customer City
		"SA",					// Customer State
		"12345",                // Customer Zip
		null,
		null,
		"sample@somedomain.com"); // Customer Email
echo "\nExcepted: n0PwnJZklsnGPNqtOweHVfAk+kA=";

?>