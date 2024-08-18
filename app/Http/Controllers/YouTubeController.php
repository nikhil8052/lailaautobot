<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Google_Client;
use Google_Service_YouTube;


class YouTubeController extends Controller
{
    protected $apiKey;
    protected $client;
    protected $googleClient;

    public function __construct()
    {
        // NIKK ACCOUNT 
        // $this->apiKey = "AIzaSyBaPYmJUCc4L90NsvR9ZSI8_nSVWRZ1pCE";

        // CLINT ACCOUNT 
        $this->apiKey = env('YOUTUBE_API_KEY');
        $this->client = new Client();
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->googleClient->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        // $this->googleClient->setRedirectUri(route('auth.google.callback'));
        $this->googleClient->addScope(Google_Service_YouTube::YOUTUBE);
        $this->googleClient->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);


    }

    public function index(){
        return view('youtube.index');
    }

  public function showChannelVideos(Request $request)
{
    $channelId = $request->input('channel_id');
    $channelName = $request->input('channel_name');

    if (!$channelId || !$channelName) {
        return redirect()->back()->with('error', 'Please provide both channel ID and channel name.');
    }

    $url = "https://www.googleapis.com/youtube/v3/search";
    $playlistsIds = [
        'RDD-grFPvf9r4',
        'RDlDncBebKbe4',
        'RDGMEM2j3yRsqu_nuzRLnHd2bMVAVMeLjmQ0aGC1U',
        'RDQvY4PnYuAa4',
        'RDD-grFPvf9r4',
        'RDxgSMibE5Tdc',
    ];

    try {
        $response = $this->client->get($url, [
            'query' => [
                'channelId' => $channelId,
                'part' => 'snippet',
                'order' => 'date',
                'maxResults' => 100, // Adjust this number as needed
                'key' => $this->apiKey,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $videos = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $randomKey = array_rand($playlistsIds);
                $randomPlaylistId = $playlistsIds[$randomKey];

                $videos[] = [
                    'title' => $item['snippet']['title'],
                    'thumbnail' => $item['snippet']['thumbnails']['medium']['url'],
                    'videoId' => $item['id']['videoId'] ?? null,
                    'playlist_id' => $randomPlaylistId,
                ];
            }
        }

        return view('youtube.videos', compact('videos', 'channelName'));

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $responseBody = $e->getResponse()->getBody(true);
        $errorData = json_decode($responseBody, true);

        // Check for specific error code if needed
        if (isset($errorData['error']['code']) && $errorData['error']['code'] == 400) {
            return redirect()->back()->with('error', 'Invalid channel ID. Please check the ID and try again.');
        }

        return redirect()->back()->with('error', 'An error occurred while fetching the videos. Please try again.');
    }
}

}
