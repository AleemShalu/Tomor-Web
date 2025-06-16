<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;

class AppVersionController extends Controller
{
    public function getLatestVersion()
    {
        $platform = request()->input('platform', 'Android');

        // Fetch the latest version applicable to the specified platform
        $latestVersion = AppVersion::whereJsonContains('platforms', $platform)
            ->orderBy('release_date', 'desc')
            ->first();

        if ($latestVersion) {
            return response()->json([
                'version' => $latestVersion->version,
                'details' => [
                    'ar' => $latestVersion->details_ar,
                    'en' => $latestVersion->details_en,
                ],
                'is_mandatory' => $latestVersion->is_mandatory,
                'download_url' => $latestVersion->download_url,
                'release_notes' => [
                    'ar' => $latestVersion->release_notes_ar,
                    'en' => $latestVersion->release_notes_en,
                ],
                'release_date' => $latestVersion->release_date,
                'platforms' => json_decode($latestVersion->platforms), // Decode platforms JSON to an array
            ]);
        }

        return response()->json([
            'message' => 'No version information available for this platform.',
        ], 404);
    }

    public function getAllVersions()
    {
        $platform = request()->input('platform', 'Android');

        // Fetch all versions applicable to the specified platform
        $versions = AppVersion::whereJsonContains('platforms', $platform)
            ->orderBy('release_date', 'desc')
            ->get()
            ->map(function ($version) {
                return [
                    'version' => $version->version,
                    'details' => [
                        'ar' => $version->details_ar,
                        'en' => $version->details_en,
                    ],
                    'is_mandatory' => $version->is_mandatory,
                    'download_url' => $version->download_url,
                    'release_notes' => [
                        'ar' => $version->release_notes_ar,
                        'en' => $version->release_notes_en,
                    ],
                    'release_date' => $version->release_date,
                    'platforms' => json_decode($version->platforms), // Decode platforms JSON to an array
                ];
            });

        return response()->json($versions);
    }
}
