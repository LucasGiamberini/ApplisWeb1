<?php
   
    session_start();//demarre un nouvelle sessions ou recupere une session pour un utilisateur
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=width-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Récapitulatif des produits</title>
</head>
<body>
<header>
            <figure>
                <a href="http://localhost/ApplisWeb1/index.php">
                    <img id="logo" src=img/logo.png   alt="logo">
                </a>
            </figure>
            <nav>
                <ul>
                    <li id='panier'>
                         <a href=http://localhost/ApplisWeb1/index.php>
                            Quitter Pannier
                         </a>
                    </li>
                </ul> 
            </nav>
        </header>

    <div id="recap1">
        <div id='recap2'>
            <?php
                if(!isset($_SESSION['product']) || empty($_SESSION['product'])){//verifie que soit la clé du tableau de session 'product' n'existe pas,(empty)soit que le tableau est vide
                    echo '<p id="message"> Aucun produit en session...</p>';
                }/////////////////////////////////////////////haut du tableau/////////////////////////////
                else{
                    echo  "<h2> Recapitulatif Commande</h2>",
                        "<table class=tableauRecapitulatif>",//tableau
                            "<thead class=enteteTableau >",//decompose les donnée de chaque produits
                                "<tr  >",
                                    "<th >#</th>",
                                    "<th >Image</th>",
                                    "<th>Nom</th>",
                                    "<th>Prix</th>",
                                    "<th>Quantité</th>",
                                    "<th>Total</th>",
                                
                            "</thead>",
                        ///////////////////////////////entré du tableau///////////////////////////
                        "<tbody id=basTableau>";
                    $totalGeneral= 0;
                
                

                    foreach($_SESSION['product'] as $index => $product){
                        $total=$product['price']*$product['qtt'];//prix total pour un produit en plusieur fois
                    
                        echo "<tr >",
                            "<td>".$index."</td>",//numero index tableau
                            "<td><figure><img id='imgProduit' src='" . $product['picture'] . "'></figure></td>",//image
                            "<td>".$product['name']."</td>",
                            "<td>".number_format($product['price'],2,",", "&nbsp;")."&nbsp;€</td>",//('2') deux chiffre après la virgule, "," affichage virgule pour separer euro et centime
                            "<td id='plusMoin'><form method='post' action='traitement.php'><input type='hidden' name='totalQtt' value='" . $totalGeneral . "'> <input type='hidden' name='productQtt' value='" . $product['qtt'] . "'> <input type='hidden' name='productIndex' value='" . $index . "'> <input type='hidden' name='productImg' value='" . $product['picture'] . "'><input class='bouton' type='submit' name='moinUn' value= '-'></form>".$product['qtt'].
                            "<form method='post' action='traitement.php'> <input type='hidden' name='productQtt' value='" . $product['qtt'] . "'> <input type='hidden' name='productIndex' value='" . $index . "'> <input class='bouton' type='submit' name='plusUn' value= '+'></form></td>",//quantite
                            "<td>".number_format($total, 2,",", "&nbsp")."&nbsp;€</td>",
                                "<td >
                                    <form method='post' action='traitement.php'>
                                        <input type='hidden' name='productImg' value='" . $product['picture'] . "'>
                                        <input type='hidden' name='productIndex' value='" . $index . "'>
                                        <input type='hidden' name='productQtt' value='" . $product['qtt'] . "'>
                                        <input class='supprimer' type='submit' name='supprimer' value= 'Supprimer'>
                                    </form>
                                </td>";
                // ligne 53 et 54: bouton + et - pour la quantité et la quantité afficher
                // ligne 60 pour supprimer un article       
                    $totalGeneral+= $total;
                    }
                    echo "<tr>",
                            "<td colspan=3><br>Total général : </td>",
                            "<td colspan='2'><br><strong>" . $_SESSION['totalQtt'] . "</strong></td>" .
                            "<td><br><strong>" . number_format($totalGeneral, 2, ",", "&nbsp;") . "&nbsp;€</strong></td>" .
                            "<td><form method='post' action='traitement.php'>" .
                            "<input class='supprimer' type='submit' name='deleteAll' value='Supprimer le panier'>" .
                            "</form></td>",

                    //ligne 69 suprimer tout le panier
                            "</tbody>",
                            "</table>"; 
                    
                    // code ci dessous pour lier un bouton a traitement.php
                        "<form method='post' action='traitement.php'>
                    <input type='submit'  name='a' value='Supprimer le panier'></td>";   
                    
                }

            ?>
    </div>
   </div>
</body>
</html>