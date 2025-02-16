<!-- Footer Section -->
<footer class="bg-dark text-white py-4">
				<div class="container text-center">
					<p>&copy; 2024 FarmHub. All Rights Reserved.</p>
					<div>
						<a href="#" class="text-white me-3">Privacy Policy</a>
						<a href="#" class="text-white">Terms & Conditions</a>
					</div>
				</div>
			</footer>
			<!-- End Footer Section -->
		</div>
	
	
		
		
	
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		
		
		
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/plugins/custom/apexcharts/apexcharts.bundle.js"></script>
		<script src="assets/plugins/custom/chartjs/chartjs.bundle.js"></script>
		<script src="assets/plugins/custom/sparklines/sparklines.bundle.js"></script>
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/new-target.js"></script>
		<script src="'assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
		<script>
    $(document).ready(function() {
        const apiKey = 'ebbbbb8e99a2f80a6228ac6e264a3277';

        // Use Geolocation API to get the user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Fetch weather using latitude and longitude
                    $.ajax({
                        url: `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`,
                        type: 'GET',
                        success: function(data) {
                            const city = data.name;
                            const temperature = Math.round(data.main.temp);
                            const description = data.weather[0].description;
                            const weatherIcon = getWeatherIcon(data.weather[0].icon);

                            $('#weather-text').html(`Weather in ${city}: ${temperature}°C, ${capitalize(description)}`);
                            $('#weather-icon').removeClass().addClass(`fas ${weatherIcon} fs-5 me-2 text-primary`);
                        },
                        error: function() {
                            $('#weather-text').html('Unable to fetch weather data.');
                        }
                    });
                },
                function(error) {
                    $('#weather-text').html('Location access denied or unavailable.');
                    console.error(`Error getting location: ${error.message}`);
                }
            );
        } else {
            $('#weather-text').html('Geolocation is not supported by this browser.');
        }

        function getWeatherIcon(iconCode) {
            // Match OpenWeatherMap icons with FontAwesome classes
            const iconMap = {
                '01d': 'fa-sun',
                '01n': 'fa-moon',
                '02d': 'fa-cloud-sun',
                '02n': 'fa-cloud-moon',
                '03d': 'fa-cloud',
                '03n': 'fa-cloud',
                '04d': 'fa-cloud-meatball',
                '04n': 'fa-cloud-meatball',
                '09d': 'fa-cloud-showers-heavy',
                '09n': 'fa-cloud-showers-heavy',
                '10d': 'fa-cloud-sun-rain',
                '10n': 'fa-cloud-moon-rain',
                '11d': 'fa-bolt',
                '11n': 'fa-bolt',
                '13d': 'fa-snowflake',
                '13n': 'fa-snowflake',
                '50d': 'fa-smog',
                '50n': 'fa-smog'
            };
            return iconMap[iconCode] || 'fa-cloud';
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    });
</script>

			

	</body>
	<!--end::Body-->
</html>
