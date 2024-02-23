# Release Notes for Search Assistant

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
