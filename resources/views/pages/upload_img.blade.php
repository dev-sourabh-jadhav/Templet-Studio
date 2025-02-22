@extends('structure.main')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire('Success!', '{{ session('success') }}', 'success');
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire('Error!', '{!! implode('<br>', $errors->all()) !!}', 'error');
        </script>
    @endif

    <div class="breadcrumb-wrapper">
        <h2 class="page-title">Add Images</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Add Images</li>
        </ol>
    </div>

    <div class="container">
        <div class="card p-4">
            <h4>Upload Image</h4>
            <form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="category_id" class="form-label">Select Category</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->categories_name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" name="image" id="image" class="form-control" required>
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" name="price" id="price" class="form-control" required>
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

    {{-- <div class="container">
        <h2 class="text-center">Uploaded Images by Category</h2>

        @foreach ($categories as $category)
            <div class="category-section">
                <h3>{{ $category->categories_name }}</h3>
                <div class="image-grid">
                    @foreach ($category->images as $image)
                        <div class="image-box">
                            <img src="{{ asset($image->image_name) }}" alt="Image" class="image">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div> --}}

    <div class="container">
        <h2 class="text-center">Uploaded Images by Category</h2>
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
                        <button class="delete-btn" 
                                data-id="${image.id}" 
                                data-name="${image.image_name}">
                            <i class="ti ti-trash"></i>
                        </button>
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
            });
        });


        $(document).on("click", ".delete-btn", function(event) {
            event.stopPropagation(); // Prevent modal from opening when clicking delete

            let imageId = $(this).data("id");
            let imageName = $(this).data("name");



            if (confirm(`Are you sure you want to delete "${imageName}"?`)) {
                $.ajax({
                    url: "/delete-image/" + imageId, 
                    type: "DELETE",
                    data: {
                        imageName: imageName
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
            width: 300px;
            height: 300px;
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
