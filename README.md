# maispace/mai-news — TYPO3 Extension
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://www.php.net/)
[![TYPO3](https://img.shields.io/badge/TYPO3-13.4%20LTS-orange)](https://typo3.org/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

News / Aktuelles extension with categories, tags, RSS feed, and FAL image support. Categories use TYPO3 `sys_category`, sharing the same tree as `mai_gallery`, `mai_faq`, and `mai_timeline`.

**Requires:** TYPO3 13.4 LTS / 14.1 · PHP 8.2+

---

## Installation

```bash
composer require maispace/mai-news
```

---

## Development

### Linting

```bash
composer lint:check     # Run all linters
composer lint:fix       # Fix auto-fixable issues
```

### Testing

```bash
composer test           # Run all tests
composer test:unit      # Run unit tests only
```

---

## License

GPL-2.0-or-later — see [LICENSE](../../LICENSE) for details.
