package fr.coop.agricole.panier.client;

import fr.coop.agricole.panier.dto.ProduitDTO;
import jakarta.annotation.PostConstruct;
import jakarta.ejb.Singleton;
import jakarta.ws.rs.client.Client;
import jakarta.ws.rs.client.ClientBuilder;
import jakarta.ws.rs.core.GenericType;
import jakarta.ws.rs.core.MediaType;
import java.util.List;
import java.util.Optional;

/**
 * Client pour consommer l'API des produits.
 */
@Singleton
public class ProduitClient {

    private Client client;
    private static final String API_BASE_URL = "http://localhost:8080/produits-api/api";

    @PostConstruct
    public void init() {
        client = ClientBuilder.newClient();
    }

    /**
     * Récupère un produit par son ID.
     *
     * @param id L'ID du produit
     * @return Le produit s'il existe, Optional.empty() sinon
     */
    public Optional<ProduitDTO> getProduitById(Long id) {
        try {
            ProduitDTO produit = client.target(API_BASE_URL)
                    .path("/produits/{id}")
                    .resolveTemplate("id", id)
                    .request(MediaType.APPLICATION_JSON)
                    .get(ProduitDTO.class);
            return Optional.ofNullable(produit);
        } catch (Exception e) {
            System.err.println("Erreur lors de la récupération du produit: " + e.getMessage());
            return Optional.empty();
        }
    }

    /**
     * Récupère tous les produits.
     *
     * @return La liste de tous les produits
     */
    public List<ProduitDTO> getAllProduits() {
        try {
            return client.target(API_BASE_URL)
                    .path("/produits")
                    .request(MediaType.APPLICATION_JSON)
                    .get(new GenericType<List<ProduitDTO>>() {});
        } catch (Exception e) {
            System.err.println("Erreur lors de la récupération des produits: " + e.getMessage());
            throw new RuntimeException("Erreur lors de la récupération des produits", e);
        }
    }
}