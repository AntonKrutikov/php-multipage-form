<?php

	function find_all_passengers() {
		global $con;

		$sql = "SELECT p_fname, p_mname, p_lname, p_bdate, p_nationality, p_gdr, p_passportno, p_passport_exp_date FROM bxhs_pax ";
		$sql .= "ORDER BY p_fname";

		$result = mysqli_query($con, $sql);
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