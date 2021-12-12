<?php
require_once(__DIR__ . '/initialize.php');

function find_all_passengers($p_id)
{
	global $con;

	$sql = "SELECT p.p_fname, p.p_mname, p.p_lname, p.p_bdate, p.p_nationality, p.p_gdr, p.p_passportno, p.p_passport_exp_date FROM bxhs_pax p INNER JOIN bxhs_cust c ON p.p_id=c.p_id WHERE c.c_id=$p_id";
	$sql .= " ORDER BY p.p_fname";

	$result = mysqli_query($con, $sql);
	echo (mysqli_error($con));
	return $result;
}

function get_customer_info($cust_id)
{
	global $con;

	$sql = <<<EOD
	SELECT 
		c.c_street, 
		c.c_city, 
		c.c_cntry, 
		c.c_zip, 
		c.c_email, 
		c.c_ctrycode, 
		c.c_contctno, 
		c.c_pax_cnt, 
		c.c_emc_fname, 
		c.c_emc_lname, 
		c.c_emc_ctrycode, 
		c.c_emc_contctno, 
		c.c_type,
		m.mbr_name,
		m.assc_al,
		a.ba_name,
		a.ba_country_code,
		a.ba_contctno,
		a.web_addr,
		DATE_FORMAT(m.mbr_start_date,'%d/%m/%Y') mbr_start_date,
		DATE_FORMAT(m.mbr_end_date,'%d/%m/%Y') mbr_end_date
		FROM bxhs_cust c
		LEFT JOIN bxhs_member m
		ON c.p_id = m.p_id AND m.p_type='C'
		LEFT JOIN bxhs_bookingagent a
		ON c.p_id = a.p_id AND a.p_type='C'
	EOD;
	$sql .= "WHERE c.p_type='C' AND c.c_id = '" . $cust_id . "'";

	$result = mysqli_query($con, $sql);

	return $result;
}

function update_customer_info($id, $post)
{
	global $con;

	$con->begin_transaction();

	try {
		/* CUSTOMER INFO PART */
		//INSERT IF NOT EXISTS
		if ($stmt = $con->prepare(
			<<<EOD
			INSERT INTO bxhs_cust (p_id, c_id, c_street, c_city, c_cntry, c_zip, c_email, c_ctrycode, c_contctno,
			c_pax_cnt, c_emc_fname, c_emc_lname, c_emc_ctrycode, c_emc_contctno, c_type, p_type)
			SELECT $id,$id,?,?,?,?,?,?,?,?,?,?,?,?,?,'C' FROM DUAL
			WHERE NOT EXISTS (SELECT c_id FROM bxhs_cust WHERE c_id=$id and p_type='C');
		EOD
		)) {
			$stmt->bind_param(
				'sssssssssssss',
				$post['c_street'],
				$post['c_city'],
				$post['c_cntry'],
				$post['c_zip'],
				$post['c_email'],
				$post['c_ctrycode'],
				$post['c_contctno'],
				$post['c_pax_cnt'],
				$post['c_emc_fname'],
				$post['c_emc_lname'],
				$post['c_emc_ctrycode'],
				$post['c_emc_contctno'],
				$post['c_type']
			);
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception($stmt->error);
			}
		} else {
			throw new mysqli_sql_exception($stmt->error);
		}
		//UPDATE IF ALREADY EXISTS
		if ($stmt = $con->prepare(<<<EOD
			UPDATE bxhs_cust
			SET
				c_street = ?,
				c_city = ?,
				c_cntry = ?,
				c_zip = ?,
				c_email = ?,
				c_ctrycode = ?,
				c_contctno = ?,
				c_pax_cnt = ?,
				c_emc_fname = ?,
				c_emc_lname = ?,
				c_emc_ctrycode = ?,
				c_emc_contctno = ?,
				c_type = ?
			WHERE c_id = $id and p_type='C'
			EOD)) {
			$stmt->bind_param(
				'sssssssssssss',
				$post['c_street'],
				$post['c_city'],
				$post['c_cntry'],
				$post['c_zip'],
				$post['c_email'],
				$post['c_ctrycode'],
				$post['c_contctno'],
				$post['c_pax_cnt'],
				$post['c_emc_fname'],
				$post['c_emc_lname'],
				$post['c_emc_ctrycode'],
				$post['c_emc_contctno'],
				$post['c_type']
			);
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception($stmt->error);
			}
		}

		/* MEMBER PART */
		if ($post['c_type'] == 'M') {
			//INSERT IF NOT EXIST
			$stmt = $con->prepare(
				<<<EOD
			INSERT INTO bxhs_member(p_id, mbr_name, assc_al, mbr_start_date, mbr_end_date, p_type)
			SELECT $id, ?, ?, ?, ?, 'C' FROM DUAL
			WHERE NOT EXISTS (SELECT p_id FROM bxhs_member WHERE p_id=$id AND p_type='C');
			EOD
			);
			$stmt->bind_param(
				'ssss',
				$post['mbr_name'],
				$post['assc_al'],
				dateMysql($post['mbr_start_date']),
				dateMysql($post['mbr_end_date'])
			);
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception($stmt->error);
			}
			//ELSE UPDATE
			if ($stmt->affected_rows == 0) {
				$stmt = $con->prepare(<<<EOD
				UPDATE bxhs_member
				SET
					mbr_name = ?,
					assc_al = ?,
					mbr_start_date = ?,
					mbr_end_date = ?
				WHERE
					p_id = $id
					AND p_type='C'
				EOD);
				$stmt->bind_param(
					'ssss',
					$post['mbr_name'],
					$post['assc_al'],
					dateMysql($post['mbr_start_date']),
					dateMysql($post['mbr_end_date'])
				);
				if (!$stmt->execute()) {
					throw new mysqli_sql_exception($stmt->error);
				}
			}

			//DELETE associated agent info
			$stmt = $con->prepare("DELETE FROM bxhs_bookingagent WHERE p_id=? AND p_type='C'");
			$stmt->bind_param('s', $id);
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception($stmt->error);
			}
		}

		/* AGENT PART */
		if ($post['c_type'] == 'B') {
			//INSERT IF NOT EXIST
			if ($stmt = $con->prepare(
				<<<EOD
			INSERT INTO bxhs_bookingagent(p_id, ba_name,ba_country_code,ba_contctno,web_addr,p_type)
			SELECT $id, ?, ?, ?, ?, 'C' FROM DUAL
			WHERE NOT EXISTS (SELECT p_id FROM bxhs_bookingagent WHERE p_id=$id AND p_type='C');
			EOD
			)) {
				$stmt->bind_param(
					'ssss',
					$post['ba_name'],
					$post['ba_country_code'],
					$post['ba_contctno'],
					$post['web_addr']
				);
				if (!$stmt->execute()) {
					throw new mysqli_sql_exception($stmt->error);
				}
			} else {
				throw new mysqli_sql_exception($stmt->error);
			}
			//UPDATE IF ALREADY EXISTS
			if ($stmt->affected_rows == 0) {
				if ($stmt = $con->prepare(<<<EOD
				UPDATE bxhs_bookingagent
				SET
					ba_name = ?,
					ba_country_code = ?,
					ba_contctno = ?,
					web_addr =?
				WHERE p_id=$id and p_type='C'
				EOD)) {
					$stmt->bind_param(
						'ssss',
						$post['ba_name'],
						$post['ba_country_code'],
						$post['ba_contctno'],
						$post['web_addr']
					);
					if (!$stmt->execute()) {
						throw new mysqli_sql_exception($stmt->error);
					}
				} else {
					throw new mysqli_sql_exception($stmt->error);
				}
			}
			//DELETE MEMBER INFO IF AGENT PRESENT

			$stmt = $con->prepare("DELETE FROM bxhs_member WHERE p_id=? AND p_type='C'");
			$stmt->bind_param('s', $id);
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception($stmt->error);
			}
		}

		/* IF C OPTION SELECTED */
		if ($post['c_type'] == 'C') {
			//DELETE MEMBER 

			$stmt = $con->prepare("DELETE FROM bxhs_member WHERE p_id=? AND p_type='C'");
			$stmt->bind_param('s', $id);
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception($stmt->error);
			}

			//DELETE AGENT
			$stmt = $con->prepare("DELETE FROM bxhs_bookingagent WHERE p_id=? AND p_type='C'");
			$stmt->bind_param('s', $id);
			if (!$stmt->execute()) {
				throw new mysqli_sql_exception($stmt->error);
			}
		}

		$con->commit();  //DONT FORGET COMMIT

	} catch (mysqli_sql_exception $exception) {
		echo ('ROLLLBAC');
		$con->rollback();
		echo ($exception->getMessage());
		// throw $exception;
	}
}
