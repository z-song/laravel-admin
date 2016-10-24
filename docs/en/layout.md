# Layout

The layout usage of `laravel-admin` can be found in the `index()` method of the home page's layout file [HomeController.php](/src/Commands/stubs/ExampleController.stub).

The `Encore\Admin\Layout\Content` class is used to implement the layout of the content area. The `Content::row ($element)` method is used to add a row element:

```php
// Add text
$content->row('One line of text');

// Add a component
$content->row(new Box('title', 'xxxx'));


```

The `Encore\Admin\Layout\Row` class is used for layout of inline elements. The `Row::column($width, $element)` method is used to add columns in a row:

```php
// Add columns in the row
$content->row(function(Row $row) {
    $row->column(4, 'xxx');
    $row->column(4, 'xxx');
    $row->column(4, 'xxx');
});

```
The `$width` parameter is used to set the width of the column elements, based on the `bootstrap` layout definition of the grid system. The `$element` parameter can be any object that implements the `Illuminate\Contracts\Support\Renderable` interface or any other printable variable.
