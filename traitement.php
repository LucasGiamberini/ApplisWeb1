<?php //n'st pas visible par l'utilisateur final,voir commentaire en bas du code
session_start();//demarre un nouvelle sessions ou recupere une session pour un utilisateur

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////ENTRE INFO/////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['submit'])){//(isset)ne verifie pas si les viariable existe,le serveur creer automatique des variables ,($post) sert a contenir les infos par l'intermediaire d'un formulaire aux serveur,['submit']verifie la l'existance de la clé 'submit' dans index.php
    $name=filter_input(INPUT_POST,'name',FILTER_SANITIZE_FULL_SPECIAL_CHARS);//(filter_input)entré avec filtre pour eviter erreur et injecter du code ,(FILTER_SANITIZE_FULL_SPECIAL_CHARS)filtre pour verifier que l'entré est une chaine de caractère sans caractère speciaux
    $price=filter_input(INPUT_POST,'price',FILTER_VALIDATE_FLOAT,FILTER_FLAG_ALLOW_FRACTION);//(FILTER_VALIDATE_FLOAT )filtre pour verifier que l'entré est bien un chiffre avec deux chiffre après la virgules,(FILTER_FLAG_ALLOW_FRACTION) permet l'utilisation de virgule ou point pour separer les deux chiffres après la virgule;
    $qtt= filter_input(INPUT_POST,'qtt',FILTER_VALIDATE_INT);//(FILTER_VALIDATE_INT)filtre verifiant que la valeur entré est bien de type int(chiffre n'ayant pas de virgule)
    $picture=$_POST['picture'];




if($name && $price && $qtt){//verfie que chaque variable a une entré sauf image  (!0)

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////Upload Image//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Code pour déplacer le fichier image téléchargé vers un dossier spécifique
    $target_directory = 'C:\\laragon\\www\\ApplisWeb1\\imgUpload\\';
    $target_file = $target_directory . basename($_FILES["picture"]["name"]); // Chemin complet du fichier
    $maxFileSize = 250 *  1024; // Taille maximale autorisée en octets (ici, 250 ko),*1024 pour convertir les octet en kilo octet
    $imageTailleInfo = filesize($_FILES["picture"]["tmp_name"]);//taille image
   


   if ($imageTailleInfo > 256000){
    $message="la taille du fichier est supperieur a 250 ko! ";
    header("Location:index.php?message=" . urlencode($message));
    exit();// La fonction exit() est utilisée pour arrêter l'exécution du script après la redirection.
}



// La fonction move_uploaded_file déplace le fichier temporaire téléchargé vers l'emplacement spécifié
//Tableau des extensions que l'on accepte
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));//stokage du type d'extension de l'image uploader
$extensions = ['jpg', 'png', 'jpeg', 'gif'];

if (!in_array($imageFileType, $extensions)){
    $message="Ce format d'image n'est pas accepter! ";
    header("Location:index.php?message=" . urlencode($message));
    exit();// La fonction exit() est utilisée pour arrêter l'exécution du script après la redirection.
}


////////////////////////////////////////////////securité////////////////////////////////////////////////////////////
////////////////////verification de l'extension utiliser ainsi que de la taille pour eviter les erreurs ou virus///////////////
if (in_array($imageFileType, $extensions) && $imageTailleInfo < $maxFileSize) {// verifie si l'extension de l'image est accepter et si la taille du fichier ne depasse pas 1 mo
    $uniqueFileName = uniqid() . '.' . $imageFileType; //uniqid() Générer un nom de fichier unique
        $target_file = $target_directory . $uniqueFileName; // Chemin complet avec nom de fichier unique
    move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);// et la sauvegarde si la condition est bonne
    $imageSrc = 'imgUpload/' . $uniqueFileName;// chemin source vers l'image
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    $product =[//tableau caractèristique produit
        "picture"=>$imageSrc,
        "name"=> $name,
        "price" => $price,
        "qtt" => $qtt,
        "total" =>$price*$qtt,
    
        
    ];
    $_SESSION['product'][]= $product;//($_SESSION) solicitation du tableau de session,(['product']) crée la clée 'product' au sein de php car elle n'existait pas avant,[] emplacement pour ajouter une nouvelle entré au tableau product
    
////////
    
    
    if (!isset($_SESSION['totalQtt'])) {
        $_SESSION['totalQtt'] = 0; // Initialisation si la clé 'totalQtt' n'existe pas encore
    }
    $_SESSION['quantiteSauvegarder'] = isset($_SESSION['quantiteSauvegarder']) ? $_SESSION['quantiteSauvegarder'] : 0;

    $_SESSION['totalQtt'] += $qtt; // Ajout de la quantité du nouveau produit à la quantité totale d'articles dans le panier
    $_SESSION['quantiteSauvegarder'] += $qtt; // Mise à jour de la sauvegarde de la quantité totale d'articles



    ///////////////////////////message dans index//////////////////////////////////////////
    $message = "Produit ajouter au panier.";
    // Redirection vers le deuxième fichier avec le message comme paramètre GET
  header("Location:index.php?message=" . urlencode($message));//La fonction header("Location: index.php?message=" . urlencode($message)); permet de rediriger vers la page index.php avec un message passé en paramètre GET dans l'URL. Cela signifie que lorsque la redirection est effectuée, le message peut être récupéré à partir de l'URL dans la page index.php.
    exit();// La fonction exit() est utilisée pour arrêter l'exécution du script après la redirection.
}
 if (!$name || !$price ){// si un des champs(ou les deux ) n'est rentrer pas rentrer correctement sur index.php
    $message="Veuillez renseigner le nom et le prix du produit!";
    header("Location:index.php?message=" . urlencode($message));
    exit();// La fonction exit() est utilisée pour arrêter l'exécution du script après la redirection.
}


header("Location:index.php");//permet d'afficher le fichier index lorsqu'un utilisateur tente d'acceder a "traitement.php", sa position est a la fin du code pour faire la redirection
exit();
}
//////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////SUPPRIMER PANIER /////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
$indexProduit = $_POST['productIndex']; // Récupérer l'index du produit à modifier
$qttProduit = $_SESSION['product'][$indexProduit]['qtt'];
$qttProduit >= 0;// pour que la quantité n'aillent pas en dessous de zero
$totalQtt=$_SESSION['totalQtt'];


//////////////////////suprimer un produit///////////////////////////


if (isset($_POST['supprimer']) ) {// si il y a le bouton supprimer est appuyer ,cela recupere l'index de la ligne index et img

    $indexToRemove = $_POST['productIndex'];//variable de l'index de la ligne du tableau
    unset($_SESSION['product'][$indexToRemove]);//suprime la ligne du tableau
    $imgToRemove=$_POST['productImg'] ;//chemin de la suppression de l'image(voir reccap.php ligne 62)
    unlink ($imgToRemove);//supprime l'image

   ///////////// //enlever le nombre de produit sur le total//////////////////////////////

 
    $produitAEnlever=$_POST['productQtt'];//recupere le nombre de produit a la ligne indiquer (voir recap.php ligne 57)
    $totalQtt -= $produitAEnlever; //enleve la quantité entré dans l'index a la quantité total
    $_SESSION['totalQtt'] = $totalQtt;
    header("Location: recap.php");
     exit();//ne pas oublier le exit a la fin pour revenir a la page

}


//supprimer tout le panier//

if(isset($_POST['deleteAll'])){
    unset($_SESSION['product']);
    $totalQtt=0;

     //chemin de la suppression de l'image(voir reccap.php ligne 62)
     $directory = 'C:\\laragon\\www\\ApplisWeb1\\imgUpload\\';
     $files = glob($directory. '*'); // Récupère la liste des fichiers dans le répertoire
     
    // $target_file = $directory . basename($_FILES["picture"]["name"]); // Chemin complet du fichier
     foreach ($files as $file) {
             unlink($file); // Supprime chaque fichier trouvé dans le répertoire   
     }

    $_SESSION['totalQtt'] = $totalQtt;//mise a jour de la quantité total
     header("Location: recap.php");
    exit();
}
////////////////enlever un produit//////////////////////////////////////////



if (isset($_POST['moinUn'])) {
   
        
        // Vérifier si la quantité est supérieure à 0 avant de décrémenter
        if ($qttProduit > 0) {
            $qttProduit -= 1; // Réduire la quantité d'une unité
            $_SESSION['product'][$indexProduit]['qtt'] = $qttProduit; // Mettre à jour la quantité dans la session
            $totalQtt=$_POST['productQtt'];//recupere le nombre de produit a la ligne indiquer (voir recap.php ligne 57)
            $_SESSION['totalQtt'] -= 1;
        }
        if ($qttProduit == 0){//si quantité de produit est egale a zero
            unset($_SESSION['product'][$indexProduit]);
            $indexToRemove = $_POST['productIndex'];//variable de l'index de la ligne du tableau
            unset($_SESSION['product'][$indexToRemove]);//suprime la ligne du tableau
            $imgToRemove=$_POST['productImg'] ;//chemin de la suppression de l'image(voir reccap.php ligne 62)
            unlink ($imgToRemove);//supprime l'image
        
            
        }
       header("Location: recap.php");
        exit();
    
}

////////////////////ajouter un produit///////////////////////////////

if (isset($_POST['plusUn'])) {



    // Mettre à jour la quantité du produit en ajoutant une unité
    $qttProduit += 1;

    // Mettre à jour la quantité du produit dans la session
    $_SESSION['product'][$indexProduit]['qtt'] = $qttProduit;

    // Mettre à jour le total de la quantité dans le panier
    $_SESSION['totalQtt'] += 1;

    // Rediriger vers la page de récapitulatif
    header("Location: recap.php");
 
}


