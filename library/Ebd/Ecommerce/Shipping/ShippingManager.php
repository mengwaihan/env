<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_Shipping
 */

namespace Ebd\Ecommerce\Shipping;

use Ebd\ServiceLocator\PluginManager\AbstractPluginManager;
use Ebd\Loader\Autoloader;

class ShippingManager extends AbstractPluginManager
{
    /**
     * It saves all the shipping modules
     * You can find the related shipping object by these module names.
     *
     * @var array
     */
    protected $modules = array();

    /**
     * It is two uppercase letters
     *
     * @var string(2)
     */
    protected $countryCode = null;

    /**
     * It is two uppercase letters
     *
     * @var string(2)
     */
    protected $stateCode = null;

    /**
     * Usually, we don't need it.
     *
     * @var string
     */
    protected $postcode = null;

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
     * Get the code of shipping method
     *
     * @param string $name
     * @return string|boolean
     */
    public function getPluginClass($name)
    {
        $name = ucfirst($name);
        $class = Autoloader::find("Ecommerce\\Shipping\\$name");
        return $class ?: false;
    }

    /**
     * set shipping methods
     *
     * @param array $modules
     * @return ShippingManager
     */
    public function setModules(array $modules)
    {
        $this->modules = $modules;
        return $this;
    }

    /**
     * Get shipping methods
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Check whether or not has the module
     *
     * @param string $module
     * @return boolean
     */
    public function has($module)
    {
        return in_array($module, $this->modules);
    }

    /**
     * Set country code and state code
     *
     * @param string(2) $countryCode
     * @param string(2) $stateCode
     * @return ShippingManager
     */
    public function setState($countryCode, $stateCode)
    {
        $this->countryCode = $countryCode;
        $this->stateCode = $stateCode;
        return $this;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return ShippingManager
     */
    public function setPostcode($postcode)
    {
        $this->postcode = preg_replace('/[\-\s]/', '', $postcode);
        return $this;
    }

    /**
     * Set quantity
     *
     * @param int $quantity
     * @return ShippingManager
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Set total amount
     *
     * @param float $amount
     * @return ShippingManager
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $name
     * @return AbstractShipping
     */
    public function get($name)
    {
        $shipping = parent::get($name);
        $shipping->setState($this->countryCode, $this->stateCode);
        $shipping->setPostcode($this->postcode);
        $shipping->setQuantity($this->quantity);
        $shipping->setAmount($this->amount);
        return $shipping;
    }

    /**
     * Start to quote and return the results
     *
     * @return array
     */
    public function quote()
    {
        $quotes = array();
        $cheapest = null;
        foreach ($this->modules as $code) {
            $shipping = $this->get($code);
            $result = $shipping->quote();
            if (!isset($result['error'])) {
                if (null === $cheapest) {
                    $cheapest = $code;
                } elseif ($result['cost'] < $quotes[$cheapest]['cost']) {
                    $cheapest = $code;
                }
                $quotes[$code] = $result;
                $quotes[$code]['cheapest'] = false;
            }
        }
        if (null !== $cheapest) {
            $quotes[$cheapest]['cheapest'] = true;
        }
        return $quotes;
    }
}