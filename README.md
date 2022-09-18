# Json lines

A library to read and write files in the [json lines](https://jsonlines.org/) format.
**Note**: Still in early development.

<br>

## How to use it

**Instantiating**

```php
use AdinanCenci\JsonLines\JsonLines;

$associative = true;
$file = new JsonLines('my-file.jsonl', $associative);
```

`$associative`: It will render the entries as objects if `false` and as associative arrays if `true`, it defaults to `false`.

<br><br>

**Iterating**

```php
foreach ($file->objects as $line => $object) {
    echo $object->myProperty . '<br>';
    // or $object['myProperty'] if ::$associative is true.
}
```

<br><br>

**Add an object to the end of the file**

```php
$object = ['foo' => 'bar'];
$file->addObject($object);
```

`$object` does not need to be an array, it also may be an actual object. 

<br><br>

**Add an object to the middle of the file**

```php
$line = 5;
$object = ['foo' => 'bar'];
$file->addObject($object, $line);
```

If the file has less than `$line` lines, the gap will be filled with blank lines.

<br><br>

**Add several objects to the end of the file**

```php
$objects = [
// line => object
    0 => ['name' => 'foo'],
    5 => ['name' => 'bar'],
];

$objects->addObjects($objects);
```

<br>

<br>

**Add several objects in the middle of the file**

```php
$objects = [
// line => object
    2 => ['name' => 'foo'],
    6 => ['name' => 'bar'],
];

$objects->addObjects($objects, false);
```

<br><br>

**Set an object**

```php
$line   = 10;
$object = ['foo' => 'bar'];
$file->setObject($line, $object);
```

The difference between `::addObject()` and `::setObject()` is that setObject will overwrite whatever is already present at line `$line`. 

<br><br>

**Set multiple objects**

```php
$objects = [
// line => object
    0 => ['name' => 'foo'],
    5 => ['name' => 'bar'],
];

$objects->setObjects($objects);
```

<br><br>

**Retrieve object**

```php
$line   = 10;
$object = $file->getObject($line);
```

Returns `null` if the entry does not exist or if the json is invalid.

<br><br>

**Retrieve multiple objects**

```php
$lines   = [0, 1, 2];
$objects = $file->getObjects($lines);
```

<br><br>

**Delete objects**

```php
$line = 10;
$file->deleteObject($line);
```

<br><br>

**Delete multiple objects**

```php
$lines = [0, 1, 2];
$file->deleteObjects($lines);
```

<br><br>

## Search

Equals operator.

```php
$search = $file->search();
$search->condition('title', 'foo bar', '=');
$results = $search->find();
// Will match entries where the "title" property equals "foo bar".
```

<br><br>

In operator.

```php
$search = $file->search();
$search->condition('title', ['foo', ' bar'], 'IN');
$results = $search->find();
// Will match entries where the "title" property equals either as "foo" 
// or "bar" ( case insensitive ).
```

<br><br>

Like operator.

```php
$search = $file->search();
$search->condition('title', 'foo', 'LIKE');
$results = $search->find();
// Will match entries where the "title" property contains the word "foo"
// e.g: "foo", "foo bar", "foofighters" etc ( case insensitive ).
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
