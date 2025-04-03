package fr.coop.agricole.panier.config;

import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.core.Application;

/**
 * Configuration de l'application JAX-RS.
 */
@ApplicationPath("/api")
public class ApplicationConfig extends Application {
    // La classe est vide car JAX-RS détecte automatiquement toutes les ressources
}