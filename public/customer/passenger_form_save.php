<?php
require_once(__DIR__ . '/../../private/initialize.php');
session_start();
//Let save data to DB - All or nothing
function dateMysql($date)
{
    return DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d H:i:s');
}
$con->begin_transaction();
try {
    //General user info "bxhs_pax"
    // Fix schema before:
    // DELETE from bxhs_pax
    // ALTER TABLE bxhs_pax MODIFY COLUMN p_id bigint(20) AUTO_INCREMENT PRIMARY KEY
    if ($stmt = $con->prepare('INSERT INTO bxhs_pax (p_fname, p_mname, p_lname, p_bdate, p_nationality, p_gdr, p_passportno, p_passport_exp_date, p_type) values(?,?,?,?,?,?,?,?,?)')) {
        $stmt->bind_param(
            'sssssssss',
            $_SESSION['values']['firstname'],
            $_SESSION['values']['middlename'],
            $_SESSION['values']['lastname'],
            dateMysql($_SESSION['values']['birthdate']),
            $_SESSION['values']['nationality'],
            $_SESSION['values']['gender'],
            $_SESSION['values']['passportno'],
            dateMysql($_SESSION['values']['passportexp']),
            $_SESSION['values']['status']
        );


        if (!$stmt->execute()) {
            throw new mysqli_sql_exception($stmt->error);
        }

        $passenger_id = $stmt->insert_id;
    } else {
        throw new mysqli_sql_exception($con->error);
    }

    //Address info
    //Fix sql:
    // ALTER TABLE `bxhs_cust` modify column `c_pax_cnt` SMALLINT(6) NULL
    // ALTER TABLE `bxhs_cust` modify column `c_email` VARCHAR(30) NULL
    if ($stmt = $con->prepare('INSERT INTO bxhs_cust (p_id, p_type, c_id, c_street, c_city, c_cntry, c_zip, c_ctrycode, c_contctno, c_emc_fname, c_emc_lname, c_emc_ctrycode, c_emc_contctno, c_type) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)')) {
        $stmt->bind_param(
            'isssssssssssss',
            $passenger_id,
            $_SESSION['values']['status'],
            $_SESSION['id'], //NOT good schema in table or I smthin missunterstood
            $_SESSION['values']['street'],
            $_SESSION['values']['city'],
            $_SESSION['values']['country'],
            $_SESSION['values']['zipcode'],
            $_SESSION['values']['phone_country_code'],
            $_SESSION['values']['phone_number'],
            $_SESSION['values']['em_firstname'],
            $_SESSION['values']['em_lasttname'],
            $_SESSION['values']['em_phone_country_code'],
            $_SESSION['values']['em_phone_number'],
            $_SESSION['values']['customer_type']
        );

        if (!$stmt->execute()) {
            echo ($stmt->error);
            throw new mysqli_sql_exception($stmt->error);
        }
    } else {
        throw new mysqli_sql_exception($con->error);
    }

    //Customer type additional
    if ($_SESSION['values']['customer_type'] == 'M') {
        if ($stmt = $con->prepare('INSERT INTO bxhs_member (p_id, mbr_name, assc_al, mbr_start_date, mbr_end_date, p_type) values (?,?,?,?,?,?)')) {
            $stmt->bind_param(
                'isssss',
                $passenger_id,
                $_SESSION['values']['member_name'],
                $_SESSION['values']['associated_airline'],
                dateMysql($_SESSION['values']['membership_start_date']),
                dateMysql($_SESSION['values']['membership_end_date']),
                $_SESSION['values']['status'] //p_type from firs, bad scheme too
            );
            if (!$stmt->execute()) {
                throw new mysqli_sql_exception($stmt->error);
            }
        } else {
            throw new mysqli_sql_exception($con->error);
        }
    } else if ($_SESSION['values']['customer_type'] == 'B') {
        //Fix sql scheme
        // ALTER TABLE `bxhs_bookingagent` MODIFY COLUMN ba_contctno varchar(20) null
        // ALTER TABLE `bxhs_bookingagent` MODIFY COLUMN ba_country_code varchar(7) null
        if ($stmt = $con->prepare('INSERT INTO bxhs_bookingagent (p_id, ba_name, ba_contctno, web_addr, p_type) values (?,?,?,?,?)')) {
            $stmt->bind_param(
                'issss',
                $passenger_id,
                $_SESSION['values']['agent_name'],
                $_SESSION['values']['agent_contact_number'],
                $_SESSION['values']['web_address'],
                $_SESSION['values']['status'] //p_type from firs, bad scheme too
            );
            if (!$stmt->execute()) {
                throw new mysqli_sql_exception($stmt->error);
            }
        } else {
            throw new mysqli_sql_exception($con->error);
        }
    }
    
    //Insuurance
    if ($stmt = $con->prepare('INSERT INTO bxhs_pax_ins (purc_date, p_id, p_type, ins_id) values (CURDATE(), ?, ?, ?)')) {
        $stmt->bind_param(
            'iss',
            $passenger_id,
            $_SESSION['values']['status'], //p_type from firs, bad scheme too
            $_SESSION['values']['insurance']
        );
        if (!$stmt->execute()) {
            throw new mysqli_sql_exception($stmt->error);
        }
    } else {
        throw new mysqli_sql_exception($con->error);
    }

    $con->commit();
    //echo("DATA COMPLETLY SAVED");
    header("Location: home.php");
    exit();
} catch (mysqli_sql_exception $exception) {
    $con->rollback();

    echo ($exception->getMessage());

    throw $exception;
}

