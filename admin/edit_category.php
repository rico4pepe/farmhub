<?php 
require_once '../bootstrap.php'; 
use Model\Category;
use Model\Repository\CategoryRepository;

// Set the page title dynamically
$title = 'Edit Category - FarmConnect Hub';

include_once 'templates/header.php';

// Get the category ID from the URL
$categoryId = $_GET['id'] ?? null;

if (!$categoryId) {
    echo "<script>alert('Category ID missing.'); window.location.href='categories.php';</script>";
    exit;
}

// Initialize the repository
$categoryRepository = new CategoryRepository($entityManager);

// Fetch the existing category
$category = $categoryRepository->findById((int)$categoryId);

if (!$category) {
    echo "<script>alert('Category not found.'); window.location.href='categories.php';</script>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $name = htmlspecialchars(trim($_POST['category_name'] ?? ''));
    $description = $_POST['kt_ecommerce_add_category_description'] ?? '';

    if (empty($name)) {
        echo "<script>alert('Category name is required.');</script>";
        exit;
    }

    // Optionally, if the category name has changed, check for duplicates:
    if ($name !== $category->getName() && $categoryRepository->categoryExists($name)) {
        echo '<!--begin::Alert-->
<div class="alert alert-dismissible bg-info d-flex flex-column flex-sm-row p-5 mb-10">
    <!--begin::Icon-->
    <i class="ki-duotone ki-search-list fs-2hx text-light me-4 mb-5 mb-sm-0">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
    </i>
    <!--end::Icon-->
    <!--begin::Wrapper-->
    <div class="d-flex flex-column text-light pe-0 pe-sm-10">
        <!--begin::Title-->
        <h4 class="mb-2 light">Alert</h4>
        <!--end::Title-->
        <!--begin::Content-->
        <span>Category already exists.</span>
        <!--end::Content-->
    </div>
    <!--end::Wrapper-->
    <!--begin::Close-->
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="ki-duotone ki-cross fs-1 text-light">
            <span class="path1"></span><span class="path2"></span>
        </i>
    </button>
    <!--end::Close-->
</div>
<!--end::Alert-->
<script>  
    setTimeout(function() { window.history.back(); }, 3000);
</script>';
        exit;
    }

    // Handle file upload if a new thumbnail is provided
    $thumbnailPath = $category->getThumbnail(); // Use existing thumbnail by default
    if (isset($_FILES['category_thumbnail']) && $_FILES['category_thumbnail']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/categories/";
        $fileName = basename($_FILES['category_thumbnail']['name']);
        $targetFile = $targetDir . $fileName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], $targetFile)) {
            $thumbnailPath = $targetFile;
        } else {
            echo "<script>alert('Error uploading the thumbnail.');</script>";
            exit;
        }
    }

    try {
        // Update the category fields
        $category->setName($name);
        $category->setDescription($description);
        $category->setThumbnail($thumbnailPath);

        // Save (update) the category
        $categoryRepository->save($category);

        echo '<!--begin::Alert-->
<div class="alert alert-dismissible bg-primary d-flex flex-column flex-sm-row p-5 mb-10">
    <!--begin::Icon-->
    <i class="ki-duotone ki-check fs-2hx text-light me-4 mb-5 mb-sm-0">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
    </i>
    <!--end::Icon-->
    <!--begin::Wrapper-->
    <div class="d-flex flex-column text-light pe-0 pe-sm-10">
        <!--begin::Title-->
        <h4 class="mb-2 light">Success!</h4>
        <!--end::Title-->
        <!--begin::Content-->
        <span>Category has been successfully updated.</span>
        <!--end::Content-->
    </div>
    <!--end::Wrapper-->
    <!--begin::Close-->
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="ki-duotone ki-cross fs-1 text-light">
            <span class="path1"></span><span class="path2"></span>
        </i>
    </button>
    <!--end::Close-->
</div>
<!--end::Alert-->
<script>
    // Redirect after 3 seconds
    setTimeout(function() { window.location.href = "categories.php"; }, 3000);
</script>';
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Display the edit form populated with existing category data -->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <form id="kt_ecommerce_edit_category_form" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row" action="" method="post">
            <!--begin::Aside column-->
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                <!-- Thumbnail settings -->
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Thumbnail</h2>
                        </div>
                    </div>
                    <div class="card-body text-center pt-0">
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" style="background-image:url(<?= htmlspecialchars(str_replace('../', '', $category->getThumbnail()) ?? 'assets/media/categories/default.jpg') ?>);"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="file" name="category_thumbnail" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the category thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                    </div>
                </div>
                <!-- Status -->
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Status</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_edit_category_status"></div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_edit_category_status_select">
                            <option></option>
                            <option value="published" selected="selected">Published</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="unpublished">Unpublished</option>
                        </select>
                        <div class="text-muted fs-7">Set the category status.</div>
                        <div class="d-none mt-10">
                            <label for="kt_ecommerce_edit_category_status_datepicker" class="form-label">Select publishing date and time</label>
                            <input class="form-control" id="kt_ecommerce_edit_category_status_datepicker" placeholder="Pick date & time" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main column -->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>General</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <!-- Category Name Input -->
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Category Name</label>
                            <input type="text" name="category_name" class="form-control mb-2" placeholder="Category name" value="<?= htmlspecialchars($category->getName()) ?>" required/>
                            <div class="text-muted fs-7">A category name is required and recommended to be unique.</div>
                        </div>
                        <!-- Description Input -->
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="kt_ecommerce_add_category_description" class="form-control mb-2" placeholder="Category description"><?= htmlspecialchars($category->getDescription()) ?></textarea>
                            <div class="text-muted fs-7">Set a description to the category for better visibility.</div>
                        </div>
                    </div>
                </div>
                <!-- Meta Options -->
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Meta Options</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10">
                            <label class="form-label">Meta Tag Title</label>
                            <input type="text" class="form-control mb-2" name="meta_title" placeholder="Meta tag title" />
                            <div class="text-muted fs-7">Set a meta tag title.</div>
                        </div>
                        <div class="mb-10">
                            <label class="form-label">Meta Tag Description</label>
                            <textarea name="meta_description" class="form-control mb-2" placeholder="Meta tag description"></textarea>
                            <div class="text-muted fs-7">Set a meta tag description.</div>
                        </div>
                        <div>
                            <label class="form-label">Meta Tag Keywords</label>
                            <input type="text" class="form-control mb-2" name="meta_keywords" placeholder="Meta tag keywords" />
                            <div class="text-muted fs-7">Set a list of keywords separated by commas.</div>
                        </div>
                    </div>
                </div>
                <!-- Automation (if any) -->
                <div class="d-flex justify-content-end">
                    <a href="categories.php" id="kt_ecommerce_edit_category_cancel" class="btn btn-light me-5">Cancel</a>
                    <button type="submit" id="kt_ecommerce_edit_category_submit" class="btn btn-primary">
                        <span class="indicator-label">Save Changes</span>
                        <span class="indicator-progress">Please wait... 
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
</div>
<!--end::Content wrapper-->
<!-- Footer -->
<?php 
include_once 'templates/footer.php';
?>
