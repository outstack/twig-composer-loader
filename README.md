# Twig composer loader

> **TL;DR;** Causes `~vendor/package/example.html.twig` to load a template from `templates/example.html.twig` inside the 
> `vendor/package` composer dependency.

## What / Why?
Designed for reusing Twig templates across projects, this allows you to reference a project by its composer package name,
and automatically look up the `templates` folder within that library.

The built-in `FilesystemLoader` in Twig allows namespacing, so you could call 
`$loader->addPath(__DIR__.'/vendor/vendor/package/templates/', 'vendor_package')`; and reference
`@vendor_package/example.html.twig` instead.

This loader automates this, and provides the tilde (`~vendor/project`) syntax instead.

# How

Require `outstack/twig-composer-loader` and register a `new ComposerDependencyLoader(__DIR__.'/vendor')` - you might 
need to change the path argument based on where you're registration code is.
