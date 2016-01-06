# Metabox Control
**Table of Contents**

- [Introduction](#Introduction)
- [Installation](#Installation)
- [API Documentation](#API_Documentation)
- [Usage](#Usage)

### Introduction
Metabox Control is a small Javascript API that allows you to take back control for your page post type's edit post screen, one metabox at a time. Metabox Control allows you to **control** when metaboxes are rendered to the screen.

### Why?
Over time, it never fails. You create 2 or 3 custom page templates. You add 2 or 3 metaboxes to provide the end user an outstanding WordPress experience. And then **BAM**. Your perfectly laid plans become a cluttered mess.

### How
Metabox Control allows you to specify which metaboxes belong to which page template. From your end of things thats it. From there Metabox Control will handle the showing and hiding of your metaboxes. Which is based on which page template has been selected from the template dropdown. So lets look at some code.

## Installation
Clone or download this repo to your WordPress project.
```sh
$ git clone https://github.com/mrbobbybryant/metabox-control.git
```
**Dependencies:**
I plan to set this repo up to use Bower. However, for now you will need to also download one dependency. *Lodash*.
[Download lodash](https://raw.githubusercontent.com/lodash/lodash/3.10.1/lodash.min.js)

Once that is done, simply enqueue ```metabox-control.js``` and ```lodash.min.js```, just like any other WordPress JS file. For Example,
```php
function my_admin_enqueue_scripts() {
	$post_type = get_post_type();

	if ( 'page' === $post_type ) {
		wp_enqueue_script( 'mb-control', get_template_directory_uri() . '/js/vendor/mb-control/metabox-control.js', array( 'underscore', 'lodash' ), '0.0.1', true );
		wp_enqueue_script( 'lodash', "https://cdn.rawgit.com/lodash/lodash/3.0.1/lodash.min.js", array( 'underscore' ), '3.0.1' ,true );
	}

	wp_enqueue_script( 'admin-main-js', get_template_directory_uri() . '/js/admin-main.js', array( 'mb-control' ), '20160104', true  );
}

add_action( 'admin_enqueue_scripts', 'my_admin_enqueue_scripts' );
```
As you can see I have set lodash and underscore as dependencies on the ```metabox-control.js``` file. I have also shown in my example how you should also enqueue your theme's admin js file. Which I have added a dependency for *mb-control*. That will tell WordPress that your admin file depends on ```metabox-control.js```. For more information on how to properly enqueue js files in WordPress, check out the [WordPress Codex](https://codex.wordpress.org/Function_Reference/wp_enqueue_script).

Now you are all setup! If you have been developing for a while, then this installation will look fairly standard.

## API Documentation
#### metaboxControl.addTemplate( templateName, array( metabox-ids ) );
> This method is used to register a page template
> and it's associated metaboxes.

- ```templateName``` - (string) - **required** - This is the php file name. For example, ```page-template-one.php```
- ```metabox-ids``` - (array) - **required** - An array of all the metabox ids you wish to show and hide, depending on this page template. When registering a metabox this will be the first parameter passed into ```add_meta_box()```.
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

#### metaboxControl.initialize( ) );
> This method does not except any parameters. This method is responsible for actually registering eventListeners for all the templates you have added. It should be called after all templates have been registered. So usage for more info.

```js
metaboxControl.initialize();
```
## Usage

In the example below I will be simiulating the adding of two page template (page-one.php and page-two.php), as well as one metabox per template(mb-one and mb-two). Again these will depend on your implementation, and simply examples.
```js
metaboxControl.addTemplate( 'page-one.php', ['mb_one']);
metaboxControl.addTemplate( 'page-two.php', ['mb_two']);
metaboxControl.initialize();
```

That's it! Now when you got to Create or Edit a WordPress Page, those metaboxes will only be visible when you have selected the associated page template from the template dropdown.