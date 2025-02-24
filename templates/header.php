<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in using native PHP sessions
$user_logged_in = isset($_SESSION['user_id']); // Check if user_id is set in the session
require_once 'bootstrap.php'; // Include Doctrine bootstrap file
require_once 'models/Logger.php'; // Include Doctrine bootstrap file
use Model\Repository\CategoryRepository;



$categoryRepo = new CategoryRepository($entityManager, );
$categories = $categoryRepo->getCategoriesWithSubcategories();
$maincategories = $categoryRepo->getParentCategories();

?>
<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
	<head>
		<title><?= isset($title) ? htmlspecialchars($title) : 'FarmHubActive - Connect, Grow, Thrive' ?></title>
		<meta charset="utf-8" />
<meta name="description" content="FarmHubActive is your one-stop platform to connect farmers, buyers, and suppliers. Access machine rentals, farm loans, agricultural products, and expert advice. Join today and thrive in farming!" />
<meta name="keywords" content="farmhub, agriculture, machine rentals, farm loans, agricultural marketplace, farming products, buyers and suppliers, farm advice" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<!-- Open Graph (OG) Tags for Social Media Sharing -->
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:title" content="FarmHubActive - Connect, Grow, Thrive" />
<meta property="og:description" content="Join FarmHubActive and bridge the gap between farmers, buyers, and suppliers. Explore machine rentals, farm loans, and the latest farming trends." />
<meta property="og:url" content="https://www.farmhubactive.com" />
<meta property="og:image" content="https://www.farmhubactive.com/assets/media/og-image.jpg" />
<meta property="og:site_name" content="FarmHubActive" />
		<link rel="canonical" href="http://activefarmhub.com/" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>

    <!-- Additional Styles for Customization -->
    <style>
        .login-page {
            background-image: url('assets/media/bg-login.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        .login-form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-logo img {
            width: 150px;
            margin-bottom: 20px;
        }

        .login-btn {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
    </style>
		
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root">

			<!-- Header Section -->
			<header class="d-flex justify-content-between align-items-center bg-light px-5 py-3">
				<!-- Logo -->
				<div class="logo">
					<a href="#">
						<img src="assets/media/logos/farmhub-logo.png" alt="FarmHub Logo" height="40">
					</a>
				</div>
	
				<!-- Hamburger Menu -->
				<div>
					<button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#categoryMenu" aria-controls="categoryMenu">
						<i class="fas fa-bars fs-2"></i> <!-- FontAwesome Hamburger Icon -->
					</button>
				</div>
	
				<!-- Search Bar -->
				<div class="flex-grow-1 mx-5 d-none d-lg-block">
					<form class="d-flex" action="/search" method="GET">
						<input type="text" class="form-control form-control-lg" placeholder="Search for agricultural products...">
						<button type="submit" class="btn btn-primary ms-2">Search</button>
					</form>
				</div>
	
				<!-- Account & Cart -->
				<div class="d-flex align-items-center">
					<!-- Account Menu -->
					<div class="d-flex align-items-center">
						<!-- Account Menu with Dropdown -->
						<div class="me-4">
							<div class="dropdown">
							<?php if ($user_logged_in): ?>
								<a href="#" class="btn btn-light dropdown-toggle" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="fas fa-user fs-2"></i>
									<span class="ms-1 fw-bold">Account</span>
								</a>
								<ul class="dropdown-menu" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item" href="account.php">My Account</a></li>
                        <li><a class="dropdown-item" href="orders.php">Orders</a></li>
                        <li><a class="dropdown-item" href="saved-items">Saved Items</a></li>
                        <li><a class="dropdown-item" href="logout">Logout</a></li> <!-- Logout option -->
                    </ul>
					<?php else: ?>
                    <!-- If the user is not logged in, show login/register options -->
                    <a href="login.php" class="btn btn-light">
                        <i class="fas fa-user fs-2"></i>
                        <span class="ms-1 fw-bold">Login / Register</span>
                    </a>
                <?php endif; ?>
							</div>
						</div>
					</div>
					
					<!-- Cart Menu -->
					<div>
						<a href="#" class="btn btn-light position-relative">
							<i class="fas fa-shopping-cart fs-2"></i>
							<span class="ms-1 fw-bold">Cart</span>
							<span class="cart-item-count badge bg-danger position-absolute top-0 start-100 translate-middle p-1 rounded-circle">
								0
							</span>
						</a>
					</div>
				</div>

				<div class="bg-light py-2 px-3 d-flex justify-content-end align-items-center">
					<i class="fas fa-cloud-sun fs-5 me-2 text-primary" id="weather-icon"></i>
					<span class="fw-semibold" id="weather-text">Fetching weather...</span>
				</div>
			</header>

			




			
		

			<!-- Hamburger Menu Offcanvas -->
			<div class="offcanvas offcanvas-start" tabindex="-1" id="categoryMenu" aria-labelledby="categoryMenuLabel">
				<div class="offcanvas-header">
					<h5 id="categoryMenuLabel">All Categories</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
					<!-- Categories and Subcategories -->
					<ul class="list-group">
    <?php foreach ($categories as $category): ?>
        <li class="list-group-item">
            <a href="#" class="fw-bold text-dark d-block" data-bs-toggle="collapse" data-bs-target="#submenu-<?= $category['id'] ?>" aria-expanded="false">
                <?= htmlspecialchars($category['name']) ?> 
                
                <?php if (!empty($category['subcategories'])): ?>
                    <i class="fas fa-chevron-down float-end"></i>
                <?php endif; ?>
            </a>

            <?php if (!empty($category['subcategories'])): ?>
                <ul id="submenu-<?= $category['id'] ?>" class="collapse list-unstyled ms-4">
                    <?php foreach ($category['subcategories'] as $subcategory): ?>
                        <li>
                            <a href="subcategory.php?id=<?= $subcategory['id'] ?>" class="text-muted">
                                <?= htmlspecialchars($subcategory['name']) ?>
                                <?php if ($subcategory['thumbnail']): ?>
                                    <img src="<?= htmlspecialchars($subcategory['thumbnail']) ?>" alt="<?= htmlspecialchars($subcategory['name']) ?>" width="20">
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

				</div>
			</div>
			<!-- End Offcanvas Menu -->
	
			<!-- End Header Section -->


