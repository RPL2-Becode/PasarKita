<?php
class SessionSyncService {
    public function syncBalanceIfCurrentUser($userId, float $newBalance): void {
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
            $_SESSION['user_balance'] = $newBalance;
        }
    }
}
?>
