<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_OrderTotal
 */

namespace Ebd\Ecommerce\OrderTotal;

use Ebd\ServiceLocator\PluginManager\AbstractPluginManager;
use Ebd\Loader\Autoloader;

class TotalManager extends AbstractPluginManager
{
    /**
     * It saves all the order total modules (e.g. subtotal, point, coupon, bogo, shipping)
     * You can find the related order total object by these module names.
     *
     * @var array
     */
    protected $modules = array();

    /**
     * The code of gross total
     *
     * @var string
     */
    protected $code = 'total';

    /**
     * The title of gross total
     *
     * @var string
     */
    protected $title = 'Total';

    /**
     * Cache all the result of subtotals
     *
     * @var array
     */
    private $subtotals = null;

    /**
     * Get the code of total method
     *
     * @param string $name
     * @return string|boolean
     */
    public function getPluginClass($name)
    {
        $name = ucfirst($name);
        $class = Autoloader::find("Ecommerce\\OrderTotal\\$name");
        return $class ?: false;
    }

    /**
     * set order total methods
     *
     * @param array $modules
     * @return TotalManager
     */
    public function setModules(array $modules)
    {
        $this->modules = $modules;
        return $this;
    }

    /**
     * Get order total methods
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
     * Set the title of gross total
     *
     * @return string
     */
    public function setTitle($title)
    {
        $this->total = $title;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Flush results
     *
     * @return array {title, value}
     */
    public function process()
    {
        $this->subtotals = array();
        foreach ($this->modules as $code) {
            $total = $this->get($code);
            $result = $total->process();
            if ($result) {
                $this->subtotals[$code] = $result;
            }
        }
        return $this->subtotals;
    }

    /**
     * Get all the subtotals
     *
     * @param boolean $gross
     * @return array
     */
    public function getSubtotals($gross = false)
    {
        if (null === $this->subtotals) {
            $this->process();
        }

        if ($gross) {
            $totals = $this->subtotals;
            $totals[$this->getCode()] = $this->getTotal();
            return $totals;
        }

        return $this->subtotals;
    }

    /**
     * Get total details
     *
     * @param boolean $actual
     * @return array
     * @throws \RuntimeException
     */
    public function getTotal($actual = false)
    {
        $total = 0;
        foreach ($this->getSubtotals() as $subtotal) {
            $total += $subtotal['value'];
        }

        return array(
            'title' => $this->title,
            'value' => $actual ? $total : max($total, 0),
        );
    }
}
