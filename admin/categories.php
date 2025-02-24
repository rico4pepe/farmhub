

<?php 

// Include bootstrap and initialize dependencies
require_once '../bootstrap.php';

use Model\Category;
use Model\Repository\CategoryRepository;

// Initialize the repository
$categoryRepository = new CategoryRepository($entityManager);

// Fetch all root categories
$categories = $categoryRepository->findSubcategories(null); 
// Set the page title dynamically
$title = 'Welcome to FarmConnect Hub';

include_once 'templates/header.php';

?>



	<!--begin::Main-->
	<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Toolbar-->
							<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
									<!--begin::Page title-->
									<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
										<!--begin::Title-->
										<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Categories</h1>
										<!--end::Title-->
										<!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="index.html" class="text-muted text-hover-primary">Home</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-500 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">eCommerce</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-500 w-5px h-2px"></span>
											</li>
											<!--end::Item--> 
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Categories</li>
											<!--end::Item-->
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page title-->
									<!--begin::Actions-->
									<div class="d-flex align-items-center gap-2 gap-lg-3">
										<!--begin::Filter menu-->
										
										<!--end::Filter menu-->
										<!--begin::Secondary button-->
										<!--end::Secondary button-->
										<!--begin::Primary button-->
										<!--<a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create</a> -->
										<!--end::Primary button-->
									</div>
									<!--end::Actions-->
								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl">
									<!--begin::Category-->
									<div class="card card-flush">
										<!--begin::Card header-->
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											<!--begin::Card title-->
											<div class="card-title">
												<!--begin::Search-->
												<div class="d-flex align-items-center position-relative my-1">
													<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													<input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Category" />
												</div>
												<!--end::Search-->
											</div>
											<!--end::Card title-->
											<!--begin::Card toolbar-->
											<div class="card-toolbar">
												<!--begin::Add customer-->
												<a href="add_category.php" class="btn btn-primary">Add Category</a>
												<!--end::Add customer-->
											</div>
											<!--end::Card toolbar-->
										</div>
										<!--end::Card header-->
										<!--begin::Card body-->
										<div class="card-body pt-0">
											<!--begin::Table-->
											<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="w-10px pe-2">
															<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
																<input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
															</div>
														</th>
														<th class="min-w-250px">Category</th>
														<th class="min-w-150px">Category Description</th>
														<th class="text-end min-w-70px">Actions</th>
													</tr>
												</thead>
												<tbody class="fw-semibold text-gray-600">
        <?php
        // Recursive function to render categories and subcategories
        // Recursive function to render categories and subcategories in a table row with clear indentation
function renderCategoryRow(Model\Category $category, int $level = 0)
{
    // Calculate left padding (e.g., 20px per level)
    $padding = $level * 20;
    
    echo '<tr>';
    echo '<td>';
    echo '<div class="form-check form-check-sm form-check-custom form-check-solid">';
    echo '<input class="form-check-input" type="checkbox" value="' . $category->getId() . '" />';
    echo '</div>';
    echo '</td>';
    echo '<td>';
    echo '<div class="d-flex align-items-center">';
    // Thumbnail if available
    if ($category->getThumbnail()) {
        echo '<a href="#" class="symbol symbol-50px">';
        echo '<span class="symbol-label" style="background-image:url(' . htmlspecialchars($category->getThumbnail()) . ');"></span>';
        echo '</a>';
    }
    echo '<div class="ms-5" style="padding-left: ' . $padding . 'px;">';
    echo '<a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1" data-kt-ecommerce-category-filter="category_name">';
    echo htmlspecialchars($category->getName());
    echo '</a>';
    echo '</div>'; // End ms-5 div
    echo '</div>'; // End d-flex div
    echo '</td>';
    echo '<td>';
    if ($category->getDescription()) {
        echo '<div class="text-muted fs-7 fw-bold">' . htmlspecialchars($category->getDescription()) . '</div>';
    }
    echo '</td>';
    echo '<td class="text-end">';
    echo '<a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">';
    echo 'Actions <i class="ki-duotone ki-down fs-5 ms-1"></i>';
    echo '</a>';
    echo '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">';
    echo '<div class="menu-item px-3">';
    echo '<a href="edit_category.php?id=' . $category->getId() . '" class="menu-link px-3">Edit</a>';
    echo '</div>';
    echo '<div class="menu-item px-3">';
    echo '<a href="add_subcategory.php?id=' . $category->getId() . '" class="menu-link px-3">Subcategory</a>';
    echo '</div>';
    echo '<div class="menu-item px-3">';
    echo '<a href="javascript:void(0);" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-category-id="' . $category->getId() . '">Delete</a>';
    echo '</div>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';

    // Render subcategories recursively, increasing the indentation level
    foreach ($category->getChildren() as $child) {
        renderCategoryRow($child, $level + 1);
    }
}

        foreach ($categories as $category) {
            renderCategoryRow($category);
        }
        ?>
    </tbody>
												<!--end::Table body-->
											</table>
											<!--end::Table-->
										</div>
										<!--end::Card body-->
									</div>
									<!--end::Category-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<div id="kt_app_footer" class="app-footer">
							<!--begin::Footer container-->
							<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
								<!--begin::Copyright-->
								<div class="text-gray-900 order-2 order-md-1">
									<span class="text-muted fw-semibold me-1">2024&copy;</span>
									<a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Keenthemes</a>
								</div>
								<!--end::Copyright-->
								<!--begin::Menu-->
								<ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
									<li class="menu-item">
										<a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
									</li>
									<li class="menu-item">
										<a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
									</li>
									<li class="menu-item">
										<a href="https://1.envato.market/Vm7VRE" target="_blank" class="menu-link px-2">Purchase</a>
									</li>
								</ul>
								<!--end::Menu-->
							</div>
							<!--end::Footer container-->
						</div>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->

	
			
	
			
<?php 
// Include the footer
include_once 'templates/footer.php';
?>
