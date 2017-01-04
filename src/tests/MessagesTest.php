<?php

namespace Inani\Messager\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessagesTest extends \TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_asserts_true()
    {
        $this->assertTrue(true);
    }
}