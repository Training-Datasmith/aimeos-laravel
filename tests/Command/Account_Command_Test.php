<?php

declare(strict_types=1);

class AccountCommandTest extends AimeosTestAbstract
{
    public function testAccountCommandNew()
    {
        $args = [ 'site' => 'unittest', 'email' => 'unitCustomer@example.com', '--password' => 'test' ];
        $this->assertEquals(0, $this->artisan('aimeos:account', $args));
    }

    public function testAccountCommandAdmin()
    {
        $args = [ 'site' => 'unittest', 'email' => 'unitCustomer@example.com', '--password' => 'test', '--admin' => true ];
        $this->assertEquals(0, $this->artisan('aimeos:account', $args));
    }

    public function testAccountCommandEditor()
    {
        $args = [ 'site' => 'unittest', 'email' => 'unitCustomer@example.com', '--password' => 'test', '--editor' => true ];
        $this->assertEquals(0, $this->artisan('aimeos:account', $args));
    }
}
