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

<br><br>

**Set an object**
```php
$line   = 10;
$object = ['foo' => 'bar'];
$file->setObject($line, $object);
```

If the file has less than `$line` entries, the gap will be filled with blank lines.

`$object` does not need to be an array, it also may be an actual object. 

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

## License
MIT

<br><br>

## How to install it
Use composer.
```
composer require adinan-cenci/json-lines
```
