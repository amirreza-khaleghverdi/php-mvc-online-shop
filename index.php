    <?php
    session_start();

    $action = $_GET['action'] ?? 'homepage';

    require_once 'Model/Database.php';
    require_once 'Model/Login.php';
    require_once 'Model/Product.php';
    require_once 'Model/Cart.php';
    require_once 'Model/OrderItems.php';
    require_once 'Model/Orders.php';
    require_once 'Model/Courier.php';
    require_once 'Model/AdminLogin.php';
    require_once 'Model/AdminData.php';

    require_once 'Controller/LoginController/Login.php';
    require_once 'Controller/LoginController/Register.php';
    require_once 'Controller/HomeController/Homepage.php';
    require_once 'Controller/CartController/Cartpage.php';
    require_once 'Controller/ViewController/Viewpage.php';
    require_once 'Controller/CheckoutController/Checkout.php';
    require_once 'Controller/DashboardController/Dashboard.php';
    require_once 'Controller/ViewOrderDetails/ViewOrdersDetails.php';
    require_once 'Controller/CourierController/Courier.php';
    require_once 'Controller/AdminController/Admin.php';



    $dataBase = new Database();
    $Login = new Login($dataBase);
    $Product = new Product($dataBase);
    $Cart = new Cart($dataBase);
    $Orders = new Orders($dataBase);
    $OrderItems = new OrderItems($dataBase);
    $Admin = new AdminLogin($dataBase);
    $Courier = new Courier($dataBase);
    $AdminData       = new AdminData($dataBase);


    $loginController = new LoginController($Login);
    $registerController = new RegisterController($Login);
    $homepageController = new Homepage($Product);
    $cartpageController = new Cartpage($Cart);
    $viewpageController = new Viewpage($Product);
    $checkoutController = new checkout($Orders , $OrderItems , $Cart);
    $dashboardController = new Dashboard($Orders, $OrderItems);
    $vieworderdetails = new ViewOrdersDetails($OrderItems , $Orders);
    $courierController = new CourierController($Courier);
    $adminController = new AdminController($Admin, $AdminData);

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($action) 
    {
        case 'homepage':
            $homepageController->showHomepage();
            break;

        case 'login':
            $loginController->showLoginForm(); 
            break;

        case 'doLogin':
            if($method == 'POST')
            {
                $loginController->login();
            }
            break;

        case 'logout':
            $loginController->logout();
            break;

        case 'register':
            $registerController->showRegisterForm();
            break;

        case 'doRegister':
            if($method == 'POST')
            {
                $registerController->register();
            }
            break;

        case 'cart':
            $cartpageController->showCartpage();
            break;

        case 'remove_item':
            $cartpageController->removeItem($_GET['cart_item_id']);
            break;

        case 'update_cart':
            if(isset($_POST['quantities']))
            {
                $cartpageController->updateCart($_POST['quantities']);
            }
            break;
        
        case 'add_to_cart':
            if (isset($_POST['product_id'])) {
                $cartpageController->addToCart((int)$_POST['product_id']);
            }
            break;

        case 'view':
            if(isset($_GET['product_id'])){
                $viewpageController->showViewpage($_GET['product_id']);
            }
            break;
        
        case 'show_checkout':
            if (isset($_SESSION['user_id'])) {
                $checkoutController->Checkout();
            } else {
                header("Location: index.php?action=login");
                exit;
            }
            break;
        
        case 'confirm_order':
            if($method == 'POST' && isset($_POST['order_id'])){
                $checkoutController->confirmOrder($_POST['order_id']);
            }
            break;

        case 'cancel_order':
            if($method == 'POST' && isset($_POST['order_id'])){
                $checkoutController->cancelOrder($_POST['order_id']);
            }
            break;

        case 'dashboard':
                $dashboardController->showDashboard();
            break;

        case 'view_order_details':
                $vieworderdetails->ViewOrderItems($_GET['order_id']);
            break;

        case 'courier_dashboard':
            $courierController->showDashboard();
            break;
        
        case 'update_shipment_status':
            if ($method == 'POST') {
                $courierController->updateStatus($_POST['shipment_id'], $_POST['new_status'], $_POST['location']);
            }
            break;


        case 'admin':
            $adminController->showAdminPage();
            break;

        case 'showAdminLogin':
            $adminController->showLoginPage();
            break;

        case 'doAdminLogin':
            if ($method == 'POST') {
                $adminController->checkLogin();
            }
            break;

        case 'admin_products':
            $adminController->showProducts();
            break;

        case 'admin_add_product':
            if ($method == 'POST') {
                $adminController->addProduct();
            }
            break;

        case 'admin_edit_product':
            if (isset($_GET['id'])) {
                $adminController->showEditProduct((int)$_GET['id']);
            }
            break;

        case 'admin_update_product':
            if ($method == 'POST' && isset($_GET['id'])) {
                $adminController->updateProduct((int)$_GET['id']);
            }
            break;

        case 'admin_delete_product':
            if (isset($_GET['id'])) {
                $adminController->deleteProduct((int)$_GET['id']);
            }
            break;

        case 'admin_customers':
            $adminController->showCustomers();
            break;

        case 'admin_delete_customer':
            if (isset($_GET['id'])) {
                $adminController->deleteCustomer((int)$_GET['id']);
            }
            break;

        case 'admin_couriers':
            $adminController->showCouriers();
            break;

        case 'admin_delete_courier':
            if (isset($_GET['id'])) {
                $adminController->deleteCourier((int)$_GET['id']);
            }
            break;

        case 'admin_update_courier':
            if ($method == 'POST' && isset($_GET['id'])) {
                $adminController->updateCourier((int)$_GET['id']);
            }
            break;

        case 'admin_orders':
            $adminController->showOrders();
            break;

        case 'admin_update_order_status':
            if ($method == 'POST' && isset($_GET['id'])) {
                $adminController->updateOrderStatus((int)$_GET['id']);
            }
            break;

        case 'admin_inventory':
            $adminController->showInventory();
            break;

        case 'admin_update_inventory':
            if ($method == 'POST' && isset($_GET['id'])) {
                $adminController->updateInventory((int)$_GET['id']);
            }
            break;

        case 'admin_shipments':
            $adminController->showShipments();
            break;

        case 'admin_assign_courier':
            if ($method == 'POST' && isset($_GET['id'])) {
                $adminController->assignCourier((int)$_GET['id']);
            }
            break;

        case 'admin_add_shipment':
            if ($method == 'POST') {
                $adminController->addShipment();
            }
            break;

        case 'admin_update_shipment_status':
            if ($method == 'POST') {
                $adminController->updateShipmentStatus((int)$_POST['shipment_id'], $_POST['new_status'], $_POST['location']);
            }
            break;

        case 'admin_warehouses':
            $adminController->showWarehouses();
            break;

        case 'admin_add_warehouse':
            if ($method == 'POST') {
                $adminController->addWarehouse();
            }
            break;

        case 'admin_edit_warehouse':
            if (isset($_GET['id'])) {
                $adminController->showEditWarehouse((int)$_GET['id']);
            }
            break;

        case 'admin_update_warehouse':
            if ($method == 'POST' && isset($_GET['id'])) {
                $adminController->updateWarehouse((int)$_GET['id']);
            }
            break;

        case 'admin_delivery_status':
            $adminController->showDeliveryStatus();
            break;

        case 'admin_logout':
            $adminController->adminLogout();
            break;

            

            // case 'hi':
            //     $adminController->register();
            //     break;



            default:
                echo "404 Page Not Found";
                break;
        }
