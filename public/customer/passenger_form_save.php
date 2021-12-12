<?php
require_once(__DIR__ . '/../../private/initialize.php');
require_once(__DIR__ . '/../../private/functions.php');
session_start();

$c_id = $_SESSION['id'];
$con->begin_transaction();
try {
    //General passenger info "bxhs_pax"
    if ($stmt = $con->prepare('INSERT INTO bxhs_pax (p_fname, p_mname, p_lname, p_bdate, p_nationality, p_gdr, p_passportno, p_passport_exp_date, p_type) values(?,?,?,?,?,?,?,?,"P")')) {
        $stmt->bind_param(
            'ssssssss',
            $_SESSION['values']['firstname'],
            $_SESSION['values']['middlename'],
            $_SESSION['values']['lastname'],
            dateMysql($_SESSION['values']['birthdate']),
            $_SESSION['values']['nationality'],
            $_SESSION['values']['gender'],
            $_SESSION['values']['passportno'],
            dateMysql($_SESSION['values']['passportexp'])
        );


        if (!$stmt->execute()) {
            throw new mysqli_sql_exception($stmt->error);
        }

        $passenger_id = $stmt->insert_id;
    } else {
        throw new mysqli_sql_exception($con->error);
    }

    //Address info
    //Copy FROM customer
    if ($stmt = $con->prepare(<<<EOD
    INSERT INTO 
    bxhs_cust (p_id, p_type, c_id, c_street, c_city, c_cntry, c_zip, c_ctrycode, c_contctno, 
        c_emc_fname, c_emc_lname, c_emc_ctrycode, c_emc_contctno, c_type) 
    SELECT ?, 'P', ?, 
    c_street, c_city, c_cntry, c_zip, c_ctrycode, c_contctno, c_emc_fname, c_emc_lname, 
    c_emc_ctrycode, c_emc_contctno, c_type
    FROM bxhs_cust WHERE c_id=? and p_type='C';
    EOD)) {
        $stmt->bind_param(
            'iii',
            $passenger_id,
            $c_id,
            $c_id
        );

        if (!$stmt->execute()) {
            throw new mysqli_sql_exception($stmt->error);
        }
    } else {
        throw new mysqli_sql_exception($stmt->error);
    }
    
    //Insuurance
    if ($stmt = $con->prepare('INSERT INTO bxhs_pax_ins (purc_date, p_id, p_type, ins_id) values (CURDATE(), ?, "P", ?)')) {
        $stmt->bind_param(
            'ii',
            $passenger_id,
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
    $_SESSION['values'] = [];
    header("Location: home.php");
    exit();
} catch (mysqli_sql_exception $exception) {
    $con->rollback();

    echo ($exception->getMessage());

    throw $exception;
}

