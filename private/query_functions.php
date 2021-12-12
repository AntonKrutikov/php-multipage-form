<?php

	function find_all_passengers($c_id) {
		global $con;

		$c_id = $_SESSION['id'];

		$sql = <<<EOD
		SELECT 
			p.p_type,
			p.p_fname, 
			p.p_mname, 
			p.p_lname, 
			p.p_bdate, 
			p.p_nationality, 
			p.p_gdr, 
			p.p_passportno, 
			p.p_passport_exp_date 
		FROM bxhs_pax p
		LEFT JOIN bxhs_cust c
		on c.c_id=p.c_id
		WHERE p.c_id = $c_id
		EOD;
		$sql .= " ORDER BY p_fname";

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