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
