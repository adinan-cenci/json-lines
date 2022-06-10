# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2022-06-09
### Fixed
- JsonLines::$fineName: instead of returning the filename as a string, the object was attempting to instantiate it.

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
