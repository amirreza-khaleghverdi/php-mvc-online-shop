<?php

require_once 'Database.php';

class AdminData
{
    private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->get_connection();
    }

    // --- Products ---
    public function getAllProducts()
    {
        try {
            $sql  = "SELECT * FROM product";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function addProduct(string $title, $price, string $sku, string $description, $weight, string $image_url)
    {
        try {
            $sql  = "INSERT INTO product (Title, Price, SKU, Descriptions, weight, image_url)
                     VALUES (:title, :price, :sku, :description, :weight, :image_url)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':title',       $title);
            $stmt->bindValue(':price',       $price);
            $stmt->bindValue(':sku',         $sku);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':weight',      $weight);
            $stmt->bindValue(':image_url',   $image_url);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function editProduct(int $id, string $title, $price, string $sku, string $description, $weight, string $image_url)
    {
        try {
            $sql  = "UPDATE product SET Title = :title, Price = :price, SKU = :sku,
                     Descriptions = :description, weight = :weight, image_url = :image_url
                     WHERE ProductID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':title',       $title);
            $stmt->bindValue(':price',       $price);
            $stmt->bindValue(':sku',         $sku);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':weight',      $weight);
            $stmt->bindValue(':image_url',   $image_url);
            $stmt->bindValue(':id',          $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteProduct(int $id)
    {
        try {
            $sql  = "DELETE FROM product WHERE ProductID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getProductById(int $id)
    {
        try {
            $sql  = "SELECT * FROM product WHERE ProductID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    // --- Orders ---
    public function getAllOrders()
    {
        try {
            $sql  = "SELECT o.OrderId as id, o.CustomerId, c.FirstName, c.LastName,
                            o.TotalAmount as total, o.OrderStatus as status, o.OrderDate as created_at
                     FROM orders o
                     JOIN customers c ON o.CustomerId = c.CustomerID
                     ORDER BY o.OrderDate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateOrderStatus(int $order_id, string $status)
    {
        try {
            $sql  = "UPDATE orders SET OrderStatus = :status WHERE OrderId = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id',     $order_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // --- Inventory ---
    public function getAllInventory()
    {
        try {
            $sql  = "SELECT i.InventoryID, p.Title, w.Name as Warehouse,
                            i.AvailableStock, i.ReservedStock, i.ProductID, i.WarehouseID
                     FROM inventory i
                     JOIN product p ON i.ProductID = p.ProductID
                     JOIN warehouse w ON i.WarehouseID = w.WarehouseID";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateInventory(int $inventory_id, int $available, int $reserved)
    {
        try {
            $sql  = "UPDATE inventory SET AvailableStock = :available, ReservedStock = :reserved
                     WHERE InventoryID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':available', $available, PDO::PARAM_INT);
            $stmt->bindValue(':reserved',  $reserved,  PDO::PARAM_INT);
            $stmt->bindValue(':id',        $inventory_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // --- Shipments ---
    public function getAllShipments()
    {
        try {
            $sql  = "SELECT s.ShipmentID, s.OrderID, s.TrakingCode, s.ShippingDate,
                            c.FirstName, c.LastName, c.CourierID,
                            ds.StatusMessage, ds.Location
                     FROM shipment s
                     LEFT JOIN courier c ON s.CourierID = c.CourierID
                     LEFT JOIN deliverystatus ds ON s.ShipmentID = ds.ShipmentID
                     AND ds.StatusID = (
                         SELECT MAX(StatusID) FROM deliverystatus
                         WHERE ShipmentID = s.ShipmentID
                     )
                     ORDER BY s.ShippingDate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function assignCourier(int $shipment_id, int $courier_id)
    {
        try {
            $sql  = "UPDATE shipment SET CourierID = :courier_id WHERE ShipmentID = :shipment_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':courier_id',  $courier_id,  PDO::PARAM_INT);
            $stmt->bindValue(':shipment_id', $shipment_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addShipment(int $order_id, int $courier_id, string $tracking_code)
    {
        try {
            $sql  = "INSERT INTO shipment (OrderID, CourierID, TrakingCode)
                     VALUES (:order_id, :courier_id, :tracking_code)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id',      $order_id,      PDO::PARAM_INT);
            $stmt->bindValue(':courier_id',    $courier_id,    PDO::PARAM_INT);
            $stmt->bindValue(':tracking_code', $tracking_code);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function getCurrentStatus(int $shipment_id)
    {
        try {
            $sql  = "SELECT StatusMessage FROM deliverystatus
                    WHERE ShipmentID = :shipment_id
                    ORDER BY StatusID DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':shipment_id', $shipment_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateShipmentStatus(int $shipment_id, string $new_status, string $location)
    {
        try {
            $finalStatuses = ['delivered', 'failed_attempt'];
            $current = $this->getCurrentStatus($shipment_id);

            if ($current && in_array($current['StatusMessage'], $finalStatuses)) {
                return 'finalized';
            }

            $statusOrder = [
                'picked_up'        => 1,
                'in_transit'       => 2,
                'sorting_center'   => 3,
                'out_for_delivery' => 4,
                'delivered'        => 5,
                'failed_attempt'   => 5
            ];

            $currentOrder = $current ? ($statusOrder[$current['StatusMessage']] ?? 0) : 0;
            $newOrder     = $statusOrder[$new_status] ?? 0;

            if ($newOrder <= $currentOrder) {
                return 'invalid';
            }

            $sql  = "INSERT INTO deliverystatus (ShipmentID, StatusMessage, Location)
                    VALUES (:shipment_id, :status, :location)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':shipment_id', $shipment_id, PDO::PARAM_INT);
            $stmt->bindValue(':status',      $new_status);
            $stmt->bindValue(':location',    $location);
            $stmt->execute();
            return 'success';
        } catch (Exception $e) {
            return false;
        }
    }

    // --- Warehouses ---
    public function getAllWarehouses()
    {
        try {
            $sql  = "SELECT * FROM warehouse";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function addWarehouse(string $name, string $address, int $capacity)
    {
        try {
            $sql  = "INSERT INTO warehouse (Name, Address, Capacity)
                     VALUES (:name, :address, :capacity)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':name',     $name);
            $stmt->bindValue(':address',  $address);
            $stmt->bindValue(':capacity', $capacity, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function editWarehouse(int $id, string $name, string $address, int $capacity)
    {
        try {
            $sql  = "UPDATE warehouse SET Name = :name, Address = :address, Capacity = :capacity
                     WHERE WarehouseID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':name',     $name);
            $stmt->bindValue(':address',  $address);
            $stmt->bindValue(':capacity', $capacity, PDO::PARAM_INT);
            $stmt->bindValue(':id',       $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // --- Delivery Status History ---
    public function getDeliveryStatusHistory()
    {
        try {
            $sql  = "SELECT ds.StatusID, ds.ShipmentID, ds.StatusMessage,
                            ds.Location, ds.StatusTime, s.TrakingCode
                     FROM deliverystatus ds
                     JOIN shipment s ON ds.ShipmentID = s.ShipmentID
                     ORDER BY ds.StatusTime DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>