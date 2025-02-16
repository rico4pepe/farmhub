

<?php 
// Set the page title dynamically
$title = 'Welcome to FarmConnect Hub';

// Include the header template and pass the title
include_once 'templates/header.php';

?>
			<!-- Hero Section Slider with Autoplay and Fade Effect -->
			<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="https://via.placeholder.com/1920x600" class="d-block w-100" alt="Slide 1">
            <div class="carousel-caption d-none d-md-block">
                <h5 class="display-4">Agricultural Products for Sale</h5>
                <p>Your one-stop marketplace for all farm needs!</p>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="https://via.placeholder.com/1920x600" class="d-block w-100" alt="Slide 2">
            <div class="carousel-caption d-none d-md-block">
                <h5 class="display-4">Farm Machinery Rentals</h5>
                <p>Rent farm machinery for your next project.</p>
            </div>
        </div>
        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="https://via.placeholder.com/1920x600" class="d-block w-100" alt="Slide 3">
            <div class="carousel-caption d-none d-md-block">
                <h5 class="display-4">Farm Loans & Financing</h5>
                <p>Get financial support for your agricultural business.</p>
            </div>
        </div>
    </div>
    
    <!-- Left and Right Arrows for Navigation -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<!-- End Hero Section Slider -->

	
			
	
			<!-- Categories Section -->
			<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Shop by Category</h2>
        <div class="row g-4">

		<?php foreach ($maincategories as $category): ?>
    <div class="col-md-3">
        <a href="category.php?id=<?= $category->getId() ?>" class="card text-center shadow-sm h-100">
            <img src="<?= htmlspecialchars(str_replace('../', '', $category->getThumbnail()) ?? 'assets/media/categories/default.jpg') ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($category->getName()) ?>">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title"><?= htmlspecialchars($category->getName()) ?></h5>
            </div>
        </a>
    </div>
<?php endforeach; ?>

        </div>
    </div>
</section>

			<!-- End Categories Section -->
	
			<!-- Featured Products Section -->
			<section id="products" class="py-5 bg-light">
				<div class="container">
					<h2 class="fw-bold mb-4">Featured Products</h2>
					<div class="row g-4">
						<!-- Product Card -->
						<div class="col-md-3">
							<div class="card h-100 shadow-sm">
								<img src="assets/media/products/product1.jpg" class="card-img-top" alt="Product 1">
								<div class="card-body d-flex flex-column">
									<h5 class="card-title">High-Quality Seeds</h5>
									<p class="card-text">$20.00</p>
									<a href="#" class="btn btn-primary mt-auto">Add to Cart</a>
								</div>
							</div>
						</div>
						<!-- Repeat for other products -->
					</div>
				</div>
			</section>
			<!-- End Featured Products Section -->
<?php 
include_once 'templates/footer.php';
?>
