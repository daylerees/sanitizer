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

    public function testThatSanitizerCanSanitizeWithClass()
    {
        $s = new Sanitizer;
        $s->register('reverse', 'TestSanitizer@foo');
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'reverse'], $d);
        $this->assertEquals('elyaD', $d['name']);
    }

    public function testThatSanitizerCanSanitizeWithACallback()
    {
        $s = new Sanitizer;
        $s->register('reverse', [new TestSanitizer, 'foo']);
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'reverse'], $d);
        $this->assertEquals('elyaD', $d['name']);
    }

    public function testThatACallableRuleCanBeUsed()
    {
        $s = new Sanitizer;
        $d = ['name' => 'Dayle'];
        $s->sanitize(['name' => 'strrev'], $d);
        $this->assertEquals('elyaD', $d['name']);
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
}

class TestSanitizer
{
    public function foo($data)
    {
        return strrev($data);
    }
}
