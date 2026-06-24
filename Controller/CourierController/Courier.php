<?php

require_once 'Model/Courier.php';

class CourierController
{
    private $courier;

    public function __construct(Courier $courier)
    {
        $this->courier = $courier;
    }

    

    public function showDashboard()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'courier') {
            header("Location: index.php?action=login");
            exit;
        }

        $shipments = $this->courier->getShipmentsByCourierId($_SESSION['user_id']);
        include 'View/courier_shipments.php';
    }

    public function updateStatus(int $shipment_id, string $new_status, string $location)
    {
        var_dump($new_status); 
        die();
    
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'courier') {
            header("Location: index.php?action=login");
            exit;
        }
    
        $result = $this->courier->updateShipmentStatus($shipment_id, $new_status, $location);
    
        if ($result === 'success') {
            $_SESSION['message'] = "✅ Shipment status updated successfully!";
        } elseif ($result === 'finalized') {
            $_SESSION['error'] = "❌ This shipment is already finalized.";
        } elseif ($result === 'invalid') {
            $_SESSION['error'] = "❌ Invalid status update — you cannot go backwards.";
        } else {
            $_SESSION['error'] = "Something went wrong, try again.";
        }
    
        header("Location: index.php?action=courier_dashboard");
        exit;
    }
}
?>