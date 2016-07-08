# BDClass-PHP-MYSQLI-MANAGEMENT
BD - Simple (easy-use) &amp; Powerfull database management class.


<h3>With SQL & XSS Injection Protection</h3>
<h3>And automatic email report to serveradmin (should be you)</h3>

#<h1>What is this?!</h1>

BDClass it's a PHP class to use in MySQLi management.<br/>
The currently version it's still a beta startup, but already works.<br/>
It's included in this class [SQL Injection and XSS Injection protection](https://github.com/filipealvesbcl/BDClass-PHP-MYSQLI-MANAGEMENT/blob/master/README.md#data-escape-sql-injection-and-xss-injection-protection) (for secure stuff).<br/>

#<h1>Manual</h1>
#<h2>Installation</h2>
Download the Class file: `BD.php` (From this GIT)<br/>
Insert into your project.<br/>
Call the file:
```PHP
require_once('includes/BD.php');
```
#<h2>Configuration</h2>
The configuration it's made by that constants:

```PHP
#Database Configs
const HOSTNAME = 'localhost';            // HOSTNAME (eg: '127.0.0.1' , 'filipealves.net')
const USERNAME = 'user';                 // DATABASE USER
const PASSWORD = 'password';             // USER PASSWORD
const DATABASE = 'db_example';           // DEFAULT DATABASE
const CHARSET  = 'utf8';                 // DATABASE CHARSET
#Error Reporting Configs
const NEED_CONSTRUCT = TRUE;             // REQUIRE OBJECT CREATION (TRUE or FALSE)
const ERROR_LANG  = 'PT';                // ERROR LANG (MUST BE ADDED IF NOT 'PT' OR 'EN')
const ERROR_LOG = TRUE;                  // SAVE ON APACHE ERROR LOG (TRUE or FALSE)
const NOTIFY_ADMIN_BY_EMAIL = TRUE;      // SEND ERRORS BY EMAIL TO THE WEBMASTER, 'ADM_EMAIL' MUST BE CONFIGURED FOR TRUE (TRUE or FALSE)
const ADM_EMAIL = 'filipealvesbcl@gmail.com';// WEBMASTER EMAIL FOR RECEIVE THE ERRORS
#Escape Configs
const ESCAPE_FLAGS = ENT_QUOTES;         // DATA ESCAPE FLAGS (RECOMENDED: ENT_QUOTES)
const ESCAPE_CHARSET = 'UTF-8';          // DATA ESCAPE CHARSET (RECOMENDED: SAME OF CHARSET ABOVE)
```

`HOSTNAME` => Database host (can be IP Address or DNS).<br/>
`USERNAME` => Database username.<br/>
`PASSWORD` => Database password.<br/>
`DATABASE` => Database name (On queries you can user another database name :).<br/>
`CHARSET`  => Database Charset.<br/>

`NEED_CONSTRUCT`         => Requires or Not Class Construct<br/>
`ERROR_LANG`             => Language of Errors (Currently only 'PT' or 'EN').<br/>
`ERROR_LOG`              => Send error to apache error log.<br/>
`NOTIFY_ADMIN_BY_EMAIL`  => Send error by email to admin email (below).<br/>
`ADM_EMAIL`              => Administrator email (can be you).<br/>

`ESCAPE_FLAGS`    => Data escape FLAGS (eg: ENT_QUOTES).<br/>
`ESCAPE_CHARSET`  => Data escape charset (eg: 'UTF-8').<br/>

#<h2>Functions</h2>

| Function Name                                   | Explanation                     | Example                     |
| ----------------------------------------------- | --------------------            | ---------------------------- |
| Constructor (all starts by here)                | Starts the connection. (OBJECT)          | ` $conn = new BD(); ` |
| Query                                           | Does some query.                | ` $conn->query('SELECT * ....'); ` |
| Fetch Array                                     | Returns the Associative and Numeric Array of a query. | ` $conn->fetch_array(); ` |
| Fetch Assoc                                     | Returns the Associative Array of a query. | ` $conn->fetch_assoc(); ` |
| Fetch Row | Returns the Numeric Array of a query. | ` $conn->fetch_row(); ` |
| Num Rows | Returns the number of Rows of a query. | ` $conn->num_rows(); ` |
| Last Inserted Id | Returns the last inserted ID of a query (if query was `INSERT` or `UPDATE`). | ` $conn->last_inserted_id(); ` |
| Close | Ends the connection to the database. | ` $conn->close(); ` |
| Escape | Escapes the string or array `STATIC` (DO NOT NEED CONSTRUCT). | ` $string = BD::escape($string); ` |
#<h2>Data escape (SQL Injection and XSS Injection protection)</h2>
To escape strings you should use: `BD::escape($string)`.

It's works too with arrays: `BD::escape($array)`.
<h5>Which arrays?!</h5>
Associative & Numeric arrays.<br/>
Normal array & multidimensional (up to 2 levels) arrays.
Eg:

```PHP
$array = [
  0 => 'Peter',
  1 => 'John',
  'no-name' => [
    0 => 'Mr',
    'hello' => 'bye'
  ]
];
```

<h5>Params</h5>
```PHP
function escape($data, $flags = self::ESCAPE_FLAGS)
```

`$data` => Your string or array.<br/>
`$flags`=> Php Flags [(Valid Flags)](http://php.net/manual/en/function.htmlspecialchars.php).


<h1>Example of ERROR</h1>
<img src="https://github.com/filipealvesbcl/BDClass-PHP-MYSQLI-MANAGEMENT/blob/master/BDClass_error_example.png?raw=true">
