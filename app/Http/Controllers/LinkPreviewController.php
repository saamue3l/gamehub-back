<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;



class LinkPreviewController extends Controller
{

    private const LINK_PREVIEW_TTL = 60 * 60 * 12;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLinkPreview(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->query('url');

        $response = Cache::remember("linkPreview:$url", $this::LINK_PREVIEW_TTL, function () use ($url) {
                return Http::get("http://localhost:3001/link-preview", [
                'url' => $url,
            ])->json();
        });

        return response()->json($response)->header('Cache-Control', "public, max-age=" . $this::LINK_PREVIEW_TTL);

    }
}
