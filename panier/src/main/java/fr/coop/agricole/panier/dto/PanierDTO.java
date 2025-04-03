package fr.coop.agricole.panier.dto;

import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

/**
 * DTO pour représenter un panier avec ses produits.
 */
public class PanierDTO {

    private Long id;
    private String nom;
    private String description;
    private BigDecimal prix;
    private Integer quantiteDisponible;
    private Boolean estValide;
    private LocalDateTime dateDerniereMAJ;
    private List<ContenuPanierDTO> contenus = new ArrayList<>();

    // Constructeurs, getters et setters
    public PanierDTO() {
    }

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

    public List<ContenuPanierDTO> getContenus() {
        return contenus;
    }

    public void setContenus(List<ContenuPanierDTO> contenus) {
        this.contenus = contenus;
    }

    /**
     * Classe interne pour représenter le contenu d'un panier.
     */
    public static class ContenuPanierDTO {
        private Long id;
        private Long produitId;
        private String nomProduit;
        private String typeProduit;
        private Double quantite;
        private String unite;

        // Getters et setters
        public Long getId() {
            return id;
        }

        public void setId(Long id) {
            this.id = id;
        }

        public Long getProduitId() {
            return produitId;
        }

        public void setProduitId(Long produitId) {
            this.produitId = produitId;
        }

        public String getNomProduit() {
            return nomProduit;
        }

        public void setNomProduit(String nomProduit) {
            this.nomProduit = nomProduit;
        }

        public String getTypeProduit() {
            return typeProduit;
        }

        public void setTypeProduit(String typeProduit) {
            this.typeProduit = typeProduit;
        }

        public Double getQuantite() {
            return quantite;
        }

        public void setQuantite(Double quantite) {
            this.quantite = quantite;
        }

        public String getUnite() {
            return unite;
        }

        public void setUnite(String unite) {
            this.unite = unite;
        }
    }
}