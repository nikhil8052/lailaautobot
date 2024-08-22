<!DOCTYPE html>
<html>
<head>
    <title>{{ $channelName }} - Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="{{ asset('images/bc.png') }}" />

    <style>
        body {
            margin: 0;
            height: 100vh;
            width: 100%;
            background-image: url("{{ asset('images/bc2.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            overflow: hidden; /* Prevents body scrolling */
        }

        .container {
            padding-top: 20px;
            height: 100%;
        }

        .video-list-container {
    height: calc(100vh - 80px); /* Adjust for header and padding */
    overflow-y: auto; /* Adds vertical scroll if needed */
    padding-right: 15px; /* Space for scrollbar */
}

/* Customize scrollbar width */
.video-list-container::-webkit-scrollbar {
    width: 7px; /* Decrease width of the scrollbar */
}

/* Customize scrollbar track */
.video-list-container::-webkit-scrollbar-track {
    background: #f1f1f1; /* Background color of the track */
}

/* Customize scrollbar thumb */
.video-list-container::-webkit-scrollbar-thumb {
    background-color: #888; /* Color of the scrollbar thumb */
    border-radius: 10px; /* Rounded corners of the thumb */
    border: 2px solid #f1f1f1; /* Adjust padding around thumb */
}

/* Customize scrollbar thumb on hover */
.video-list-container::-webkit-scrollbar-thumb:hover {
    background-color: #555; /* Darker color on hover */
}


        .video-item {
            text-align: center;
            margin-bottom: 20px;
        }

        .video-item a {
            display: block;
            padding: 10px 20px;
            background-color: #0d6efd; /* Bootstrap blue */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .video-item a:hover {
            background-color: #0b5ed7;
        }
        

        .top-heading {
    background: #F5F5F599;
    border-radius: 10px;
    padding: 5px ;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center video-list-container">
            <div class="col-12 col-lg-8">
                        <h2 class="text-center my-4 top-heading">Welcome To Laila Auto Official One</h2>

                <div class="row">
                    @if(isset($videos))
                        @foreach($videos as $video)
                            @if($video['videoId'])
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 video-item mb-2">
                                    <a href="https://www.youtube.com/watch?v={{ $video['videoId'] }}&list={{ $video['playlist_id'] }}" target="_blank">
                                        Video {{ $loop->iteration }}
                                    </a>
                                    
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p>No videos found for this channel.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
