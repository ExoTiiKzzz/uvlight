<?php 

class ArticleQuantite{
    private $produit_id = 0;
    private $quantite = 0;

    public function __construct(int $produit_id, int $quantite)
    {
        $this->produit_id = $produit_id;
        $this->quantite = $quantite;
    }

    public function get_produit_id(){
        return $this->produit_id;
    }

    public function set_produit_id($produit_id){
        $produit_id = (int) $produit_id;
        if($produit_id){
            $this->produit_id = $produit_id;
        }
    }

    public function get_quantite(){
        return $this->quantite;
    }

    public function set_quantite($quant){
        $quant = (int) $quant;
        if($quant){
            $this->quantite = $quant;
        }
    }
}

?>