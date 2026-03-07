<?php

declare(strict_types=1);

class HelpersTest extends AimeosTestAbstract
{
    public function testAiconfig()
    {
        $this->assertEquals('notexisting', aiconfig('not/exists', 'notexisting'));
    }
}
