<?php

namespace App\Services;

use App\Models\Event;
use App\Models\GuestBook;
use App\Models\PhotoEvent;

class TemplateService
{
    /**
     * Get template view data for an event
     */
    public function getTemplateViewData(Event $event): array
    {
        $data_guestbook = GuestBook::where('event_id', $event->id)->get();
        $photo_event = PhotoEvent::where('event_id', $event->id)->get()->toArray();

        return [
            'event' => $event,
            'data_guestbook' => $data_guestbook,
            'photo_event' => $photo_event
        ];
    }

    /**
     * Get view name for a template
     */
    public function getTemplateName(string $template): string
    {
        $templateMap = [
            'Gold' => 'guest.preview-gold',
            'Soft' => 'guest.preview-soft',
            'Prime' => 'guest.preview-prime',
            'Silver' => 'guest.preview-silver',
            'Chocolate' => 'guest.preview-choco',
            'Pink' => 'guest.preview-pink',
            'Crystal' => 'guest.preview-crystal',
            'Grey' => 'guest.preview-grey',
            'Bronze' => 'guest.preview-bronze',
            'Blue' => 'guest.preview-blue',
            'Camel' => 'guest.preview-camel',
            'Ruby' => 'guest.preview-ruby',
            'Goldy' => 'guest.preview-goldy',
            'Navy' => 'guest.preview-navy',
            'Natural' => 'guest.preview-natural',
            'jawa' => 'guest.preview-jawa',
            'Basic' => 'guest.preview-basic',
            'Regular' => 'guest.preview-regular',
        ];

        return $templateMap[$template] ?? 'guest.preview-gold';
    }

    /**
     * Render template view for an event
     */
    public function renderTemplate(Event $event): \Illuminate\View\View
    {
        $viewData = $this->getTemplateViewData($event);
        $viewName = $this->getTemplateName($event->template);

        return view($viewName, $viewData);
    }
}