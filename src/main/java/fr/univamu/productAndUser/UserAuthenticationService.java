package fr.univamu.productAndUser;

public class UserAuthenticationService {
    protected ProductAndUserRepositoryInterface productAndUserRepositoryInterface;

    public UserAuthenticationService(ProductAndUserRepositoryInterface productAndUserRepositoryInterface) {
        this.productAndUserRepositoryInterface = productAndUserRepositoryInterface;
    }

    public boolean isValidUser(String email, String password) {
        User user = productAndUserRepositoryInterface.getUser(email);

        if (user == null)
            return false;
        if (!user.password.equals(password))
            return false;

        return true;
    }
}
