<?php
namespace kahlan\reporter\coverage\driver;

use RuntimeException;

class HHVM
{
    /**
     * Config array
     *
     * @var array
     */
    protected $_config = [];

    /**
     * Construct.
     */
    public function __construct($config = [])
    {
        $defaults = [
            'cleanup' => true
        ];
        $this->_config = $config;

        if (!defined('HHVM_VERSION')) {
            throw new RuntimeException('HHVM is not loaded.');
        }
    }

    /**
     * Start code coverage.
     */
    public function start()
    {
        fb_enable_code_coverage();
    }

    /**
     * Stop code coverage.
     *
     * @return array The collected coverage
     */
    public function stop()
    {
        $data = fb_get_code_coverage($this->_config['cleanup']);
        fb_disable_code_coverage();

        $result = [];
        foreach ($data as $file => $coverage) {
            foreach ($coverage as $line => $value) {
                if ($line && $value !== -2) {
                    $result[$file][$line - 1] = $value === -1 ? 0 : $value;
                }
            }
        }
        return $result;
    }
}
