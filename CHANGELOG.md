# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Added

- Add PHP 8.4 Support ([#11](https://github.com/claudiodekker/changelog-updater/pull/11))


## [v1.1.0](https://github.com/claudiodekker/changelog-updater/compare/v1.0.0...v1.1.0) - 2024-01-31

### Added

- Add PHP 8.3 Support ([#7](https://github.com/claudiodekker/changelog-updater/pull/7))

### Fixed

- Workflow: Use `pull_request_target` event to prevent permission issues from forks ([#6](https://github.com/claudiodekker/changelog-updater/pull/6))
- Fix issue #9: Data loss with duplicate sections ([#10](https://github.com/claudiodekker/changelog-updater/pull/10)


## [v1.0.0](https://github.com/claudiodekker/changelog-updater/releases/tag/v1.0.0) - 2023-04-11

### Added

- Everything!
- Add Docker Image publisher ([#2](https://github.com/claudiodekker/changelog-updater/pull/2))

### Optimized

- Use pre-built Docker image in Action ([#3](https://github.com/claudiodekker/changelog-updater/pull/3))

### Fixed

- Fix crash when there are no parseable releases ([#1](https://github.com/claudiodekker/changelog-updater/pull/1))
- Action: Fix bug causing Docker image not to be used ([#4](https://github.com/claudiodekker/changelog-updater/pull/4))
