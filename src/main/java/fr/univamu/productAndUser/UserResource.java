package fr.univamu.productAndUser;

import jakarta.inject.Inject;
import jakarta.ws.rs.*;

@Path("/users")
public class UserResource {
    private UserService userService;

    public UserResource() {}
    public @Inject UserResource(ProductAndUserRepositoryInterface prodAndUserRepository) {
        this.userService = new UserService(prodAndUserRepository);
    }

    @GET
    @Produces("application/json")
    public String getAllUsers() {
        return userService.getAllUsersJSON();
    }

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
