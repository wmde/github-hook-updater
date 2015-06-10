WMDE github-hook-updater
==================

Makes changing all of the github IRC hooks for WMDE stuff easy

## Usage

The first step to use this script is to download composer:

```bash
$ curl -s http://getcomposer.org/installer | php
```

Then we have to install our dependencies using:
```bash
$ php composer.phar install
```

Then we just have to run run.php:
```bash
$ php run.php
```

We will be prompted for an access token which can be retrieved from https://github.com/settings/tokens
```bash
$ Please generate a personal access token at https://github.com/settings/tokens
$ Github Token:
```

The hooks will then be updated!