# Planning: PasarKita Marketplace Application

## 1. Project Overview
**PasarKita** is a marketplace application designed for UMKM (Micro, Small, and Medium Enterprises). It serves as a demand generator in a larger ecosystem, facilitating product discovery, sales, and transaction management.

## 2. Technology Stack
- **Backend**: PHP (Native with MVC structure).
- **Database**: MySQL.
- **Frontend**: HTML5, CSS3 (Tailwind CSS), JavaScript.
- **Authentication**: JWT (JSON Web Token).
- **Communication**: REST API with JSON format.

## 3. Database Schema (MySQL)
### Tables:
- `users`: id, username, password (hashed), role (consumen/admin/pelapak/operator), balance_id, created_at.
- `products`: id, seller_id, name, description, price, stock, image_url, category_id, created_at.
- `categories`: id, name.
- `orders`: id, buyer_id, total_subtotal, fee_marketplace, fee_shipping, total_payment, status, smartbank_trx_id, created_at.
- `order_items`: id, order_id, product_id, quantity, price_at_purchase.

## 4. Feature Requirements (Based on Kebutuhan Fungsional)
| Feature | Description | Endpoint |
|---------|-------------|----------|
| Management Produk | Pelapak can add, edit, or delete products. | `/marketplace/manajemen_produk` |
| Browse Produk | Consumen can view list of products. | `/marketplace/browse_produk` |
| Checkout | Consumen can purchase items from cart. | `/marketplace/checkout` |
| Integrasi Pembayaran | Sending payment request to SmartBank. | `/marketplace/integrasi_pembayaran` |
| Monitoring Transaksi | Admin Operator can monitor and update order status. | `/marketplace/status_order` |
| Manajemen User | Admin can manage accounts (Consumen, Pelapak, Operator). | `/marketplace/manajemen_user` |
| Dashboard Finansial | Admin can see global revenue and fees. | `/marketplace/dashboard_admin` |
| Biaya Layanan | Automatic deduction of 2% fee. | `/marketplace/biaya_layanan_marketplace` |

## 5. System Architecture (MVC)
- **/app**
    - **/models**: Handles database interactions.
    - **/views**: PHP templates for UI.
    - **/controllers**: Logic for routing and processing requests.
- **/public**: Entry point (index.php), assets (CSS/JS).
- **/config**: Database and JWT configuration.
- **/api**: REST API endpoints.

## 6. Development Milestones
1. **Phase 1: Environment Setup**
    - Setup folder structure.
    - Database initialization.
2. **Phase 2: Authentication & User Management**
    - Login/Register with JWT.
    - Role-based access control.
3. **Phase 3: Product Management**
    - CRUD functionality for sellers.
    - Image upload handling.
4. **Phase 4: Marketplace Core**
    - Catalog browsing and search.
    - Shopping cart logic.
5. **Phase 5: Checkout & Integration**
    - Checkout processing.
    - Integration logic with SmartBank API.
    - Order status updates.
6. **Phase 6: UI Refinement & Testing**
    - Integrating `Design_ui.html` into PHP views.
    - End-to-end testing.

## 7. Financial Logic
- **Total Payment** = Subtotal + (Subtotal * 2%) + Shipping Fee.
- **Shipping Fee** = Flat rate (Rp 5,000 as per design).
- **Payment** = Must trigger a request to SmartBank. Order only confirmed if SmartBank returns success.
