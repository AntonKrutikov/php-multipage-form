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

Look at passenger_form_*.php files

3. Mysql fix

DELETE from bxhs_pax
ALTER TABLE bxhs_pax MODIFY COLUMN p_id bigint(20) AUTO_INCREMENT PRIMARY KEY

ALTER TABLE `bxhs_cust` modify column `c_pax_cnt` SMALLINT(6) NULL
ALTER TABLE `bxhs_cust` modify column `c_email` VARCHAR(30) NULL

ALTER TABLE `bxhs_bookingagent` MODIFY COLUMN ba_contctno varchar(20) null
ALTER TABLE `bxhs_bookingagent` MODIFY COLUMN ba_country_code varchar(7) null