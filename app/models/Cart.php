<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
Use ishop\App;

/**
 * Description of Cart
 *
 * @author Sergey
 */
class Cart extends AppModel
{
    public function addToCart($product, $qty = 1, $mod = null) {
        if (!isset($_SESSION['cart.currency'])) {
             $_SESSION['cart.currency'] = App::$app->getProperty('currency'); 
        }
        if ($mod) {
            $ID = "{$product->id}-{$mod->id}";
            $title = "{$product->title} ({$mod->title})";
            $price = $mod->price;
        }else {
            $ID = $product->id;
            $title = $product->title;
            $price = $product->price;
        }
        if(!isset($_SESSION['cart'][$ID])) {
            $_SESSION['cart'][$ID] = [
                'qty' => $qty,
                'title' => $title,
                'alias' => $product->alias,
                'price' => $price * $_SESSION['cart.currency']['value'],
                'img' => $product->img,
            ];
        } else {
            $_SESSION['cart'][$ID]['qty'] += $qty;
        }
        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $price * $_SESSION['cart.currency']['value'] : $price * $_SESSION['cart.currency']['value'] * $qty;
  
    }
    
    public function deleteItem($id) {
        $qtyMinus = $_SESSION['cart'][$id]['qty'];
        $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
        $_SESSION['cart.qty'] -= $qtyMinus;
        $_SESSION['cart.sum'] -= $sumMinus;
        unset($_SESSION['cart'][$id]);
    }
    
    public static function recalc($curr) {
        if (isset($_SESSION['cart.currency'])) {
            if($_SESSION['cart.currency']['base']){
                $_SESSION['cart.sum'] *= $curr['value']; 
                
            }else {
                $_SESSION['cart.sum'] = $_SESSION['cart.sum'] / $_SESSION['cart.currency']['value'] * $curr['value'];
            }
            foreach ($_SESSION['cart'] as $id => $item) {
            if ($_SESSION['cart.currency']['base']) {
                $_SESSION['cart'][$id]['price'] *= $curr['value'];
            }else {
                $_SESSION['cart'][$id]['price'] = $_SESSION['cart'][$id]['price'] / $_SESSION['cart.currency']['value'] * $curr['value'];
                }
            }
            foreach ($curr as $k => $v) {
                $_SESSION['cart.currency'][$k] = $v;
            }
        }
    }
}
