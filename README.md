# Opinionated Inertia Vue Component Creator

## Installation

Install the package via composer:

```bash
composer require pkboom/laravel-inertia-vue-component
```

## Usage

Create a new controller, which contains `Inertia:render`.

```php
// SomeController.php

public function index() {
    return Inertia::render('Some/Index', [
        'foo' => 'foo',
        'bar' => 'bar',
    ]);
}
```

Run this command.

```bash
php artisan make:inertia-vue-component <Controller Name>
// e.g. php artisan make:inertia-vue-component SomeController
```

`js/Pages/Some/Index.vue` is created with props

```js
export default {
    props: {
        foo: String,
        bar: String,
    }
    ...
}
```

To add a prop to an existing component, first add a new `key => value` to `Inertia::render`.

```php
// SomeController.php

public function index() {
    return Inertia::render('Some/Index', [
        'foo' => 'foo',
        'bar' => 'bar',
        'new' => 'new',
    ]);
}
```

Run this command.

```bash
php artisan make:inertia-vue-component SomeController
```

A new prop is created in `js/Pages/Some/Index.vue`.

```js
export default {
    props: {
        new: String,
        foo: String,
        bar: String,
    }
    ...
}
```

Publish to edit stub.

```bash
php artisan vendor:publish --provider="Pkboom\InertiaVueComponent\InertiaVueComponentServiceProvider"
```
