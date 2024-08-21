<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Support\Facades\Http;

class YouTubeController extends Controller
{
    protected $apiKey;
    protected $client;
    protected $googleClient;


    public function index()
    {

        return view('youtube.index');
    }

    // public function showChannelVideos(Request $request)
    // {
    //     $channelId = $request->input('channel_id');
    //     $channelName = $request->input('channel_name');

    //     if (!$channelId || !$channelName) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'Please provide both channel ID and channel name.');
    //     }

    //     $url = "https://www.googleapis.com/youtube/v3/search";
    //     $playlistsIds = ['RDD-grFPvf9r4', 'RDlDncBebKbe4', 'RDGMEM2j3yRsqu_nuzRLnHd2bMVAVMeLjmQ0aGC1U', 'RDQvY4PnYuAa4', 'RDD-grFPvf9r4', 'RDxgSMibE5Tdc'];

    //     try {
    //         $response = $this->client->get($url, [
    //             'query' => [
    //                 'channelId' => $channelId,
    //                 'part' => 'snippet',
    //                 'order' => 'date',
    //                 'maxResults' => 100, // Adjust this number as needed
    //                 'key' => $this->apiKey,
    //             ],
    //         ]);

    //         $data = json_decode($response->getBody(), true);

    //         $videos = [];
    //         if (isset($data['items'])) {
    //             foreach ($data['items'] as $item) {
    //                 $randomKey = array_rand($playlistsIds);
    //                 $randomPlaylistId = $playlistsIds[$randomKey];

    //                 $videos[] = [
    //                     'title' => $item['snippet']['title'],
    //                     'thumbnail' => $item['snippet']['thumbnails']['medium']['url'],
    //                     'videoId' => $item['id']['videoId'] ?? null,
    //                     'playlist_id' => $randomPlaylistId,
    //                 ];
    //             }
    //         }

    //         return view('youtube.videos', compact('videos', 'channelName'))->with('openInNewTab', true);
    //     } catch (\GuzzleHttp\Exception\ClientException $e) {
    //         $responseBody = $e->getResponse()->getBody(true);
    //         $errorData = json_decode($responseBody, true);

    //         // Check for specific error code if needed
    //         if (isset($errorData['error']['code']) && $errorData['error']['code'] == 400) {
    //             return redirect()
    //                 ->back()
    //                 ->with('error', 'Invalid channel ID. Please check the ID and try again.');
    //         }

    //         return redirect()
    //             ->back()
    //             ->with('error', 'An error occurred while fetching the videos. Please try again.');
    //     }
    // }

public function showChannelVideos(Request $request)
{



    if(!session()->has('secret_auth') || session('secret_auth') === false ){
           return redirect()->route('home');
    }



    $channelId = $request->input('channel_id');
    $channelName = $request->input('channel_name');

    if (!$channelId || !$channelName) {
        return redirect()
            ->back()
            ->with('error', 'Please provide both channel ID and channel name.');
    }

    $rssUrl = "https://www.youtube.com/feeds/videos.xml?channel_id=" . $channelId;

    try {
        // Fetch and parse the RSS feed
        $response = Http::get($rssUrl);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch RSS feed');
        }

        $xml = simplexml_load_string($response->body());
        if ($xml === false) {
            throw new \Exception('Failed to parse RSS feed');
        }

        $videos = [];
        foreach ($xml->entry as $entry) {
            $videoId = $this->extractVideoId((string) $entry->link['href']);
            $videos[] = [
                'title' => (string) $entry->title,
                'thumbnail' => (string) $entry->children('media', true)->thumbnail['url'],
                'videoId' => $videoId,
                'playlist_id' => $this->getRandomPlaylistId(),
            ];
        }

        // Ensure we have between 40 and 50 videos
        $totalVideos = count($videos);
        if ($totalVideos < 40) {
            // Calculate how many times to repeat the existing videos
            $repeatTimes = ceil(40 / $totalVideos);
            $videos = array_merge(...array_fill(0, $repeatTimes, $videos));
        }

        // Shuffle and slice the array to get between 40 and 50 videos
        shuffle($videos);
        $videos = array_slice($videos, 0, rand(40, 50));

        return view('youtube.videos', compact('videos', 'channelName'))->with('openInNewTab', true);

    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'An error occurred while fetching the videos. Please try again.');
    }
}


public function checkSecretKey(Request $request)
{
    // Validate the input
    $request->validate([
        'secret_key' => 'required|string',
    ]);

    // Get the secret key from the .env file
    $envSecretKey = env('SECRET_KEY');

    // Check if the provided secret key matches the one in the .env file
    if ($request->input('secret_key') === $envSecretKey) {
        // Set the session variable
        session(['secret_auth' => true]);

        // Redirect to the same page or another page with a success message
        return redirect()->back()->with('success', 'Access granted. You can now use the other form.');
    } else {
        // If the key does not match, redirect back with an error message
        return redirect()->back()->with('error', 'Invalid secret key. Please try again.');
    }
}

private function extractVideoId($url)
{
    // Extract the video ID from the URL
    parse_str(parse_url($url, PHP_URL_QUERY), $query);
    return $query['v'] ?? null;
}

private function getRandomPlaylistId()
{
    $playlistsIds = ['RDD-grFPvf9r4', 'RDlDncBebKbe4', 'RDGMEM2j3yRsqu_nuzRLnHd2bMVAVMeLjmQ0aGC1U', 'RDQvY4PnYuAa4', 'RDD-grFPvf9r4', 'RDxgSMibE5Tdc'];
    $randomKey = array_rand($playlistsIds);
    return $playlistsIds[$randomKey];
}


}
