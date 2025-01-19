# Json lines

A library to read and write files in the [json lines](https://jsonlines.org/) format.

<br><br>

## Instantiating

```php
use AdinanCenci\JsonLines\JsonLines;

$associative = true;
$file = new JsonLines('my-file.jsonl', $associative);
```

`$associative`: It will render the entries as objects if `false` and as associative arrays if `true`, it defaults to `false`.

<br><br>

## Iterating

```php
foreach ($file->objects as $line => $object) {
    echo $object->myProperty . '<br>';
    // or $object['myProperty'] if ::$associative is true.
}
```

<br><br>

## Editing

### Add an object to the end of the file
```php
$object = ['foo' => 'bar'];
$file->addObject($object);
```

`$object` does not need to be an array, it also may be an actual object. 

### Add an object to the middle of the file

```php
$line = 5;
$object = ['foo' => 'bar'];
$file->addObject($object, $line);
```

If the file has less than `$line` lines, the gap will be filled with blank lines.

### Add several objects to the end of the file

```php
$objects = [
// line => object
    0 => ['name' => 'foo'],
    5 => ['name' => 'bar'],
];

$objects->addObjects($objects);
```

### Add several objects in the middle of the file

```php
$objects = [
// line => object / array
    2 => ['name' => 'foo'],
    6 => ['name' => 'bar'],
];

$objects->addObjects($objects, false);
```

### Set an object

```php
$line   = 10;
$object = ['foo' => 'bar'];
$file->setObject($line, $object);
```

The difference between `::addObject()` and `::setObject()` is that `::setObject()` will overwrite whatever is already present at `$line`. 

### Set multiple objects

```php
$objects = [
// line => object / array
    0 => ['name' => 'foo'],
    5 => ['name' => 'bar'],
];

$objects->setObjects($objects);
```

### Retrieving object

```php
$line   = 10;
$object = $file->getObject($line);
```

Returns `null` if the entry does not exist or if the json is invalid.


### Retrieving multiple objects

```php
$lines   = [0, 1, 2];
$objects = $file->getObjects($lines);
```

### Delete objects

```php
$line = 10;
$file->deleteObject($line);
```

### Delete multiple objects

```php
$lines = [0, 1, 2];
$file->deleteObjects($lines);
```

<br><br>

## Searching

The library also provides a way to query the file.  
Instantiate a new `Search` object, give it conditions and call the `::find()` method, 
it will return an array of matching objects indexed by their line in the file.

```php
$search = $file->search();
$search->condition("object's property", 'value to compare', 'operator');
$results = $search->find();
```

### Is null operator

```php
$search->condition('title', null, 'IS NULL');
// Will match entries where the "title" property equals null or is 
// not defined.
```

### Equals operator

```php
$search->condition('title', 'Iliad', '=');
// Will match entries where the "title" property equals "Iliad" 
// ( case insensitive ).
```

### In operator

```php
$search->condition('title', ['Iliad', ' Odyssey'], 'IN');
// Will match entries where the "title" property equals to either 
// "Iliad" or "Odyssey" ( case insensitive ).
```

### Like operator

```php
$search->condition('title', 'foo', 'LIKE');
// Will match entries where the "title" property contains the word "foo"
// e.g: "foo", "foo bar", "foofighters" etc ( case insensitive ).

$search->condition('title', ['foo', 'bar'], 'LIKE');
// It also accept arrays. This will match match 
// "fool", "barrier", "barista" etc.
```

### Regex operator

```php
$search->condition('rating', '#\d stars?#', 'REGEX');
// Will match entries where the "rating" property matching "#\d stars?#"
// e.g: "1 star", "2 star", "3 stars" etc ( case insensitive ).
```

### Number comparison operators

It also supports "less than", "greater than", "less than or equal", "greater than or equal" and "between".

```php
$search
  ->condition('year', 2022, '<')
  ->condition('year', 1990, '>')
  ->condition('age', 60, '<=')
  ->condition('age', 18, '>=')
  ->condition('price', [10, 50], 'BETWEEN');
```

### Negating operators

You may also negate the operators.

```php
$search
  ->condition('title', 'Iliad', '!=') // Different to ( case insensitive ).
  ->condition('title', ['Iliad', ' Odyssey'], 'NOT IN') // case insensitive.
  ->condition('price', [10, 50], 'NOT BETWEEN')
  ->condition('title', ['foo', 'bar'], 'UNLIKE');
```

### Multiple conditions

You may add multiple conditions to a search.
By default all of the conditions must be met.

```php
$search = $file->search();
$search
  ->condition('band', 'Iron Maiden', '=')
  ->condition('release', 2000, '<');
$results = $search->find();
// Will match entries for Iron Maiden from before the yar 2000.
```

But you can make it so that only one needs to be met.

```php
$search = $file->search('OR');
$search
  ->condition('band', 'Blind Guardian', '=')
  ->condition('band', 'Demons & Wizards', '=');
$results = $search->find();
// Will match entries for both Blind Guardian and Demons & Wizards.
```

### Conditions groups

You may also group conditons to create complex queries.

```php
$search = $file->search('OR');

$search->andConditionGroup()
  ->condition('band', 'Angra', '=')
  ->condition('release', 2010, '<');

$search->andConditionGroup()
  ->condition('band', 'Almah', '=')
  ->condition('release', 2013, '>');

$results = $search->find();
// Will match entries for Angra from before 2010 OR
// entries for Almah from after 2013
```

### Order
You may also order the results by different properties.

```php
$search = $file->search();

$search->orderBy('title', 'ASC');
// Order search results by the title property alphabetically.
$search->orderBY('description', 'DESC');
// Order results by the description decrescently.
```

<br><br>

## License

MIT

<br><br>

## How to install it

Use composer.

```
composer require adinan-cenci/json-lines
```
