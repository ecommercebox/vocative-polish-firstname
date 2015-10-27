# VocativePolishFirstName
Component for remake first name to Polish vocative


Installation
======================

Install the library by adding it to your composer.json or running:

    composer require "ecommercebox/vocative-polish-firstname:*"

# How to use

Create object with first name, you can setup encoding and own "titles" as optional arguments.
First name is converted to first letter up.

```php
//required
$input = 'MARIUSZ';

//optional
$encoding = "UTF-8";
//default ['U' => '', 'M' => 'Panie', 'W' => 'Pani'];
//define own titles
$titles = ['U' => '', 'M' => 'Szanowny Panie', 'W' => 'Szanowna Pani'];

//init object
$name = new \ecbox\VocativePolishFirstName($input, $encoding, $titles);
```

Get vocative first name with title. Optional argument $delimiter, default is space
```php
echo $name->getVocativeString();
// output: Szanowny Panie Mariuszu
```

Get vocative first name
```php
echo $name->getVocativeFirstName();
// output: Mariuszu
```

Get title for first name
```php
echo $name->getDetectedTitle();
// output: Szanowny Panie
```

Check if male
```php
echo $name->isMale();
// output: true if yes
```

Check if woman
```php
echo $name->isWoman();
// output: true if yes
```

Get array
```php
$array = $name->getVocativeArray();
// array: ['M', "Mariuszu"];
// where M: Male, W: Woman, U: Unknown
```

# Test results

You can check quality using test file: [testVocativePolishFirstName](test/testVocativePolishFirstName.php)

We are using dictionary test. [See results!](https://htmlpreview.github.io/?https://github.com/ecommercebox/vocative-polish-firstname/blob/master/test/RESULTS.html)

Test date: 2015-10-27

Total dictionary names: 1705 

Differences: 30 (Unknowns: 22)

The percentage of errors: 2% 

# TODO

* add manual exceptions

License
-------
MIT license. See the LICENSE file for more details.
