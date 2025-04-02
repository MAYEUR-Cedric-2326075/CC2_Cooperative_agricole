package fr.univamu.productAndUser;

import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;

import java.util.ArrayList;

public class UserService {
    protected ProductAndUserRepositoryInterface prodAndUserRepository;

    public UserService(ProductAndUserRepositoryInterface prodAndUserRepository) {
        this.prodAndUserRepository = prodAndUserRepository;
    }

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
