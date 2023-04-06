<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Installation

Follows the below step to setup project:

- Add database details in .env file.
- Run **composer update** command for Laravel packages install.
- Run **php artisan migrate** command for database migration. If you need dummy data in database run **php artisan migrate --seed** command instead of **php artisan migrate** command.
- Run **php artisan passport:install** command for Laravel Passport encryption keys generation.
- Run **npm install** command for NPM packages install.
- Run **npm run dev** command for complied VueJS code.
- [*Optional*] Add mailing credentials in .env for Forgot Password and Any email send functionality.
- [*Optional*] Add pusher credentials in .env for Broadcasting functionality.
- Run **php artisan serve** to run project.

## Excel-sheet fields description

- A1 contains **table** text.
- B1 contains table name.
- [*Optional*] C1 contains Broadcasting text. Like, **broadcast** as **Public Broadcasting**, **private_broadcast** as **Private Broadcasting** {It means this module Public or Private Broadcasting is apply. }
- [*Optional*] D1 contains Recaptcha text. Like, **captcha** {It means this module APIs is Google Captcha verification code is add in request. }
- [*Optional*] E1 contains Module No CRUD text. Like **no_crud** { It means this module CRUD API is not creating when Admin Panel is generated. }

#### List of excel-sheet column as per below sequence

- column
- type
- length
- is_null
- is_unique
- is_index
- constraints
- comments
- validation_rule
- is_light
- sortable
- casts
- exportable
- importable
- foreign_table
- foreign_key
- foreign_export_key
- is_hidden
- is_file
- is_in_listing
- is_input_form
- is_remove_in_edit_form
- ui_component
- is_in_log

##### column:

- Primary Key name should be **id**.
- Foreign Key name end with **_id**.
- If your **type** is **hasMany** than you have to parse **singular_foreign_table_name|current_table_foreign_id|foreign_table_column_name** format detail. Like, **product_tag|product_id|tag**
- If your **type** is **belongsToMany** than you have to parse **singular_foreign_table_name|current_table_foreign_id|foreign_table_column_id** format detail. Like, **supplier|product_id|supplier_id**

##### type:

- Types list:

**tinyint**, **smallint**, **mediumint**, **int**, **bigint**,
**char**, **varchar**, **mediumtext**, **text**, **longtext**, 
**enum**, **set**, **decimal**, **float**, **double**, **date**, 
**datetime**, **dateTime**, **timestamp**,  **time**, **year**, 
**boolean**, **binary**, **geometry**, **geometrycollection**, 
**point**, **multipoint**, **linestring**, **multilinestring**, 
**polygon**, **multipolygon**, **json**

##### length:

- Length of column. Like id as int 10 digits limit or name as varchar 191 characters limit and so on.

##### is_null:

-  If you accept NULL value for specific column parse **Y**.

##### is_unique:

-  If you make UNIQUE for specific column parse **Y**.

##### is_index:

-  If you add INDEX for specific column parse **Y**.  

##### constraints:

-  Only **PK** and **FK** value is contains in this column.

##### comments:

- Write column comment in this column. Like, **Roles table ID**, **0 => 'Female', 1 => 'Male'**
- **0 => 'Female', 1 => 'Male'** this format is must required for enum values.

##### validation_rule:

- Write column laravel validation rule. Like, **required|max:191**
- When type is **hasMany** and **belongsToMany** then you have to write two rules concate with **#** operator. Like, **required|array#&#required|exists:suppliers,id,deleted_at,NULL**

##### is_light:

-  If you need to column in Is-Light response parse **Y**. 

##### sortable:

-  Add column in search and sorting parse **Y**. 

##### casts:

-  Column value casting. Like, **string**

##### exportable:

-  Add column in export parse **Y**. 

##### importable:

-  Add column in import parse **Y**.

##### foreign_table:

-  If you add foreign key in current table parse it's table name. Like, suppliers.

##### foreign_key:

- If you add foreign key in current table parse it's column-name which is used in search/sort.

##### foreign_export_key:

- If you add foreign key in current table parse it's column-name which is used in export data.

##### hidden:

-  Hide column in response parse **Y**.

##### is_file:

-  If column use for file uploading parse **Y**.

##### is_in_listing:

-  If you need to display current column in listing parse **Y**.

##### is_input_form:

-  If you need to add current column in Create Form parse **Y**.

##### is_remove_in_edit_form:

-  If you need to remove current column in Edit Form parse **Y**.

##### ui_component:

- You need to choose which component will be use in Create/Update Form. 
- List of components are: 

**text**, **textarea**, **dropdown**, **radio**, **checkbox**, **wysiwyg**,
**multiselect_dropdown**, **auto_complete**, **date_picker**, **file_upload**, 
**chips**, **switch**
