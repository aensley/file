# File

A basic file/directory library.

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/aensley/file/blob/master/LICENSE)
[![Latest Stable Version](https://poser.pugx.org/aensley/file/v/stable)](https://packagist.org/packages/aensley/file)
[![Packagist Downloads](https://img.shields.io/packagist/dt/aensley/file.svg)](https://packagist.org/packages/aensley/file)
[![Build Status](https://travis-ci.org/aensley/file.svg)](https://travis-ci.org/aensley/file)
[![Tested Versions Status](https://php-eye.com/badge/aensley/file/tested.svg?branch=dev-master)](https://php-eye.com/package/aensley/file)

[![GitHub Issues](https://img.shields.io/github/issues-raw/aensley/file.svg)](https://github.com/aensley/file/issues)
[![Dependency Status](https://www.versioneye.com/php/aensley:file/dev-master/badge)](https://www.versioneye.com/php/aensley:file)
[![Code Climate Grade](https://codeclimate.com/github/aensley/file/badges/gpa.svg)](https://codeclimate.com/github/aensley/file)
[![Code Climate Issues](https://img.shields.io/codeclimate/issues/github/aensley/file.svg)](https://codeclimate.com/github/aensley/file/issues)
[![Code Climate Test Coverage](https://codeclimate.com/github/aensley/file/badges/coverage.svg)](https://codeclimate.com/github/aensley/file/coverage)

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

use use \Aensley\File\Directory;

// Recursively delete a directory.
Directory::delete('/some/dir/');
```

----

[![Supercharged by ZenHub.io](https://raw.githubusercontent.com/ZenHubIO/support/master/zenhub-badge.png)](https://zenhub.io)
