SELECT stockfinal.art_ID, stockfinal.art_commentaire, stockfinal.art_nom, stockfinal.fk_cat_ID, stockfinal.fk_cas_ID, stockfinal.stock 
FROM 
    (SELECT (achetee.quantite_achetee - vendue.quantite_vendue) as stock, achetee.quantite_achetee, vendue.quantite_vendue, art_ID, art_commentaire, art_nom, fk_cat_ID, fk_cas_ID
    FROM article, 
      (SELECT SUM(Lign_quantite) AS quantite_vendue, fk_art_ID FROM lignes_commande WHERE Lign_is_vente = 1 GROUP BY fk_art_ID ) AS vendue, 
      (SELECT SUM(Lign_quantite) AS quantite_achetee, fk_art_ID FROM lignes_commande WHERE Lign_is_vente = 0 GROUP BY fk_art_ID ) AS achetee
              
      WHERE vendue.fk_art_ID = article.art_ID AND achetee.fk_art_ID = article.art_ID GROUP BY art_ID) as stockfinal,

article
          
          

WHERE art_is_visible = 1 AND stockfinal.art_ID != 0
GROUP BY stockfinal.art_ID
ORDER BY stockfinal.art_ID ;