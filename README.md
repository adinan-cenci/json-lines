# Json lines

A library to read and write files in the [json lines](https://jsonlines.org/) format.

<br><br>

## How to use it

**Instantiate**

```php
use AdinanCenci\JsonLines\JsonLines;

$associative = true;
$file = new JsonLines('my-file.jsonl', $associative);
```

`$associative`: It will render the entries as objects if `false` and as associative arrays if `true`, it defaults to `false`.

<br><br>

**Iterate**

```php
foreach ($file->objects as $key => $object) {
    echo $object->myProperty . '<br>';
    // or $object['myProperty'] if ::associative is true.
}
```

<br><br>

**Set an object**

```php
$line   = 10;
$object = ['foo' => 'bar'];
$file->setObject($line, $object);
```

If the file has less than `$line` entries, the gap will be filled with blank lines.

`$object` does not need to be an array, it also may be an object. 

<br><br>

**Set multiples**

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

Returns null if the entry does not exist.
<br><br>

**Retrieve multiples**

```php
$lines   = [0, 1, 2];
$objects = $file->getObjects($lines);
```

<br><br>

## License

MIT

## How to install it
