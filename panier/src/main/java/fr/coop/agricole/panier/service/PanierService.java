package fr.coop.agricole.panier.service;

import fr.coop.agricole.panier.client.ProduitClient;
import fr.coop.agricole.panier.client.UtilisateurClient;
import fr.coop.agricole.panier.dto.PanierDTO;
import fr.coop.agricole.panier.dto.ProduitDTO;
import fr.coop.agricole.panier.model.ContenuPanier;
import fr.coop.agricole.panier.model.Panier;
import fr.coop.agricole.panier.repository.ContenuPanierRepository;
import fr.coop.agricole.panier.repository.PanierRepository;
import jakarta.ejb.Stateless;
import jakarta.inject.Inject;
import jakarta.transaction.Transactional;
import jakarta.ws.rs.NotFoundException;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

/**
 * Service métier pour la gestion des paniers.
 */
@Stateless
public class PanierService {

    @Inject
    private PanierRepository panierRepository;

    @Inject
    private ContenuPanierRepository contenuPanierRepository;

    @Inject
    private ProduitClient produitClient;

    @Inject
    private UtilisateurClient utilisateurClient;

    /**
     * Récupère tous les paniers.
     *
     * @return La liste des paniers convertis en DTO
     */
    public List<PanierDTO> getAllPaniers() {
        List<Panier> paniers = panierRepository.findAll();
        return paniers.stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }

    /**
     * Récupère tous les paniers validés.
     *
     * @return La liste des paniers validés convertis en DTO
     */
    public List<PanierDTO> getPaniersValides() {
        List<Panier> paniers = panierRepository.findAllValides();
        return paniers.stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }

    /**
     * Récupère un panier par son ID.
     *
     * @param id L'ID du panier
     * @return Le panier converti en DTO
     * @throws NotFoundException si le panier n'existe pas
     */
    public PanierDTO getPanierById(Long id) {
        Panier panier = panierRepository.findById(id)
                .orElseThrow(() -> new NotFoundException("Panier non trouvé avec l'ID: " + id));

        return convertToDTO(panier);
    }

    /**
     * Crée un nouveau panier.
     *
     * @param panierDTO Les données du panier à créer
     * @param utilisateurId L'ID de l'utilisateur créant le panier
     * @return Le panier créé converti en DTO
     * @throws IllegalArgumentException si l'utilisateur n'est pas un gestionnaire
     */
    @Transactional
    public PanierDTO createPanier(PanierDTO panierDTO, Long utilisateurId) {
        if (!utilisateurClient.estGestionnaire(utilisateurId)) {
            throw new IllegalArgumentException("Seuls les gestionnaires peuvent créer des paniers");
        }

        Panier panier = new Panier();
        panier.setNom(panierDTO.getNom());
        panier.setDescription(panierDTO.getDescription());
        panier.setPrix(panierDTO.getPrix());
        panier.setQuantiteDisponible(panierDTO.getQuantiteDisponible());
        panier.setEstValide(false);  // Par défaut, un nouveau panier n'est pas validé
        panier.setDateDerniereMAJ(LocalDateTime.now());

        Panier savedPanier = panierRepository.save(panier);
        return convertToDTO(savedPanier);
    }

    /**
     * Met à jour un panier existant.
     *
     * @param id L'ID du panier à mettre à jour
     * @param panierDTO Les nouvelles données du panier
     * @param utilisateurId L'ID de l'utilisateur mettant à jour le panier
     * @return Le panier mis à jour converti en DTO
     * @throws NotFoundException si le panier n'existe pas
     * @throws IllegalArgumentException si l'utilisateur n'est pas un gestionnaire
     */
    @Transactional
    public PanierDTO updatePanier(Long id, PanierDTO panierDTO, Long utilisateurId) {
        if (!utilisateurClient.estGestionnaire(utilisateurId)) {
            throw new IllegalArgumentException("Seuls les gestionnaires peuvent modifier des paniers");
        }

        Panier panier = panierRepository.findById(id)
                .orElseThrow(() -> new NotFoundException("Panier non trouvé avec l'ID: " + id));

        panier.setNom(panierDTO.getNom());
        panier.setDescription(panierDTO.getDescription());
        panier.setPrix(panierDTO.getPrix());
        panier.setQuantiteDisponible(panierDTO.getQuantiteDisponible());
        panier.setDateDerniereMAJ(LocalDateTime.now());

        Panier updatedPanier = panierRepository.save(panier);
        return convertToDTO(updatedPanier);
    }

    /**
     * Supprime un panier.
     *
     * @param id L'ID du panier à supprimer
     * @param utilisateurId L'ID de l'utilisateur supprimant le panier
     * @return true si le panier a été supprimé, false sinon
     * @throws IllegalArgumentException si l'utilisateur n'est pas un gestionnaire
     */
    @Transactional
    public boolean deletePanier(Long id, Long utilisateurId) {
        if (!utilisateurClient.estGestionnaire(utilisateurId)) {
            throw new IllegalArgumentException("Seuls les gestionnaires peuvent supprimer des paniers");
        }

        return panierRepository.deleteById(id);
    }

    /**
     * Ajoute un produit à un panier.
     *
     * @param panierId L'ID du panier
     * @param produitId L'ID du produit à ajouter
     * @param quantite La quantité du produit à ajouter
     * @param utilisateurId L'ID de l'utilisateur ajoutant le produit
     * @return Le panier mis à jour converti en DTO
     * @throws NotFoundException si le panier ou le produit n'existe pas
     * @throws IllegalArgumentException si l'utilisateur n'est pas un gestionnaire
     */
    @Transactional
    public PanierDTO ajouterProduitAuPanier(Long panierId, Long produitId, Double quantite, Long utilisateurId) {
        if (!utilisateurClient.estGestionnaire(utilisateurId)) {
            throw new IllegalArgumentException("Seuls les gestionnaires peuvent modifier le contenu des paniers");
        }

        Panier panier = panierRepository.findById(panierId)
                .orElseThrow(() -> new NotFoundException("Panier non trouvé avec l'ID: " + panierId));

        // Vérifier si le produit existe
        ProduitDTO produit = produitClient.getProduitById(produitId)
                .orElseThrow(() -> new NotFoundException("Produit non trouvé avec l'ID: " + produitId));

        // Vérifier si le produit est déjà dans le panier
        Optional<ContenuPanier> contenuExistant = panier.getContenus().stream()
                .filter(c -> c.getProduitId().equals(produitId))
                .findFirst();

        if (contenuExistant.isPresent()) {
            // Mettre à jour la quantité
            ContenuPanier contenu = contenuExistant.get();
            contenu.setQuantite(quantite);
            contenuPanierRepository.save(contenu);
        } else {
            // Ajouter un nouveau produit
            panier.ajouterProduit(produitId, quantite);
        }

        panierRepository.save(panier);
        return convertToDTO(panier);
    }

    /**
     * Supprime un produit d'un panier.
     *
     * @param panierId L'ID du panier
     * @param produitId L'ID du produit à supprimer
     * @param utilisateurId L'ID de l'utilisateur supprimant le produit
     * @return Le panier mis à jour converti en DTO
     * @throws NotFoundException si le panier n'existe pas
     * @throws IllegalArgumentException si l'utilisateur n'est pas un gestionnaire
     */
    @Transactional
    public PanierDTO supprimerProduitDuPanier(Long panierId, Long produitId, Long utilisateurId) {
        if (!utilisateurClient.estGestionnaire(utilisateurId)) {
            throw new IllegalArgumentException("Seuls les gestionnaires peuvent modifier le contenu des paniers");
        }

        Panier panier = panierRepository.findById(panierId)
                .orElseThrow(() -> new NotFoundException("Panier non trouvé avec l'ID: " + panierId));

        boolean removed = panier.supprimerProduit(produitId);
        if (!removed) {
            throw new NotFoundException("Produit non trouvé dans le panier avec l'ID: " + produitId);
        }

        panierRepository.save(panier);
        return convertToDTO(panier);
    }

    /**
     * Valide un panier pour qu'il puisse être commandé.
     *
     * @param panierId L'ID du panier à valider
     * @param utilisateurId L'ID de l'utilisateur validant le panier
     * @return Le panier validé converti en DTO
     * @throws NotFoundException si le panier n'existe pas
     * @throws IllegalArgumentException si l'utilisateur n'est pas un gestionnaire
     */
    @Transactional
    public PanierDTO validerPanier(Long panierId, Long utilisateurId) {
        if (!utilisateurClient.estGestionnaire(utilisateurId)) {
            throw new IllegalArgumentException("Seuls les gestionnaires peuvent valider des paniers");
        }

        Panier panier = panierRepository.findById(panierId)
                .orElseThrow(() -> new NotFoundException("Panier non trouvé avec l'ID: " + panierId));

        panier.valider();
        panierRepository.save(panier);

        return convertToDTO(panier);
    }

    /**
     * Invalide un panier pour qu'il ne puisse plus être commandé.
     *
     * @param panierId L'ID du panier à invalider
     * @param utilisateurId L'ID de l'utilisateur invalidant le panier
     * @return Le panier invalidé converti en DTO
     * @throws NotFoundException si le panier n'existe pas
     * @throws IllegalArgumentException si l'utilisateur n'est pas un gestionnaire
     */
    @Transactional
    public PanierDTO invaliderPanier(Long panierId, Long utilisateurId) {
        if (!utilisateurClient.estGestionnaire(utilisateurId)) {
            throw new IllegalArgumentException("Seuls les gestionnaires peuvent invalider des paniers");
        }

        Panier panier = panierRepository.findById(panierId)
                .orElseThrow(() -> new NotFoundException("Panier non trouvé avec l'ID: " + panierId));

        panier.invalider();
        panierRepository.save(panier);

        return convertToDTO(panier);
    }

    /**
     * Convertit un panier en DTO.
     *
     * @param panier Le panier à convertir
     * @return Le DTO correspondant
     */
    private PanierDTO convertToDTO(Panier panier) {
        PanierDTO dto = new PanierDTO();
        dto.setId(panier.getId());
        dto.setNom(panier.getNom());
        dto.setDescription(panier.getDescription());
        dto.setPrix(panier.getPrix());
        dto.setQuantiteDisponible(panier.getQuantiteDisponible());
        dto.setEstValide(panier.getEstValide());
        dto.setDateDerniereMAJ(panier.getDateDerniereMAJ());

        List<PanierDTO.ContenuPanierDTO> contenuDTOs = new ArrayList<>();

        for (ContenuPanier contenu : panier.getContenus()) {
            PanierDTO.ContenuPanierDTO contenuDTO = new PanierDTO.ContenuPanierDTO();
            contenuDTO.setId(contenu.getId());
            contenuDTO.setProduitId(contenu.getProduitId());
            contenuDTO.setQuantite(contenu.getQuantite());

            // Récupérer les informations du produit
            produitClient.getProduitById(contenu.getProduitId()).ifPresent(produit -> {
                contenuDTO.setNomProduit(produit.getNom());
                contenuDTO.setTypeProduit(produit.getTypeProduit());
                contenuDTO.setUnite(produit.getUnite());
            });

            contenuDTOs.add(contenuDTO);
        }

        dto.setContenus(contenuDTOs);

        return dto;
    }
}