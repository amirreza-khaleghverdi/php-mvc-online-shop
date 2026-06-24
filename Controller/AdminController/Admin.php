<?php

require_once 'Model/AdminLogin.php';
require_once 'Model/AdminData.php';

class AdminController
{
    private $adminLogin;
    private $adminData;

    public function __construct(AdminLogin $adminLogin, AdminData $adminData)
    {
        $this->adminLogin = $adminLogin;
        $this->adminData  = $adminData;
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=showAdminLogin");
            exit;
        }
    }

    public function showLoginPage()
    {
        include 'View/AdminLogin.php';
    }

    public function checkLogin()
    {
        $name    = trim($_POST['admin_name'] ?? '');
        $passkey = trim($_POST['passkey']    ?? '');

        if (empty($name) || empty($passkey)) {
            $_SESSION['error'] = "All fields are required";
            header("Location: index.php?action=showAdminLogin");
            exit;
        }

        $admin = $this->adminLogin->checkLogin($name, $passkey);

        if ($admin) {
            $_SESSION['admin_id']   = $admin['AdminID'];
            $_SESSION['admin_name'] = $admin['Name'];
            header("Location: index.php?action=admin");
            exit;
        } else {
            header("Location: index.php?action=showAdminLogin");
            exit;
        }
    }

    public function showAdminPage()
    {
        $this->checkAuth();
        include 'View/Admin.php';
    }

    // --- Products ---
    public function showProducts()
    {
        $this->checkAuth();
        $products = $this->adminData->getAllProducts();
        include 'View/Admin/AdminProducts.php';
    }

    public function addProduct()
    {
        $this->checkAuth();
        $result = $this->adminData->addProduct(
            $_POST['Title'],
            $_POST['Price'],
            $_POST['SKU'],
            $_POST['Descriptions'],
            $_POST['weight'],
            $_POST['image_url']
        );
        if ($result) {
            $_SESSION['message'] = "Product added successfully!";
        } else {
            $_SESSION['error'] = "Could not add product.";
        }
        header("Location: index.php?action=admin_products");
        exit;
    }

    public function showEditProduct(int $id)
    {
        $this->checkAuth();
        $product = $this->adminData->getProductById($id);
        include 'View/Admin/AdminEditProduct.php';
    }

    public function updateProduct(int $id)
    {
        $this->checkAuth();
        $result = $this->adminData->editProduct(
            $id,
            $_POST['Title'],
            $_POST['Price'],
            $_POST['SKU'],
            $_POST['Descriptions'],
            $_POST['weight'],
            $_POST['image_url']
        );
        if ($result) {
            $_SESSION['message'] = "Product updated successfully!";
        } else {
            $_SESSION['error'] = "Could not update product.";
        }
        header("Location: index.php?action=admin_products");
        exit;
    }

    public function deleteProduct(int $id)
    {
        $this->checkAuth();
        $result = $this->adminData->deleteProduct($id);
        if ($result) {
            $_SESSION['message'] = "Product deleted successfully!";
        } else {
            $_SESSION['error'] = "Could not delete product.";
        }
        header("Location: index.php?action=admin_products");
        exit;
    }

    // --- Customers ---
    public function showCustomers()
    {
        $this->checkAuth();
        $customers = $this->adminLogin->getAllCustomers();
        include 'View/Admin/AdminCustomers.php';
    }

    public function deleteCustomer(int $id)
    {
        $this->checkAuth();
        $result = $this->adminLogin->deleteCustomer($id);
        if ($result) {
            $_SESSION['message'] = "Customer deleted successfully!";
        } else {
            $_SESSION['error'] = $_SESSION['error'] ?? "Could not delete customer.";
        }
        header("Location: index.php?action=admin_customers");
        exit;
    }
    
    public function updateCustomer(int $id)
    {
        $this->checkAuth();
        $result = $this->adminLogin->editCustomer(
            $id,
            $_POST['FirstName'],
            $_POST['LastName'],
            $_POST['Email'],
            $_POST['PhoneNumber']
        );
        if ($result) {
            $_SESSION['message'] = "Customer updated successfully!";
        } else {
            $_SESSION['error'] = $_SESSION['error'] ?? "Could not update customer.";
        }
        header("Location: index.php?action=admin_customers");
        exit;
    }

    // --- Couriers ---
    public function showCouriers()
    {
        $this->checkAuth();
        $couriers = $this->adminLogin->getAllCouriers();
        include 'View/Admin/AdminCouriers.php';
    }

    public function deleteCourier(int $id)
    {
        $this->checkAuth();
        $result = $this->adminLogin->deleteCourier($id);
        if ($result) {
            $_SESSION['message'] = "Courier deleted successfully!";
        } else {
            $_SESSION['error'] = $_SESSION['error'] ?? "Could not delete courier.";
        }
        header("Location: index.php?action=admin_couriers");
        exit;
    }

    public function updateCourier(int $id)
    {
        $this->checkAuth();
        $result = $this->adminLogin->editCourier(
            $id,
            $_POST['FirstName'],
            $_POST['LastName'],
            $_POST['ContactNumber'],
            $_POST['NationalCode'],
            $_POST['VehicleType']
        );
        if ($result) {
            $_SESSION['message'] = "Courier updated successfully!";
        } else {
            $_SESSION['error'] = $_SESSION['error'] ?? "Could not update courier.";
        }
        header("Location: index.php?action=admin_couriers");
        exit;
    }

    // --- Orders ---
    public function showOrders()
    {
        $this->checkAuth();
        $orders = $this->adminData->getAllOrders();
        include 'View/Admin/AdminOrders.php';
    }

    public function updateOrderStatus(int $id)
    {
        $this->checkAuth();
        $result = $this->adminData->updateOrderStatus($id, $_POST['status']);
        if ($result) {
            $_SESSION['message'] = "Order status updated!";
        } else {
            $_SESSION['error'] = "Could not update order status.";
        }
        header("Location: index.php?action=admin_orders");
        exit;
    }

    // --- Inventory ---
    public function showInventory()
    {
        $this->checkAuth();
        $inventory = $this->adminData->getAllInventory();
        include 'View/Admin/AdminInventory.php';
    }

    public function updateInventory(int $id)
    {
        $this->checkAuth();
        $result = $this->adminData->updateInventory($id, (int)$_POST['AvailableStock'], (int)$_POST['ReservedStock']);
        if ($result) {
            $_SESSION['message'] = "Inventory updated!";
        } else {
            $_SESSION['error'] = "Could not update inventory.";
        }
        header("Location: index.php?action=admin_inventory");
        exit;
    }

    // --- Shipments ---
    public function showShipments()
    {
        $this->checkAuth();
        $shipments = $this->adminData->getAllShipments();
        $couriers  = $this->adminLogin->getAllCouriers();
        include 'View/Admin/AdminShipments.php';
    }

    public function assignCourier(int $shipment_id)
    {
        $this->checkAuth();
        $result = $this->adminData->assignCourier($shipment_id, (int)$_POST['courier_id']);
        if ($result) {
            $_SESSION['message'] = "Courier assigned successfully!";
        } else {
            $_SESSION['error'] = "Could not assign courier.";
        }
        header("Location: index.php?action=admin_shipments");
        exit;
    }

    public function addShipment()
    {
        $this->checkAuth();
        $result = $this->adminData->addShipment(
            (int)$_POST['order_id'],
            (int)$_POST['courier_id'],
            $_POST['tracking_code']
        );
        if ($result) {
            $_SESSION['message'] = "Shipment added successfully!";
        } else {
            $_SESSION['error'] = "Could not add shipment.";
        }
        header("Location: index.php?action=admin_shipments");
        exit;
    }

    public function updateShipmentStatus(int $shipment_id, string $new_status, string $location)
    {
        $this->checkAuth();
        $result = $this->adminData->updateShipmentStatus($shipment_id, $new_status, $location);
        if ($result === 'success') {
            $_SESSION['message'] = " Status updated successfully!";
        } elseif ($result === 'finalized') {
            $_SESSION['error'] = " This shipment is already finalized.";
        } elseif ($result === 'invalid') {
            $_SESSION['error'] = " Invalid status — cannot go backwards.";
        } else {
            $_SESSION['error'] = "Something went wrong.";
        }
        header("Location: index.php?action=admin_shipments");
        exit;
    }

    // --- Warehouses ---
    public function showWarehouses()
    {
        $this->checkAuth();
        $warehouses = $this->adminData->getAllWarehouses();
        include 'View/Admin/AdminWarehouses.php';
    }

    public function addWarehouse()
    {
        $this->checkAuth();
        $result = $this->adminData->addWarehouse($_POST['Name'], $_POST['Address'], (int)$_POST['Capacity']);
        if ($result) {
            $_SESSION['message'] = "Warehouse added successfully!";
        } else {
            $_SESSION['error'] = "Could not add warehouse.";
        }
        header("Location: index.php?action=admin_warehouses");
        exit;
    }

    public function showEditWarehouse(int $id)
    {
        $this->checkAuth();
        $warehouses = $this->adminData->getAllWarehouses();
        $warehouse  = array_filter($warehouses, fn($w) => $w['WarehouseID'] == $id);
        $warehouse  = reset($warehouse);
        include 'View/Admin/AdminEditWarehouse.php';
    }

    public function updateWarehouse(int $id)
    {
        $this->checkAuth();
        $result = $this->adminData->editWarehouse($id, $_POST['Name'], $_POST['Address'], (int)$_POST['Capacity']);
        if ($result) {
            $_SESSION['message'] = "Warehouse updated successfully!";
        } else {
            $_SESSION['error'] = "Could not update warehouse.";
        }
        header("Location: index.php?action=admin_warehouses");
        exit;
    }

    // --- Delivery Status History ---
    public function showDeliveryStatus()
    {
        $this->checkAuth();
        $history = $this->adminData->getDeliveryStatusHistory();
        include 'View/Admin/AdminDeliveryStatus.php';
    }

    public function adminLogout()
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_name']);
        header("Location: index.php?action=showAdminLogin");
        exit;
    }
}
?>