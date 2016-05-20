<?php 
require_once 'functions.php';

if(isset($_POST['submit'])) {
	if (isset($_REQUEST["getpage"])) {
		switch ($_REQUEST["getpage"]) {
			case 'login':
				$email = $_REQUEST['email'];
				$pass = $_REQUEST['password'];
				if (!isset($email) || !isset($pass)) {
					header("location: /login/Data Missing");
					exit();
				} else {
					
					$ris=executeQuery("select * from utenti where utenti.EMAIL='$email' AND utenti.PASSWORD='$pass'");
					if ($ris && ($ris->num_rows > 0)) {
						$riga=$ris->fetch_assoc();
						if($riga['ACCETTATO']=="0"){
							header("location: /login/Activate your account");
							exit();
						}
						$_SESSION["ID"]=$riga['ID'];
						$_SESSION["NOME"]=$riga['NOME'];
						$_SESSION["COGNOME"]=$riga['COGNOME'];
						$_SESSION["EMAIL"]=$riga['EMAIL'];
						if ((isset($_REQUEST['remember'])) && ($_REQUEST['remember']==true)) {
							setcookie("ID", $riga['ID'], time() + (86400 * 30), "/");
							setcookie("NOME", $riga['NOME'], time() + (86400 * 30), "/");
							setcookie("COGNOME", $riga['COGNOME'], time() + (86400 * 30), "/");
							setcookie("EMAIL", $riga['EMAIL'], time() + (86400 * 30), "/");
							echo "true";
						}
						header("location: /account");
						exit();
					} else {
						header("location: /login/Input Error");
						exit();
					}
				}
				exit();
				break;

			case 'create':
				print_r($_POST);
				$title=$_REQUEST['title'];
				$description=$_REQUEST['description'];
				$subcategory=$_REQUEST['subcategory'];

				$QUERY=executeQueryAndGetLastId("insert into progetti  (DESCRIZIONE, FK_CATEGORIA_SECONDARIA, FK_UTENTE, NOME) VALUES ('$description', $subcategory, $_SESSION[ID], '$title')");
				$lastId=$QUERY['id'];

				$projectPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/";

				if (!file_exists($projectPath)) {
		            mkdir($projectPath, 0777, true);
		            copy('img/bg1.jpg', $projectPath."projectWallpaper.jpg");
		   	 	}

				$files = array_slice(scandir($projectPath), 2);
			
				foreach ($files as $file) {
	    			$QUERY=executeQuery("insert into parti_3d  (FK_PROGETTO, NOME) VALUES ('$lastId','$file')");
				}

				if (isset($_REQUEST['tags'])) {
					$tags=$_REQUEST['tags'];
					foreach ($tags as $tag) {
		    			$QUERY=executeQuery("insert ignore into tag (NOME) VALUES ('$tag')");
					}

					foreach ($tags as $tag) {
		    			$QUERY=executeQuery("select * from tag where NOME='$tag'");
		    			if ($QUERY && ($QUERY->num_rows > 0)) {
							$riga=$QUERY->fetch_assoc();
							$fkTag = $riga['ID'];
							$QUERY=executeQuery("insert into progetti_hanno_tag (FK_PROGETTO, FK_TAG) VALUES ('$lastId', '$fkTag')");
						}
					}
				}

				
				imageUpload($projectPath."projectWallpaper.jpg");

				rename ($projectPath,"users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/".$lastId."/");
				exit();
				break;

			case 'logout':
				echo "CIAO";
				$_SESSION=array();
				session_unset();
				session_destroy();
				setcookie("ID", "", time() - 3600);
				setcookie("NOME", "", time() - 3600);
				setcookie("COGNOME", "", time() - 3600);
				setcookie("EMAIL", "", time() - 3600);
				header("location: /login");
				exit();
				break;

			case 'register':
				$name = $_REQUEST['first-name'];
			    $surname = $_REQUEST['last-name'];
			    $email = $_REQUEST['email'];
			    $city = $_REQUEST['city'];
			    $password = $_REQUEST['password'];
			    if (!isset($name) || !isset($surname) || !isset($email) || !isset($city) || !isset($password)) {
			    	header("location: /register/Data Missing");
					exit();
			    } else {
			    	$randomString=randomString(10);
			    	$QUERY=executeQuery("insert ignore into utenti  (NOME, COGNOME, EMAIL, DESCRIZIONE, PASSWORD, CODICE_CONFERMA, ACCETTATO, FK_COMUNE) VALUES ('$name', '$surname', '$email', NULL, '$password', '$randomString', 'FALSE', '$city')");
			    	$last_id = $_SESSION["LASTINSERTEDID"];
			    	if ($last_id==0) {
			    		session_unset();
						session_destroy();
		                header("location: /register/Mail already exist");
		                exit();
		            } else {
		            	echo "New record created successfully. Last inserted ID is: " . $last_id;
			            if (!file_exists("users/".$name."-".$surname."-".$email)) {
			                mkdir("users/".$name."-".$surname."-".$email, 0777, true);
			           	 	copy('img/default.jpg', "users/".$name."-".$surname."-".$email."/profile.jpg");
		           	 	}

		                localMail($email, $name, $surname, $randomString, $last_id);
		                //altervistaMail($email, $randomString, $last_id);

		                imageUpload("users/".$name."-".$surname."-".$email."/profile.jpg");

		                session_unset();
						session_destroy();
						header("location: /login");
			    		exit();
		            }
			    }
		    	exit();
				break;

			default:
				header("location: /");
				exit();
				break;
		}
	}
}

?>