<?php 

require_once '../bootstrap.php'; 
// Set the page title dynamically
$title = 'Welcome to FarmConnect Hub';

include_once 'templates/header.php';

use Model\Category;
use Model\Repository\CategoryRepository;

// Check if the form is submitted

$categoryRepo = new CategoryRepository($entityManager);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subcategories = $_POST['sub_category_name'] ?? [];
    $parentId = $_GET['id'] ?? null;

    

    if ($parentId && !empty($subcategories)) {
        $parentId = (int) $parentId;
        $addedSubcategories = [];

        //die("Parent ID: " . htmlspecialchars($parentId) . " ..... Subcategories: " . implode(", ", $subcategories));

        foreach ($subcategories as $subcategoryName) {

            //echo "Processing subcategory: " . htmlspecialchars($subcategoryName) . "<br>";
            if (!$categoryRepo->subcategoryExists($subcategoryName, $parentId)) {
                //echo "Subcategory added successfully!<br>";
                if ($categoryRepo->addSubcategory($subcategoryName, $parentId)) {
                    //echo "Subcategory added successfully!<br>";
                    $addedSubcategories[] = $subcategoryName;
                }
            }
        }

        

        if (!empty($addedSubcategories)) {
            echo "Added: " . implode(", ", $addedSubcategories);
        } else {
            echo "No new subcategories added. They might already exist.";
        }
    } else {
        echo "Missing category ID or subcategories.";
    }
}



?>


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
										<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Add Subcategory</h1>
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
											<li class="breadcrumb-item text-muted">Catalog</li>
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
										<a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create</a>
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
									<form id="kt_ecommerce_add_category_form" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row" action="#" method="post">
										<!--begin::Aside column-->
										
										<!--end::Aside column-->
										<!--begin::Main column-->
										<div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
											<!--begin::General options-->
											<div class="card card-flush py-4">
												<!--begin::Card header-->
												<div class="card-header">
													<div class="card-title">
														<h2>General</h2>
													</div>
												</div>
												<!--end::Card header-->
												<!--begin::Card body-->
												<div class="card-body pt-0">
													<!--begin::Input group-->
													<div class="mb-10 fv-row">
														<!--begin::Label-->
														<label class="required form-label">Subcategory Name</label>
														<!--end::Label-->
														<!--begin::Input-->
														<div id="subcategoryWrapper">
                                                        <div class="subcategory-group">
                                                            <input type="text" name="sub_category_name[]" class="form-control mb-2" placeholder="Subcategory name" />
                                                        </div>
                                                    </div>
														<!--end::Input-->
														<!--begin::Description-->
														<div class="text-muted fs-7">Sub category name is required and recommended to be unique.</div>
														<!--end::Description-->
													</div>

                                                    <button type="button" id="addSubcategory" class="btn btn-primary">Add More</button>
                                                    <button type="button" id="removeSubcategory" class="btn btn-info">Remove</button>
													<!--end::Input group-->
													<!--begin::Input group-->
													
													<!--end::Input group-->
												</div>
												<!--end::Card header-->
											</div>
											<!--end::General options-->
											<!--begin::Meta options-->
										
											<!--end::Meta options-->
											<!--begin::Automation-->
											
											<!--end::Automation-->
											<div class="d-flex justify-content-end">
												<!--begin::Button-->
												<a href="apps/ecommerce/catalog/products.html" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
												<!--end::Button-->
												<!--begin::Button-->
												<button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-primary">
													<span class="indicator-label">Save Changes</span>
													<span class="indicator-progress">Please wait... 
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
												</button>
												<!--end::Button-->
											</div>
										</div>
										<!--end::Main column-->
									</form>
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

<?php 
// Include the footer
include_once 'templates/footer.php';
?>