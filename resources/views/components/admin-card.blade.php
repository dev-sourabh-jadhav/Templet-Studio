<div>
    <div class="col-xl-4 col-md-6">
        <div class="card bg-secondary-dark dashnum-card text-white overflow-hidden">
            <span class="round small"></span>
            <span class="round big"></span>
            <div class="card-body text-center">

                <span class="text-white d-block f-34 f-w-500 my-2" id="user-count">

                    <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                </span>
                <h3 class="mb-0 text-white">Total User Count</h3>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Make an AJAX GET request to get the user count
            $.ajax({
                url: '/user-count', // The route to your controller method
                type: 'GET',
                success: function(response) {
                    // Update the content of #user-count with the response count
                    $('#user-count').text(response.count);
                },
                error: function() {
                    $('#user-count').text('Error fetching count');
                }
            });
        });
    </script>
</div>
