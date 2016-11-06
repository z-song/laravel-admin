# Model-Grid

Class `Encore\Admin\Grid` is used to generate tables based on the data model,for example,we have a table `movies` in database:

```sql
CREATE TABLE `movies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` int(10) unsigned NOT NULL,
  `describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` tinyint unsigned NOT NULL,
  `released` enum(0, 1),
  `release_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

```

And the model of this table is `App\Models\Movie`,The following code can generate the data ggrid for `users`:

```php

use App\Models\Movie;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;

$grid = Admin::grid(Movie::class, function(Grid $grid){

    // The first column displays the id field and sets the column as a sortable column
    $grid->id('ID')->sortable();

    // The second column shows the title field, because the title field name and the Grid object's title method conflict, so use Grid's column () method instead
    $grid->column('title');
    
    // The third column shows the director field, which is set by the value($callback) method to display the corresponding user name in the users table
    $grid->director()->value(function($userId) {
        return User::find($userId)->name;
    });
    
    // The fourth column appears as the describe field
    $grid->describe();
    
    // The fifth column is displayed as the rate field
    $grid->rate();

    // The sixth column shows the released field, formatting the display output through the value($callback) method
    $grid->released('Release?')->value(function ($released) {
        return $released ? 'yes' : 'no';
    });

    // The following shows the columns for the three time fields
    $grid->release_at();
    $grid->created_at();
    $grid->updated_at();

    // The filter($callback) method is used to set up a simple search box for the table
    $grid->filter(function ($filter) {
    
        // Sets the range query for the created_at field
        $filter->between('created_at', 'Created Time')->datetime();
    });
});

// Displays the table contents
echo $grid;

```

## Basic Usage

#### Set the table title
```php
$grid->title('Movie list');
```

#### Add a column
```php

// Add the column directly through the field name `username`
$grid->username('Username');

// The effect is the same as above
$grid->column('username', 'Username');

// Add multiple columns
$grid->columns('email', 'username' ...);
```

#### Modify the source data
```php
$grid->model()->where('id', '>', 100);

$grid->model()->orderBy('id', 'desc');

$grid->model()->take(100);

```

#### Sets the number of lines displayed per page

```php
// The default is 15 per page
$grid->paginate(15);
```

#### Modify the display output

```php
$grid->text()->value(function($text) {
    return str_limit($text, 30, '...');
});

$grid->name()->value(function ($name) {
    return "<span class='label'>$name</span>";
});

$grid->email()->value(function ($email) {
    return "mailto:$email";
});

```

#### Disable The batch delete button
```php
$grid->disableBatchDeletion();
```
#### Modify the row action button
```php
//Opens the edit and delete operations
$grid->actions('edit|delete');

//Close all operations
$grid->disableActions();
```

#### Column control 
```php
$grid->rows(function($row){

    //add style to lines which Id less than 10 
    if($row->id < 10) {
        $row->style('color:red');
    }

    // Open the edit operation for specified column
    if($row->id % 3) {
        $row->action('edit');
    }

    //Specifies the column to add a custom action button
    if($row->id % 2) {
        $row->actions()->add(function ($row) {
            return "<a class=\"btn btn-xs btn-danger\">btn</a>";
        });
    }
});
```

#### Add query filters
```php
$grid->filter(function($filter){

    // sql: ... WHERE `user.name` LIKE "%$name%";
    $filter->like('name', 'name');

    // sql: ... WHERE `user.email` = $email;
    $filter->is('emial', 'Email');

    // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
    $filter->between('created_at', 'Created Time')->datetime();

    // sql: ... WHERE `title` LIKE "%$input" OR `content` LIKE "%$input";
    $filter->where(function ($query) {

        $query->where('title', 'like', "%{$this->input}%")
            ->orWhere('content', 'like', "%{$this->input}%");

    }, 'Text');
    
    // sql: ... WHERE `rate` >= 6 AND `created_at` = {$input};
    $filter->where(function ($query) {

        $query->whereRaw("`rate` >= 6 AND `created_at` = {$this->input}");

    }, 'Text');
});
```
