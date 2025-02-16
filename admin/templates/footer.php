<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
	
	
		
		<!--begin::Javascript-->
		<script>var hostUrl = "../assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="../assets/plugins/global/plugins.bundle.js"></script>
		<script src="../assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="../assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="../assets/js/custom/apps/ecommerce/catalog/categories.js"></script>
		<script src="../assets/js/widgets.bundle.js"></script>
		<script src="../assets/js/custom/widgets.js"></script>
		<script src="../assets/js/custom/apps/chat/chat.js"></script>
		<script src="../assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="../assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="../assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<script>
document.addEventListener('DOMContentLoaded', function () {
    // Add click event listener to all delete links
    const deleteLinks = document.querySelectorAll('[data-kt-ecommerce-category-filter="delete_row"]');
    
    deleteLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            const categoryId = this.getAttribute('data-category-id');

            if (confirm('Are you sure you want to delete this category?')) {
                // Make the AJAX request
                fetch('delete_category.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: categoryId }) // Send the category ID
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Successfully deleted, remove the category row from the table
                        this.closest('tr').remove();
                       // alert('Category deleted successfully');
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        });
    });
});
</script>

<script>
$(document).ready(function () {
    $("#addSubcategory").click(function () {
        $("#subcategoryWrapper").append(`
            <div class="subcategory-group">
                <input type="text" name="sub_category_name[]" class="form-control mb-2" placeholder="Subcategory name" />
            </div>
        `);
    });

    $("#removeSubcategory").click(function () {
        $("#subcategoryWrapper .subcategory-group:last-child").remove();
    });
});
</script>

		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>