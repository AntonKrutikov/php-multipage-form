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
			p.p_passport_exp_date,
			ins.ins_name,
			ins.cost_per_pax
		FROM bxhs_pax p
		LEFT JOIN bxhs_cust c
		on c.c_id=p.c_id
		LEFT JOIN bxhs_pax_ins pi
		on pi.p_id = p.p_id
		LEFT JOIN bxhs_ins ins
		on ins.ins_id = pi.ins_id
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

	function get_invoice($c_id) {
		global $con;
		$sql = <<<EOD
		SELECT 
			inv_id, 
			DATE_FORMAT(inv_date, '%d/%m/%Y') inv_date, 
			inv_amount,
			(SELECT pmt_id FROM bxhs_payment WHERE inv_id=i.inv_id LIMIT 1) pmt_id
		FROM bxhs_invoice i  WHERE c_id=$c_id
		EOD;
		$result = mysqli_query($con, $sql);
		return $result;
	}

	function callculate_invoice($c_id) {
		global $con;

		//INSERT IF NOT EXISTS
		if($stmt = $con->prepare(<<<EOD
			INSERT INTO bxhs_invoice (inv_id, inv_date, inv_amount, ins_id, c_id)
			SELECT (
				SELECT max(inv_id)+1 FROM bxhs_invoice),
				CURRENT_DATE(),
				(
					SELECT SUM(ins.cost_per_pax) 
					FROM bxhs_pax_ins i 
					INNER JOIN bxhs_pax p
					ON i.p_id=p.p_id
					INNER JOIN bxhs_ins ins
					on ins.ins_id = i.ins_id
					WHERE p.c_id = ?
				) inv_amount,
				10006,
				?
			FROM DUAL
			WHERE NOT EXISTS (SELECT 1 FROM bxhs_invoice WHERE c_id = ?)
			AND EXISTS (SELECT 1 FROM bxhs_pax WHERE c_id= ?)

		EOD)){
			$stmt->bind_param('iiii', $c_id, $c_id, $c_id, $c_id); //Add one i and $c_id
			if (!$stmt->execute()){
				throw new mysqli_sql_exception($stmt->error);
			}

		} else {
			throw new mysqli_sql_exception("Prepare statement error: {$stmt->error}");
		}
		if($con->affected_rows == 0) {
			if($stmt = $con->prepare(<<<EOD
			UPDATE bxhs_invoice
			SET 
				inv_amount = (
					SELECT SUM(ins.cost_per_pax) 
					FROM bxhs_pax_ins i 
					INNER JOIN bxhs_pax p
					ON i.p_id=p.p_id
					INNER JOIN bxhs_ins ins
					on ins.ins_id = i.ins_id
					WHERE p.c_id = ?
				),
				inv_date = CURRENT_DATE()
			WHERE c_id = ?
			EOD)){
				$stmt->bind_param('ii', $c_id, $c_id);
				if (!$stmt->execute()){
					throw new mysqli_sql_exception($stmt->error);
				}
			} else {
				throw new mysqli_sql_exception("Prepare statement error: {$stmt->error}");
			}
		}
	}

	function make_payment($c_id,$values) {
		global $con;

		if($stmt = $con->prepare(<<<EOD
			INSERT INTO bxhs_payment (pmt_id, pmt_date, pmt_amount, pmt_method, pmt_cardno, card_fname, card_lname, card_exp, 	card_exp_year, inv_id)
			SELECT 
				(SELECT max(pmt_id)+1 FROM bxhs_payment),
				CURRENT_DATE(),
				?,?,?,?,?,?,?,?
			FROM DUAL
			WHERE NOT EXISTS (SELECT 1 FROM bxhs_payment WHERE inv_id = ?)
		EOD)) {
			$stmt->bind_param(
				"sssssssss",
				$values['inv_amount'],
				$values['pmt_method'],
				$values['pmt_cardno'],
				$values['card_fname'],
				$values['card_lname'],
				$values['card_exp'],
				$values['card_exp_year'],
				$values['inv_id'],
				$values['inv_id']
			);
			if (!$stmt->execute()){
				throw new mysqli_sql_exception($stmt->error);
			}

		} else {
			throw new mysqli_sql_exception("Prepare statement error: {$stmt->error}");
		}

	}

?>