# Martha Plugins

Plugins allow developers to build upon and enhance the functionality of the Martha software. There are several ways these plugins can enhance or change the functionality of the application, which we'll discuss below.

## Plugin Package Layout

A Martha plugin MUST be laid out in the following directory structure: 

    plugin-name/
        plugin.json
        src/
            Martha/
                Plugin/
                    PluginName/
                        ...

The root directory MUST be a directory named the same as the name of the plugin. Under which, there MUST be a directory tree starting with `src/Martha/Plugin/` and ending with the directory named the same as the name of the plugin. The plugin MUST follow [PSR-0 coding standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) so that the plugin class files can be properly autoloaded.

## The `plugin.json` file

Each plugin MUST have a `plugin.json` file located within the root directory of the plugin package. This [JSON](http://www.json.org/) file describes the plugin, as well as identifies its dependencies and information about installing it. A full plugin file might look like: 

```json
{
    "name": "vendor-name/plugin-name",
    "description": "A description of the plugin",
    "keywords": ["some","keywords","describing","the","plugin"],
    "homepage": "Where a person may go to find out more information about the plugin",
    "require-php": {
        "vendor-name/library-name": ">1.0",
        "vendor-name/other-library": "2.0"
    },
    "require-plugin": {
    
    },
    "require-js": {
    
    },
    "assets-css": [],
    "assets-js": [],
    "assets-images": []
}
```

The required `name` key should contain the name of the Plugin, the required `description` key should contain a brief description of the plugin, the optional `keywords` key can be an array of keywords that describe the plugin, and the optional `homepage` is a website that someone can go to to find out more information about the plugin. 

The `require-` and `assets-` sections are described in detail below.

#### `require-php`

The `require-php` section outlines 3rd party libraries that the plugin needs to function properly. These libraries will be downloaded and installed using [Composer](http://getcomposer.org/), and usage should match the use of the [`require` key](http://getcomposer.org/doc/01-basic-usage.md#the-require-key) in Composer.

For example, if your project depends on a GitHub API library, you would add it to `require-php`:

```json
{
    "require-php": {
        "martha-ci/github-api": "1.*"
    }
}
```

And the latest version of Martha's GitHub API library (matching 1.*) will be installed.

#### `require-plugin`

This section of the JSON file defines other plugins that the plugin needs installed in order to operate. When installing this plugin, these plugins are installed as well. The definition for this section follows the definition of the `require-php` section, which follows the definition of [Composer](http://getcomposer.org/doc/01-basic-usage.md#the-require-key).

For example, if your project depends on the GitHub plugin, you would add it to `require-plugin`:

```json
{
    "require-plugin": {
        "martha-ci/plugin-github": "1.*"
    }
}
```

And the latest version of Martha's GitHub Plugin (matching 1.*) will be installed.

#### `require-js`

This section defines any 3rd party JavaScript libraries that need to be installed for the plugin to operate correctly. These JavaScript libraries are installed into `public/js/vendor/[package-name]`, and are installed using [Jam](http://jamjs.org/), a package manager for JavaScript. Packages listed in this section should follow the [formatting dictated by Jam](http://jamjs.org/docs).

For example, if your project depends on jQuery, you would add it to `require-js`:

```json
{
    "require-js": {
        "jquery": "2.0"
    }
}
```

And jQuery will be installed to `public/js/vendor/jquery`.

#### `assets-css`, `assets-js`, and `assets-images`

**Note**: it's very important that any 3rd party JavaScript libraries are installed through `require-js` to prevent collision. **DO NOT** install them using `assets-js`.

These three optional array values are all directories contained within the plugin source that contain either CSS, JavaScript, or images (respectively) that are used by the plugin. Upon installation or updating of the plugin, these files will be extracted to the root `css/`, `js/`, and `images/` directories (respectively) of the Martha project. They will be stored in a sub directory matching the vendor name and the plugin name (example: `css/vendor-name/plugin-name`).

A project with the name of `myvendor/myplugin` with the following in the `plugin.json` file:


```json
{
    "assets-css": ["assets/css"],
    "assets-js": ["assets/js"],
    "assets-images: ["assets/images"]
}
```

Will have the assets extracted to: 

```
css/
    myvendor/
        myplugin/
            [contents of assets/css]
images/
    myvendor/
        myplugin/
            [contents of assets/images]
js/
    myvendor/
        myplugin/
            [contents of assets/js]
```

These asset files will be removed and re-extracted on update, and removed on plugin removal. 

