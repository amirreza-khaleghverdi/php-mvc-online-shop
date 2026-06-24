<?php

require_once 'Database.php';

class Courier
{
    private $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->get_connection();
    }

    public function checkCourierLogin(string $nationalcode, string $password)
    {
        try {
            $sql  = "SELECT * FROM courier WHERE NationalCode = :nationalcode LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nationalcode', $nationalcode);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getShipmentsByCourierId(int $courier_id)
    {
        try {
            $sql  = "SELECT s.ShipmentID, s.OrderID, s.TrakingCode, s.ShippingDate,
                            ds.StatusMessage, ds.Location, ds.StatusTime
                     FROM shipment s
                     LEFT JOIN deliverystatus ds ON s.ShipmentID = ds.ShipmentID
                     AND ds.StatusTime = (
                         SELECT MAX(StatusTime) 
                         FROM deliverystatus 
                         WHERE ShipmentID = s.ShipmentID
                     )
                     WHERE s.CourierID = :courier_id
                     ORDER BY s.ShippingDate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':courier_id', $courier_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCurrentStatus(int $shipment_id)
    {
        try {
            $sql  = "SELECT StatusMessage FROM deliverystatus
                     WHERE ShipmentID = :shipment_id
                     ORDER BY StatusTime DESC LIMIT 1";
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

            // get current status
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

    public function registerCourier(string $firstname, string $lastname, string $nationalcode, string $contactnumber, string $vehicletype, string $password)
    {
        try {
            $sql  = "INSERT INTO courier (FirstName, LastName, NationalCode, ContactNumber, VehicleType, password)
                     VALUES (:firstname, :lastname, :nationalcode, :contactnumber, :vehicletype, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname',     $firstname);
            $stmt->bindValue(':lastname',      $lastname);
            $stmt->bindValue(':nationalcode',  $nationalcode);
            $stmt->bindValue(':contactnumber', $contactnumber);
            $stmt->bindValue(':vehicletype',   $vehicletype);
            $stmt->bindValue(':password',      $password);
            $stmt->execute();

            $id   = $this->conn->lastInsertId();
            $sql  = "SELECT * FROM courier WHERE CourierID = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>