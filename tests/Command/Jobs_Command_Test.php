<?php

declare(strict_types=1);

class JobsCommandTest extends AimeosTestAbstract
{
    public function testJobsCommand()
    {
        $this->assertEquals(0, $this->artisan('aimeos:jobs', [ 'jobs' => 'customer/email/watch', 'site' => 'unittest' ]));
    }
}
