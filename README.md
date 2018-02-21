Theme Bundle
============

This bundle provides you the possibility to add themes to each bundle. In your
bundle directory it will look under `Resources/themes/<themename>` or fall back
to the normal Resources/views if no matching file was found.

## Installation

Installation is a quick (I promise!) 3 step process:

1. Download ShapecodeThemeBundle
2. Enable the Bundle

### Step 1: Install ShapecodeThemeBundle with composer

Run the following composer require command:

``` bash
$ php composer.phar require shapecode/theme-bundle

```

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new \Shapecode\Bundle\ThemeBundle\ShapecodeThemeBundle(),
    );
}
```

## Configuration

You will have to set your possible themes and the currently active theme. It
is required that the active theme is part of the themes list.

``` yaml
# app/config/config.yml
shapecode_theme:
    themes: ['standardTheme', 'winter_theme', 'weekend']
    active_theme: 'standardTheme'
```

### Get active theme information from cookie

If you want to select the active theme based on a cookie you can add:

``` yaml
# app/config/config.yml
shapecode_theme:
    cookie:
        name: NameOfTheCookie
        lifetime: 31536000 # 1 year in seconds
        path: /
        domain: ~
        secure: false
        http_only: false
```


### Theme Cascading Order

The following order is applied when checking for templates that live in a bundle, for example `@BundleName/Resources/template.html.twig`
with theme name ``phone`` is located at:

1. Override themes directory: `app/Resources/themes/phone/BundleName/template.html.twig`
2. Override view directory: `app/Resources/BundleName/views/template.html.twig`
3. Bundle theme directory: `src/BundleName/Resources/themes/phone/template.html.twig`
4. Bundle view directory: `src/BundleName/Resources/views/template.html.twig`

For example, if you want to integrate some TwigBundle custom error pages regarding your theme
architecture, you will have to use this directory structure :
`app/Resources/themes/phone/TwigBundle/Exception/error404.html.twig`

The following order is applied when checking for application-wide base templates, for example `::template.html.twig`
with theme name ``phone`` is located at:

1. Override themes directory: `app/Resources/themes/phone/template.html.twig`
2. Override view directory: `app/Resources/views/template.html.twig`

#### Change Theme Cascading Order

You able change cascading order via configurations directives: `path_patterns.app_resource`, `path_patterns.bundle_resource`, `path_patterns.bundle_resource_dir`. For example:

``` yaml
# app/config/config.yml
shapecode_theme:
    path_patterns:
        app_resource:
            - %%app_path%%/themes/%%current_theme%%/%%template%%
            - %%app_path%%/themes/fallback_theme/%%template%%
            - %%app_path%%/views/%%template%%
        bundle_resource:
            - %%bundle_path%%/Resources/themes/%%current_theme%%/%%template%%
            - %%bundle_path%%/Resources/themes/fallback_theme/%%template%%
        bundle_resource_dir:
            - %%dir%%/themes/%%current_theme%%/%%bundle_name%%/%%template%%
            - %%dir%%/themes/fallback_theme/%%bundle_name%%/%%template%%
            - %%dir%%/%%bundle_name%%/%%override_path%%
```

##### Cascading Order Patterns Placeholders

<table>
  <tr>
    <th>Placeholder</th>
  <th>Representation</th>
  <th>Example</th>
  </tr>
  <tr>
    <td><code>%app_path%</code></td>
  <td>Path where application resources are located</td>
  <td><code>app/Resources</code></td>
  </tr>
  <tr>
    <td><code>%bundle_path%</code></td>
  <td>Path where bundle located, for example</td>
  <td><code>src/Vendor/CoolBundle/VendorCoolBundle</code></td>
  </tr>
  <tr>
    <td><code>%bundle_name%</code></td>
  <td>Name of the bundle</td>
  <td><code>VendorCoolBundle</code></td>
  </tr>
  <tr>
    <td><code>%dir%</code></td>
  <td>Directory, where resource should looking first</td>
  <td></td>
  </tr>
  <tr>
    <td><code>%current_theme%</code></td>
  <td>Name of the current active theme</td>
  <td></td>
  </tr>
    <td><code>%template%</code></td>
  <td>Template name</td>
  <td><code>view.html.twig</code></td>
  </tr>
  <tr>
    <td><code>%override_path%</code></td>
  <td>Like template, but with views directory</td>
  <td><code>views/list.html.twig</code></td>
  </tr>
</table>


### Change Active Theme

For that matter have a look at the ThemeRequestListener.

If you are early in the request cycle and no template has been rendered you
can still change the theme without problems. For this the theme service
exists at:

``` php
$activeTheme = $container->get('shapecode_theme.active_theme');
echo $activeTheme->getName();
$activeTheme->setName("phone");
```

## Contribution

Active contribution and patches are very welcome. 

First install dependencies:

```bash
   composer.phar install --dev
```
