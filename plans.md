Entity-Relationship Diagram (ERD) Overview
Tasks Table

Primary Key: id
Attributes: title, description, completed, created_at, updated_at
Relationships:
User: Each task can be associated with a user. This would typically involve a foreign key user_id in the tasks table linking to the id in the users table.
Category: If you include categories, each task might also include a foreign key category_id linking to the id in the categories table.
Users Table

Primary Key: id
Attributes: name, email, password, email_verified_at, remember_token
Relationships:
Tasks: A user can have many tasks, creating a one-to-many relationship from users to tasks.
Categories Table (Optional)

Primary Key: id
Attributes: name
Relationships:
Tasks: A category can have many tasks, creating a one-to-many relationship from categories to tasks.


```
[Users] (one to many)
   |
   | 1
   |
   | *
[Tasks] (many to one)
   |
   | *
   |
   | 1
[Categories]


One-to-Many Relationship:
Users (1) → Tasks (*): A single user can be associated with many tasks, but each task is associated with one user.

Many-to-One Relationship:
Categories (1) → Tasks (*) (Optional): A single category can be associated with many tasks, but each task is associated with one category.

```

# Understand the Requirements
Users: The application has users who will manage tasks.
Tasks: Each user will have their own set of tasks.
Categories (Optional): Tasks can be organized into categories.

# Determine How Entities Interact
Users and Tasks:
Requirement: Each user needs to manage multiple tasks.
Relationship:
A user can create and manage many tasks.
Each task is assigned to only one user.
Result: This is a one-to-many relationship (one user, many tasks). Each User record can be related to multiple Task records, but each Task record is related to only one User.

# Tasks and Categories (Optional):
Requirement: Tasks can be grouped or categorized for better organization.
Relationship:
A category can have multiple tasks.
Each task belongs to one category.
Result: This is also a one-to-many relationship (one category, many tasks). Each Category record can have multiple Task records, but each Task record is related to only one Category.


# POST ERD-CONFIRMATION: CREATE TABLES
```
php artisan make:migration create_tasks_table # Create tasks table, many-to-one with users and categories

php artisan make:migration create_categories_table # Create categories table, one-to-many relationship with tasks

php artisan make:migration create_user_task_table  # For many-to-many relationships between users and tasks

php artisan make:migration create_users_table # ???

// migrate specific table example
php artisan migrate --path=/database/migrations/2024_08_22_020134_create_users_table.php

```

# CREATE MODELS
```
php artisan make:model Task ✅
php artisan make:model Category ✅
php artisan make:model User (automatically generated, not sure why) ✅

Task is used for managing individual tasks and their attributes, while UserTask is used to manage the relationships between users and tasks, especially in cases where tasks might be associated with multiple users or have specific roles or permissions.


file path: todoApp-BE\app\Models
```

# Check Migration Status

```
php artisan migrate:status

Access psql to see database and tables via psql -h aws-0-ap-southeast-1.pooler.supabase.com -p 6543 -d postgres -U postgres.kczbkbczlzoqrmqxfant

TERMNIAL CONSOLE EXAMPLE:

aldrich@MMIntern2:~/todoFS/todoApp-BE$ psql -h aws-0-ap-southeast-1.pooler.supabase.com -p 6543 -d postgres -U postgres.kczbkbczlzoqrmqxfant
Password for user postgres.kczbkbczlzoqrmqxfant: 
psql (14.13 (Ubuntu 14.13-0ubuntu0.22.04.1), server 15.6)WARNING: psql major version 14, server major version 15. 
         Some psql features might not work.
SSL connection (protocol: TLSv1.3, cipher: TLS_AES_256_GCM_SHA384, bits: 256, compression: off)
Type "help" for help.

postgres=> \dt
           List of relations
 Schema |    Name    | Type  |  Owner
--------+------------+-------+----------
 public | categories | table | postgres
 public | migrations | table | postgres
 public | tasks      | table | postgres
 public | user_task  | table | postgres
(4 rows)

postgres=>
```

# DB Table Queries
```

### PSQL QUERY ###

View Table Data:
To view data in a table, you can use SQL queries

SELECT * FROM tasks;
SELECT * FROM categories;
SELECT * FROM user_task;

### TINKER QUERY ### (add task via tinker)

php artisan tinker

examples:
$task = \App\Models\Task::find(1);
$category = \App\Models\Category::all();

```

# Tinker push data into Supabase

```
// TASK ✅
use App\Models\Task;

$task = Task::create([
    'title' => 'tinker test',
    'description' => 'tinker test',
    'status' => 'complete',
    'priority' => 'low',
    'due_date' => now(),
]);

$task;

// USER ✅
use App\Models\User;

$user = User::create([
     'name' => 'test',
     'email' => 'test@gmail',
     'password' => 'sigma123',
]);

$user;

```

# SUPABASE
```
see active connections !


SELECT
  pid,
  datname,
  usename,
  application_name,
  client_addr
FROM
  pg_stat_activity
WHERE
  datname = 'todoApp-BE';



delete active connection !

-- Replace 'your_database_name' with your actual database name
SELECT pg_terminate_backend(6665)
FROM pg_stat_activity
WHERE datname = 'todoApp-BE'
  AND pid <> pg_backend_pid();


### TERMINAL PEEK SUPABASE ###
psql "host=aws-0-ap-southeast-1.pooler.supabase.com port=6543 dbname=postgres user=postgres.acvjeoqaldhqpmndncjy password=xxx* sslmode=disable"

TO SEE DATA STRUCTURE:
\l
\d tasks
\d users

TO SEE DATA INSIDE DATATABLE (sql query):
SELECT * FROM tasks;
SELECT * FROM users;


```

# SEEDERS (to populate your database with initial data)

```
create fake data for development using seeders (reflect model and migration table)

// run spefic seeder
php artisan db:seed --class=UsersTableSeeder
php artisan db:seed --class=TaskTableSeeder
php artisan db:seed --class=CategoriesTableSeeder

// run all seeders
php artisan db:seed

```

# Controller Implementation (interact with model, set up CRUD operation)

```
php artisan make:controller TaskController --resource


### Define Routes @ routes/web.php if you build laravel FS, if it's react you do routes/api.php ###

Go to api.php: 

use App\Http\Controllers\TaskController;
// use prefix for simple app, use middleware for more control, security and consistency i.e authentication
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

```

# TESTING (unite test or POSTMAN)

```
Unit Testing: Automate route and controller testing using Laravel’s built-in testing tools.

Manual Testing: Use tools like Postman or cURL to interact with your API manually and verify responses.

UNIT TESTING ROUTES
php artisan make:test TaskTest

after created, go to tests/Feature/TaskTest.php (define what you need)

once finished, run php artisan test

for specific test, i.e run php artisan test --filter test_can_retrieve_all_tasks


NOTE: To clear confusion the PREFIX "tasks".When you use  Route::get('/', [TaskController::class, 'index'])->name('tasks.index');, 
it means '/tasks'

in the same way
Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');,
it means '/tasks/{task}

php artisan migrate:fresh
php artisan db:seed (or php artisan db:seed --class=TaskTableSeeder)
php artisan tinker
>>> App\Models\Task::all();

psql "host=aws-0-ap-southeast-1.pooler.supabase.com port=6543 dbname=postgres user=postgres.acvjeoqaldhqpmndncjy password=xxx* sslmode=disable"

SELECT * FROM tasks;

you should expect these:
aldrich@MMIntern2:~/mmTodoFs/mmtodobe$ php artisan tinker
PHP Warning:  PHP Startup: Unable to load dynamic library 'mysqli' (tried: /usr/lib/php/20220829/mysqli (/usr/lib/php/20220829/mysqli: cannot open shared object file: No such file or directory), /usr/lib/php/20220829/mysqli.so (/usr/lib/php/20220829/mysqli.so: undefined symbol: mysqlnd_global_stats)) in Unknown on line 0
PHP Warning:  PHP Startup: Unable to load dynamic library '/path/to/extension/mysqli.so' (tried: /path/to/extension/mysqli.so (/path/to/extension/mysqli.so: cannot open shared object file: No such file or directory), /usr/lib/php/20220829///path/to/extension/mysqli.so.so (/usr/lib/php/20220829///path/to/extension/mysqli.so.so: cannot open shared object file: No such file or directory)) in Unknown on line 0
PHP Warning:  PHP Startup: Unable to load dynamic library 'pdo_mysql' (tried: /usr/lib/php/20220829/pdo_mysql (/usr/lib/php/20220829/pdo_mysql: cannot open shared object file: No such file or directory), /usr/lib/php/20220829/pdo_mysql.so (/usr/lib/php/20220829/pdo_mysql.so: undefined symbol: pdo_parse_params)) in Unknown on line 0
PHP Warning:  PHP Startup: Unable to load dynamic library 'pdo_pgsql' (tried: /usr/lib/php/20220829/pdo_pgsql (/usr/lib/php/20220829/pdo_pgsql: cannot open shared object file: No such file or directory), /usr/lib/php/20220829/pdo_pgsql.so (/usr/lib/php/20220829/pdo_pgsql.so: undefined symbol: pdo_parse_params)) in Unknown on line 0
PHP Warning:  Module "mbstring" is already loaded in Unknown on line 0
PHP Warning:  Module "pgsql" is already loaded in Unknown on line 0
Psy Shell v0.12.4 (PHP 8.2.22 — cli) by Justin Hileman
> App\Models\Task::all();
= Illuminate\Database\Eloquent\Collection {#5988
    all: [
      App\Models\Task {#5990
        id: 1,
        title: "test",
        description: "test",
        status: "incomplete",
        due_date: "2024-08-24",
        priority: "low",
        created_at: "2024-08-23 08:39:06",
        updated_at: "2024-08-23 08:39:06",
      },
      App\Models\Task {#5991
        id: 2,
        title: "test1",
        description: "test1",
        status: "incomplete",
        due_date: "2024-08-25",
        priority: "medium",
        created_at: "2024-08-23 08:39:06",
        updated_at: "2024-08-23 08:39:06",
      },
      App\Models\Task {#5992

[14]+  Stopped                 php artisan tinker
aldrich@MMIntern2:~/mmTodoFs/mmtodobe$ psql "host=aws-0-ap-southeast-1.pooler.supabase.com port=6543 dbname=postgres user=postgres.acvjeoqaldhqpmndncjy password=xxx* sslmode=disable"
psql (14.13 (Ubuntu 14.13-0ubuntu0.22.04.1), server 15.6)
WARNING: psql major version 14, server major version 15.
         Some psql features might not work.
Type "help" for help.

postgres=> SELECT * FROM tasks;
 id | title | description |   status   |  due_date  | priority |     created_at      |     updated_at      
----+-------+-------------+------------+------------+----------+---------------------+---------------------
  1 | test  | test        | incomplete | 2024-08-24 | low      | 2024-08-23 08:39:06 | 2024-08-23 08:39:06
  2 | test1 | test1       | incomplete | 2024-08-25 | medium   | 2024-08-23 08:39:06 | 2024-08-23 08:39:06
  3 | test2 | test2       | incomplete | 2024-08-26 | medium   | 2024-08-23 08:39:06 | 2024-08-23 08:39:06
(3 rows)

postgres=> 

```


