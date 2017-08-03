<?php
class NCompass_CustomCarrier4_Model_Carrier
extends Mage_Shipping_Model_Carrier_Abstract
implements Mage_Shipping_Model_Carrier_Interface{

  protected $_code = 'ncompass_customcarrier4';

  public function collectRates(Mage_Shipping_Model_Rate_Request $request){
    $result = Mage::getModel('shipping/rate_result');
    /* @var $result Mage_Shipping_Model_Rate_Result */

    $result->append($this->_getStandardShippingRate());
    $result->append($this->_getExpressShippingRate());

    if ($request->getFreeShipping()) {
      /**
      *  If the request has the free shipping flag,
      *  append a free shipping rate to the result.
      */
      $freeShippingRate = $this->_getFreeShippingRate();
      $result->append($freeShippingRate);
    }
    return $result;
  }

  protected function _getStandardShippingRate(){

    $rate = Mage::getModel('shipping/rate_result_method');
    /* @var $rate Mage_Shipping_Model_Rate_Result_Method */

    $rate->setCarrier($this->_code);
    /**
    * getConfigData(config_key) returns the configuration value for the
    * carriers/[carrier_code]/[config_key]
    */

    $rate->setCarrierTitle($this->getConfigData('title'));
    $rate->setMethod('standard');
    $rate->setMethodTitle($this->getConfigData('titleStandard'));
    $rate->setPrice($this->getConfigData('priceStandard'));
    $rate->setCost(0);
    return $rate;
  }

  protected function _getExpressShippingRate(){
    $rate = Mage::getModel('shipping/rate_result_method');
    /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
    $rate->setCarrier($this->_code);
    $rate->setCarrierTitle($this->getConfigData('title'));
    $rate->setMethod('express');
    $rate->setMethodTitle($this->getConfigData('titleExpress'));
    $rate->setPrice($this->getConfigData('priceExpress'));
    $rate->setCost(0);
    return $rate;
  }

  protected function _getFreeShippingRate(){
    $rate = Mage::getModel('shipping/rate_result_method');
    /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
    $rate->setCarrier($this->_code);
    $rate->setCarrierTitle($this->getConfigData('title'));
    $rate->setMethod('free_shipping');
    $rate->setMethodTitle($this->getConfigData('titleFree'));
    $rate->setPrice(0);
    $rate->setCost(0);
    return $rate;
  }


  public function getAllowedMethods(){
    return array(
      'standard' => 'Standard',
      'express' => 'Express',
      'free_shipping' => 'Free Shipping',
    );
  }

  public function isTrackingAvailable(){
    return true;
  }
  public function getTrackingInfo($tracking){
    $track = Mage::getModel('shipping/tracking_result_status');
    if($this->getConfigData('trackingafter') != 0){
      $track->setUrl($this->getConfigData('trackingUrl').$tracking)
      ->setTracking($tracking)
      ->setCarrierTitle($this->getConfigData('carrierName'));
    }
    else {
      $track->setUrl($this->getConfigData('trackingUrl'))
      ->setTracking($tracking)
      ->setCarrierTitle($this->getConfigData('carrierName'));
    }
    return $track;
  }
}
