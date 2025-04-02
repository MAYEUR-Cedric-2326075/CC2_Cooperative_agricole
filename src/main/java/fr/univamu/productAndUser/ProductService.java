package fr.univamu.productAndUser;

import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;

import java.util.ArrayList;

public class ProductService {
    protected ProductAndUserRepositoryInterface productAndUserRepository;

    public ProductService(ProductAndUserRepositoryInterface productAndUserRepository) {
        this.productAndUserRepository = productAndUserRepository;
    }

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

    public boolean updateProductJSON(String reference, Product product){
        return productAndUserRepository.updateProduct(reference, product.name, product.price, product.status);
    }
}
