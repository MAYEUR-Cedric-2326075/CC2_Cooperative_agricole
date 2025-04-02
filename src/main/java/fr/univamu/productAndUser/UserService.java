package fr.univamu.productAndUser;

import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;

import java.util.ArrayList;

/**
 * @class UserService
 * JSON.
 */
public class UserService {
    protected ProductAndUserRepositoryInterface prodAndUserRepository;

    /**
     * Constructeur.
     * @param prodAndUserRepository
     */
    public UserService(ProductAndUserRepositoryInterface prodAndUserRepository) {
        this.prodAndUserRepository = prodAndUserRepository;
    }

    /**
     * Récupère tous les utilisateurs existants.
     * @return un fichier JSON.
     */
    public String getAllUsersJSON(){
        ArrayList<User> allUsers = prodAndUserRepository.getAllUsers();

        for(User u : allUsers){
            u.setEmail("");
            u.setPassword("");
        }

        String result = null;
        try(Jsonb jsonb = JsonbBuilder.create()){
            result = jsonb.toJson(allUsers);
        }catch(Exception e){
            System.err.println(e.getMessage());
        }
        return result;
    }

    /**
     * Récupère un utilisateur particulier.
     * @param email
     * @return un fichier JSON.
     */
    public String getUserJSON(String email){
        User user = prodAndUserRepository.getUser(email);
        String result = null;

        if(user != null){
            try(Jsonb jsonb = JsonbBuilder.create()){
                result = jsonb.toJson(user);
            }catch (Exception e){
                System.err.println(e.getMessage());
            }
        }
        return result;
    }
}
