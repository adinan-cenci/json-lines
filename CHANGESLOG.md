# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [3.1.0] - 2025-01-19
### Added
- `JsonLines::getRandomObjects()` to retrieve random objects.
- [Issue 2](https://github.com/adinan-cenci/json-lines/issues/2): `Search::orderBy()` to order search results.

---

## [3.0.0] - 2023-07-21
### Changed
- Exceptions changed namespace to `AdinanCenci\FileEditor\Exception`.

### Added
- Added a new operator to the search functionality: `REGEX`.

## [2.0.2] - 2022-10-04

### Fixed
- `JsonLines::addObject()` was ignoring the `$line` parameter and always adding objects to the end of the file.

## [2.0.1] - 2022-09-30

### Fixed
- `JsonLines::$fileName`: Was trying to instantiate the fileName instead of returning a string.
- `Crud::getNumberOfLinesToProcess()`: Cannot use `max()` on empty arrays.

## [2.0.0] - 2022-09-29

The library was rewritten almost entirely from scratch.

### Removed

- `Search::equals()`
- `Search::includes()`
- `Search::like()`, all three replaced by `Search::condition()`.

### Added

- `Search::condition()`
- `Search::andConditionGroup()`
- `Search::orConditionGroup()`, can use them to make complex queries.
- `JsonLines::crud()`: Exposes the object used to edit the file.

## [1.0.1] - 2022-06-09

### Fixed

- `JsonLines::$fineName`: instead of returning the filename ( a string ), the `JsonLines` was attempting to instantiate it.

## [1.0.0] - 2022-05-30

### Changed

- Changed the parameters order in `JsonLines::addObject()`.
- Added type hinting and revised documentation.

### Fixed

- Fixed an error with `JsonLines::deleteObject()` passing the wrong type to its dependency.
- Fixed an error with `JsonLines::addObject()` when creating a file from scratch, it would not write to the end of the file.

## [0.3.0] - 2022-05-25

### Added

- `JsonLines::addObjects()`
- `Search::equals()`

### Changed

- `JsonLines::addObject()` used to add object to the end of the file only.
  now it can also add to the middle of the file.

### Fixed

- `JsonLines::deleteObject()` was using an undefined variable.

## [0.2.0] - 2022-05-22

### Added

- `JsonLines::lineCount`
- `JsonLines::countLines()`
- `JsonLines::addObject()`

## [0.1.1] - 2022-02-07

### Fixed

Removed `composer.lock` and the contents from the `vendor/` folder.
