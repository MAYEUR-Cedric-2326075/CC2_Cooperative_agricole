package fr.univamu.productAndUser;

import jakarta.inject.Inject;
import jakarta.ws.rs.*;

/**
 * @class UserResource
 * Ressource utilisateur.
 */
@Path("/users")
public class UserResource {
    /**
     * JSON
     */
    private UserService userService;

    /**
     * Constructeur par défaut.
     */
    public UserResource() {}

    /**
     * Constructeur
     * @param prodAndUserRepository
     */
    public @Inject UserResource(ProductAndUserRepositoryInterface prodAndUserRepository) {
        this.userService = new UserService(prodAndUserRepository);
    }

    /**
     * Récupère tous les utilisateurs existants.
     * @return tous les utilisateurs existants sous forme de chaîne de caractère.
     */
    @GET
    @Produces("application/json")
    public String getAllUsers() {
        return userService.getAllUsersJSON();
    }

    /**
     * Récupère un utilisateur existant.
     * @param email
     * @return une chaîne de caractères avec les informations de l'utilisateur.
     */
    @GET
    @Path("{email}")
    @Produces("application/json")
    public String getUser(@PathParam("email") String email) {
        String result = userService.getUserJSON(email);

        if (result == null) {
            throw new NotFoundException();
        }
        return result;
    }


}
