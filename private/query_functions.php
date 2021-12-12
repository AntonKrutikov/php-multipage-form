<?php

	function find_all_passengers($p_id) {
		global $con;

		$sql = "SELECT p.p_fname, p.p_mname, p.p_lname, p.p_bdate, p.p_nationality, p.p_gdr, p.p_passportno, p.p_passport_exp_date FROM bxhs_pax p INNER JOIN bxhs_cust c ON p.p_id=c.p_id WHERE c.c_id=$p_id";
		$sql .= " ORDER BY p.p_fname";

		$result = mysqli_query($con, $sql);
		echo(mysqli_error($con));
		return $result;
	}

	function get_customer_info($cust_id) {
		global $con;

		$sql = "SELECT c_street, c_city, c_cntry, c_zip, c_email, c_ctrycode, c_contctno, c_pax_cnt, c_emc_fname, c_emc_lname, c_emc_ctrycode, c_emc_contctno FROM bxhs_cust ";
		$sql .= "WHERE c_id = '".$cust_id."'";

		$result = mysqli_query($con, $sql);
		return $result;
	}

?>