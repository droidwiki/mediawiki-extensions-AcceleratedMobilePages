# MediaWiki extension AcceleratedMobilePages

This extension provides versions of pages compliant with the [Accelerated Mobile Pages HTML standard](https://amp.dev/).
AMP pages are supposed to be optimized for smartphones and a very fast loading speed.

## Installation

1. Download a copy of this extension
2. Install it in your `LocalSettings.php`:
```php
wfLoadExtension( 'AcceleratedMobilePages' );
```
3. Done

There's no configuration provided for this extension at the moment.

## Usage

This extension adds a new action to MediaWiki, called `amp`, which can be used to view an AMP optimized version of the current page.
This action is only available on pages in the main namespace of MediaWiki, as most relevant content should be in this namespace.

Each page in the main namespace also automatically gets the `amphtml` link tag in it's HTML head in order to allow search engines to discover the amp version of the page.

## Limitations

Because of the nature of amp sites, this extension basically disables the following features of MediaWiki usually provided to users:
* No editing possible (there's an edit link at the bottom, though, allowing users to navigate to the non-amp editor)
* No Site-JavaScript and no Site-CSS
* No User-JavaScript and no User-CSS
* No Search bar (for now? ;))
* No sidebar for navigation
* basically every extension dangling with the UI is not doing it on the AMP page
* No skins, there's only this specific AMP styling for now
* Basically any other comfort feature

These limitations are the result of building the whole DOM of the content page again, based on the AMPHTML standard.

## Running tests

In order to allow easier test-setup, running tests is a two-step approach:
1. Create a HTML dump of an example AMP page
2. Assert on that page using nodejs

This decision was made, as the official amp-validator is unfortunately only available in JavaScript.
In order to run the tests, you need to execute the PHPUnit tests suite first, and the jest test suite afterwards.
Most IDEs allow you to run a test suite and run other tasks (like another test suite) before, e.g. IntelliJ IDEA.
