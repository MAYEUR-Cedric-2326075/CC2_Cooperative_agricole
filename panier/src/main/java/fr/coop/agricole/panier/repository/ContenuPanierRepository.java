package fr.coop.agricole.panier.repository;

import fr.coop.agricole.panier.model.ContenuPanier;
import jakarta.ejb.Stateless;
import jakarta.persistence.EntityManager;
import jakarta.persistence.PersistenceContext;
import jakarta.persistence.TypedQuery;
import java.util.List;
import java.util.Optional;

/**
 * Repository pour l'entité ContenuPanier.
 */
@Stateless
public class ContenuPanierRepository {

    @PersistenceContext(unitName = "panierPU")
    private EntityManager em;

    /**
     * Enregistre un nouveau contenu de panier.
     *
     * @param contenu Le contenu à enregistrer
     * @return Le contenu enregistré avec son ID généré
     */
    public ContenuPanier save(ContenuPanier contenu) {
        if (contenu.getId() == null) {
            em.persist(contenu);
            return contenu;
        } else {
            return em.merge(contenu);
        }
    }

    /**
     * Récupère un contenu de panier par son ID.
     *
     * @param id L'ID du contenu
     * @return Le contenu s'il existe, Optional.empty() sinon
     */
    public Optional<ContenuPanier> findById(Long id) {
        ContenuPanier contenu = em.find(ContenuPanier.class, id);
        return Optional.ofNullable(contenu);
    }

    /**
     * Récupère tous les contenus d'un panier.
     *
     * @param panierId L'ID du panier
     * @return La liste des contenus du panier
     */
    public List<ContenuPanier> findByPanierId(Long panierId) {
        TypedQuery<ContenuPanier> query = em.createQuery(
                "SELECT c FROM ContenuPanier c WHERE c.panier.id = :panierId", ContenuPanier.class);
        query.setParameter("panierId", panierId);
        return query.getResultList();
    }

    /**
     * Supprime un contenu de panier par son ID.
     *
     * @param id L'ID du contenu à supprimer
     * @return true si le contenu a été supprimé, false sinon
     */
    public boolean deleteById(Long id) {
        Optional<ContenuPanier> contenuOpt = findById(id);
        if (contenuOpt.isPresent()) {
            em.remove(contenuOpt.get());
            return true;
        }
        return false;
    }

    /**
     * Supprime tous les contenus d'un panier.
     *
     * @param panierId L'ID du panier
     */
    public void deleteByPanierId(Long panierId) {
        em.createQuery("DELETE FROM ContenuPanier c WHERE c.panier.id = :panierId")
                .setParameter("panierId", panierId)
                .executeUpdate();
    }
}