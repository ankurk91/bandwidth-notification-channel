<?php

namespace NotificationChannels\Bandwidth\Test;

use NotificationChannels\Bandwidth\Rules\PhoneNumberRule;
use PHPUnit\Framework\TestCase;

class PhoneNumberRuleTest extends TestCase
{
    /**
     * @var PhoneNumberRule
     */
    protected $rule;

    public function setUp()
    {
        parent::setUp();
        $this->rule = new PhoneNumberRule();
    }

    public function invalidNumbersProvider()
    {
        return [
            ['abcd', false],
            ['1234567891211212121212121', false],
            ['abcd123', false],
        ];
    }

    /**
     * @test
     * @dataProvider invalidNumbersProvider
     */
    public function it_can_fails_on_invalid_numbers($number, $expected)
    {
        $this->assertEquals($expected, $this->rule->passes('phone', $number));
    }

    public function validNumbersProvider()
    {
        return [
            ['123456', true],
            ['+123456789', true],
        ];
    }

    /**
     * @test
     * @dataProvider validNumbersProvider
     */
    public function it_can_passed_on_valid_numbers($number, $expected)
    {
        $this->assertEquals($expected, $this->rule->passes('phone', $number));
    }

    /** @test */
    public function it_can_validates_against_minimum_parameter()
    {
        $rule = new PhoneNumberRule(8);
        $this->assertEquals(false, $rule->passes('phone', '1234567'));
        $this->assertEquals(false, $rule->passes('phone', '12345678'));
        $this->assertEquals(true, $rule->passes('phone', '123456789'));
    }
}
