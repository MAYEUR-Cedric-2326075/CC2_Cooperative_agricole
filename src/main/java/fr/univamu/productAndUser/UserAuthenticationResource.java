package fr.univamu.productAndUser;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.ws.rs.GET;
import jakarta.ws.rs.Path;
import jakarta.ws.rs.Produces;
import jakarta.ws.rs.container.ContainerRequestContext;
import jakarta.ws.rs.core.Context;
import jakarta.ws.rs.core.Response;

import java.io.UnsupportedEncodingException;
import java.util.Base64;

@Path("/authenticate")
@ApplicationScoped
public class UserAuthenticationResource {
    private UserAuthenticationService userAuthenticationService;

    public UserAuthenticationResource() {}
    public @Inject UserAuthenticationResource(ProductAndUserRepositoryInterface productAndUserRepository) {
        this.userAuthenticationService = new UserAuthenticationService(productAndUserRepository);
    }

    @GET
    @Produces("text/plain")
    public Response authenticate(@Context ContainerRequestContext request) throws UnsupportedEncodingException {
        boolean res;

        String authHeader = request.getHeaderString("Authorization");
        if (authHeader != null || !authHeader.startsWith("Basic")) {
            return Response.status(Response.Status.UNAUTHORIZED).header("WWW-Authenticate", "Basic").build();
        }

        String[] tokens = (new String(Base64.getDecoder().decode(authHeader.split(" ")[1]), "UTF-8")).split(":");
        final String email = tokens[0];
        final String password = tokens[1];

        res = userAuthenticationService.isValidUser(email, password);

        return Response.ok(String.valueOf(res)).build();
    }
}
