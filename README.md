# Cloudonaut Validator

This is a simple reusable validator class for PHP.

## Installation

### Install with composer
```
composer require cloudonaut/validator
```

Include it into your scripts by using composer autoload and create a new validator object.
```php
require './vendor/autoload.php';
use Cloudonaut\Lib\Validator;
$validator = new Validator();
```

### Manueal installation
Just copy the Validator.php file from the src folder to your project and include it into your scripts. Than create a new validator object.

```php
require_once './PATH/TO/Validator.php';
use Cloudonaut\Lib\Validator;
$validator = new Validator();
```

## Basic usage

The validator stores violations automatically when there is a violation against a rule. 
To check is a validator has violations you can use the hasViolations() function. To get an array of the violations you can call getViolations().
To add violations you can use one of the many build in check functions or simply add a violation to the list using your own code to check the value.

### Example using a build in check isNotEmpty
Check if a value is not empty. If it is empty a violation is added to the validator object.

```php
$value = "";
$msg = "Value can't be empty";
$validator->isNotEmpty($value, $msg);
if ($validator->hasViolations())
{
    implode("," $validator->getViolations());
}
```
Output
```
Value can't be empty
```

### Example using addViolation
In this example we need the value to match 7 as a simple example. But you can build on this example to do other stuff with value. For example: Call another api and based on the result validate the value, check the value against a database table etc...

```php
$value = 8;
$msg = "Value must be 7";
if ($value != 7)
{
    $validator->addViolation($msg);
}

if ($validator->hasViolations())
{
    implode("," $validator->getViolations());
}
```
output
```
Value must be 7
```

### Example combining rules
You can check the same or other values as many as you want. For example. A e-mail adres must be filled in and a name. But a name is text only.

```php
$name = "R2D2"; // wrong name
$mail="test.example.com"; //wrong mail

$validator->isNotEmpty($name, "Name can't be empty");
$validator->isNotEmpty($mail, "Mail can't be empty");
$validator->isTextOnly($name, "Name can't have numbers");
$validator->isEmail($name, "Mails is not in the correct format");

if ($validator->hasViolations())
{
    implode(", " $validator->getViolations());
}
```
output
```
Name can't have numbers, Mails is not in the correct format
```


## Basic functions

### addViolation($msg='')
Adds a violation with the given message

### hasViolations()
Checks if the validator has violations. It will return true when there are violations or false when there are no violations.

### getViolations()
Returns an array of the messages of all the violations. If there are no violations it just returns an empty array.

## Build in checks
All functions will also return true when no violation is added and false when the value violates the rule.

### isNotEmpty($value, $msg='')
Adds a violation when the value is empty.

### isText($value,$msg='')
Adds a violation when the value is not alphanumeric

### isTextOnly($value,$msg='')
Adds a violation when the value is not containing only letters.

### isNumber($value,$msg='')
Adds a violation when the value is not a number.

### isDigit($value,$msg='')
Adds a violation when the value contains anything else than number characters.

### isBetween($min, $max, $value, $msg='')
Adds a violation when the value is not between the min and max value.

### isComplex($value,$msg='')
Adds a violation when the value is not containing:
* At least one lower case letter
* At least one upper case letter
* At least one number or symbol
* and should be between 8 and 20 characters long

### isDate($value,$msg='')
Adds a violation when the value is not a date in the folowing format: yyyy-mm-dd

### isTime($value,$msg='')
Adds a violation when the value is not a time indication in the folowing format: hh:mm:ss

### isEmail($value,$msg='')
Adds a violation when the value is not an e-mail address

### isURL($value,$msg='')
Adds a violation when the value is not an URL.

### isIP($value,$msg='')
Adds a violation when the value is not an IP address. (v4)

### isFilename($value,$msg='')
Adds a violation when the value is not a filename.

### isInList($value,$list, $msg='')
Adds a violation when the value is not a given list.