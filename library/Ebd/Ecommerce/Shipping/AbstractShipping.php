<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_Shipping
 */

namespace Ebd\Ecommerce\Shipping;

abstract class AbstractShipping
{
    /**
     * @var string
     */
    const ERROR_CANNOT_LOCATE_COUNTRY = 'No shipping available to the selected country.';

    /**
     * @var string
     */
    const ERROR_CANNOT_LOCATE_STATE = 'No shipping available to the selected state.';

    /**
     * Subclass should override it.
     *
     * @var array
     * @example
     *  $rates = array(
     *      'US' => array(
     *          array(
     *              'PR',
     *              '1:8',
     *          ),
     *          array(
     *              '!FM,PW,VI',
     *              '1:5.95,2:5.95,3:9.9,4:9.9,5:14.8,6:14.8,7:19.8,8:19.8',
     *          ),
     *      ),
     *      '!JP,KR' => array(
     *          '*',
     *          '1:5',
     *          'This is title',
     *          'This is description',
     *      ),
     *  );
     */
    protected $rates = array();

    /**
     * It identifies the unique shipping method.
     *
     * @var string
     */
    protected $code = null;

    /**
     * The title of shipping method
     *
     * @var string
     */
    protected $title = null;

    /**
     * The description of shipping method
     *
     * @var string
     */
    protected $description = null;

    /**
     * The icon of shipping method
     *
     * @var string
     */
    protected $icon = null;

    /**
     * It saves the code of current country.
     * It should be two uppercase letters.
     *
     * @var string(2)
     */
    protected $countryCode = null;

    /**
     * It saves the code of current country.
     * It should be two uppercase letters.
     *
     * @var string(2)
     */
    protected $stateCode = null;

    /**
     * Usually, it should be the quantity of shopping cart
     *
     * @var int
     */
    protected $quantity = null;

    /**
     * Usually, it should be the sub-total of shopping cart
     *
     * @var float
     */
    protected $amount = null;

    /**
     * It save the error while calculating the right rate
     *
     * @var string
     */
    protected $error = null;

    /**
     * Set country code and state code
     *
     * @param string(2) $countryCode
     * @param string(2) $stateCode
     * @return AbstractShipping
     */
    public function setState($countryCode, $stateCode)
    {
        $this->countryCode = $countryCode;
        $this->stateCode = $stateCode;
        return $this;
    }

    /**
     * Usually, you should set the quantity of shopping cart
     *
     * @param $quantity
     * @return AbstractShipping
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Usually, you should set the sub-total of shopping cart
     *
     * @param float $amount
     * @return AbstractShipping
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get code of shipping method
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get title of shipping method
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get description of shipping method
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get icon of shipping method
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * To quote
     *
     * The result should include code, cost, title, description, icon, [error] fields
     *
     * @return array
     */
    public function quote()
    {
        $quote = array(
            'title' => $this->title,
            'description' => $this->description,
            'icon' => $this->icon,
            'cost' => 0,
        );

        $rate = $this->getRate();
        if (false === $rate) {
           $quote['error'] = $this->error;
        }
        else {
            $quote['cost'] = $this->getCost($rate[1]);
            if (isset($rate[2])) {
                $quote['title'] = $rate[2];
            }
            if (isset($rate[3])) {
                $quote['description'] = $rate[3];
            }
        }
        return $quote;
    }

    /**
     * Get the right rate
     *
     * @return array|false
     */
    protected function getRate()
    {
        $rates = false;

        // locate country
        foreach ($this->rates as $country => $stateRates) {

            // always
            if ('*' === $country) {
                $rates = $stateRates;
                break;
            }

            // exclude
            elseif ('!' === $country{0}) {
                $countries = explode(',', substr($country, 1));
                if (!in_array($this->countryCode, $countries)) {
                    $rates = $stateRates;
                    break;
                }
            }

            // normal
            else {
                $countries = explode(',', $country);
                if (in_array($this->countryCode, $countries)) {
                    $rates = $stateRates;
                    break;
                }
            }
        }

        // Can't locate country
        if (false === $rates) {
            $this->error = self::ERROR_CANNOT_LOCATE_COUNTRY;
            return false;
        }

        // fix rates
        if (is_string(current($rates))) {
            $rates = array($rates);
        }

        // locate state
        $rate = false;
        foreach ($rates as $details) {

            $state = $details[0];

            // always
            if ('*' === $state) {
                $rate = $details;
                break;
            }

            // exclude
            elseif ('!' === $state{0}) {
                $states = explode(',', substr($state, 1));
                if (!in_array($this->stateCode, $states)) {
                    $rate = $details;
                    break;
                }
            }

            // normal
            else {
                $states = explode(',', $state);
                if (in_array($this->stateCode, $states)) {
                    $rate = $details;
                    break;
                }
            }
        }

        // Can't locate state
        if (false === $rate) {
            $this->error = self::ERROR_CANNOT_LOCATE_STATE;
            return false;
        }

        // return
        $this->error = null;
        return $rate;
    }

    /**
     * Get cost by shipping fee list
     *
     * @param string $list
     * @return array
     */
    protected function getCost($list)
    {
        // parse list
        $costs = array();
        $array = preg_split('/[:,]/', $list);
        for ($i = 0, $count = count($array); $i < $count; $i += 2) {
            $costs[$array[$i]] = $array[$i + 1];
        }
        krsort($costs);

        // get the lowest cost
        $quantity = $this->quantity;
        $cost = 0;
        while ($quantity > 0) {
            foreach ($costs as $num => $value) {
                if ($quantity >= $num) {
                    $cost += floor($quantity / $num) * $value;
                    $quantity = $quantity % $num;
                    continue 2;
                }
            }

            $cost += $value;
            $quantity = 0;
        }

        // return
        return $cost;
    }
}