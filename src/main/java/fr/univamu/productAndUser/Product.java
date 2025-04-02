package fr.univamu.productAndUser;

public class Product {
    protected String reference;
    protected String name;
    protected String price;
    protected char status;

    public Product() {}

    public Product(String reference, String name, String price) {
        this.reference = reference;
        this.name = name;
        this.price = price;
        this.status = 'd';
    }

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
