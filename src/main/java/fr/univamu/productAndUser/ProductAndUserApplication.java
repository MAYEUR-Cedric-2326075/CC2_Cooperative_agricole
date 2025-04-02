package fr.univamu.productAndUser;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.Produces;

@ApplicationPath("/api")
@ApplicationScoped
public class ProductAndUserApplication {
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

    private void closeDbConnection(ProductAndUserRepositoryInterface productAndUserRepository) {
        productAndUserRepository.close();
    }
}
