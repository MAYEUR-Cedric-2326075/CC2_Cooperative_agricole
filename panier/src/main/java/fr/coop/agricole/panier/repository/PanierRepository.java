package fr.coop.agricole.panier.repository;

import fr.coop.agricole.panier.model.Panier;
import jakarta.ejb.Stateless;
import jakarta.persistence.EntityManager;
import jakarta.persistence.PersistenceContext;
import jakarta.persistence.TypedQuery;
import java.util.List;
import java.util.Optional;

/**
 * Repository pour l'entité Panier.
 */
@Stateless
public class PanierRepository {

    @PersistenceContext(unitName = "panierPU")
    private EntityManager em;

    /**
     * Enregistre un nouveau panier.
     *
     * @param panier Le panier à enregistrer
     * @return Le panier enregistré avec son ID généré
     */
    public Panier save(Panier panier) {
        if (panier.getId() == null) {
            em.persist(panier);
            return panier;
        } else {
            return em.merge(panier);
        }
    }

    /**
     * Récupère un panier par son ID.
     *
     * @param id L'ID du panier
     * @return Le panier s'il existe, Optional.empty() sinon
     */
    public Optional<Panier> findById(Long id) {
        Panier panier = em.find(Panier.class, id);
        return Optional.ofNullable(panier);
    }

    /**
     * Récupère tous les paniers.
     *
     * @return La liste de tous les paniers
     */
    public List<Panier> findAll() {
        TypedQuery<Panier> query = em.createQuery("SELECT p FROM Panier p", Panier.class);
        return query.getResultList();
    }

    /**
     * Récupère tous les paniers validés.
     *
     * @return La liste des paniers validés
     */
    public List<Panier> findAllValides() {
        TypedQuery<Panier> query = em.createQuery(
                "SELECT p FROM Panier p WHERE p.estValide = true", Panier.class);
        return query.getResultList();
    }

    /**
     * Supprime un panier par son ID.
     *
     * @param id L'ID du panier à supprimer
     * @return true si le panier a été supprimé, false sinon
     */
    public boolean deleteById(Long id) {
        Optional<Panier> panierOpt = findById(id);
        if (panierOpt.isPresent()) {
            em.remove(panierOpt.get());
            return true;
        }
        return false;
    }
}