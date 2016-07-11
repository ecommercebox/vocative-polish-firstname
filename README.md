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
//default group ['U' => 'Panie/Pani', 'M' => 'Panie', 'W' => 'Pani'];
//override default group titles
$titles = ['U' => '', 'M' => 'Drogi Panie', 'W' => 'Droga Pani'];
//override or add new exeptions, where W = Woman, M = Man
$exeptions = [
    'Ola' => ['W', 'Oluu'],
    'Jan' => ['M', 'Janku']
];

//init object
$name = new \ecbox\VocativePolishFirstName($input, $encoding, $titles, $exeptions);
```

Setup additional titles group
```php
$titles = ['U' => 'Szanowna(y) Pani(e)', 'M' => 'Szanowny Panie', 'W' => 'Szanowna Pani'];
$name->setTitles($titles, 'polite');
```

Get vocative first name with default title definition. Optional argument $delimiter, default is space and title definition group name
```php
echo $name->getVocativeString();
// output: Drogi Panie Mariuszu
```

Get vocative first name with custom title definition. Optional argument $delimiter, default is space and title definition group name
```php
echo $name->getVocativeString('polite');
// output: Szanowny Panie Mariuszu
```

Get vocative first name
```php
echo $name->getVocativeFirstName();
// output: Mariuszu
```

Get default title for first name
```php
echo $name->getDetectedTitle();
// output: Drogi Panie
```

Get custom group title for first name
```php
echo $name->getDetectedTitle('polite');
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

We are using dictionary test. [See results!](https://htmlpreview.github.io/?https://github.com/ecommercebox/vocative-polish-firstname/blob/master/test/test_results.html)

Test date: 2016-07-11

Total dictionary names: 1704 

Differences: 0

The percentage of errors: 0% 


License
-------
MIT license. See the LICENSE file for more details.
