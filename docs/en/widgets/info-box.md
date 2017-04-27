# Infobox

The `Encore\Admin\Widgets\InfoBox` class is used to generate the information presentation block:

```php
use Encore\Admin\Widgets\InfoBox;

$infoBox = new InfoBox('New Users', 'users', 'aqua', '/admin/users', '1024');

echo $infoBox->render();

```

Refer to the section on the `InfoBox` in the` index()`method of the home page layout file [HomeController.php](/src/Commands/stubs/ExampleController.stub).