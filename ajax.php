<?php
if (isset($_POST['key'])) {
	$conn = new mysqli('localhost', 'root', '', 'users');

	if ($_POST['key'] == 'getRowData') {
		$rowID = $conn->real_escape_string($_POST['rowID']);
		$sql = $conn->query("SELECT breaker, lunch, dinner, anythingElse FROM foods WHERE id='$rowID'");
		$data = $sql->fetch_array();
		$jsonArray = array(
			'breaker' => $data['breaker'],
			'lunch' => $data['lunch'],
			'dinner' => $data['dinner'],
			'anythingElse' => $data['anythingElse'],
		);

		exit(json_encode($jsonArray));
	}

	if ($_POST['key'] == 'getExistingData') {
		$start = $conn->real_escape_string($_POST['start']);

		$limit = $conn->real_escape_string($_POST['limit']);

		$sql = $conn->query("SELECT id, breaker, lunch, dinner, anythingElse FROM foods LIMIT $start, $limit");

		if ($sql->num_rows > 0) {
			$response = "";
			while ($data = $sql->fetch_array()) {
				$response .= '
						<tr>
							<td>' . $data["id"] . '</td>
							<td id="breaker_' . $data["id"] . '">' . $data["breaker"] . '</td>
							<td id="lunch_' . $data["id"] . '">' . $data["lunch"] . '</td>
							<td id="dinner_' . $data["id"] . '">' . $data["dinner"] . '</td>
							<td id="anythingElse_' . $data["id"] . '">' . $data["anythingElse"] . '</td>
							<td>
								<input type="button" onclick="viewRedit(' . $data["id"] . ', \'edit\')" value="Edit" class="btn btn-primary">
								<input type="button" value="View" class="btn">
								<input type="button" value="Delete" class="btn btn-danger">
							</td>
						</tr>
					';
			}
			exit($response);
		} else {
			exit('reachedMax');
		}

	}

	/*CONNECT DATA BASE*/
	$breaker = $conn->real_escape_string($_POST['breaker']);
	$lunch = $conn->real_escape_string($_POST['lunch']);
	$dinner = $conn->real_escape_string($_POST['dinner']);
	$anythingElse = $conn->real_escape_string($_POST['anythingElse']);
	$rowID = $conn->real_escape_string($_POST['rowID']);

	if ($_POST['key'] == 'updateRow') {
		$conn->query("UPDATE foods SET breaker='$breaker', lunch='$lunch', dinner='$dinner', anythingElse='$anythingElse' WHERE id='$rowID' ");
		exit('success');
	}

	if ($_POST['key'] == 'addNew') {
		$sql = $conn->query("SELECT id FROM users WHERE breaker = '$breaker'");
		$conn->query("INSERT INTO foods (breaker, lunch, dinner, anythingElse)
					VALUES('$breaker', '$lunch', '$dinner', '$anythingElse')");
		exit('AGGREGATE LUNCH!');
	}
}
?>