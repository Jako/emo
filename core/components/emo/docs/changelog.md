# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.9.1] - 2024-11-03

### Fixed

- Fix uninitialized string offset 64 warning

## [1.9.0] - 2024-10-01

### Added

- Detect a TLD during replacement to avoid replacing i.e. "@2x.png" code in src attributes

## [1.8.10] - 2024-05-14

### Fixed

- Fix deprecated passing null to parameter for PHP 8.1+

## [1.8.9] - 2022-05-25

### Added

- Add a default chunk tplEmoAdresses for the encrypted adresses
- Modify the output of the encrypted adresses by the referenced chunk in the emo.adresses_tpl system setting

## [1.8.8] - 2022-05-05

### Fixed

- Fix current resource not found in selection_range

## [1.8.7] - 2022-02-22

### Fixed

- Fix missing encryption with an empty selection_range system setting

## [1.8.6] - 2022-02-02

### Fixed

- Restore accidentally removed selection_range, selection_type and tpl_only handling

## [1.8.5] - 2022-01-04

### Fixed

- Fix 'Array and string offset access syntax with curly braces is deprecated' message

### Changed

- Full MODX 3 compatibility

## [1.8.4] - 2021-02-04

### Fixed
 
- Bugfix for emptying the rendered resource during a regular expression issue

### Changed

- Logging a regular expression issue
- Retain only the original classes of the a-tag

## [1.8.3] - 2020-10-22

### Changed

- Prevent an error, when $modx->resource is not set
- Retain the original classes of a link

## [1.8.2] - 2019-08-18

### Changed

- PHP/MODX version check
- Normalized/refactored code

## [1.8.1] - 2018-10-03

### Fixed

- Bugfix for not installed assets/components files

## [1.8.0] - 2017-11-20

### Added

- Exclude sections between `<!-- emo-exclude -->` and `<!-- /emo-exclude -->` from replacement

## [1.7.2] - 2016-11-17

### Fixed

- Restore accidentally removed selection range and selection type code

## [1.7.1] - 2016-10-28

### Fixed

- Fix for 'selection_type' system setting (thanks to @davidpede)

### Added

- Improved email regular expression (thanks to @davidpede)
- Plugin event code refactored

## [1.7.0] - 2016-08-31

### Added

- Output the addresses as JSON encoded array
- The MODX placeholder 'emo_addresses' contains the JSON encoded array too

## [1.6.1] - 2016-08-16

### Fixed

- Bugfix: Parse error in PHP 7

## [1.6.0] - 2016-02-28

### Fixed

- Bugfix: Replace not linked email adresses

### Added

- Added system setting include_scripts

## [1.5.4] - 2016-01-19

### Fixed

- Bugfix: Don't escape html special chars in linktext

## [1.5.3] - 2016-01-10

### Changed

- Using Grunt uglify and cssmin

### Fixed

- Bugfix for addLoadEvent

## [1.5.2] - 2015-02-27

### Fixed

- Bugfix: Wrong default options

### Added

- German and French lexicon

## [1.5.1] - 2015-02-18

### Fixed

- Bugfix: UTF-8 characters in linktext

## [1.5.0] - 2013-10-08

### Added

- First release for MODX Revolution
