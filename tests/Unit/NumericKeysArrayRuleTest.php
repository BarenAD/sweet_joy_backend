<?php


namespace Tests\Unit;

use App\Rules\NumericKeysArray;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NumericKeysArrayRuleTest extends TestCase
{
    use RefreshDatabase;

    private NumericKeysArray $rule;

    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new NumericKeysArray('test_object');
    }

    public function testPasses()
    {
        $this->assertTrue(
            $this->rule->passes(
                'attribute',
                [
                    34 => 'test',
                    67 => 'test',
                    11 => 'test',
                    65 => 'test',
                ]
            )
        );
    }

    public function testPassesFailed()
    {
        $this->assertFalse(
            $this->rule->passes(
                'attribute',
                [
                    34 => 'test',
                    'no_int' => 'test',
                    11 => 'test',
                    65 => 'test',
                ]
            )
        );
    }

    public function testMessage()
    {
        $this->assertEquals(
            $this->rule->message(),
            'Ключи "test_object.*" должны быть целочисленными значениеми.'
        );
    }
}
