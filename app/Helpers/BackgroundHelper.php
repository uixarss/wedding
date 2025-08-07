<?php

namespace App\Helpers;

use App\Models\Event;

class BackgroundHelper
{
    /**
     * Check and process background type for a section
     */
    public static function checkBackgroundType($section, string $slug, Event $event): void
    {
        if (!$section || !$section->background) {
            return;
        }

        $cekbackground = explode('#', $section->background);

        if (count($cekbackground) !== 1) {
            // Keep the background as is (likely a color value)
            $section->background = $section->background;
        } else {
            // Process as image background
            $img = $section->background;
            $section->background = 'linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.3)), url(/admin/assets/images/events/builder/' . $slug . '-' . $event->id . '/' . $img . ') center / cover';
        }
    }

    /**
     * Process multiple sections backgrounds
     */
    public static function processAllSections(array $sections, string $slug, Event $event): void
    {
        foreach ($sections as $section) {
            self::checkBackgroundType($section, $slug, $event);
        }
    }
}