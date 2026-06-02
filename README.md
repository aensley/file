# aensley/file

[![Version](https://img.shields.io/packagist/v/aensley/file.svg?logo=packagist&logoColor=fff)][packagist]
![PHP Version](https://img.shields.io/packagist/dependency-v/aensley/file/php?logo=php&logoColor=fff)
[![License](https://img.shields.io/github/license/aensley/file.svg)](https://github.com/aensley/file/blob/main/LICENSE)
[![prettier](https://img.shields.io/badge/prettier-ff69b4.svg?&logo=prettier&logoColor=fff)](https://prettier.io/)
[![Downloads](https://img.shields.io/packagist/dt/aensley/file.svg?logo=packagist&logoColor=fff)][packagist]
[![dependencies](https://img.shields.io/badge/dependencies-check-brightgreen?logo=packagist&logoColor=fff)](https://libraries.io/packagist/aensley%2Ffile)<br>
[![Maintainability](https://qlty.sh/gh/aensley/projects/file/maintainability.svg)][qltysh]
[![Code Coverage](https://qlty.sh/gh/aensley/projects/file/coverage.svg)][qltysh]
[![Tests](https://github.com/aensley/file/actions/workflows/test.yml/badge.svg)](https://github.com/aensley/file/actions/workflows/test.yml)
[![Socket](https://badge.socket.dev/composer/package/aensley/file)](https://socket.dev/composer/package/aensley/file)
[![Snyk](https://snyk.io/test/github/aensley/file/badge.svg)](https://security.snyk.io/package/composer/aensley%2Ffile)

A basic file/directory library.

## What it does

Basic file and directory access/manipulation.

## Installation

Install the latest version with

```bash
composer require aensley/file
```

## Requirements

- PHP >= 7.1

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
