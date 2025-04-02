package fr.univamu.productAndUser;

import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Response;

/**
 * @class ProductResource
 * Classe de l'API Produit.
 */
@Path("/products")
public class ProductResource {

    /**
     * Pour le fichier JSON.
     */
    private ProductService productService;

    /**
     * Constructeur par défaut.
     */
    public ProductResource() {}

    /**
     * Constructeur
     * @param productAndUserRepository
     */
    public @Inject ProductResource(ProductAndUserRepositoryInterface productAndUserRepository) {
        this.productService = new ProductService(productAndUserRepository);
    }

    /**
     * Récupère tous les produits.
     * @return tous les produits sous forme de chaîne de caractères.
     */
    @GET
    @Produces("application/json")
    public String getAllProducts() {
        return productService.getAllProductsJSON();
    }

    /**
     * Récupère un produit particulier.
     * @param reference
     * @return le produit sous forme de chaîne de caractères.
     */
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

    /**
     * Met à jour un produit.
     * @param reference
     * @param product
     * @return si le produit a bien été mis à jour, sinon lance une NotFoundException.
     */
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