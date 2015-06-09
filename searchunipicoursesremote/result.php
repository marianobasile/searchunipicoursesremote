<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta name="description" content="Ricerca Corsi Unipi per nome del corso e per docente titolare del corso" >
<meta name="keywords" content="ricerca corsi, unipi" >
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Ricerca Corsi Unipi</title>
</head>
<body onload="window.resizeTo(700,625)">
<p id="errorDescription" style="font-size: 18px; color: #dd4b39; position: relative; left: 25%; top: 5%;"></p>
<?php
if(isset($_GET['trova'])) {

	if (isset($_GET['flag']) && $_GET['flag'] == 0)
		$params = array( 'coursename' => $_GET['params'] );

	else if (isset($_GET['flag']) && $_GET['flag'] == 1)
		$params = array( 'teachername' => $_GET['params'] );

	$function_name = $_GET['function_name'];

	//print_r($params) ;
	//echo $function_name;

	$token = "7d2bc175951c4b84c1d3d3ee13ec60a3";

	require_once( './curl.php' );
	$curl = new curl;

 	$selectedAreas = array();

 	foreach ($_GET['aree'] as $select) 
 			array_push($selectedAreas, $select);

 	//Creazione della richiesta verso gli N siti remoti da interrogare
	//Ipotesi $remoteSites = numero di siti remoti da interrogare = 2
 	if ( (in_array("INTERO ATENEO",$selectedAreas )) )
		$remoteSites = 6;
	else
		$remoteSites = sizeof($selectedAreas);

	//echo $remoteSites;

	$domain = array();

	foreach ($_GET['aree'] as $select) {

		if( $select == "AGRARIA E VETERINARIA" )
				array_push($domain, "http://131.114.28.114");

		else if( $select == "DISCIPLINE UMANISTICHE") 
				array_push($domain, "http://131.114.28.114");

		else if( $select == "INGEGNERIA")
				array_push($domain, "http://131.114.28.114");

		else if( $select == "MEDICINA E FARMACIA" )
				array_push($domain, "http://131.114.28.114");

		else if( $select == "SCIENZE GIURIDICHE, ECONOMICHE E SOCIALI" )
				array_push($domain, "http://131.114.28.114");

		else if( $select == "SCIENZE MATEMATICHE, FISICHE E DELLA NATURA") 
				array_push($domain, "http://131.114.28.114");
		
		else  {
			$domain = array();			//svuota l'array nel caso siano presenti ulteriori scelte oltre a 'intero ateneo'
			for($i=0; $i<6; $i++)
				array_push($domain, "http://131.114.28.114");
			break;
		}

	}
	//print_r($domain);

	$response = array(); //array contenente i risultati ottenuti. I risultati sono ritornati sotto forma di stringa.
	for ($i = 0; $i < $remoteSites; $i++)
		$response[$i] = $curl->post($domain[$i] . '/elearn/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$function_name, $params);
	
	$field = array();
	//Preparo la risposta da visualizzare: riaggiunta degli spazi ai corsi aventi il nome composto da più parole separate tra loro
 	echo "<div >
					  <table style=\"border-collapse: collapse; width: 100%;\">
					  <tr>
					  <th style=\"padding: 0.25rem; text-align: center; border: 1px solid #ccc;\"> Moodle Site </th>
					  <th style=\"padding: 0.25rem; text-align: center; border: 1px solid #ccc;\"> Corso </th>
					  <th style=\"padding: 0.25rem; text-align: center; border: 1px solid #ccc;\"> Cognome Docente </th>
					  <th style=\"padding: 0.25rem; text-align: center; border: 1px solid #ccc;\"> Nome Docente </th>
					  </tr>
					  ";
	


	for ($i = 0; $i < $remoteSites; $i++) {
		if($response[$i] != "") {
			//echo $response[$i];
			$field[$i] = explode (" ",$response[$i]);
			
			for($j = 0; $j < sizeof($field[$i]); $j++) 
				$field[$i][$j] = str_replace("*", " ",$field[$i][$j]);	//Riaggiunta dello spazio tra una parola e l'altra del nome del corso

				

				$countForRow = 2;
				for($j = 0; $j < sizeof($field[$i]); $j++){

					if($countForRow %5 == 0 && $countForRow != 5)
						echo "</tr><tr>";

					if($countForRow %5 == 0 && $countForRow == 5)
						echo "<tr>";

					if($j <= 2){
						$countForRow++;
						continue;
					}

					if($j == 3)
						$field[$i][3] = str_replace("?>", "",$field[$i][3]);

					if( ($countForRow-1)%5 == 0) {
						$countForRow++;
						continue;
					}

					if($j%5 == 0) {
						if($j==5){
							$val = strip_tags($field[$i][3]);
							$val = trim ($val);
							echo "<td style=\"padding: 0.25rem; text-align: center; border: 1px solid #ccc;\"> <a href=http://".$val."/elearn/course/view.php?id=".$field[$i][$j-1].">".$field[$i][$j]."</a></td>";
						}
								
						 else
						echo "<td style=\"padding: 0.25rem; text-align: center; border: 1px solid #ccc;\"> <a href=http://".$field[$i][$j-2]."/elearn/course/view.php?id=".$field[$i][$j-1].">".$field[$i][$j]."</a></td>";
						$countForRow++;
						continue;
					}
					
					echo "<td style=\"padding: 0.25rem; text-align: center; border: 1px solid #ccc;\">".$field[$i][$j]."</td>";
					$countForRow++;
				}
		}
	}
	echo"</table><br>";

}

if($_POST) {

	echo "<script>
			self.opener.parent.document.getElementById('searchCourse').reset();
	</script>";

	$subject;	//will contain parameter value received by client converted in iso-8859-1 charset

	if(isset($_POST['corso']) && $_POST['corso']!= "")
		$subject = utf8_decode($_POST['corso']);
	else if(isset($_POST['docente']) && $_POST['docente']!= "")
		$subject = utf8_decode($_POST['docente']);
	else {
		echo "<script>
				document.getElementById('errorDescription').appendChild(document.createTextNode('Carattere inserito non valido! Chiusura finestra...')); 	
				window.setTimeout('window.close();', 2500);
        </script> ";
		exit();
	}
		
    $pattern = '/^[a-zA-Z\xE0\xE8\xE9\xF9\xF2\xEC\xC0\xC8\xC9\xD9\xD2\xCC\s]*$/';
		
	if(preg_match($pattern, $subject) == 0  ) {

        echo "<script>
				document.getElementById('errorDescription').appendChild(document.createTextNode('Carattere inserito non valido! Chiusura finestra...')); 	
				window.setTimeout('window.close();', 2500);
        </script> ";
        
        exit();
    }

	if(isset($_POST['corso']) && $_POST['corso']!= "")
		$function_name = 'local_unipipluginshowall_search_unipi_courses';

	else if(isset($_POST['docente']) && $_POST['docente']!= "")
		$function_name = 'local_unipipluginshowall_search_by_teacher';
	else
		exit("Valore inserito non valido!!");

	echo "	<form name = \"selectAreas\" action = \"\" method = \"get\" >
				<div id=\"aree_didattica\" align= \"center\">
				<h1 id=\"search_title\" style=\"font-size: x-large; color: rgb(51, 102, 153); font-family: 'Times New Roman';\" > Aree di Ricerca </h1>
				<span style=\"font-size:70%; font-style:italic; display:block;\">(per selezionare piu' aree utilizzare il tasto CTRL)</span><br>
				<select name = \"aree[]\" multiple = \"multiple\" size =\"7\" style=\"width:75%; font-family: Tahoma; font-size: 12px; font-weight: lighter; font-variant: normal; text-transform: uppercase; color: #555555; margin-top: 10px; text-align: center!important; letter-spacing: 0.3em;\">";	

			for($a = 0; $a < 7; $a++) {
				$val;
				switch($a) {
					case 0:	
							$val = 'INTERO ATENEO';
							break;
					case 1:	
							$val = 'DISCIPLINE UMANISTICHE';
							break;
					case 2:	
							$val = 'INGEGNERIA';
							break;
					case 3:	
							$val = 'MEDICINA E FARMACIA';
							break;
					case 4:	
							$val = 'SCIENZE GIURIDICHE, ECONOMICHE E SOCIALI';
							break;
					case 5:	
							$val = 'SCIENZE MATEMATICHE, FISICHE E DELLA NATURA';
							break;
					case 6:	
							$val = 'AGRARIA E VETERINARIA';
							break;
				}
				if($a == 0)
					echo "<option value =\"$val\" align= \"center\" selected> $val </option>";
				else
					echo "<option value =\"$val\" align= \"center\"> $val </option>";
			}

			if(isset($_POST['corso']) && $_POST['corso']!= ""){
				$corso = $_POST['corso'];
				echo "<input type=\"hidden\" name=\"params\" value=\"$corso\"></input> ";
				echo "<input type=\"hidden\" name=\"flag\" value=\"0\"></input> ";

			} else if(isset($_POST['docente']) && $_POST['docente']!= ""){
				$docente = $_POST['docente'];
				echo "<input type=\"hidden\" name=\"params\" value=\"$docente\"></input> ";
				echo "<input type=\"hidden\" name=\"flag\" value=\"1\"></input> ";
			}

			echo" <input type=\"hidden\" name=\"function_name\" value=\"$function_name\"></input>";
			echo "</select><br><br><input type=\"submit\" name=\"trova\" id=\"trova\" value= \"Cerca\" style=\"width:25%; font-family: Tahoma; font-size: 12px; font-weight: lighter; font-variant: normal; text-transform: uppercase; color: #555555; margin-top: 10px; text-align: center!important; letter-spacing: 0.3em;\"></input> <div><br><br><br><br><br>";
}	
?>
</body>
</html>
