package fr.univamu.productAndUser;

import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;

import java.util.ArrayList;

/**
 * @class ProductService
 */
public class ProductService {
    protected ProductAndUserRepositoryInterface productAndUserRepository;

    /**
     * Constructeur.
     * @param productAndUserRepository
     */
    public ProductService(ProductAndUserRepositoryInterface productAndUserRepository) {
        this.productAndUserRepository = productAndUserRepository;
    }

    /**
     * Récupère tous les produits existants.
     * @return le fichier JSON qui contient tous les produits existants.
     */
    public String getAllProductsJSON(){
        ArrayList<Product> allProducts = productAndUserRepository.getAllProducts();

        String result = null;
        try(Jsonb jsonb = JsonbBuilder.create()){
            result = jsonb.toJson(allProducts);
        }catch (Exception e){
            System.err.println(e.getMessage());
        }
        return result;
    }

    /**
     * Récupère un produit particulier.
     * @param reference
     * @return un fichier JSON contenant des informations sur le produit.
     */
    public String getProductJSON(String reference){
        String result = null;
        Product product = productAndUserRepository.getProduct(reference);

        if(product != null){
            try(Jsonb jsonb = JsonbBuilder.create()){
                result = jsonb.toJson(product);
            }catch (Exception e){
                System.err.println(e.getMessage());
            }
        }
        return result;
    }

    /**
     * Met à jour d'un produit.
     * @param reference
     * @param product
     * @return le résultat de la mise à jour du produit.
     */
    public boolean updateProductJSON(String reference, Product product){
        return productAndUserRepository.updateProduct(reference, product.name, product.price, product.status);
    }
}
