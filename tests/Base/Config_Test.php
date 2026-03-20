<?php

declare(strict_types=1);

class ConfigTest extends AimeosTestAbstract
{
    public function testGet()
    {
        $aimeos = $this->app->make('\Aimeos\Shop\Base\Aimeos');

        $configMock = $this->getMockBuilder('\Illuminate\Config\Repository')
            ->onlyMethods([ 'get' ])->getMock();

        $configMock->expects($this->exactly(4))->method('get')
            ->will($this->onConsecutiveCalls(true, 'laravel:', [], []));

        $object = new \Aimeos\Shop\Base\Config($configMock, $aimeos);

        $this->assertInstanceOf('\Aimeos\Base\Config\Iface', $object->get());
    }
}
