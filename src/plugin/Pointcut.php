<?php
namespace kahlan\plugin;

use kahlan\Suite;

class Pointcut
{
    protected static $_classes = [
        'call' => 'kahlan\plugin\Call',
        'stub' => 'kahlan\plugin\Stub'
    ];

    /**
     * Point cut called before method execution.
     *
     * @return boolean If `true` is returned, the normal execution of the method is aborted.
     */
    public static function before($method, $self, &$params)
    {
        if (!Suite::registered()) {
            return false;
        }

        list($class, $name) = explode('::', $method);

        $lsb = is_object($self) ? get_class($self) : $self;

        if (!Suite::registered($lsb) && !Suite::registered($class)) {
            return false;
        }

        if ($name === '__call' || $name === '__callStatic') {
            $name = array_shift($params);
            $params = array_shift($params);
        }

        return static::_stubbedMethod($lsb, $self, $class, $name, $params);
    }

    protected static function _stubbedMethod($lsb, $self, $class, $name, $params)
    {
        if (is_object($self)) {
            $list = $lsb === $class ? [$self, $lsb] : [$self, $lsb, $class];
        } else {
            $list = $lsb === $class ? [$lsb] : [$lsb, $class];
            $name = '::' . $name;
        }

        $call = static::$_classes['call'];
        $stub = static::$_classes['stub'];

        $call::log($list, compact('name', 'params'));

        if ($method = $stub::find($list, $name, $params)) {
            return $method;
        }

        return false;
    }

}
