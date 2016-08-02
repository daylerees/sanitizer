![Travis Status](http://img.shields.io/travis/daylerees/sanitizer.svg?style=flat-square)
![Github Release](http://img.shields.io/github/release/daylerees/sanitizer.svg?style=flat-square)
![Packagist License](http://img.shields.io/packagist/l/daylerees/sanitizer.svg?style=flat-square)
![Packagist Downloads](http://img.shields.io/packagist/dt/daylerees/sanitizer.svg?style=flat-square)
![Github Issues](http://img.shields.io/github/issues/daylerees/sanitizer.svg?style=flat-square)
[![Tips](http://img.shields.io/gratipay/daylerees.svg?style=flat-square)](https://gratipay.com/daylerees)

# Sanitizer Package

Sanitizers can be used to standardize data to ease validation, or provide data consistency.

## Basic Usage

First construct a rules array.

    $rules = [
        'name'      => 'trim',
        'email'     => 'trim|strtolower'
    ];

Rules can contain either callable functions, or the name of a sanitizer binding (more later). You can use either a pipe `|` or an array to specify multiple sanitization rules.

The sanitizer can be executed in the following fashion.

    $sanitizer = new Sanitizer;
    $sanitizer->sanitize($rules, $data);

Here's a full example.

    // Construct rules array.
    $rules = [
        'name'      => 'trim',
        'email'     => 'trim|strtolower'
    ];

    // Data array to be sanitized.
    $data = [
        'name' => ' Dayle ',
        'email' => ' me@DAYLEREES.com'
    ];

    // Construct a new sanitizer.
    $sanitizer = new Sanitizer;

    // Execute the sanitizer.
    $sanitizer->sanitize($rules, $data);

Here's the content of `$data` after execution.

    [
        'name' => 'Dayle',
        'email' => 'me@daylerees.com'
    ]

Using the Laravel facade, the syntax can be made a little cleaner.

    Sanitizer::sanitize($rules, $data);
    
Sanitize a single value like so. 

    $rules = 'trim|strtolower';
    $data = '  Dayle';
    
    Sanitizer::sanitizeValue($rules, $data);

Here is the value returned.

    dayle

## Custom Sanitization Rules

Sanitizers can be added multiple ways.

### Using a Closure.

    Sanitizer::register('reverse', function ($field) {
        return strrev($field);
    });

### Using a Callback.

    Sanitizer::register('reverse', [new ClassHere, 'method']);

### Using a class/method pair.

    Sanitizer::register('reverse', 'Namespace\Class\Here@method');

The class will be resolved through an instance of the Illuminate IoC container, if no method is provided then `sanitize()` is assumed.

## Installation

The Sanitizer package can be used stand-alone or with the Laravel Framework.

### Stand-alone

First include the sanitizer package.

    "daylerees/sanitizer": "dev-master"

Now simply `use` the Sanitizer class.

    use Rees\Sanitizer\Sanitizer;


### With Laravel

Include the Service Provider class within the `app/config/app.php` file.

    'providers' => array(
        ...
        'Rees\Sanitizer\SanitizerServiceProvider'
    )

Now simply add the facade alias.

    'aliases' => array(
        ...
        'Sanitizer' => 'Rees\Sanitizer\Facade'
    )
