@extends('structure.main')

@section('content')
    <div class="container text-center mt-5">
        <h2>Payment Successful! 🎉</h2>
        <p>Your image is ready for download.</p>
        <a id="downloadImage" href="{{ $image_url }}" download class="btn btn-primary">
            Click here to Download Again
        </a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let downloadLink = document.getElementById("downloadImage");
            if (downloadLink) {
                downloadLink.click(); // Automatically trigger download
            }
        });
    </script>
@endsection
