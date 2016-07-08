<?php

/**
 * Example of usage of BDClass.
 */

require_once('includes/BD.php');

/**********************/

$conn = new BD();

$conn->query("SELECT username, email FROM users;");

$users = [];

while($row = $conn->fetch_assoc()){
    $users[] = $row;
}

print_r($users);

/* OUTPUTS:

Array
(
    [0] => Array
        (
            [username] => chbcl
            [email] => filipealvesbcl@gmail.com
        )

    [1] => Array
        (
            [username] => foo
            [email] => foo@something.com
        )

    [2] => Array
        (
            [username] => John
            [email] => john@dohn.com
        )

)

*/

print $conn->num_rows();

/* OUTPUTS:

3

*/

$conn->query("INSERT INTO users (username, password) VALUES ('hello','hello@world.com');");

print $conn->last_inserted_id();

/* OUTPUTS:

4

*/



$conn->close();






$str = "<img src='http://example.com/badimage.png'> <strong>hello</strong>";

print BD::escape($str);

/* OUTPUTS:

html view: <img src='http://example.com/badimage.png'> <strong>hello</strong>
html source: &lt;img src=&#039;http://example.com/badimage.png&#039;&gt; &lt;strong&gt;hello&lt;/strong&gt;gt;

*/

$str = "' OR 1 = '1";

print BD::escape($str);

/* OUTPUTS:

html view: ' OR 1 = '1
html source: &#039; OR 1 = &#039;1

*/

$array = [
    0 => "<b onmouseover=alert('Wufff!')>click me!</b>",
    1 => "<strong>CLEAN</strong>"
];

print_r(BD::escape($array));

/* OUTPUTS:

html view:
Array
(
    [0] => <b onmouseover=alert('Wufff!')>click me!</b>
    [1] => <strong>CLEAN</strong>
)

html source:
Array
(
    [0] => &lt;b onmouseover=alert(&#039;Wufff!&#039;)&gt;click me!&lt;/b&gt;
    [1] => &lt;strong&gt;CLEAN&lt;/strong&gt;
)

*/
