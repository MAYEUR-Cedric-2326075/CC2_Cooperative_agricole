package fr.coop.agricole.panier.model;

import jakarta.persistence.*;
import java.io.Serializable;

/**
 * Entité représentant le contenu d'un panier (association entre un panier et un produit).
 */
@Entity
@Table(name = "contenu_paniers")
public class ContenuPanier implements Serializable {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "panier_id", nullable = false)
    private Panier panier;

    @Column(name = "produit_id", nullable = false)
    private Long produitId;

    @Column(nullable = false)
    private Double quantite;

    // Constructeurs
    public ContenuPanier() {
    }

    // Getters et Setters
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Panier getPanier() {
        return panier;
    }

    public void setPanier(Panier panier) {
        this.panier = panier;
    }

    public Long getProduitId() {
        return produitId;
    }

    public void setProduitId(Long produitId) {
        this.produitId = produitId;
    }

    public Double getQuantite() {
        return quantite;
    }

    public void setQuantite(Double quantite) {
        this.quantite = quantite;
    }
}