package fr.coop.agricole.panier.model;

import jakarta.persistence.*;
import java.io.Serializable;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.HashSet;
import java.util.Set;

/**
 * Entité représentant un panier de produits disponible à la vente.
 */
@Entity
@Table(name = "paniers")
public class Panier implements Serializable {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String nom;

    @Column(length = 1000)
    private String description;

    @Column(nullable = false, precision = 10, scale = 2)
    private BigDecimal prix;

    @Column(nullable = false)
    private Integer quantiteDisponible;

    @Column(nullable = false)
    private Boolean estValide;

    @Column(nullable = false)
    private LocalDateTime dateDerniereMAJ;

    @OneToMany(mappedBy = "panier", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<ContenuPanier> contenus = new HashSet<>();

    // Constructeurs
    public Panier() {
        this.dateDerniereMAJ = LocalDateTime.now();
        this.estValide = false;
    }

    // Getters et Setters
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public BigDecimal getPrix() {
        return prix;
    }

    public void setPrix(BigDecimal prix) {
        this.prix = prix;
    }

    public Integer getQuantiteDisponible() {
        return quantiteDisponible;
    }

    public void setQuantiteDisponible(Integer quantiteDisponible) {
        this.quantiteDisponible = quantiteDisponible;
    }

    public Boolean getEstValide() {
        return estValide;
    }

    public void setEstValide(Boolean estValide) {
        this.estValide = estValide;
    }

    public LocalDateTime getDateDerniereMAJ() {
        return dateDerniereMAJ;
    }

    public void setDateDerniereMAJ(LocalDateTime dateDerniereMAJ) {
        this.dateDerniereMAJ = dateDerniereMAJ;
    }

    public Set<ContenuPanier> getContenus() {
        return contenus;
    }

    public void setContenus(Set<ContenuPanier> contenus) {
        this.contenus = contenus;
    }

    /**
     * Ajoute un produit au panier avec la quantité spécifiée.
     * Met à jour la date de dernière modification.
     *
     * @param produitId ID du produit à ajouter
     * @param quantite Quantité du produit
     * @return L'objet ContenuPanier créé
     */
    public ContenuPanier ajouterProduit(Long produitId, Double quantite) {
        ContenuPanier contenu = new ContenuPanier();
        contenu.setPanier(this);
        contenu.setProduitId(produitId);
        contenu.setQuantite(quantite);

        this.contenus.add(contenu);
        this.dateDerniereMAJ = LocalDateTime.now();

        return contenu;
    }

    /**
     * Supprime un produit du panier.
     * Met à jour la date de dernière modification.
     *
     * @param produitId ID du produit à supprimer
     * @return true si le produit a été supprimé, false sinon
     */
    public boolean supprimerProduit(Long produitId) {
        boolean removed = this.contenus.removeIf(contenu -> contenu.getProduitId().equals(produitId));

        if (removed) {
            this.dateDerniereMAJ = LocalDateTime.now();
        }

        return removed;
    }

    /**
     * Valide le panier pour qu'il puisse être commandé.
     */
    public void valider() {
        this.estValide = true;
        this.dateDerniereMAJ = LocalDateTime.now();
    }

    /**
     * Invalide le panier pour qu'il ne puisse plus être commandé.
     */
    public void invalider() {
        this.estValide = false;
        this.dateDerniereMAJ = LocalDateTime.now();
    }
}