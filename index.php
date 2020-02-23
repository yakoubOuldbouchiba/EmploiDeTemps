<?php
     $xml = file_get_contents("edudiant.xml");
     $xml =utf8_encode($xml);
     $document = new DomDocument();
     $document->loadXml($xml);
     $xpath = new DomXpath($document);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Welcome</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="Style.css">
    </head>
    <body class="text-center">
        <div class="container well">
            <div class="row">
                <div class="col-sm-12 text-center">
                      <?php
                        $groupe = $xpath->query('/etudiant/@groupe');
                        echo"<h1>Emploi de temps : groupe ( ";
                        echo$groupe[0]->value;
                        echo" ) </h1>";
                      ?>  
                <div>
            </div>
            <div class=row>
                <form action="Result.php" method="get" class="col-lg-12">
                    <div class="mt-4 row">
                        <div class="col-lg-4">
                            <input type="checkbox" id="global" name="way1" value="global">
                            <label for="male">Global</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" id="matiere" name="way2" value="matiere">
                            <label for="female">Par matière</label><br>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" id="enseignant" name="way3" value="enseignant">
                            <label for="other">Par enseignant</label>
                        </div>
                    </div> 
                    
                    <div class="mt-4 row">
                            <div class="col-lg-4 ">
                                <samp>Semaine</samp>
                                <select name="semaine">
                                    <?php
                                    $semaines = $xpath->query('//liste_semaines/semaine/@num_semaine');
                                    foreach($semaines as $semaine)
                                    echo"<option>".$semaine->value."</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <samp>Matière</samp>
                                <select name ="matiere">
                                    <?php
                                         $matieres = $xpath->query('//liste_semaines/semaine/jour/creneau/matiere[not(. = preceding::matiere)]');
                                         foreach($matieres as $matiere)
                                         echo"<option>".$matiere->nodeValue."</option>";
                                         
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <samp>Enseignant</samp>
                                <select name="prof">
                                    <?php
                                        $profsNom=$xpath->query('//liste_profs/prof/nom');
                                        $profsPrenom=$xpath->query('//liste_profs/prof/prenom');
                                        foreach($profsNom as $key=> $prof)
                                        echo '<option>'.$prof->nodeValue.' '.$profsPrenom[$key]->nodeValue.'</option>';
                                    ?>
                                </select>
                            </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-4 offset-md-8 mt-4">
                            <button type="submit" class="btn btn-outline-secondary col-md-4">Secondary</button>
                        </div>    
                    <div>                       
                </from> 
            </div>
        </div>
    </body>
</html>
