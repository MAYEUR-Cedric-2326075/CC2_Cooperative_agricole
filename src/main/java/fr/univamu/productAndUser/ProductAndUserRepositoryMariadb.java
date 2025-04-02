package fr.univamu.productAndUser;

import java.io.Closeable;
import java.sql.*;
import java.util.ArrayList;

public class ProductAndUserRepositoryMariadb implements ProductAndUserRepositoryInterface, Closeable {
    protected Connection dbConnection;

    public ProductAndUserRepositoryMariadb(String infoConnection, String user, String password) throws SQLException, ClassNotFoundException {
        Class.forName("org.mariadb.jdbc.Driver");
        dbConnection = DriverManager.getConnection(infoConnection, user, password);
    }

    @Override
    public void close() {
        try{
            dbConnection.close();
        }catch(SQLException e){
            System.err.println(e.getMessage());
        }
    }

    @Override
    public Product getProduct(String reference) {
        Product product = null;
        String query = "SELECT * FROM Product WHERE reference = ?";

        try(PreparedStatement ps = dbConnection.prepareStatement(query)){
            ps.setString(1, reference);
            ResultSet rs = ps.executeQuery();

            if(rs.next()){
                String name = rs.getString("name");
                String price = rs.getString("price");
                char status = rs.getString("status").charAt(0);

                product = new Product(reference, name, price);
                product.setStatus(status);
            }
        }catch(SQLException e){
            throw new RuntimeException(e);
        }
        return product;
    }

    @Override
    public ArrayList<Product> getAllProducts() {
        ArrayList<Product> products;
        String query = "SELECT * FROM Product";

        try(PreparedStatement ps = dbConnection.prepareStatement(query)){
            ResultSet rs = ps.executeQuery();
            products = new ArrayList<>();

            while(rs.next()){
                String reference = rs.getString("reference");
                String name = rs.getString("name");
                String price = rs.getString("price");
                char status = rs.getString("status").charAt(0);

                Product product = new Product(reference, name, price);
                product.setStatus(status);

                products.add(product);
            }
        }catch(SQLException e){
            throw new RuntimeException(e);
        }
        return products;
    }

    @Override
    public boolean updateProduct(String reference, String name, String price, char status) {
        String query = "UPDATE Product SET name = ?, price = ? WHERE reference = ?";
        int nbRowModified = 0;

        try(PreparedStatement ps = dbConnection.prepareStatement(query)){
            ps.setString(1, name);
            ps.setString(2, price);
            ps.setString(3, String.valueOf(status));
            ps.setString(4, reference);

            nbRowModified = ps.executeUpdate();
        }catch(SQLException e){
            throw new RuntimeException(e);
        }
        return (nbRowModified != 0);
    }

    @Override
    public User getUser(String email) {
        User user = null;
        String query = "SELECT * FROM User WHERE email = ?";

        try(PreparedStatement ps = dbConnection.prepareStatement(query)){
            ps.setString(1, email);
            ResultSet rs = ps.executeQuery();

            if(rs.next()){
                String name = rs.getString("name");
                String password = rs.getString("password");

                user = new User(name, password, email);
            }
        }catch(SQLException e){
            throw new RuntimeException(e);
        }
        return user;
    }

    @Override
    public ArrayList<User> getAllUsers() {
        ArrayList<User> users;
        String query = "SELECT * FROM User";

        try(PreparedStatement ps = dbConnection.prepareStatement(query)){
            ResultSet rs = ps.executeQuery();
            users = new ArrayList<>();

            while(rs.next()){
                String name = rs.getString("name");
                String password = rs.getString("password");
                String email = rs.getString("email");

                User user = new User(name, password, email);

                users.add(user);
            }
        }catch(SQLException e){
            throw new RuntimeException(e);
        }
        return users;
    }
}
