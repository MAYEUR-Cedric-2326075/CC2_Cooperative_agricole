package fr.univamu.productAndUser;

/**
 * @class UserAuthenticationService
 * Classe du service d'authentification utilisateur.
 */
public class UserAuthenticationService {
    protected ProductAndUserRepositoryInterface productAndUserRepositoryInterface;

    /**
     * Constructeur.
     * @param productAndUserRepositoryInterface
     */
    public UserAuthenticationService(ProductAndUserRepositoryInterface productAndUserRepositoryInterface) {
        this.productAndUserRepositoryInterface = productAndUserRepositoryInterface;
    }

    /**
     * Vérifie si un utilisateur est valide.
     * @param email
     * @param password
     * @return
     */
    public boolean isValidUser(String email, String password) {
        User user = productAndUserRepositoryInterface.getUser(email);

        if (user == null)
            return false;
        if (!user.password.equals(password))
            return false;

        return true;
    }
}
