<?php
	require_once 'includes/all.php';

	if (!is_logged_in()) {
		header("Location: signin.php");
		exit(0);
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$db = connect_db();
		$user = get_user($db, get_logged_in_user_id());

		if (!password_verify($_POST["oldPassword"], $user["password_hash"])) {
			$_SESSION["flash_errors"] = array('Old password did not match current password');
		}else {
			$stmt = $db->prepare("UPDATE users SET password_hash = :password_hash WHERE users.id = :usrId");

			$stmt->bindValue("password_hash", password_hash($_POST["newPassword"], PASSWORD_BCRYPT));
			$stmt->bindValue("usrId", get_logged_in_user_id());
			$stmt->execute();
		}
	}
	header("Location: profile_edit.php");
