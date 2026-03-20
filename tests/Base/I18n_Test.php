<?php

declare(strict_types=1);

class I18nTest extends AimeosTestAbstract
{
    public function testGet()
    {
        $aimeos = $this->app->make('\Aimeos\Shop\Base\Aimeos');

        $configMock = $this->getMockBuilder('\Illuminate\Config\Repository')
            ->onlyMethods([ 'get', 'has' ])->getMock();

        $configMock->expects($this->once())->method('has')
            ->will($this->returnValue(true));

        $configMock->expects($this->exactly(3))->method('get')
            ->will($this->onConsecutiveCalls(true, 'laravel:', []));

        $object = new \Aimeos\Shop\Base\I18n($configMock, $aimeos);
        $list = $object->get([ 'en' ]);

        $this->assertInstanceOf('\Aimeos\Base\Translation\Iface', $list['en']);
    }
}
