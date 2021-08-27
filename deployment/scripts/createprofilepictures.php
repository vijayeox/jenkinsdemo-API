<?php
$conn = new mysqli("localhost","bharat","password","oxzionapi");
$query= "SELECT ox_user.uuid,avatars.username,avatars.icon FROM avatars INNER JOIN ox_user ON avatars.username=ox_user.username where avatars.orgid=1;";
$result=mysqli_query($conn,$query);
while ($row=$result->fetch_assoc()) {
	$updateQuery="";
	if($row['icon']){
		$extension = strtolower(pathinfo($row['icon'], PATHINFO_EXTENSION)); 
        if(__DIR__."/2.0/".$row['icon']){
    		switch ($extension) {
        		case 'jpg':
        		case 'jpeg':
        		case 'JPG':
        		case 'JPEG':
           			$image = imagecreatefromjpeg(__DIR__."/2.0/".$row['icon']);
        		break;
        		case 'gif':
        		case 'GIF':
           			$image = imagecreatefromgif(__DIR__."/2.0/".$row['icon']);
        			break;
        		case 'png':
        		case 'PNG':
           			$image = imagecreatefrompng(__DIR__."/2.0/".$row['icon']);
        			break;
    			}
			if($image){
if(!file_exists("generated/".$row['uuid'])){
				mkdir("generated/".$row['uuid']);
} else {
print_r($row['username']);
}
				$created = imagepng($image, "generated/".$row['uuid'].'/profile.png');
				if($created){
					$updateQuery= "UPDATE ox_user set icon='https://www3.oxzion.com/data/uploads/user/".$row['uuid']."/profile.png' where username='".$row['username']."';";
$updateResult=mysqli_query($conn,$updateQuery);
				}
				$image = null;
			}
        	}
	}
}
?>
