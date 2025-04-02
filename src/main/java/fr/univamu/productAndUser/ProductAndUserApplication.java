package fr.univamu.productAndUser;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.Produces;

/**
 * @class ProductAndUserApplication
 * L'API Produit et Utilisateur.
 */
@ApplicationPath("/api")
@ApplicationScoped
public class ProductAndUserApplication {

    /**
     * Méthode d'ouverture de la connexion à la base de données.
     * @return la base de données
     */
    @Produces
    private ProductAndUserRepositoryInterface openDbConnection() {
        ProductAndUserRepositoryMariadb db = null;
        try{
            db = new ProductAndUserRepositoryMariadb("jdbc:mariadb://mysql-moui.alwaysdata.net/moui_produser_db", "moui_prodanduser", "#fan22Picsou");
        }catch (Exception e){
            System.err.println(e.getMessage());
        }
        return db;
    }

    /**
     * Méthode de fermeture de la connexion à la base de données.
     * @param productAndUserRepository
     */
    private void closeDbConnection(ProductAndUserRepositoryInterface productAndUserRepository) {
        productAndUserRepository.close();
    }
}
