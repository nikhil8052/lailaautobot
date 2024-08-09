<!-- resources/views/channel_videos.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Channel Videos</title>
</head>
<body>
    <h1>Enter YouTube Channel Details</h1>

    @if (session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('channel.videos') }}" method="POST">
        @csrf
        <label for="channel_id">Channel ID:</label>
        <input type="text" id="channel_id" name="channel_id" required><br><br>

        <label for="channel_name">Channel Name:</label>
        <input type="text" id="channel_name" name="channel_name" required><br><br>

        <button type="submit">Get Videos</button>
    </form>

    @if(isset($videos))
        <h2>Channel Name: {{ $channelName }}</h2>
        <div>
            @foreach($videos as $video)
                @if($video['videoId'])
                    <div>
                        <h3>{{ $video['title'] }}</h3>
                        <img src="{{ $video['thumbnail'] }}" alt="{{ $video['title'] }}">
                        <p><a href="https://www.youtube.com/watch?v={{ $video['videoId'] }}&list={{ $video['playlist_id'] }}" target="_blank">Watch Video</a></p>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</body>
</html>
