<?php
		$dbhost = 'diveinsurance.eoxvantage.com';
		$dbuser = 'apiuser';
		$dbpass = 'Apipasswd321!';
		$db = 'DiveInsurance___d77ea120b028479b8c6e60476b6a4456';
		$query = "SELECT member_number,GROUP_CONCAT(rating) as rating,COUNT(member_number) as count from 
		(SELECT DISTINCT member_number,rating from padi_data
		where rating IS NOT NULL) as a
		group by member_number HAVING COUNT(member_number) > 1;";

		$appDb = new mysqli($dbhost,$dbuser,$dbpass,$db);
		if($appDb->connect_errno) {
			echo "Failed to connect to mysql".$appDb->connect_error;
			exit();
		}

		$db = 'oxzionapi';
		$oxzionDb = new mysqli($dbhost,$dbuser,$dbpass,$db);
		if($oxzionDb->connect_errno) {
			echo "Failed to connect to mysql".$oxzionDb->connect_error;
			exit();
		}

		$memberData = array();
		$query3 = "Update ox_file SET data = ? where id = ?";
		$stmt = $oxzionDb->prepare($query3);
		if($result = $appDb->query($query)){
			while($row = $result->fetch_assoc()) {
				$padi = $row['member_number'];
				$query2 = "Select * from ox_file where ox_file.`data` LIKE '%\"padi\":".$padi."%' and entity_id in (2,8)";
				$fileResult = $oxzionDb->query($query2);
				if($fileResult){
					$fileResults = $fileResult->fetch_assoc();
					if($fileResults) {
						$fileData = json_decode($fileResults['data'],true);
						if($fileData['product'] == 'Dive Store' || $fileData['product'] == 'Group Professional Liability') {
							if(isset($fileData['groupPL'])) {
								foreach ($fileData['groupPL'] as $key => $value) {
									if($value['padi'] == $padi) {
										print_r("FILE: ".$fileResults['id']." PADI PROCESSED ".$padi." With rating ".$row['rating']);
										print_r("\n");
										$fileData['groupPL'][$key]['rating'] = $row['rating'];
										break;
									}
								}
							}
						}
						$fileDataEncoding = json_encode($fileData);
						$stmt->bind_param("si",$fileDataEncoding,$fileResults['id']);
						$stmt->execute();
					}
				}
			}
		}
		$appDb->close();
		$oxzionDb->close();
?>