## Description

0. 
    Use `require_once(__DIR__.'/../../private/initialize.php')` in all your files, not reinitialize mysqli credentials in each, easy to change in future;

1. Register customer account

    `$stmt->execute();` Can return true or false

    You need to check if statement executes or not

    To see last mysqli error use `$stmt->error`

    You get this error: `Field 'id' doesn't have a default value`

    That mean accounts table has column id without autoincrement for example.

    Fix: `ALTER TABLE accounts MODIFY id int(11) AUTO_INCREMENT;`

2. Input personal information (profile info)