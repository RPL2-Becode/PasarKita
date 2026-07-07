<?php
require_once '../app/models/User_model.php';

class RevenueService {
    private const MARKETPLACE_FEE_RATE = 0.02;
    private $userModel;

    public function __construct() {
        $this->userModel = new User_model();
    }

    public function distributeSellerRevenue(array $items): void {
        foreach ($items as $item) {
            if (empty($item->seller_id)) { 
                continue; 
            }
            $itemTotal = $item->price_at_purchase * $item->quantity;
            $sellerRevenue = $itemTotal * (1 - self::MARKETPLACE_FEE_RATE);
            $this->userModel->addBalance($item->seller_id, $sellerRevenue);
        }
    }
}
?>
