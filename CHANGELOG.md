# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2020-12-07
### Added
- PHP 8 support

### Changed
- GitHub Actions replaced Travis CI

### Removed
- Drop PHP 7 support
- Remove Prophecy `phpspec/prophecy` usage for testing
- Remove PSR-7 implementation (`slim/psr7`) for testing (test only with Mock)

## [1.0.5] - 2020-04-25
### Changed
- Tests now use Slim PSR-7 implementation

## [1.0.4] - 2020-04-11
### Removed
- PHP 7.2 support (PHPUnit 9 support only PHP ^7.3)

### Fixed
- `phpspec/prophecy` added as dependency with `phpspec/prophecy-phpunit` to respect PHPUnit deprecations

## [1.0.3] - 2020-01-07
### Changed
- CHANGELOG ignored in .gitattributes file

## [1.0.2] - 2020-01-07
### Added
- This CHANGELOG file
- Coveralls coverage in parallel
- Copyright with new year 2020
- PHPStan tests

### Changed
- PHP 7.4 Travis test
- Refactor Travis config file
- Refactor Composer config file
- README file

## [1.0.1] - 2019-10-03
### Added
- PHP 7.4 Travis test (snapshot versions)
- `Generic.Arrays.DisallowLongArraySyntax` rule to PHP_CodeSniffer

### Removed
- PHP-CS-Fixer

## [1.0.0] - 2019-09-13
### Added
- First version

[Unreleased]: https://github.com/t0mmy742/trailing-slash-middleware/compare/1.0.6...HEAD
[1.0.6]: https://github.com/t0mmy742/trailing-slash-middleware/compare/1.0.5...1.0.6
[1.0.5]: https://github.com/t0mmy742/trailing-slash-middleware/compare/1.0.4...1.0.5
[1.0.4]: https://github.com/t0mmy742/trailing-slash-middleware/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/t0mmy742/trailing-slash-middleware/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/t0mmy742/trailing-slash-middleware/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/t0mmy742/trailing-slash-middleware/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/t0mmy742/trailing-slash-middleware/releases/tag/1.0.0