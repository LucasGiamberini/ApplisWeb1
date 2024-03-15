<?php
    session_start();//demarre un nouvelle sessions ou recupere une session pour un utilisateur
  
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <title>Ajout produit </title>

    </head>
 
    <body>
    <!-- Affichage message en haut de page-->
        <?php 
             if (isset($_GET['message'])) {
                $message = htmlspecialchars($_GET['message']);
                echo "<p id='message'>$message</p>";
;
            } 
        ?>
        <header>
            <figure>
            <a href=http://localhost/ApplisWeb1/index.php>
                 <img id="logo" src=img/logo.png  href="http://localhost/ApplisWeb1/index.php" span="logo">
            </a>
            </figure>
            <nav>
                <ul>
                    <li id='panier'>
                        <a href=http://localhost/ApplisWeb1/recap.php>
                        <img  id= "logoPanier" src=img\panier.png span="panier">
                        Panier 
                        <?php
                        //affichage Nombre produit Panier//
                            if(isset($_SESSION['totalQtt'])){
                             echo "<br>".$_SESSION['totalQtt']."  Article(s)";
                            }
                            else{
                                echo '<br> 0 Article';
                            }  
                        ?>
                        </a>
                    
                    </li>
                </ul>
            </nav>
        </header>
        <column>
            <div id="fenetreAjouter">
                <h1>Ajouter un produit</h1>
                
                    <form action="traitement.php" method="post" enctype= "multipart/form-data">
                    <p>
                            <label>
                                Image :<br>
                                <input type="file" name="picture" accept="image/*"> <!-- upload image + accept="image/*" pour prendre que le fichier choisis est bien une ima -->
                            </label>
                        </p>   
                    <p>
                            <label>
                                Nom du produit :<br>
                                <input class=champTexte type="text" name="name" placeholder="Nom du produit">
                            </label>
                        </p>
                        <p>
                            <label>
                                Prix du produit :<br>
                                <input class=champTexte type="number" step="any" name="price" min="0" placeholder="prix">
                            </label>
                        </p>
                    
                        <p>
                            <label>
                                Quantité désirée :<br>
                                <input  class=champTexte type="number" name="qtt" value="1" min="1" placeholder="Quantité" > <!-- placeholder met du texte dans le champ de texte-->
                            </label>
                        </p>
                        <p>
                            <input id="boutonAjouter" type="submit" name="submit" value="Ajouter le produit"><!-- balise input liée a traitement.php ligne 4-->
                        </p>
                </div>
            </form>
          </column>    
        </body>
    </html>