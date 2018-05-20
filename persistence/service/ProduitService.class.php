<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/13/2018
 * Time: 12:44 PM
 */

class ProduitService extends AbstractService implements ProduitDAO {

    public function __construct() {
        parent::__construct();
    }

    public function create(Produit $produit){
        try {
            $this->getDb()->beginTransaction();
            $pdoStatement = $this->getDb()->prepare( "INSERT INTO produit VALUES (NULL, :titre, :description, :prix, :nomFichier, :idFournisseur, :idCategorie)" );
            $pdoStatement->bindValue(':titre', $produit->getTitre(), PDO::PARAM_STR);
            $pdoStatement->bindValue(':description', $produit->getDescription(), PDO::PARAM_STR);
            $pdoStatement->bindValue(':prix', $produit->getPrix(), PDO::PARAM_INT);
            $pdoStatement->bindValue(':nomFichier', $produit->getNomFichier(), PDO::PARAM_STR);
            $pdoStatement->bindValue(':idFournisseur', $produit->getFournisseur()->getIdUser(), PDO::PARAM_INT);
            $pdoStatement->bindValue(':idCategorie', $produit->getCategorie()->getId(), PDO::PARAM_INT);
            if($pdoStatement->execute()){
                $idProduit = $this->getDb()->lastInsertId();
                $pdoStatement = $this->getDb()->prepare("SELECT id_client FROM register_client_fournisseur WHERE id_fournisseur = :idFournisseur ");
                $pdoStatement->bindValue(":idFournisseur", $produit->getFournisseur()->getIdUser(), PDO::PARAM_INT);
                $pdoStatement->execute();

                while($rslt = $pdoStatement->fetch(PDO::FETCH_ASSOC)){
                    $rq = $this->getDb()->prepare("INSERT INTO notification_client_produit VALUES (:idClient, :idProduit)");
                    $rq->execute(array("idClient" => $rslt['id_client'], "idProduit" => $idProduit));
                }
            }
            $this->getDb()->commit();

        } catch (Exception $e) {
            $this->getDb()->rollback();
            throw  $e;
        }
    }

    public function find(array $filters) {
        $sql = "SELECT * FROM produit";

        if (isset($filters['idFournisseur'])) {
            $sql .= " WHERE id_fournisseur = :idFournisseur";
        }

        if (isset($filters['idCategorie'])) {
            $sql .= " AND id_categorie = :idCategorie";
        }

        //REQUETE PREPAREE
        $pdoStatement = $this->getDb()->prepare( $sql );

        if (isset($filters['idFournisseur'])) {
            $pdoStatement->bindValue(":idFournisseur", $filters['idFournisseur'], PDO::PARAM_INT);
        }

        if (isset($filters['idCategorie'])){
            $pdoStatement->bindValue(":idCategorie", $filters['idCategorie'], PDO::PARAM_INT);
        }

        if($pdoStatement->execute() && isset($filters['idUser'])){
            $idUser = $filters['idUser'];
            $ps = $this->getDb()->prepare("DELETE FROM register_client_fournisseur WHERE id_client = :idClient");
            $ps->bindValue(":idClient",$idUser, PDO::PARAM_INT);
            $ps->execute();

            $ps = $this->getDb()->prepare("INSERT INTO register_client_fournisseur VALUES (:idClient, :idFournisseur)");
            $ps->bindValue(":idClient",$idUser, PDO::PARAM_INT);
            $ps->bindValue("idFournisseur", $filters['idFournisseur'], PDO::PARAM_INT);
            $ps->execute();
        }
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $pdoStatement = $this->getDb()->query("SELECT * FROM produit");
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpdates(User $user) {
        $pdoStatement = $this->getDb()->prepare("SELECT p.* FROM produit p INNER JOIN fournisseur f ON p.id_fournisseur = f.id_fournisseur INNER JOIN notification_client_produit r ON r.id_produit = p.id_produit WHERE r.id_client = :idClient");
        $pdoStatement->bindValue(":idClient", $user->getIdUser(), PDO::PARAM_INT);
        $pdoStatement->execute();
        $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);


        $pdoStatement = $this->getDb()->prepare("DELETE FROM notification_client_produit WHERE id_client = :idClient");
        $pdoStatement->bindValue(":idClient", $user->getIdUser(), PDO::PARAM_INT);
        $pdoStatement->execute();

        return $result;
    }

    public function saveCommande($idUser,$idFournisseur, $montant, $data) {
        $pdo = $this->getDb()->prepare("INSERT INTO commande VALUES (NULL,:montant, NOW(), 'EN_ATTENTE', NULL, :idClient,
              :idFournisseur)");
        $pdo->execute(array('montant' => $montant, 'idClient' => $idUser, 'idFournisseur' => $idFournisseur));

        $idCommande = $this->getDb()->lastInsertId();

        foreach ($data as $panier) {
            $idProduit = $panier['idProduit'];
            $quantite = $panier['quantite'];

            $pdo = $this->getDb()->prepare("INSERT INTO commande_produit VALUES (:idProduit, :quantite, :idCommande)");
            $pdo->execute(array('idProduit' => $idProduit, 'quantite' => $quantite, 'idCommande' => $idCommande));
        }

        $qrCodeFichier = random_str(50) . ".png";
        QRcode::png("http://192.168.43.246/easyorder/rest/getCommande/".$idCommande, './qrcodes/'.$qrCodeFichier , QR_ECLEVEL_H, 10);

        $pdo = $this->getDb()->prepare("UPDATE commande SET nom_fichier_qr_code = :nomFichier WHERE id_commande = :idCommande");
        $pdo->execute(array('nomFichier' => $qrCodeFichier, 'idCommande' => $idCommande));

        return $idCommande;
    }

    public function getDetailsCommande($idCommande) {
        $data = array();
        $pdo = $this->getDb()->prepare("SELECT p.* FROM produit p INNER JOIN commande_produit c ON p.id_produit = c.id_produit WHERE c.id_commande = :idCommande");
        $pdo->execute(array('idCommande' => $idCommande));

        $data['produits'] = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $pdo = $this->getDb()->prepare("SELECT * FROM commande WHERE id_commande = :idCommande");

        $pdo->execute(array('idCommande' => $idCommande));
        $data['commande'] = $pdo->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


}