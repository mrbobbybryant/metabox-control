# Metabox Control
**Table of Contents**

- [Introduction](#introduction)
- [Installation](#installation)
- [API Documentation](#api_documentation)
- [Usage](#usage)
- [Roadmap](#roadmap)

### Introduction
Metabox Control is a small Javascript API that allows you to take back control of your Page edit screen. One metabox at a time. Metabox Control allows you to **control** when metaboxes are rendered to the screen.

### Why?
Over time, it never fails. You create 2 or 3 custom page templates. You add 2 or 3 metaboxes to provide the end user an outstanding WordPress experience. And then **BAM**. Your perfectly laid plans become a cluttered mess.

### How
Metabox Control allows you to specify which metaboxes belong to which page template. And thats all you have to do. Wait until use see the Usage section. It is super minimal. 

From there Metabox Control will handle the showing and hiding of your metaboxes. Which is based on the currently selected template from the page template dropdown. So lets look at some code.

## Installation
Clone or download this repo to your WordPress project's plugin directory.
```sh
$ git clone https://github.com/mrbobbybryant/metabox-control.git
```
Once you have the code downloaded, simply activate the plugin just like normal.

## API Documentation
Metabox Control provides both a Javscript and a PHP API for registering Templates and their associted metaboxes.

###Javascript
#### metaboxControl.addTemplate( templateName, array( metabox-ids ) );
> This method is used to register a page template
> and it's associated metaboxes.

- ```templateName``` - (string) - **required** - This is the php file name. For example, ```page-template-one.php```
- ```metabox-ids``` - (array) - **required** - An array of all the metabox ids you wish to show and hide, depending on this page template. These IDs are the same ones used when registering a metabox. When registering a metabox, this will be the first parameter passed into ```add_meta_box()```.
- **Returns** - New array -or- Error.
```js
metaboxControl.addTemplate( templateName, array(metabox-ids));
```

#### metaboxControl.removeTemplate( templateName );
> This method will remove the given template from Metabox Control.

- ```templateName``` - (string) - **required** - This is the php file name. For example, ```page-template-one.php```. This should correlate to a previously registered template.

- **Returns** - New array -or- Error.
```js
metaboxControl.removeTemplate( templateName );
```

#### metaboxControl.getTemplates( ) );
> This method does not except any parameters. It allows you to query Metabox Control and get a list of all templates that it is tracking. This can be useful for troubleshooting.

- **Returns** - The ```registeredTemplates``` array which is encapsulated within Metabox Control.
```js
metaboxControl.getTemplates();
```

#### metaboxControl.getTemplates( ) );
> This method does not except any parameters. It is simply a helper function for quickly see which WordPress page template is currently selected.

- **Returns** - The currently selected WordPress page template.
```js
metaboxControl.currentTemplate();;
```

## API Documentation( Continued )
###PHP
The PHP code has been namespaced. The Public API uses the namespace ```metabox_control\API;```.

#### add_metabox_template( $template, $metaboxes );
> This method is used to register a page template
> and it's associated metaboxes. It will also check if the request template
> has already been registered. If so it will call `update_metabox_templates``` internally.

- ```template``` - (string) - **required** - This is the php file name. For example, ```page-template-one.php```
- ```metaboxes``` - (array) - **required** - An array of all the metabox ids you wish to show and hide, depending on this page template. These IDs are the same ones used when registering a metabox. When registering a metabox, this will be the first parameter passed into ```add_meta_box()```.
- **Returns** - Boolean -or- Exception.
```php
\metabox_control\API\add_metabox_template( 'page-two.php', array('mb_two') );
```

#### remove_metabox_template( $template );
> This method is used to remove a previously registers template

- ```template``` - (string) - **required** - This is the php file name. For example, ```page-template-one.php```
- **Returns** - Boolean -or- Exception.
```php
\metabox_control\API\remove_metabox_template( 'page-two.php' );
```

#### update_metabox_template( $metaboxes, $exists = null );
> This method is used to update a previously registers template.

- ```template``` - (string) - **required** - This is the php file name. For example, ```page-template-one.php```
- ```metaboxes``` - (array) - **required** - This is an array of metabox id.
- ``exists``` - (bool) - **optional** - This argument is used internally by ```add_metabox_template()``` when it determines
a template has already been entered.
- **Returns** - Boolean -or- Exception.
```php
\metabox_control\API\remove_metabox_template( 'page-two.php', array('mb_two') );
```

## Usage

In the example below I will be simiulating the adding of two page templates in Javascript ( **page-one.php** and **page-two.php** ), as well as a few metabox( **mb_one**, **mb_three** and **mb_two** ). Again these will depend on your implementation, and is simply an example.
```js
metaboxControl.addTemplate( 'page-one.php', ['mb_one', 'mb_three']);
metaboxControl.addTemplate( 'page-two.php', ['mb_two']);
```

That's it! Now when you go to Create or Edit a WordPress Page, those metaboxes will only be visible when you have selected the associated page template.

## Roadmap
- Test Converage
- While already possible, I'd like to make it even easier to remove Default Metaboxes.
- Add Build Process to Minify
- Convert to ES6
- Host on Bower to better handle Dependencies.
- Improve DOM caching. Current Implementation could lead to redundant DOM Object being cached.
