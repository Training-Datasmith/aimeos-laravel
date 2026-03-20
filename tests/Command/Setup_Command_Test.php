<?php

declare(strict_types=1);

class SetupCommandTest extends AimeosTestAbstract
{
    public function testSetupCommand()
    {
        $args = [ 'site' => 'unittest', 'tplsite' => 'unittest', '--option' => 'setup/default/demo:0' ];
        $this->assertEquals(0, $this->artisan('aimeos:setup', $args));
    }
}
