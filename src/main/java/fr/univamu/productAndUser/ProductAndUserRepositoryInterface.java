package fr.univamu.productAndUser;

import java.util.ArrayList;

public interface ProductAndUserRepositoryInterface {
    void close();
    Product getProduct(String reference);
    ArrayList<Product> getAllProducts();
    boolean updateProduct(String reference, String name, String price, char status);
    User getUser(String email);
    ArrayList<User> getAllUsers();
}
