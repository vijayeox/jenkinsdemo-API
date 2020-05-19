UPDATE 	carrier_policy SET start_date = CONCAT(DATE_FORMAT(start_date, '%Y-%m-'), '22') ,end_date = CONCAT(CONCAT(DATE_FORMAT(end_date, '%Y-%m-'), '22'),' ','11:59:59')  WHERE product = 'Dive Boat';

