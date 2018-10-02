# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.8.1] - 2018-10-03
### Added
- Bugfix for not installed assets/components files

## [1.8.0] - 2017-11-20
### Added
- Exclude sections between `<!-- emo-exclude -->` and `<!-- /emo-exclude -->` from replacement

## [1.7.2] - 2016-11-17
### Changed
- Restore accidentally removed selection range and selection type code

## [1.7.1] - 2016-10-28
### Changed
- Fix for 'selection_type' system setting (thanks to @davidpede)
### Added
- Improved email regular expression (thanks to @davidpede)
- Plugin event code refactored
      
## [1.7.0] - 2016-08-31
### Added
- Output the addresses as JSON encoded array
- The MODX placeholder 'emo_addresses' contains the JSON encoded array too

## [1.6.1] - 2016-08-16
### Changed
- Bugfix: Parse error in PHP 7

## [1.6.0] - 2016-02-28
### Changed
- Bugfix: Replace not linked email adresses
### Added
- Added system setting include_scripts

## [1.5.4] - 2016-01-19
### Changed
- Bugfix: Don't escape html special chars in linktext

## [1.5.3] - 2016-01-10
### Changed
- Using Grunt uglify and cssmin
### Added
- Bugfix for addLoadEvent

## [1.5.2] - 2015-02-27
### Changed
- Bugfix: Wrong default options
### Added
- German and french lexicon

## [1.5.1] - 2015-02-18
### Changed
- Bugfix: UTF-8 characters in linktext

## [1.5.0] - 2013-10-08
### Added
- First release for MODX Revolution
