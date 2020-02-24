<?php
    if(isset($_GET['way1']))$way_gobal = $_GET['way1'];
    else $way_gobal = null;
    if(isset($_GET['way2'])) $way_metier = $_GET['way2'];
    else $way_metier = null;
    if(isset($_GET['way3'])) $way_ensiegnant =$_GET['way3'];
    else $way_ensiegnant =null;
    $semaine =$_GET['semaine'];
    $metiere =$_GET['matiere'];
    $ensiegnant =$_GET['prof'];
    $jours =    array('sa' , 'di' , 'lu' , 'me' , 'ma' , 'je');
    $xml=file_get_contents("edudiant.xml");
    $xml =utf8_encode($xml);
    $document = new DomDocument();
    $document->loadXml($xml);
    $xpath = new DomXpath($document);
    $ordre =$xpath->query('//types_creneaux/plage/@ordre');
    $herbs= $xpath->query("//types_creneaux/plage/hdeb");
    $idprofs=$xpath->query('//liste_profs/prof/@idprof');
    $nomprofs=$xpath->query('//liste_profs/prof/nom');
    $prenomprofs=$xpath->query('//liste_profs/prof/prenom');
    $Fidprofs=array();
    foreach($idprofs as $key=>$idprof)$Fidprofs[$key]=$idprof->nodeValue;
    $FullNumProfs=array();
    foreach($nomprofs as $key=>$nom)$FullNumProfs[$key]=$nom->nodeValue." ".$prenomprofs[$key]->nodeValue;
?>
<!DOCTYPE HTML>
<HTML>
    <HEAD>
        <meta charset="utf-8">
        <title>Welcome</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="Style.css">
    </HEAD>
    <BODY class="text-center">
        <div class="container">
        <?php
            $groupe = $xpath->query('/etudiant/@groupe');
            echo"<h3 class=\"mt-3\">Emploi de temps : groupe ( ";
            echo($groupe[0]->value); 
            echo" )</h3>";
            ?>  
            <?php
                    echo'<table class="mt-3 table table-dark">';
                    echo'<thead>';
                    echo    '<tr>';
                    echo    '<th scope="col">jour / heure</th>';
                    echo    '<th scope="col" class="sa">Samedi</th>';
                    echo    '<th scope="col" class="di">Dimanche</th>';
                    echo    '<th scope="col" class="lu">Lundi</th>';
                    echo    '<th scope="col" class="ma">Mardi</th>';
                    echo    '<th scope="col" class="me">Mercredi</th>';
                    echo    '<th scope="col" class="je">Jeudi</th>';
                    echo    '</tr>';
                    echo '<tbody>';
            ?>
             <?php
                    $nprofchercher = array_search($ensiegnant ,$FullNumProfs);
                    foreach($herbs as $key1 => $herb) {
                        echo'<tr class="'.$ordre[$key1]->nodeValue.'">';
                        echo '<th scope="row">'.$herb->nodeValue.'</th>';
                        foreach($jours as $key3 => $jour){ 
                                $type =$xpath->query("//liste_semaines/semaine[@num_semaine ='".$semaine."']/jour[@nomjour='".$jours[$key3] ."']/creneau[@nplage='".$ordre[$key1]->nodeValue."']/type");
                                $Xmatiere =$xpath->query("//liste_semaines/semaine[@num_semaine ='".$semaine."']/jour[@nomjour='".$jours[$key3] ."']/creneau[@nplage='".$ordre[$key1]->nodeValue."']/matiere");
                                $nprof =$xpath->query("//liste_semaines/semaine[@num_semaine ='".$semaine."']/jour[@nomjour='".$jours[$key3] ."']/creneau[@nplage='".$ordre[$key1]->nodeValue."']/@nprof");
                               if(isset($nprof[0]->value)){
                                    $nomProf=$xpath->query("//liste_profs/prof[@idprof='".($nprof[0]->value)."']/nom");
                                }
                                $salle =$xpath->query("//liste_semaines/semaine[@num_semaine ='".$semaine."']/jour[@nomjour='".$jours[$key3] ."']/creneau[@nplage='".$ordre[$key1]->nodeValue."']/@nsalle");
                                /*Par ensiegnant et metier */
                                if($way_ensiegnant!=null && $way_metier!=null){
	                                if(isset($type[0]->nodeValue) && ($Fidprofs[$nprofchercher]==$nprof[0]->nodeValue)&&($Xmatiere[0]->nodeValue==$metiere))
	                                echo"<td>".$type[0]->nodeValue."-".$Xmatiere[0]->nodeValue."<br>".($nomProf[0]->nodeValue)."-".($salle[0]->value)."</td>";
	                                else
	                                    echo"<td></td>";  
                            	}
                            	/*Par metier */
                            	else if($way_metier!=null){
                            		if(isset($type[0]->nodeValue)&& $Xmatiere[0]->nodeValue==$metiere)
                            		    echo"<td>".$type[0]->nodeValue."-".$Xmatiere[0]->nodeValue."<br>".($nomProf[0]->nodeValue)."-".($salle[0]->value)."</td>";
                            		else
                            		    echo"<td></td>"; 
	                            }  
	                            /*Par ensiegnant  */
	                            else if($way_ensiegnant!=null){
	                            	if(isset($type[0]->nodeValue) && ($Fidprofs[$nprofchercher]==$nprof[0]->nodeValue))
                                    echo"<td>".$type[0]->nodeValue."-".$Xmatiere[0]->nodeValue."<br>".($nomProf[0]->nodeValue)."-".($salle[0]->value)."</td>";
                               		else
                                    echo"<td></td>";                    

	                            }
	                            //Global
	                            else{
	                            	if(isset($type[0]->nodeValue))
                                	echo"<td>".$type[0]->nodeValue."-".$Xmatiere[0]->nodeValue."<br>".($nomProf[0]->nodeValue)."-".($salle[0]->value)."</td>";
	                                else
	                                echo"<td></td>";
	                            }
                
                        }
                        echo"</tr>";
                    
                }
            ?>
            <?php
                    echo '</tbody>';
                    echo '</thead>';
                    echo '</table>';
            ?>
        </div>
    </BODY>
</HTML>