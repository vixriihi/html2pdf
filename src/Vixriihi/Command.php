<?php
namespace Vixriihi;


abstract class Command {

    const PREFIX_PARAM = '--';
    const PREFIX_FLAG  = '-';

    protected $cmd = null;

    protected $flags  = [];
    protected $params = [];

    protected $outputFile = null;

    abstract public function execute();

    public function setOptionsFromArray(array $options, array $whiteList = null) {
        if ($whiteList !== null) {
            $options = array_intersect_key($options, array_flip($whiteList));
        }
        foreach ($options as $param => $value) {
            if (empty($value)) {
                $this->setParam($param);
            }
            $this->setParam($param, $value);
        }
    }

    public function setFlag($name) {
        $this->setOption($name);
    }

    public function setParam($name, $value = null) {
        $this->setOption($name, $value, self::PREFIX_PARAM);
    }

    public function setOption($name, $value = null, $prefix = self::PREFIX_FLAG) {
        if ($value === null) {
            $this->params[] = $prefix . $name;
        }
        $this->params[] = $prefix . $name . '=' . $value;
    }

    public function setPdfOutput($file) {
        $this->outputFile = $file;
    }

    protected function getParams() {
        return join(' ', $this->params);
    }

    protected function getFlags() {
        return join(' ', $this->params);
    }
}