package fr.univamu.productAndUser;

/**
 * @class User
 * Définit ce qu'est un utilisateur.
 */
public class User {

    /**
     * Nom.
     */
    protected String name;

    /**
     * Mot de passe.
     */
    protected String password;

    /**
     * Email.
     */
    protected String email;

    /**
     * Constructeur.
     * @param name
     * @param password
     * @param email
     */
    public User(String name, String password, String email) {
        this.name = name;
        this.password = password;
        this.email = email;
    }

    /**
     * Getters et setters.
     * @return
     */
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    @Override
    public String toString() {
        return "User{" +
                ", name='" + name + '\'' +
                ", password='" + password + '\'' +
                ", email='" + email + '\'' +
                '}';
    }
}
