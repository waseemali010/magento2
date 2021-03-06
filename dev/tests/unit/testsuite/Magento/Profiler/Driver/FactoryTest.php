<?php
/**
 * Test class for Magento_Profiler_Driver_Factory
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magento_Profiler_Driver_FactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Magento_Profiler_Driver_Factory
     */
    protected $_factory;

    /**
     * @var string
     */
    protected $_defaultDriverPrefix = 'Magento_Profiler_Driver_Test_';

    /**
     * @var string
     */
    protected $_defaultDriverType = 'default';

    protected function setUp()
    {
        $this->_factory = new Magento_Profiler_Driver_Factory(
            $this->_defaultDriverPrefix,
            $this->_defaultDriverType
        );
    }

    public function testConstructor()
    {
        $this->assertAttributeEquals($this->_defaultDriverPrefix, '_defaultDriverPrefix', $this->_factory);
        $this->assertAttributeEquals($this->_defaultDriverType, '_defaultDriverType', $this->_factory);
    }

    public function testDefaultConstructor()
    {
        $factory = new Magento_Profiler_Driver_Factory();
        $this->assertAttributeNotEmpty('_defaultDriverPrefix', $factory);
        $this->assertAttributeNotEmpty('_defaultDriverType', $factory);
    }

    /**
     * @dataProvider createDataProvider
     * @param array $config
     * @param string $expectedClass
     */
    public function testCreate($config, $expectedClass)
    {
        $driver = $this->_factory->create($config);
        $this->assertInstanceOf($expectedClass, $driver);
        $this->assertInstanceOf('Magento_Profiler_DriverInterface', $driver);
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        $defaultDriverClass = $this->getMockClass(
            'Magento_Profiler_DriverInterface', array(), array(), 'Magento_Profiler_Driver_Test_Default'
        );
        $testDriverClass = $this->getMockClass(
            'Magento_Profiler_DriverInterface', array(), array(), 'Magento_Profiler_Driver_Test_Test'
        );
        return array(
            'Prefix and concrete type' => array(
                array(
                    'type' => 'test'
                ),
                $testDriverClass
            ),
            'Prefix and default type' => array(
                array(),
                $defaultDriverClass
            ),
            'Concrete class' => array(
                array(
                    'type' => $testDriverClass
                ),
                $testDriverClass
            )
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cannot create profiler driver, class "Magento_Profiler_Driver_Test_Baz" doesn't exist.
     */
    public function testCreateUndefinedClass()
    {
        $this->_factory->create(array(
            'type' => 'baz'
        ));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Driver class "stdClass" must implement Magento_Profiler_DriverInterface.
     */
    public function testCreateInvalidClass()
    {
        $this->_factory->create(array(
            'type' => 'stdClass'
        ));
    }
}
