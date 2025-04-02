package fr.univamu.productAndUser;

import java.util.ArrayList;

/**
 * @interface ProductAndUserRepositoryInterface
 * Interface regroupant les méthodes qui seront utilisées dans la classe ProductAndUserRepositoryMariadb.
 */
public interface ProductAndUserRepositoryInterface {
    void close();
    Product getProduct(String reference);
    ArrayList<Product> getAllProducts();
    boolean updateProduct(String reference, String name, String price, char status);
    User getUser(String email);
    ArrayList<User> getAllUsers();
}
