# Lazy Load Ultimate for Magento 1 / OpenMage

**Lazy loading of images for faster Magento page loads.**

![Lazy CMS / home page loading (blurred)](docs/images/screenshots/01-lazy-cms-home-page-loading-blurred.webp)

Loads images only when they enter the viewport, which speeds up page loading and reduces bandwidth.

Shows a transparent, blurred or low-resolution placeholder until the image is loaded.

> [!NOTE]
> Lazy Load Ultimate is free and open source under the MIT License (since 2026). Plugin Company no longer actively develops this extension, but issues and pull requests are welcome.

## Features

- Loads images when they enter the viewport
- Faster loading of store pages
- Less server bandwidth usage
- Works with the Google Lighthouse audit
- Transparent, blurred or low-resolution placeholders
- Configurable loading transitions
- Supports remote (CDN) images

## Screenshots

<table>
  <tr>
    <td width="50%" valign="top"><img src="docs/images/screenshots/02-lazy-cms-home-page-loading-focused.webp" alt="Lazy CMS / home page loading (focused)"><br><sub>Lazy CMS / home page loading (focused)</sub></td>
    <td width="50%" valign="top"><img src="docs/images/screenshots/03-lazy-category-page-loading-blurred-1-of-3.webp" alt="Lazy category page loading (blurred, 1 of 3)"><br><sub>Lazy category page loading (blurred, 1 of 3)</sub></td>
  </tr>
  <tr>
    <td width="50%" valign="top"><img src="docs/images/screenshots/04-lazy-category-page-loading-focused-1-of-3.webp" alt="Lazy category page loading (focused, 1 of 3)"><br><sub>Lazy category page loading (focused, 1 of 3)</sub></td>
    <td width="50%" valign="top"><img src="docs/images/screenshots/05-lazy-category-page-loading-blurred-2-of-3.webp" alt="Lazy category page loading (blurred, 2 of 3)"><br><sub>Lazy category page loading (blurred, 2 of 3)</sub></td>
  </tr>
  <tr>
    <td width="50%" valign="top"><img src="docs/images/screenshots/06-lazy-category-page-loading-focused-2-of-3.webp" alt="Lazy category page loading (focused, 2 of 3)"><br><sub>Lazy category page loading (focused, 2 of 3)</sub></td>
    <td width="50%" valign="top"><img src="docs/images/screenshots/07-lazy-category-page-loading-blurred-3-of-3.webp" alt="Lazy category page loading (blurred, 3 of 3)"><br><sub>Lazy category page loading (blurred, 3 of 3)</sub></td>
  </tr>
</table>

More screenshots are on the [product page](https://plugin.company/magento1-extensions/lazy-load-ultimate.html).

## Compatibility

- **Magento:** Community Edition 1.4.x – 1.9.x, Enterprise Edition 1.8.x – 1.14.x
- **Latest version:** 1.7.0 (released 2020-10-23)
- **Composer package:** `plugincompany/magento1-lazy-load-ultimate`
- **Module:** `Plugincompany_Lazyload`

## Installation

### modman

```bash
cd /path/to/magento
modman init   # once per Magento installation
modman clone https://github.com/PluginCompany/magento1-lazy-load-ultimate.git
```

modman creates symlinks, so enable *Allow Symlinks* under System > Configuration > Advanced > Developer > Template Settings.

### Composer

With [magento-hackathon/magento-composer-installer](https://github.com/Cotya/magento-composer-installer) (it reads the `modman` file of this repository):

```bash
composer config repositories.plugin.company composer https://plugin.company/
composer require plugincompany/magento1-lazy-load-ultimate
```

Your project `composer.json` needs the Magento root directory in its `extra` section (and, with Composer 2.2+, the installer plugin allowed), for example:

```json
{
    "extra": { "magento-root-dir": "./" },
    "config": { "allow-plugins": { "magento-hackathon/magento-composer-installer": true } }
}
```

### Manual installation

Download the ZIP of the latest release from the [tags page](https://github.com/PluginCompany/magento1-lazy-load-ultimate/tags) ([1.7.0](https://github.com/PluginCompany/magento1-lazy-load-ultimate/archive/refs/tags/1.7.0.zip)) and copy the `app/`, `js/`, `skin/` folders into your Magento root:

```bash
unzip magento1-lazy-load-ultimate-1.7.0.zip
cp -R magento1-lazy-load-ultimate-1.7.0/app magento1-lazy-load-ultimate-1.7.0/js magento1-lazy-load-ultimate-1.7.0/skin /path/to/magento/
```

Step-by-step instructions are in the [installation guide](docs/02-installing-the-magento-extension.md).

### After installing

Clear the cache (System > Cache Management, or `rm -rf var/cache/*`), then log out of the admin and log in again so the new configuration sections and permissions are loaded.

### Upgrading from a purchased copy

Replace the old files with this release. No license key is needed; your settings and data stay as they are.

## Documentation

- [Documentation in this repository](docs/README.md) (Installation Guide, User Manual)
- Online documentation: <https://plugin.company/knowledge-base/docs/magento-lazy-load-ultimate/>
- Product page: <https://plugin.company/magento1-extensions/lazy-load-ultimate.html>

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for the release history.

## Contributing

Found a bug or have an improvement? Please open an [issue](https://github.com/PluginCompany/magento1-lazy-load-ultimate/issues) or send a [pull request](https://github.com/PluginCompany/magento1-lazy-load-ultimate/pulls). Since the extension is no longer actively developed by Plugin Company, fixes from the community are the best way to keep it working on current Magento 1 / OpenMage and PHP versions.

## License

Plugin Company's code in this repository is released under the [MIT License](LICENSE), Copyright (c) 2014-2026 Plugin Company.

Bundled third-party libraries keep their own licenses; see [THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md) for the full list.

In particular:

- **Zebra_Image 2.2.3** (`app/code/local/Plugincompany/Lazyload/Helper/Data.php`) is licensed under **LGPL-3.0**, not MIT.
