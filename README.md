# aensley/file

[![Version](https://img.shields.io/packagist/v/aensley/file.svg?logo=packagist&logoColor=fff)][packagist]
[![License](https://img.shields.io/github/license/aensley/file.svg)](https://github.com/aensley/file/blob/master/LICENSE)
[![Downloads](https://img.shields.io/packagist/dt/aensley/file.svg?logo=packagist&logoColor=fff)][packagist]
[![Tests](https://github.com/aensley/file/actions/workflows/test.yml/badge.svg)](https://github.com/aensley/file/actions/workflows/test.yml)<br>
[![Maintainability](https://qlty.sh/gh/aensley/projects/file/maintainability.svg)][qltysh]
[![Code Coverage](https://qlty.sh/gh/aensley/projects/file/coverage.svg)][qltysh]
[![Socket](https://badge.socket.dev/composer/package/aensley/file)](https://socket.dev/composer/package/aensley/file)

A basic file/directory library.

## What it does

Basic file and directory access/manipulation.

## Installation

Install the latest version with

```bash
composer require aensley/file
```

## Requirements

* PHP >= 5.6

## Example usage

### Simple example

```php
<?php

require '/path/to/composer/autoload.php';

use \Aensley\File\Directory;

// Recursively delete a directory.
Directory::delete('/some/dir/');
```

[packagist]: https://packagist.org/packages/aensley/file
[qltysh]: https://qlty.sh/gh/aensley/projects/file