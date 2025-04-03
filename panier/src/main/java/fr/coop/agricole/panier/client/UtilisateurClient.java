package fr.coop.agricole.panier.client;

import fr.coop.agricole.panier.dto.UtilisateurDTO;
import jakarta.annotation.PostConstruct;
import jakarta.ejb.Singleton;
import jakarta.ws.rs.client.Client;
import jakarta.ws.rs.client.ClientBuilder;
import jakarta.ws.rs.core.GenericType;
import jakarta.ws.rs.core.MediaType;
import java.util.List;
import java.util.Optional;

/**
 * Client pour consommer l'API des utilisateurs.
 */
@Singleton
public class UtilisateurClient {

    private Client client;
    private static final String API_BASE_URL = "http://localhost:8080/produits-api/api";

    @PostConstruct
    public void init() {
        client = ClientBuilder.newClient();
    }

    /**
     * Récupère un utilisateur par son ID.
     *
     * @param id L'ID de l'utilisateur
     * @return L'utilisateur s'il existe, Optional.empty() sinon
     */
    public Optional<UtilisateurDTO> getUtilisateurById(Long id) {
        try {
            UtilisateurDTO utilisateur = client.target(API_BASE_URL)
                    .path("/utilisateurs/{id}")
                    .resolveTemplate("id", id)
                    .request(MediaType.APPLICATION_JSON)
                    .get(UtilisateurDTO.class);
            return Optional.ofNullable(utilisateur);
        } catch (Exception e) {
            System.err.println("Erreur lors de la récupération de l'utilisateur: " + e.getMessage());
            return Optional.empty();
        }
    }

    /**
     * Vérifie si un utilisateur est un gestionnaire.
     *
     * @param id L'ID de l'utilisateur
     * @return true si l'utilisateur est un gestionnaire, false sinon
     */
    public boolean estGestionnaire(Long id) {
        Optional<UtilisateurDTO> utilisateurOpt = getUtilisateurById(id);
        return utilisateurOpt.map(u -> "GESTIONNAIRE".equalsIgnoreCase(u.getRole())).orElse(false);
    }

    /**
     * Vérifie si un utilisateur existe.
     *
     * @param id L'ID de l'utilisateur
     * @return true si l'utilisateur existe, false sinon
     */
    public boolean existeUtilisateur(Long id) {
        return getUtilisateurById(id).isPresent();
    }
}