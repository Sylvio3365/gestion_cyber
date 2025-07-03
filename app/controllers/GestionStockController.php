<?php
namespace app\controllers;

use Flight;

class GestionStockController {
    /**
     * Display stock products (all or low stock based on threshold)
     */
    public function showStock() {
        $threshold = Flight::request()->query['threshold'] ?? null;
        
        try {
            if ($threshold === null) {
                // No threshold provided, show all products
                $products = Flight::stockModel()->getStockProducts();
                $isLowStock = false;
                $threshold = 0; // Default for display purposes
            } else {
                // Threshold provided, show low stock products
                if (!is_numeric($threshold)) {
                    Flight::json(['error' => 'Le seuil doit être un nombre'], 400);
                    return;
                }
                $threshold = (int)$threshold;
                $products = Flight::stockModel()->getLowStockProducts($threshold);
                $isLowStock = true;
            }
            
            Flight::render('GestionStock', [
                'products' => $products,
                'threshold' => $threshold,
                'productCount' => count($products),
                'isLowStock' => $isLowStock
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'error' => 'Erreur lors de la récupération du stock',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API endpoint for low stock products
     */
    public function apiLowStock() {
        $threshold = Flight::request()->query['threshold'] ?? 10;
        
        if (!is_numeric($threshold)) {
            Flight::json(['error' => 'Le seuil doit être un nombre'], 400);
            return;
        }
        
        $threshold = (int)$threshold;
        
        try {
            $products = Flight::stockModel()->getLowStockProducts($threshold);
            
            Flight::json([
                'success' => true,
                'threshold' => $threshold,
                'count' => count($products),
                'products' => $products
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'error' => 'Erreur lors de la récupération du stock',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint for all stock products
     */
    public function AllStock() {
        try {
            $products = Flight::stockModel()->getStockProducts();
            
            Flight::json([
                'success' => true,
                'count' => count($products),
                'products' => $products
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'error' => 'Erreur lors de la récupération de tous les produits en stock',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}