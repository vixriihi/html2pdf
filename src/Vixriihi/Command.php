<?php
namespace Vixriihi;


abstract class Command {

    const PREFIX_PARAM = '--';
    const PREFIX_FLAG  = '-';

    protected $cmd = null;

    protected $flags  = [];
    protected $params = [];

    /**
     * Returns the command to be executed
     * @return mixed
     */
    abstract public function __toString();

    /**
     * This sets parameters from array
     *
     * This has a basic cleanup for the value. Removes all non alphanumeric characters except -
     * If you need to set a value without cleaning use setParameter instead
     *
     * @param array $options
     * @param array $whiteList
     */
    public function setOptionsFromArray(array $options, array $whiteList = null) {
        if ($whiteList !== null) {
            $options = array_intersect_key($options, array_flip($whiteList));
        }
        foreach ($options as $param => $value) {
            if (strlen($param) === 1) {
                $this->setFlag($param);
            } elseif (empty($value) && is_string($value)) {
                $this->setParam($param);
            } else {
                $this->setParam($param, $this->clearValue($value));
            }
        }
    }

    /**
     * Clears the value so that it can only have alphanumeric and dash characters
     *
     * @param $value
     * @return mixed
     */
    public function clearValue($value) {
        return preg_replace("/[^a-zA-Z_\-0-9]+/", "", $value);
    }

    /**
     * Sets flag for the command
     *
     * Please note that this isn't filtered in any way so make sure that the name is clean
     *
     * @param $name
     */
    public function setFlag($name) {
        $this->setOption($name);
    }

    /**
     * Sets parameter value.
     *
     * Please note that this isn't filtered in any way so make sure that the name and value is clean
     *
     * @param $name
     * @param null $value
     */
    public function setParam($name, $value = null) {
        $this->setOption($name, $value, self::PREFIX_PARAM);
    }

    /**
     * Generic way to add option to command
     *
     * Please note that this isn't filtered in any way so make sure that the name and value are clean
     *
     * @param $name
     * @param null $value
     * @param string $prefix
     */
    public function setOption($name, $value = null, $prefix = self::PREFIX_FLAG) {
        if ($value === null) {
            $this->params[] = $prefix . $name;
            return;
        }
        $this->params[] = $prefix . $name . ' ' . $value;
    }

    /**
     * Clears all options
     */
    public function clearOptions() {
        $this->params = [];
        $this->flags  = [];
    }

    protected function getParams() {
        return join(' ', $this->params);
    }

    protected function getFlags() {
        return join(' ', $this->flags);
    }
}