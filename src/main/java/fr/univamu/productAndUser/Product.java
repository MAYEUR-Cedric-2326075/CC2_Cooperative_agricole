package fr.univamu.productAndUser;

/**
 * @class Product
 * Définit ce qu'est un produit.
 */
public class Product {
    /**
     * Référence du produit
     */
    protected String reference;

    /**
     * Nom du produit.
     */
    protected String name;

    /**
     * Prix du produit.
     */
    protected String price;

    /**
     * Statut du produit : 'r' pour "en rupture de stock" et 'd' pour "disponible".
     */
    protected char status;

    public Product() {}

    /**
     * Constructeur
     * @param reference  Référence du produit.
     * @param name       Nom du produit.
     * @param price             Prix du produit.
     */
    public Product(String reference, String name, String price) {
        this.reference = reference;
        this.name = name;
        this.price = price;
        this.status = 'd';
    }

    /**
     * Getters et setters.
     * @return
     */
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getPrice() {
        return price;
    }

    public void setPrice(String price) {
        this.price = price;
    }

    public char getStatus() {
        return status;
    }

    public void setStatus(char status) {
        this.status = status;
    }

    public String getReference() {
        return reference;
    }

    public void setReference(String reference) {
        this.reference = reference;
    }

    @Override
    public String toString() {
        return "Product{" +
                "reference='" + reference + '\'' +
                ", name='" + name + '\'' +
                ", price=" + price +
                ", status=" + status +
                '}';
    }
}
