# Release Notes for Search Assistant

## Unreleased

### Fixed
- Resolve prepareFieldDefinitions() deprecation warnings ([#10](https://github.com/jrrdnx/craft-search-assistant/pull/10))
- Split Db::upsert() logic to resolve database exception when using PostgreSQL ([#9](https://github.com/jrrdnx/craft-search-assistant/pull/9))
- Restructure recent/popular search queries to resolve database exception when using PostgreSQL ([#9](https://github.com/jrrdnx/craft-search-assistant/pull/9))

### Updated
- Cleanup logging, remove unused classes
- Default `enabled` setting to true
- Add `debugMode` setting to assist with troubleshooting

## 1.2.1 - 2025-06-22

### Fixed
- Fixes namespace ([#6](https://github.com/jrrdnx/craft-search-assistant/pull/6))
- Removes duplicate file
- Sets default plugin name in template ([#7](https://github.com/jrrdnx/craft-search-assistant/pull/7))

## 1.2.0 - 2025-05-31

### Added
- Adds GraphQL support

## 1.1.4 - 2024-02-23

### Fixed
- Fixes template reference
- Deletes all HistoryElement entries from `elements` table on uninstall

## 1.1.3 - 2024-02-22

### Fixed
- Fixes an issue where only the last word of the search term was being tracked ([#2](https://github.com/jrrdnx/craft-search-assistant/issues/2))

## 1.1.2 - 2024-02-15

### Fixed
- Track only the search term and not the full query ([#1](https://github.com/jrrdnx/craft-search-assistant/issues/1))
- Don't redirect to plugin settings after install on console requests

### Changed
- Cleanup aliasing

## 1.1.1 - 2024-02-10

### Fixed
- Fixed HistoryElementQuery PHP error for Craft v5

## 1.1.0 - 2024-02-10

### Changed
- Updated requirements for Craft CMS 5 compatibility

## 1.0.0 - 2024-01-26

### Initial release
