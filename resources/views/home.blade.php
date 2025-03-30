@extends('structure.main')

@section('content')
    @auth
        @if (auth()->user()->role_id == 1)
            <x-admin-card></x-admin-card>
        @endif
    @endauth


    <div class="container">
        <h2 class="text-center"> Image's and its Category</h2>
        <div id="image-container"></div>
    </div>


    <!-- Bootstrap 5 Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Normal Image Preview -->
                    <img id="modalImage" src="" class="img-fluid rounded" alt="Full Image">

                    <!-- TIFF Preview (Canvas) -->
                    <canvas id="tiffCanvas" style="display: none; max-width: 100%;"></canvas>

                    <p id="imagePrice" class="mt-3 fw-bold fs-5 text-primary"></p>

                    <!-- Coupon Input -->
                    <div class="mt-3">
                        <input type="text" id="couponCode" class="form-control" placeholder="Enter Coupon Code">
                        <input type="hidden" id="priceInput" class="form-control">
                        <button id="applyCoupon" class="btn btn-success mt-2">Apply Coupon</button>
                    </div>
                    <div class="mt-3" id="coupon-message"></div>

                    <!-- Download Button -->
                    <button id="downloadButton" class="btn btn-primary mt-3">Make Payment</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $.ajax({
                url: "/get-images",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    let html = '';

                    response.categories.forEach(category => {
                        html += `<div class="category-section">
                    <h3 class="categories_name">${category.categories_name}</h3>
                    <div class="image-grid">`;

                        category.images.forEach(image => {
                            let fileExtension = image.image_url.split('.').pop()
                                .toLowerCase();

                            html += `<div class="image-box">`;

                            if (fileExtension === "tiff" || fileExtension === "tif") {
                                // If TIFF, show "View More" instead of the image
                                html += `<span class="view-more" style="cursor: pointer;"
                                    data-img="${image.image_url}" 
                                    data-price="${image.price}">
                                        Open Full-Size TIFF Image
                                 </span>`;
                            } else {
                                // If not TIFF, show the image
                                html += `<img src="${image.image_url}" alt="Image" class="image" 
                                    data-img="${image.image_url}" 
                                    data-price="${image.price}">
                                `;
                            }

                            html += `</div>`;
                        });

                        html += `</div></div>`;
                    });

                    $('#image-container').html(html);
                },
                error: function(error) {
                    console.log("Error fetching images:", error);
                }
            });

            // Handle modal opening
            $(document).on("click", ".image, .view-more", function() {
                let imgSrc = $(this).attr("data-img");
                let imgPrice = $(this).attr("data-price");
                let fileExtension = imgSrc.split('.').pop().toLowerCase();

                if (fileExtension === "tiff" || fileExtension === "tif") {
                    $("#modalImage").hide();
                    $("#tiffCanvas").show();

                    fetch(imgSrc)
                        .then(response => response.arrayBuffer())
                        .then(buffer => {
                            if (typeof window.Tiff !== "undefined") {
                                let tiff = new window.Tiff({
                                    buffer
                                });
                                let canvas = document.getElementById("tiffCanvas");
                                let ctx = canvas.getContext("2d");

                                let width = tiff.width();
                                let height = tiff.height();
                                canvas.width = width;
                                canvas.height = height;

                                ctx.drawImage(tiff.toCanvas(), 0, 0, width, height);
                                $("#imageModal").modal("show"); // Open modal manually
                            } else {
                                console.error("tiff.js is not loaded properly.");
                            }
                        })
                        .catch(error => console.error("Error loading TIFF:", error));
                } else {
                    $("#modalImage").attr("src", imgSrc).show();
                    $("#tiffCanvas").hide();
                    $("#imageModal").modal("show"); // Open modal manually
                }

                $("#imagePrice").text("Price: ₹" + imgPrice);
            });
        });

        $(document).on("click", ".delete-btn", function(event) {
            event.stopPropagation(); // Prevent modal from opening when clicking delete

            let imageId = $(this).data("id");
            let imageName = $(this).data("name");


            alert(imageId + " " + imageName);

            if (confirm(`Are you sure you want to delete "${imageName}"?`)) {
                $.ajax({
                    url: "/delete-image/" + imageId, // Change URL as per your API
                    type: "DELETE",
                    success: function(response) {
                        alert("Image deleted successfully!");
                        location.reload(); // Reload to update UI
                    },
                    error: function(error) {
                        alert("Error deleting image.");
                        console.log(error);
                    }
                });
            }
        });
    </script>

    {{-- 
    <script>
        $(document).ready(function() {
            let selectedImageUrl = "";
            let selectedImagePrice = 0;

            $(document).on("click", ".image", function() {
                selectedImageUrl = $(this).data("img");
                selectedImagePrice = parseFloat($(this).data("price"));
                $("#modalImage").attr("src", selectedImageUrl);
                $("#imagePrice").text("Price: ₹" + selectedImagePrice.toFixed(2));
            });



            // Apply Coupon
            // Apply Coupon
            $(document).on('click', '#applyCoupon', function() {
                var couponCode = $('#couponCode').val();
                var originalPrice = $('#priceInput').val();

                if (couponCode.trim() === '') {
                    $('#coupon-message').html('<p class="text-danger">Please enter a coupon code!</p>')
                        .fadeIn();
                    setTimeout(function() {
                        $('#coupon-message').fadeOut();
                    }, 3000);
                    return;
                }

                $.ajax({
                    url: '/apply-coupon',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        coupon: couponCode,
                        price: originalPrice
                    },
                    success: function(response) {
                        $('#imagePrice').text("Price after discount: ₹" + response
                            .discounted_price);
                        $('#priceInput').val(response.discounted_price);
                        selectedImagePrice = response.discounted_price;

                        $('#coupon-message').html('<p class="text-success">' + response
                            .message + '</p>').fadeIn();
                        setTimeout(function() {
                            $('#coupon-message').fadeOut();
                        }, 3000);
                    },
                    error: function(xhr) {
                        $('#coupon-message').html('<p class="text-danger">' + xhr.responseJSON
                            .error + '</p>').fadeIn();
                        setTimeout(function() {
                            $('#coupon-message').fadeOut();
                        }, 3000);
                    }
                });
            });



            // Proceed to Payment
            $("#downloadButton").click(function() {
                var finalPrice = $('#priceInput').val(); // Get the current price
                var selectedImageUrl = $("#selectedImage").attr(
                    "src"); // Assuming image selection

                alert(finalPrice);
                // Show the loader in the corner
                $("body").append(`<div id="corner-loader" class="corner-loader">
                              <div class="spinner-border text-primary" role="status"></div>
                          </div>`);

                $.ajax({
                    url: "/process-payment",
                    type: "POST",
                    data: {
                        price: finalPrice,
                        image_url: selectedImageUrl
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Remove the loader
                        $("#corner-loader").remove();

                        // Show success toast
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful!',
                            text: 'Redirecting to download...',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Redirect after a delay
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 2000);
                    },
                    error: function() {
                        // Remove the loader
                        $("#corner-loader").remove();

                        // Show error toast
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed!',
                            text: 'Please try again.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            let selectedImageUrl = "";
            let selectedImagePrice = 0;

            // Open modal & store selected image details
            $(document).on("click", ".image, .view-more", function() {
                selectedImageUrl = $(this).attr("data-img");
                selectedImagePrice = parseFloat($(this).attr("data-price")) || 0;

                // Set values in the modal
                $("#modalImage").attr("src", selectedImageUrl);
                $("#imagePrice").text("Price: ₹" + selectedImagePrice.toFixed(2));
                $("#priceInput").val(selectedImagePrice); // ✅ Set the correct price in hidden input
            });

            // Proceed to Payment
            $("#downloadButton").click(function() {
                var finalPrice = $('#priceInput').val().trim(); // Get updated price

                if (finalPrice === "" || isNaN(finalPrice) || finalPrice <= 0) {
                    Swal.fire({
                        position: 'top-end', // ✅ Positioned at the top-right
                        icon: 'warning',
                        title: 'Invalid Price!',
                        text: 'Please select an image before making a payment.',
                        toast: true, // ✅ Displays as a toast notification
                        timer: 3000,
                        showConfirmButton: false
                    });
                    return;
                }

                // Show a loading toast until the request completes
                Swal.fire({
                    position: 'top-end', // ✅ Positioned at the top-right
                    title: 'Processing Payment...',
                    text: 'Please wait...',
                    toast: true, // ✅ Toast-style notification
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading(); // ✅ Show loading spinner
                    }
                });

                $.ajax({
                    url: "/process-payment",
                    type: "POST",
                    data: {
                        price: finalPrice,
                        image_url: selectedImageUrl // ✅ Ensure correct image URL
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-end', // ✅ Positioned at the top-right
                            icon: 'success',
                            title: 'Payment Successful!',
                            text: 'Redirecting to download...',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true // ✅ Displays as a toast
                        });

                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 2000);
                    },
                    error: function() {
                        Swal.fire({
                            position: 'top-end', // ✅ Positioned at the top-right
                            icon: 'error',
                            title: 'Payment Failed!',
                            text: 'Please try again.',
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true // ✅ Displays as a toast
                        });
                    }
                });
            });

        });
    </script>

    <style>
        .category-section {
            margin-bottom: 30px;
            padding: 1em;
        }

        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .image-box {
            width: 250px;
            height: 250px;
            overflow: hidden;
            border: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background-color: #f8f9fa00;
            position: relative;
            transition: transform 0.3s ease-in-out;
            /* Smooth scaling */
        }


        .image-box:hover {
            transform: scale(1.1);
            /* Increases size by 10% */
        }

        /* Ensure the image doesn't overflow */
        .image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            transition: transform 0.3s ease-in-out;
            /* Smooth scaling for image */
        }

        /* Optional: If you want to scale the image inside instead of the box */
        .image-box:hover .image {
            transform: scale(1.1);
            /* Increase the image size inside */
        }

        /* Move Delete Button to Bottom-Right */
        .delete-btn {
            position: absolute;
            bottom: 5px;
            /* Pushed to the bottom */
            right: 5px;
            /* Pushed to the right */
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }

        .delete-btn:hover {
            background: darkred;
        }


        .image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* Ensures full image is visible */
            display: block;
        }


        .img-fluid {
            max-width: 50% !important;
            height: auto;
        }

        .categories_name {
            font-family: 'Times New Roman', Times, serif;
            font-size: 30px;
            text-decoration: underline;
        }

        .view-more {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }
    </style>
@endsection
