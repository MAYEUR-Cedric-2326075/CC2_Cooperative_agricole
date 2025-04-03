package fr.coop.agricole.panier.resource;

import fr.coop.agricole.panier.dto.PanierDTO;
import fr.coop.agricole.panier.service.PanierService;
import jakarta.ejb.EJB;
import jakarta.validation.Valid;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Context;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import jakarta.ws.rs.core.UriInfo;
import java.net.URI;
import java.util.List;

/**
 * Resource REST pour les paniers.
 */
@Path("/paniers")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class PanierResource {

    @EJB
    private PanierService panierService;

    @Context
    private UriInfo uriInfo;

    /**
     * Récupère tous les paniers.
     *
     * @return La liste des paniers
     */
    @GET
    public Response getAllPaniers() {
        List<PanierDTO> paniers = panierService.getAllPaniers();
        return Response.ok(paniers).build();
    }

    /**
     * Récupère les paniers validés.
     *
     * @return La liste des paniers validés
     */
    @GET
    @Path("/valides")
    public Response getPaniersValides() {
        List<PanierDTO> paniers = panierService.getPaniersValides();
        return Response.ok(paniers).build();
    }

    /**
     * Récupère un panier par son ID.
     *
     * @param id L'ID du panier
     * @return Le panier correspondant
     */
    @GET
    @Path("/{id}")
    public Response getPanierById(@PathParam("id") Long id) {
        PanierDTO panier = panierService.getPanierById(id);
        return Response.ok(panier).build();
    }

    /**
     * Crée un nouveau panier.
     *
     * @param panierDTO Les données du panier à créer
     * @param utilisateurId L'ID de l'utilisateur (doit être un gestionnaire)
     * @return Le panier créé
     */
    @POST
    public Response createPanier(@Valid PanierDTO panierDTO, @HeaderParam("X-Utilisateur-Id") Long utilisateurId) {
        if (utilisateurId == null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("L'ID de l'utilisateur est requis")
                    .build();
        }

        PanierDTO createdPanier = panierService.createPanier(panierDTO, utilisateurId);
        URI location = uriInfo.getAbsolutePathBuilder().path(createdPanier.getId().toString()).build();
        return Response.created(location).entity(createdPanier).build();
    }

    /**
     * Met à jour un panier existant.
     *
     * @param id L'ID du panier à mettre à jour
     * @param panierDTO Les nouvelles données du panier
     * @param utilisateurId L'ID de l'utilisateur (doit être un gestionnaire)
     * @return Le panier mis à jour
     */
    @PUT
    @Path("/{id}")
    public Response updatePanier(
            @PathParam("id") Long id,
            @Valid PanierDTO panierDTO,
            @HeaderParam("X-Utilisateur-Id") Long utilisateurId) {

        if (utilisateurId == null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("L'ID de l'utilisateur est requis")
                    .build();
        }

        PanierDTO updatedPanier = panierService.updatePanier(id, panierDTO, utilisateurId);
        return Response.ok(updatedPanier).build();
    }

    /**
     * Supprime un panier.
     *
     * @param id L'ID du panier à supprimer
     * @param utilisateurId L'ID de l'utilisateur (doit être un gestionnaire)
     * @return 204 No Content si le panier a été supprimé, 404 Not Found sinon
     */
    @DELETE
    @Path("/{id}")
    public Response deletePanier(
            @PathParam("id") Long id,
            @HeaderParam("X-Utilisateur-Id") Long utilisateurId) {

        if (utilisateurId == null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("L'ID de l'utilisateur est requis")
                    .build();
        }

        boolean deleted = panierService.deletePanier(id, utilisateurId);
        if (deleted) {
            return Response.noContent().build();
        } else {
            return Response.status(Response.Status.NOT_FOUND)
                    .entity("Panier non trouvé avec l'ID: " + id)
                    .build();
        }
    }

    /**
     * Ajoute un produit à un panier.
     *
     * @param panierId L'ID du panier
     * @param produitId L'ID du produit à ajouter
     * @param quantite La quantité du produit
     * @param utilisateurId L'ID de l'utilisateur (doit être un gestionnaire)
     * @return Le panier mis à jour
     */
    @POST
    @Path("/{panierId}/produits/{produitId}")
    public Response ajouterProduitAuPanier(
            @PathParam("panierId") Long panierId,
            @PathParam("produitId") Long produitId,
            @QueryParam("quantite") Double quantite,
            @HeaderParam("X-Utilisateur-Id") Long utilisateurId) {

        if (utilisateurId == null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("L'ID de l'utilisateur est requis")
                    .build();
        }

        if (quantite == null || quantite <= 0) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("La quantité doit être supérieure à 0")
                    .build();
        }

        PanierDTO updatedPanier = panierService.ajouterProduitAuPanier(panierId, produitId, quantite, utilisateurId);
        return Response.ok(updatedPanier).build();
    }

    /**
     * Supprime un produit d'un panier.
     *
     * @param panierId L'ID du panier
     * @param produitId L'ID du produit à supprimer
     * @param utilisateurId L'ID de l'utilisateur (doit être un gestionnaire)
     * @return Le panier mis à jour
     */
    @DELETE
    @Path("/{panierId}/produits/{produitId}")
    public Response supprimerProduitDuPanier(
            @PathParam("panierId") Long panierId,
            @PathParam("produitId") Long produitId,
            @HeaderParam("X-Utilisateur-Id") Long utilisateurId) {

        if (utilisateurId == null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("L'ID de l'utilisateur est requis")
                    .build();
        }

        PanierDTO updatedPanier = panierService.supprimerProduitDuPanier(panierId, produitId, utilisateurId);
        return Response.ok(updatedPanier).build();
    }

    /**
     * Valide un panier.
     *
     * @param id L'ID du panier à valider
     * @param utilisateurId L'ID de l'utilisateur (doit être un gestionnaire)
     * @return Le panier validé
     */
    @PUT
    @Path("/{id}/valider")
    public Response validerPanier(
            @PathParam("id") Long id,
            @HeaderParam("X-Utilisateur-Id") Long utilisateurId) {

        if (utilisateurId == null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("L'ID de l'utilisateur est requis")
                    .build();
        }

        PanierDTO panier = panierService.validerPanier(id, utilisateurId);
        return Response.ok(panier).build();
    }

    /**
     * Invalide un panier.
     *
     * @param id L'ID du panier à invalider
     * @param utilisateurId L'ID de l'utilisateur (doit être un gestionnaire)
     * @return Le panier invalidé
     */
    @PUT
    @Path("/{id}/invalider")
    public Response invaliderPanier(
            @PathParam("id") Long id,
            @HeaderParam("X-Utilisateur-Id") Long utilisateurId) {

        if (utilisateurId == null) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("L'ID de l'utilisateur est requis")
                    .build();
        }

        PanierDTO panier = panierService.invaliderPanier(id, utilisateurId);
        return Response.ok(panier).build();
    }
}