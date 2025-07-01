<div align="center">

# silverstripe-webp-image

</div>

[![Build Status](https://travis-ci.org/LoveDuckie/silverstripe-webp-image.svg?branch=master)](https://travis-ci.org/loveduckie/silverstripe-webp-image)
[![License](https://poser.pugx.org/loveduckie/silverstripe-webp-image/license)](https://packagist.org/packages/loveduckie/silverstripe-webp-image)

## Overview

The **silverstripe-webp-image** module enhances your SilverStripe website's performance by automatically converting image assets to the efficient `.webp` format on demand. Learn more about WebP's benefits [here](https://developers.google.com/speed/webp). For additional details and insights, check out [Tips for Optimizing Page Speeds](https://lucshelton.com/blog/tips-for-optimizing-page-speeds/).

## Thanks

Thanks to [nomidi/silverstripe-webp-image](https://github.com/nomidi/silverstripe-webp-image) for developing the original version of this extension.

I've since forked it and modified it to use Imagick, added support for `.gif`, and made other optimizations.

## Features

- :white_check_mark: Automatically generates `.webp` versions of resized JPEG, PNG, and GIF images.
- :white_check_mark: Significantly improves page load times with minimal configuration, [making it SEO friendly](https://web.dev/articles/choose-the-right-image-format?hl=en#:~:text=WebP%20and%20AVIF%20will%20generally%20provide%20better%20compression%20than%20older%20formats%2C%20and%20should%20be%20used%20where%20possible.%20You%20can%20use%20WebP%20or%20AVIF%20images%20along%20with%20a%20JPEG%20or%20PNG%20image%20as%20a%20fallback.%20See%20Use%20WebP%20images%20for%20more%20details.).
- :white_check_mark: Designed for seamless integration with [NGINX](https://nginx.org/).
- :white_check_mark: Native support for [Imagick](https://www.php.net/manual/en/book.imagick.php).

## How does it work?

Resized or altered versions of image assets are stored to disk for caching and optimization purposes. This extension intercepts calls for retrieving these cached and resized assets by automatically generating `.webp` counterparts of the same images. This usually occurs when leveraging Silverstripe's [image manipulation](https://docs.silverstripe.org/en/5/developer_guides/files/images/#manipulating-images-in-templates) features via its templating engine.

Afterwards, and depending on your server's configuration, NGINX will attempt to serve the `.webp` version of your resized image asset instead of the source version. If the `.webp` version does not exist, then it will instead serve the original version.

This extension provides conversion support for `.jpeg`, `.png`, and `.gif` assets using [Imagick](https://www.php.net/manual/en/book.imagick.php).

## Requirements

- SilverStripe `>= 4.2`
- GD Library with WebP support enabled
- Imagick

## Installation

Install the module using Composer:

```bash
composer require loveduckie/silverstripe-webp-image
```

Run the following to initialize:

```bash
vendor/bin/sake dev/build flush=1
```

## Web Server Configuration

This module creates `.webp` images with filenames structured as `file_name.<original_extension>.webp`. To serve WebP images when supported, configure your web server as follows:

### NGINX Configuration

Add the following to your NGINX configuration:

```nginx
map $http_accept $webp_suffix {
  default   "";
  "~*webp"  ".webp";
}

location ~* /assets/.+\.(?<extension>jpe?g|png|gif|webp)$ {
    gzip_static on;
    gzip_types image/png image/x-icon image/webp image/svg+xml image/jpeg image/gif;

    add_header Vary Accept;
    expires max;
    sendfile on;
    try_files "${request_uri}${webp_suffix}" $uri =404;
}
```

This setup detects browsers that support WebP via the `Accept` header and serves the `.webp` version if available.

### Apache `.htaccess` Configuration

For Apache users, add rules to prioritize `.webp` files in your `.htaccess`. This module will automatically update the .htaccess in the public/assets folder on build. [See the template here](templates/SilverStripe/Assets/Flysystem/PublicAssetAdapter_HTAccess.ss)

## Testing WebP Support

To verify WebP support in your environment, create a `.php` file with the following content and open it in your browser:

```php
<?php

if (function_exists('imagewebp')) {
    echo "WebP is available";
} else {
    echo "WebP is not available";
}
```

## Browser Support and Fallbacks

For browsers that don't support WebP, ensure your HTML or server configuration serves alternative formats like JPEG or PNG. Learn more about graceful degradation strategies on [CSS-Tricks](https://css-tricks.com/using-webp-images/).

## Roadmap

- Enhanced documentation
- ~Imagick support~
- PHP tests to validate WebP compatibility
- Automatic cleanup of unused `.webp` files
- Command-line functionality for flushing `.webp` files

## Contributing

Contributions are welcome! Please feel free to submit issues or pull requests to improve the module.

---
