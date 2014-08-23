<?php

use Rees\Sanitizer\Sanitizer;

class SanitizerTest extends PHPUnit_Framework_TestCase
{
    public function testSanitzerInstanceCanBeCreated()
    {
        $s = new Sanitizer;
    }

    public function testThatSanitizerCanSanitizeWithClosure()
    {
        $s = new Sanitizer;
        $s->register('reverse', function($field) { return strrev($field); });
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'reverse'], $d);
        $this->assertEquals('elyaD', $d['name']);
    }

    public function testThatSanitizerCanSanitizeWithClosureAndParameters()
    {
        $s = new Sanitizer;
        $s->register('substring', function($string, $start, $length) { return substr($string, $start, $length); });
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'substring:2,3'], $d);
        $this->assertEquals('yle', $d['name']);
    }

    public function testThatSanitizerCanSanitizeWithClass()
    {
        $s = new Sanitizer;
        $s->register('reverse', 'TestSanitizer@foo');
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'reverse'], $d);
        $this->assertEquals('elyaD', $d['name']);
    }

    public function testThatSanitizerCanSanitizeWithClassAndParameters()
    {
        $s = new Sanitizer;
        $s->register('suffix', 'Suffix@sanitize');
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'suffix:Rees'], $d);
        $this->assertEquals('Dayle Rees', $d['name']);
    }

    public function testThatSanitizerCanSanitizeWithACallback()
    {
        $s = new Sanitizer;
        $s->register('reverse', [new TestSanitizer, 'foo']);
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'reverse'], $d);
        $this->assertEquals('elyaD', $d['name']);
    }

    public function testThatSanitizerCanSanitizeWithACallbackAndParameters()
    {
        $s = new Sanitizer;
        $s->register('suffix', [new Suffix, 'sanitize']);
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'suffix:Rees'], $d);
        $this->assertEquals('Dayle Rees', $d['name']);
    }

    public function testThatACallableRuleCanBeUsed()
    {
        $s = new Sanitizer;
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'strrev'], $d);
        $this->assertEquals('elyaD', $d['name']);
    }

    public function testThatACallableRuleCanBeUsedWithParameters()
    {
        $s = new Sanitizer;
        $d = ['number' => '2435'];
        $s->sanitize(['number' => 'str_pad:10,0,0'], $d);
        $this->assertEquals('0000002435', $d['number']);
    }

    public function testThatSanitizerFunctionsWithMultipleRules()
    {
        $s = new Sanitizer;
        $d = ['name' => '  Dayle_ !'];
        $s->register('alphabetize', function($field) {
            return preg_replace('/[^a-zA-Z]/', null, $field);
        });
        $s->sanitize(['name' => 'strrev|alphabetize|trim'], $d);
        $this->assertEquals('elyaD', $d['name']);
    }

    public function testThatSanitizerFunctionsWithMultipleRulesWithParameters()
    {
        $s = new Sanitizer;
        $d = ['name' => '  Dayle_ !'];
        $s->register('suffix', [new Suffix, 'sanitize']);
        $s->register('alphabetize', function($field) {
            return preg_replace('/[^a-zA-Z]/', null, $field);
        });
        $s->sanitize(['name' => 'suffix: Rees |strrev|alphabetize|trim'], $d);
        $this->assertEquals('seeRelyaD', $d['name']);
    }

    public function testThatGlobalRulesCanBeSet()
    {
        $s = new Sanitizer;
        $d = [
            'first_name' => ' Dayle',
            'last_name' => 'Rees ',
        ];
        $s->sanitize([
            '*' => 'trim|strtolower',
            'last_name' => 'strrev',
        ], $d);
        $this->assertEquals([
            'first_name' => 'dayle',
            'last_name' => 'seer',
        ], $d);
    }

    public function testThatGlobalRulesCanBeSetWithParameters()
    {
        $s = new Sanitizer;
        $d = [
            'first_name' => ' Dayle',
            'last_name' => 'Rees ',
        ];
        $s->sanitize([
            '*' => 'trim|strtolower|substr:1',
            'last_name' => 'strrev',
        ], $d);
        $this->assertEquals([
            'first_name' => 'ayle',
            'last_name' => 'see',
        ], $d);
    }

}

class TestSanitizer
{
    public function foo($data)
    {
        return strrev($data);
    }
}

class Suffix
{
    public static function sanitize($value, $suffix = '')
    {
        return $value . ' ' .  $suffix;
    }
}
