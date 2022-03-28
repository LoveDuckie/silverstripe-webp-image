# silverstripe-webp-image

[![Build Status](https://travis-ci.org/loveduckie/silverstripe-webp-image.svg?branch=master)](https://travis-ci.org/loveduckie/silverstripe-webp-image)
[![License](https://poser.pugx.org/loveduckie/silverstripe-webp-image/license)](https://packagist.org/packages/loveduckie/silverstripe-webp-image)

## Information

You can read more about the modifications made to this fork, and the reasoning behind it at the link below.

[Tips for Optimizing Page Speeds](https://lucshelton.com/blog/tips-for-optimizing-page-speeds/)

## Modifications

I've modified this add-on so that `.webp` that are created possess the file name of `file_name_goes_here.<original extension>.webp`. The reason for this is so that the images created can be automatically served in place of their original image assets by using a NGINX configuration such as the one below.

```nginx
map $http_accept $webp_suffix {
  default   "";
  "~*webp"  ".webp";
}

location ~* /assets/.+\.(?<extension>jpe?g|png|gif|webp)$ {
    # more_set_headers 'Content-Type: image/webp';
    gzip_static on;
    gzip_types image/png image/x-icon image/webp image/svg+xml image/jpeg image/gif;

    add_header Vary Accept;
    expires max;
    sendfile on;
    try_files "${request_uri}${webp_suffix}" $uri =404;
}
```

The variable `webp_suffix` will be populated with the `.webp` extension if the requesting web client has `webp` defined as part of its `Accept` header. NGINX will then attempt to find the `.webp` version of the asset, and failing that, it will serve the original instead.

## Introduction

This module creates webp images from resized jpeg and png images. More Information about webp images [https://developers.google.com/speed/webp/](https://developers.google.com/speed/webp/)

## Requirements

- Silverstripe > 4.2
- GDLib with webp Extension

## Installation

```sh
composer require loveduckie/silverstripe-webp-image
```

## Usage

- run `dev/build?flush=1`

- force Browser to load webp image // Example 1 (default)
edit `.htaccess` in your `root` directory, and add `webp` forwarding in compatible browsers

- force Browser to load webp image // Example 2
for information on usage of webp image in html see [css-tricks.com](https://css-tricks.com/using-webp-images/)

## Quick Testfile for checking if webp is available

Below you will find the code to quickly check if webp is available with the installed GD Library. Simply copy this code into a `.php` file in your `root` folder and open the file in a browser.

```php
<?php

if (function_exists(imagewebp)) {
    echo "WebP is available";
} else {
    echo "WebP is not available";
}

```

## TODO

- documentation
- IMagick Support
- PHP test to check support
- Delete Webp Image
- Flush Webp Image
