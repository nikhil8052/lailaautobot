<!DOCTYPE html>
<html>
    <head>
        <title>Channel Videos</title>
        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="{{ asset('images/bc.png') }}" />

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <style>
            body{
                                background-image: url("{{ asset("images/bc.png") }}");

            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="main-div">
                <!-- Image on the left (8 columns) -->
                <div class=" image-container main-img-container">
                    <img src="{{ asset('images/youtube.png') }}" alt="Background Image" />
                </div>
                <!-- Form on the right (4 columns) -->
                <div class="form-main-div">
                    <div class="form-container">
                        <h2>Hey, This is the Free Website you can all use without any Hassle</h2>
                        <p>Say Thanks to Ali Riaz</p>
                        <p>Connect with me On Whatsapp +92 3018191025, If you need any help you can always contact me privately.</p>

                        @if (session('error'))
                        <p style="color: red;">{{ session('error') }}</p>
                        @endif

                        <form action="{{ route('channel.videos') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="channel_id" class="form-label">Channel ID:</label>
                                <input type="text" id="channel_id" name="channel_id" class="form-control" required />
                            </div>

                            <div class="mb-3">
                                <label for="channel_name" class="form-label">Channel Name:</label>
                                <input type="text" id="channel_name" name="channel_name" class="form-control" required />
                            </div>

                            <button type="submit" class="btn btn-primary">Get Videos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS and Popper.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
