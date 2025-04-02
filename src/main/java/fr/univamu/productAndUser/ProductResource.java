package fr.univamu.productAndUser;

import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Response;

@Path("/products")
public class ProductResource {
    private ProductService productService;

    public ProductResource() {}

    public @Inject ProductResource(ProductAndUserRepositoryInterface productAndUserRepository) {
        this.productService = new ProductService(productAndUserRepository);
    }

    @GET
    @Produces("application/json")
    public String getAllProducts() {
        return productService.getAllProductsJSON();
    }

    @GET
    @Path("{reference}")
    @Produces("application/json")
    public String getProduct(@PathParam("reference") String reference) {
        String result = productService.getProductJSON(reference);

        if (result == null) {
            throw new NotFoundException();
        }
        return result;
    }

    @PUT
    @Path("{reference}")
    @Consumes("application/json")
    public Response updateProduct(@PathParam("reference") String reference, Product product) {
        if(!productService.updateProductJSON(reference, product)) {
            throw new NotFoundException();
        }else{
            return Response.ok("updated").build();
        }
    }
}