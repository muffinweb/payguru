# PAYGURU - PHP API
Php API for Payguru mobile

# Sample
```
$payguru = new PayguruApi([
	'paymentUrl' => 'https://payguru.com/token',
	'merchantId' => 4255,
	'serviceId' => 6354,
	'secretKfey' => '7D4F8D5DF6DF',
	'successUrl' => 'https://www.google.com.tr',
	'failureUrl' => 'https://www.yahoo.com.tr'
]);
```

```
$payguru->item('1 AYLIK ABONELIK')->reference('01A552')->price(30)->hasTrialPeriod(29)->tryPayment();
```