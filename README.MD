# handlebars.php

Handlebar.php Helper for [agency-boilerplate](https://github.com/agency-framework/agency-boilerplate).

## Local Installation

```
./handlebars.php $ composer install
```

## Options

`\AgencyFramework\Handlebars\Core::init($options)`

```php
[
  'partialDir' => '.',
  'extension' => '.hbs',
  'prefix' => '',
  'extraDefinitionsDefaultGroup' => 'default',
  'globalDisableMixin' => 'AGENCY_FRAMEWORK_HBS_GLOBAL_MIXIN_DEACTIVATED',
  'globalDisableExtraDefineArea' => 'AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINE_AREA_DEACTIVATED',
  'globalExtraDefinitionsVar' => 'AGENCY_FRAMEWORK_HBS_EXTRA_DEFINITIONS_VAR',
  'globalExtraDefinitionsArea' => 'AGENCY_FRAMEWORK_HBS_EXTRA_DEFINITIONS_AREA',  
  'globalMixinPath' => 'AGENCY_FRAMEWORK_HBS_GLOBAL_MIXIN_PATH',
]
```

### partialDir

Type: `mixed|string`

Sets partial dirs for loading partial via mixin helper.

### extension

Type: `string` | Default: `.handlebars`

### prefix

Type: `string` | Default: `null`

Sets prefix for hbs files.

*Example: `prefix: "_" -> filename: "_partial.hbs"`*

### extraDefinitionsDefaultGroup

Type: `string` | Default: `default`

Global variable name for default `definition-var` group.

### globalDisableMixin

Type: `string` | Default: `AGENCY_FRAMEWORK_HBS_GLOBAL_MIXIN_DEACTIVATED`

Name for global variable to deactivate `mixin` helper.

> `$GLOBALS['AGENCY_FRAMEWORK_HBS_MIXIN_DEACTIVATED']`


### globalDisableExtraDefineArea

Type: `string` | Default: `AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINE_AREA_DEACTIVATED`

Name for global variable to deactivate `define-area` helper.

> `$GLOBALS['AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINE_AREA_DEACTIVATED']`


### globalExtraDefinitionsVar

Type: `string` | Default: `AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINITIONS_VAR`

Global variable name for `definition-var` results.

> `$GLOBALS['AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINITIONS_VAR']`

### globalExtraDefinitionsArea

Type: `string` | Default: `AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINITIONS_AREA`

Global variable name for `definition-area` results.

> `$GLOBALS['AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINITIONS_AREA']`

### globalMixinPath

Type: `string` | Default: `AGENCY_FRAMEWORK_HBS_GLOBAL_MIXIN_PATH`

Name for global variable, need for rendering mixin.

> `$GLOBALS['AGENCY_FRAMEWORK_HBS_MIXIN_PATH']`

### Helpers

Available Helpers: `lookup`, `replace`, `raw`, `with`, `extname`, `base64`, `relative`, `stringify`

#### `mixin` Helper

**Preview:** <https://hbs-php-agency-boilerplate.herokuapp.com/tests/mixin>

#### `definition-area` Helper

**Pattern**:

For the rendering of an item, the partial attribute is required.
This sets the path for the element that is rendered in the area.

```javascript
$data = [
    'links' => [
        [
            'partial' => 'partial/link', // template path
            'title' => 'Test Link 1.',
            'url' => '#test-link-1'
        ],
        [
            'partial' => 'partial/link', // template path
            'title' => 'Test Link 2.',
            'url' => '#test-link-2'
        ]
    ]
];
```

Render with partial wrapper.

```mustache
<ul>
  {{#definition-area links name="area-1"}}
    <li>{{{this}}}</li>
  {{/definition-area}}
</ul>
```

Render without partial wrapper.

```mustache
<ul>
  {{{#definition-area links name="area-1"}}}
</ul>
```

##### Properties:

Property      | Description
------------- | -------------
`name`        | Name

###### name

Type: `string`

Sets property name.


#### `definition-var` Helper

**Pattern**:

```mustache
{{{definition-var name="PropertyName" type="PropertyType"}}}
```

##### Properties:

Property      | Description
------------- | -------------
`group`       | Group
`name`        | Name
`type`        | Type
`title`       | Title
`description` | Description
`default`     | Default Value

###### name

Type: `string`

Sets property name.

###### type

Type: `string`

Sets property type.

###### default

Type: `mixed`

Sets default property value.

## Use

### Handlebars index.hbs

```mustache
<h1>stringify example</h1>

<h2>scope: this</h2>
<p>{{{stringify this}}}</p>

<h2>scope: object</h2>
<p>{{{stringify object}}}</p>

<h2>scope: array</h2>
<p>{{{stringify array}}}</p>
```

### PHP index.php

```php
<?php

// require via composer
require __DIR__ . '/../../vendor/autoload.php';

// init core
$core = \AgencyFramework\Handlebars\Core::init([
  'partialDir' => __DIR__ . '/tmpl/'
]);

// defined data object
$data = [
  'object' => [
    'hello' => 'world'
  ],
  'array' => [
    0, 1, 2, 3
  ]
];

// render with engine
echo $core->getEngine()->render('index', $data);

?>
```

## Features

> yaml (in progress) <https://hbs-php-agency-boilerplate.herokuapp.com/tests/yaml>

## TODOS

- [x] mixin helper with block and content
- [x] implement var helper, for define properties
- [x] yaml placeholder data
- [ ] implement relativeToRoot to mixin `ctx.relativeToRoot = options.data.root.relativeToRoot;`

