<?php

namespace App\Http\Controllers;

use App\Mail\DaftarUser;
use App\Models\Audio;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Event;
use App\Models\GuestBook;
use App\Models\Invite;
use App\Models\PhotoEvent;
use App\Models\Post;
use App\Notifications\UserCreated;
use App\Notifications\UserInvited;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;
use App\Services\TemplateService;
use App\Services\EventBuilderService;
use App\Helpers\BackgroundHelper;

class GuestController extends Controller
{
    private TemplateService $templateService;
    private EventBuilderService $eventBuilderService;

    public function __construct(TemplateService $templateService, EventBuilderService $eventBuilderService)
    {
        $this->templateService = $templateService;
        $this->eventBuilderService = $eventBuilderService;
    }

    public function welcome()
    {
        $blogs = Post::orderBy('created_at', 'DESC')->get();
        LogActivity::addToLog('','landing page');

        return view('welcome', [
            'blogs' => $blogs
        ]);
    }

    public function view(string $slug)
    {
        $event = Event::where('slug', $slug)->with('audio')->first();
        
        if (!$event) {
            abort(404);
        }

        $kalimat = 'Lihat undangan pernikahan ' . $event->nama_lengkap_mempelai_wanita . ' & ' . $event->nama_lengkap_mempelai_pria;
        LogActivity::addToLog($kalimat, 'Lihat Undangan');

        // Handle custom template with event builder
        if ($event->template === 'Custom') {
            return $this->renderCustomTemplate($event, $slug);
        }

        // Handle standard templates
        return $this->templateService->renderTemplate($event);
    }

    /**
     * Render custom template with event builder sections
     */
    private function renderCustomTemplate(Event $event, string $slug): \Illuminate\View\View
    {
        $viewData = $this->eventBuilderService->getEventBuilderData($event);
        $sections = $this->eventBuilderService->getEventBuilderSections($event);
        $this->eventBuilderService->processBackgrounds($sections, $slug, $event);

        return view('event-builder-page', $viewData);
    }

    public function redirect(int $id): \Illuminate\Http\RedirectResponse
    {
        $event = Event::find($id);
        
        if (!$event) {
            abort(404);
        }

        return redirect()->route('see.guestbook', [
            'slug' => $event->slug
        ]);
    }

    public function guestbook(string $slug): \Illuminate\View\View
    {
        $event = Event::where('slug', $slug)->first();
        
        if (!$event) {
            abort(404);
        }
        
        $data_guestbook = GuestBook::where('event_id', $event->id)->get();

        return view('guest.guestbook', [
            'data_guestbook' => $data_guestbook,
            'event' => $event
        ]);
    }

    public function basiceventpage(string $slug): \Illuminate\View\View
    {
        $event = Event::where('slug', $slug)->with('audio')->first();
        
        if (!$event) {
            abort(404);
        }

        $viewData = $this->eventBuilderService->getEventBuilderData($event);
        $sections = $this->eventBuilderService->getEventBuilderSections($event);
        $this->eventBuilderService->processBackgrounds($sections, $slug, $event);

        return view('event-builder-page', $viewData);
    }

    public function listEvent(): \Illuminate\View\View
    {
        $events = Event::with('order')->get();
        return view('story', [
            'events' => $events
        ]);
    }

    function _cekJenisBackground($section, $slug, $event)
    {
        if ($section) {
            $cekbackground = explode('#', $section->background);
        }

        if (count($cekbackground) !== 1) {
            $section->background = $section->background;
        } else {
            $img = $section->background;
            $section->background = 'linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.3)), url(/admin/assets/images/events/builder/' . $slug . '-' . $event->id . '/' . $img . ') center / cover';
        }
    }

    public function demoGold()
    {
        return view('guest.demo-1');
    }

    public function demoSoft()
    {
        return view('guest.demo-2');
    }

    public function demoPrime()
    {
        return view('guest.demo-3');
    }

    public function demoSilver()
    {
        return view('guest.demo-4');
    }

    public function demoChoco()
    {
        return view('guest.demo-5');
    }

    public function demoPink()
    {
        return view('guest.demo-6');
    }

    public function demoCrystal()
    {
        return view('guest.demo-7');
    }

    public function demoGrey()
    {
        return view('guest.demo-8');
    }

    public function demoBronze()
    {
        return view('guest.demo-9');
    }

    public function demoBlue()
    {
        return view('guest.demo-10');
    }

    public function demoV1()
    {
        return view('guest.demo-11');
    }

    public function demoV2()
    {
        return view('guest.demo-12');
    }

    public function demoV3()
    {
        return view('guest.demo-13');
    }

    public function demoV4()
    {
        return view('guest.demo-14');
    }

    public function demoV5()
    {
        return view('guest.demo-15');
    }

    public function demoV6()
    {
        return view('guest.demo-16');
    }

    public function demoV7()
    {
        return view('guest.demo-17');
    }

    public function attending(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|email|email_address',
            'name' => 'required'
        ]);
        $role_customer = Role::where('name', 'customer')->first();
        $user = User::where('email', $request->email)->first();
        $invite = null;

        // jika user tidak ada
        if ($user == null) {
            // membuat user baru
            $password = Str::random(8);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($password)
            ]);
            $user->assignRole($role_customer->name);
            $user->notify(new UserCreated($request->name, $request->email, $password));
            $kalimat = $user->name . ' register';
            LogActivity::addToLog($kalimat, 'Register via undangan');
            $invite = Invite::where('event_id', $id)->where('guest_id', $user->id)->get();

            if ($invite->count() > 0) {
                return redirect()->back()->with([
                    'error' => 'Anda sudah dalam daftar RSVP!'
                ]);
            } else {
                $new_invite = Invite::create([
                    'kode_kupon' => Str::random(7),
                    'event_id' => $id,
                    'guest_id' => $user->id,
                    'is_invited' => 1,
                    'is_confirmed' => 0,
                ]);
                $event = Event::find($id);
                $user->notify(new UserInvited($event));
                $kalimat = $user->name . ' diundang ke pernikahan ' . $event->nama_lengkap_mempelai_wanita . ' & ' . $event->nama_lengkap_mempelai_pria;
                LogActivity::addToLog($kalimat, 'Undangan');
                return redirect()->back()->with([
                    'success' => 'Thank you for your attending'
                ]);
            }
        } else {

            $invite = Invite::where('event_id', $id)->where('guest_id', $user->id)->get();

            if ($invite->count() > 0) {
                return redirect()->back()->with([
                    'error' => 'Anda sudah dalam daftar RSVP!'
                ]);
            } else {

                $new_invite = Invite::create([
                    'kode_kupon' => Str::random(7),
                    'event_id' => $id,
                    'guest_id' => $user->id,
                    'is_invited' => 1,
                    'is_confirmed' => 0,
                ]);
                $event = Event::find($id);
                $user->notify(new UserInvited($event));
                $kalimat = $user->name . ' diundang ke pernikahan' . $event->nama_lengkap_mempelai_wanita . ' & ' . $event->nama_lengkap_mempelai_pria;
                LogActivity::addToLog($kalimat, 'Undangan');
                return redirect()->back()->with([
                    'success' => 'Thank you for your attending'
                ]);
            }
        }
    }

    public function wishes(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|email|email_address',
            'name' => 'required',
            'text' => 'required'
        ]);

        $role_customer = Role::where('name', 'customer')->first();
        $user = User::where('email', $request->email)->first();
        $guest_book = null;

        // jika user tidak ada
        if ($user == null) {
            // membuat user baru
            $password = Str::random(8);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($password)
            ]);
            $user->assignRole($role_customer->name);
            $user->notify(new UserCreated($request->name, $request->email, $password));
            $kalimat = $user->name . ' register';
            LogActivity::addToLog($kalimat, 'Register via undangan');
            $guest_book = GuestBook::where('event_id', $id)->where('user_id', $user->id)->get();

            if ($guest_book->count() > 0) {
                return redirect()->back()->with([
                    'error' => 'Anda sudah mengucapkan...'
                ]);
            } else {
                $new_guest_book = GuestBook::create([
                    'event_id' => $id,
                    'user_id' => $user->id,
                    'text' => $request->text,
                ]);

                $kalimat = $user->name . ' mengucapkan '. $request->text;
                LogActivity::addToLog($kalimat, 'Mengucapkan via undangan');

                return redirect()->back()->with([
                    'success' => 'Terima kasih...'
                ]);
            }
        } else {

            $invite = GuestBook::where('event_id', $id)->where('user_id', $user->id)->get();

            if ($invite->count() > 0) {
                return redirect()->back()->with([
                    'error' => 'Anda sudah mengucapkan...'
                ]);
            } else {

                $new_guest_book = GuestBook::create([
                    'event_id' => $id,
                    'user_id' => $user->id,
                    'text' => $request->text,
                ]);
                $kalimat = $user->name . ' mengucapkan '. $request->text;
                LogActivity::addToLog($kalimat, 'Mengucapkan via undangan');

                return redirect()->back()->with([
                    'success' => 'Terima kasih...'
                ]);
            }
        }
    }

    public function landingpages()
    {
        return view('guest.landingpages');
    }
}
