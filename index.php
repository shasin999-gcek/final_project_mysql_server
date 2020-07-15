<?php 
    $host = '127.0.0.1';
    $username = 'root';
    $password = '170veg0tj02';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		extract($_POST);
		$conn = mysqli_connect($host, $username, $password); 
		mysqli_query($conn, "CREATE DATABASE $dbname"); 
		mysqli_query($conn, "CREATE USER '$dbuser'@'localhost' IDENTIFIED BY '$dbpassword';"); 
		mysqli_query($conn, "GRANT SELECT, INSERT, UPDATE, DELETE ON $dbname.* TO '$dbuser'@'localhost'"); 
        mysqli_close($conn);
        
        try {
            $conn = new PDO("mysql:host=$servername;dbname=apps_databases", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO db_configs (server_id, dbname, dbuser, dbpassword) VALUES (?, ?, ?, ?)";
            // use exec() because no results are returned
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($server_id, $dbname, $dbuser, $dbpassword));
            echo "Database created successfully<br>";
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		extract($_GET);
        try {
            $conn = new PDO("mysql:host=$servername;dbname=apps_databases", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM db_configs WHERE server_id=?";
            // use exec() because no results are returned
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($server_id));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result)
                echo json_encode(array('status' => '404', 'data' => $result));
            else
                echo json_encode(array('status' => '404', 'data' => null));    
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
	}
?>