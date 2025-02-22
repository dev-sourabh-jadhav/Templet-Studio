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
                    <img id="modalImage" src="" class="img-fluid rounded" alt="Full Image">
                    <p id="imagePrice" class="mt-3 fw-bold fs-5 text-primary"></p>

                    <!-- Coupon Input -->
                    <div class="mt-3">
                        <input type="text" id="couponCode" class="form-control" placeholder="Enter Coupon Code">
                        <input type="hidden" id="priceInput" class="form-control">
                        <button id="applyCoupon" class="btn btn-success mt-2">Apply Coupon</button>
                    </div>

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
                            html += `<div class="image-box">
                    <img src="${image.image_url}" alt="Image" class="image" 
                         data-bs-toggle="modal" 
                         data-bs-target="#imageModal"
                         data-img="${image.image_url}" 
                         data-price="${image.price}">

                </div>`;
                        });

                        html += `</div></div>`;
                    });

                    $('#image-container').html(html);
                },


                error: function(error) {
                    console.log("Error fetching images:", error);
                }
            });

            // Show image in modal when clicked
            $(document).on("click", ".image", function() {
                let imgSrc = $(this).attr("data-img");
                let imgPrice = $(this).attr("data-price");

                $("#modalImage").attr("src", imgSrc);
                $("#imagePrice").text("Price: ₹" + imgPrice);
                $("#priceInput").val(imgPrice);
            });
        });



        $(document).on("click", ".delete-btn", function(event) {
            event.stopPropagation(); // Prevent modal from opening when clicking delete

            let imageId = $(this).data("id");
            let imageName = $(this).data("name");


            alert(imageId + " " + imageName);

            // if (confirm(`Are you sure you want to delete "${imageName}"?`)) {
            //     $.ajax({
            //         url: "/delete-image/" + imageId, // Change URL as per your API
            //         type: "DELETE",
            //         success: function(response) {
            //             alert("Image deleted successfully!");
            //             location.reload(); // Reload to update UI
            //         },
            //         error: function(error) {
            //             alert("Error deleting image.");
            //             console.log(error);
            //         }
            //     });
            // }
        });
    </script>

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
                    alert('Please enter a coupon code.');
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
                        $('#imagePrice').text("Price after discount: ₹" + response.discounted_price);
                        $('#priceInput').val(response.discounted_price); // Update hidden input value
                        selectedImagePrice = response.discounted_price; // Update selectedImagePrice with discounted price
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error);
                    }
                });
            });


            // Proceed to Payment
            $("#downloadButton").click(function() {
                var finalPrice = $('#priceInput').val(); // Get the current price (original or discounted)
                
                $.ajax({
                    url: "/process-payment", 
                    type: "POST",
                    data: {
                        price: finalPrice, // Use finalPrice instead of selectedImagePrice
                        image_url: selectedImageUrl
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        window.location.href = response.redirect_url;
                    },
                    error: function() {
                        alert("Payment failed!");
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
    </style>
@endsection
