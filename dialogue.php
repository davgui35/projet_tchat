<?php
//connexion
$pdo = new PDO('mysql:host=localhost;dbname=dialogue;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
/*-----------------------------------------------*/
//Insertion du commentaire
if($_POST){
    //addslashes — Ajoute des antislashs dans une chaîne
    $_POST['pseudo'] = addslashes($_POST['pseudo']);
    $_POST['message'] = addslashes($_POST['message']);

    if (empty($_POST['pseudo']) || empty($_POST['message'])) {
        $message = '<div class="erreur">Pour déposer un commentaire, veuillez remplir les champs du formulaire.</div>';
    }else{
            //insertion dans la base de données
             $result = $pdo->exec("INSERT INTO commentaire(pseudo, message) VALUES('$_POST[pseudo]', '$_POST[message]')");
             $message = '<div class="validation">Votre message a bien été enregistré.</div>';        
    }
}
/*-----------------------------------------------*/
//Affichage des commentaires
$result = $pdo->query("SELECT pseudo, message, DATE_FORMAT(date_enregistrement, '%d/%m/%Y') AS datefr, DATE_FORMAT(date_enregistrement, '%H:%i:%s') AS heurefr FROM commentaire ORDER BY date_enregistrement DESC LIMIT 0, 5");
$commentaires = $result->fetchAll(PDO::FETCH_ASSOC);
$counter = $result->rowCount();
// var_dump($commentaires);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dialogue.css">
    <title>Virtual tchat</title>
</head>
<body>
    <div class="container">
        <h1>Virtual tchat</h1>
        <form action="dialogue.php" method="post">
            <p><label for="pseudo">Pseudo</label></p>
            <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo"><br/><br/>
            <p><label for="message">Message</label></p>
            <textarea name="message" id="" cols="30" rows="10" placeholder="Votre message"></textarea><br/>
            <input type="submit" value="Envoyer le message" name="envoyer">
        </form>
    </div>
    <hr/>
    <div class="alert">
        <?php if(isset($message)){ echo $message;}?>
    </div>
    
    <?php if(isset($commentaires)){ 
                $nomSauvegarder = "";
//Initialiser le nom de départ et pouvoir rester sur la même couleur
                $nomSauvegarder = $commentaires[0]['pseudo'];
                for($i=0; $i<count($commentaires); $i++){
                    echo '<div class="container_box">';
                    if( $nomSauvegarder != $commentaires[$i]['pseudo']){
                            echo '<div class="box_tchat1">
                            <div class="message>
                                <div class="title">Par: '.$commentaires[$i]['pseudo'].', le '.$commentaires[$i]['datefr'].' à '.$commentaires[$i]['heurefr'].'.</div>
                                <div class="contenu">'.$commentaires[$i]['message'].'</div>
                            </div>
                        </div>';
                    }
                    
                    if($nomSauvegarder ===$commentaires[$i]['pseudo']){
                        $nomSauvegarder = $commentaires[$i]['pseudo'];
                        echo '<div class="box_tchat2">
                        <div class="message>
                            <div class="title">Par: '.$commentaires[$i]['pseudo'].', le '.$commentaires[$i]['datefr'].' à '.$commentaires[$i]['heurefr'].'.</div><div class="contenu">'.$commentaires[$i]['message'].'</div>
                        </div>
                    </div>';
                    }
                    echo '</div>';
                }
            }?>
</body>
</html>