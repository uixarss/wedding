<?php

namespace App\Services;

use App\Models\Event;
use App\Models\GuestBook;
use App\Models\PhotoEvent;
use App\Helpers\BackgroundHelper;
use Illuminate\Support\Facades\DB;

class EventBuilderService
{
    /**
     * Get all event builder sections for an event
     */
    public function getEventBuilderSections(Event $event): array
    {
        return [
            'hero' => DB::table('hero_section')->where('event_id', $event->id)->first(),
            'invitation' => DB::table('invitation_section')->where('event_id', $event->id)->first(),
            'gallery' => DB::table('gallery_section')->where('event_id', $event->id)->first(),
            'countdown' => DB::table('countdown_section')->where('event_id', $event->id)->first(),
            'maps' => DB::table('maps_section')->where('event_id', $event->id)->first(),
            'streaming' => DB::table('streaming_section')->where('event_id', $event->id)->first(),
            'videos' => DB::table('videos_section')->where('event_id', $event->id)->first(),
            '_event' => DB::table('event_section')->where('event_id', $event->id)->first(),
            'comment' => DB::table('comment_section')->where('event_id', $event->id)->first(),
            'footer' => DB::table('footer_section')->where('event_id', $event->id)->first(),
        ];
    }

    /**
     * Process backgrounds for all sections
     */
    public function processBackgrounds(array $sections, string $slug, Event $event): void
    {
        BackgroundHelper::processAllSections($sections, $slug, $event);
    }

    /**
     * Get view data for event builder page
     */
    public function getEventBuilderData(Event $event): array
    {
        $data_guestbook = GuestBook::where('event_id', $event->id)->get();
        $photo_event = PhotoEvent::where('event_id', $event->id)->get()->toArray();
        $sections = $this->getEventBuilderSections($event);

        return array_merge([
            'event' => $event,
            'data_guestbook' => $data_guestbook,
            'photo_event' => $photo_event,
        ], $sections);
    }
}