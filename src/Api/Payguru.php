<?php 

/**
 * Payguru API | MobileSMS Payment System
 *
 * @author Ugur Cengiz <ugurcengiz@mail.com.tr>
 * @version 0.1
 * @link github.com/muffinweb/payguru-phpapi
 */

namespace Muffinweb\Api;

class Payguru
{
	/** Service Host|Url*/
	public $paymentUrl;

	/** Merchant ID */
	public $merchantId;

	/** Service ID */
	public $serviceId;

	/** Secret Key */
	public $secretKey;

	/** Product Title */
	public $item;

	/** Product Reference Code Prefix */
	public $referencePrefix = '';

	/** Product Reference Code */
	public $referenceCode;

	/** Product Price */
	public $price;

	/** Url to redirect after successful payment */
	public $successUrl;

	/** Url to redirect after failed payment */
	public $failureUrl;

	/** This payment has period? */
	public $hasTrialPeriod;

	/** Period Day */
	public $trialPeriodDay;

	public $otherMethodsEnabled = false;

	/** Constructor method */
	public function __construct($args = array())
	{
		if(!array_diff_key(array_flip([
			'paymentUrl',
			'merchantId',
			'serviceId',
			'secretKey',
			'successUrl',
			'failureUrl',

		]), $args)) {

			//Parse2 Object
			$args = (object) $args;

			if(filter_var($args->paymentUrl, FILTER_VALIDATE_URL)){
				$this->paymentUrl = $args->paymentUrl;
			} else {
				die("URL FORMATI YANLIS");
			}

			$this->merchantId = $args->merchantId;
			$this->serviceId = $args->serviceId;
			$this->secretKey = $args->secretKey;
			$this->successUrl = $args->successUrl;
			$this->failureUrl = $args->failureUrl;

			$this->otherMethodsEnabled = true;

		} else {
			die("EKSIK ARGUMANLAR VAR");
		}
	}

	/**
	 * Product name setter
	 *
	 * @param $name|string
	 */
	public function item($name = false) {
		if($name && $this->otherMethodsEnabled) {
			$this->item = $name;
			return $this;
		}
		die('Productname required');
		return false;
	}

	/**
	 * Set Prefix
	 *
	 * @param $prefix
	 */
	public function prefix($prefix = false) {
		if($prefix) {
			$this->referencePrefix = $prefix;
		} else{
			die("Product Prefix Required");
			return false;
		}
	}


	/**
	 * Product Unique Reference Code
	 *
	 * @param $code|string|int
	 */
	public function reference($code = false) {
		if($code && $this->otherMethodsEnabled) {
			$this->referenceCode = $code;
			return $this;
		}
		die("Product Referencecode Required");
		return false;
	}

	/**
	 * Price setter
	 * 
	 * @param $price|int
	 */
	public function price($price = false) {
		if($price && is_numeric($price) && $this->otherMethodsEnabled) {
			$this->price = $price;
			return $this;
		}
		die("Price must be set");
		return false;
	}

	/**
	 * Trial Period Setter
	 *
	 * @param $bool|Boolean, $period|Int
	 */
	public function hasTrialPeriod(Int $period)
	{
		if($this->otherMethodsEnabled){
			$this->hasTrialPeriod = true;
			$this->trialPeriodDay = $period;
			return $this;
		}
	}


	/**
	 * Restful Based Payment Request
	 *
	 */
	public function tryPayment() {

		$keyValue = md5($this->merchantId . $this->serviceId . $this->referenceCode . $this->item . $this->price . $this->successUrl . $this->failureUrl. $this->$secretKey);

		$args = [
		        'merchantId' => $this->merchantId,
		        'serviceId' => $this->serviceId,
		        'item' => $this->item,
		        'price' => $this->price,
		        'referenceCode' => $this->referenceCode,
		        'successUrl' => $this->successUrl,
		        'failureUrl' => $this->failureUrl,
		        'key' =>  $keyValue
		    ];

   		if($this->hasTrialPeriod) {
   			$args = array_merge($args, [
   				'hasTrialPeriod' => true,
   				'trialPeriodDay' => $this->trialPeriodDay
   			]);
   		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->paymentUrl);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($args));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);

		//Show result
		if(function_exists('dd')){
			dd(json_decode($response));
		} else {
			echo '<pre>', print_r((object) json_decode($response));
		}
	}

	public function preview()
	{
		$keyValue = md5($this->merchantId . $this->serviceId . $this->referenceCode . $this->item . $this->price . $this->successUrl . $this->failureUrl. $this->$secretKey);

		$args = [
		        'merchantId' => $this->merchantId,
		        'serviceId' => $this->serviceId,
		        'item' => $this->item,
		        'price' => $this->price,
		        'referenceCode' => $this->referenceCode,
		        'successUrl' => $this->successUrl,
		        'failureUrl' => $this->failureUrl,
		        'key' =>  $keyValue
		    ];

		if($this->hasTrialPeriod){
			$args = array_merge($args, [
				'trialPeriodDay' => $this->trialPeriodDay
			]);
		}
		
		dd($args);
	}
}



?>