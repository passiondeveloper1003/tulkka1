<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Session;
use App\Models\SessionRemind;
use App\Models\Subscribe;
use App\Models\SubscribeRemind;
use App\Models\TextLesson;
use App\Models\WebinarChapterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    public function index(Request $request, $methodName)
    {
        return $this->$methodName($request);
    }

    public function sendSessionsReminder($request)
    {
        $buyersCount = 0;
        $hour = getRemindersSettings('webinar_reminder_schedule') ?? 1;
        $time = time();
        $hoursLater = $time + ($hour * 60 * 60);

        $sessions = Session::where('date', '>=', $time)
            ->whereBetween('date', [$time, $hoursLater])
            ->with([
                'webinar'
            ])
            ->get();


        foreach ($sessions as $session) {
            $webinar = $session->webinar;

            $buyers = Sale::whereNull('refund_at')
                ->where('webinar_id', $session->webinar_id)
                ->pluck('buyer_id')
                ->toArray();

            $notifyOptions = [
                '[c.title]' => $webinar->title,
                '[time.date]' => dateTimeFormat($session->date, 'j M Y , H:i'),
            ];

            $buyersCount = count($buyers);

            if (count($buyers)) {
                foreach ($buyers as $buyer) {
                    $check = SessionRemind::where('session_id', $session->id)
                        ->where('user_id', $buyer)
                        ->first();

                    if (empty($check)) {
                        sendNotification('webinar_reminder', $notifyOptions, $buyer); // consultant

                        SessionRemind::create([
                            'session_id' => $session->id,
                            'user_id' => $buyer,
                            'created_at' => time()
                        ]);
                    }
                }
            }

            $check = SessionRemind::where('session_id', $session->id)
                ->where('user_id', $session->creator_id)
                ->first();

            if (empty($check)) {
                sendNotification('webinar_reminder', $notifyOptions, $session->creator_id); // consultant

                SessionRemind::create([
                    'session_id' => $session->id,
                    'user_id' => $session->creator_id,
                    'created_at' => time()
                ]);
            }
        }

        return response()->json([
            'sessions_count' => count($sessions),
            'buyers' => $buyersCount,
            'message' => "Notifications were sent for sessions starting from (" . dateTimeFormat($time, 'j M Y, H:i') . ')  to  (' . dateTimeFormat($hoursLater, 'j M Y, H:i') . ')'
        ]);
    }

    public function sendMeetingsReminder($request)
    {
        $hour = getRemindersSettings('meeting_reminder_schedule') ?? 1;
        $time = time();
        $hoursLater = $time + ($hour * 60 * 60);

        $reserves = ReserveMeeting::whereBetween('start_at', [$time, $hoursLater])
            ->whereNotNull('reserved_at')
            ->whereHas('sale')
            ->with([
                'meeting' => function ($query) {
                    $query->with([
                        'creator' => function ($query) {
                            $query->select('id', 'full_name');
                        }
                    ]);
                }
            ])
            ->get();

        foreach ($reserves as $reserve) {
            try {
                $notifyOptions = [
                    '[instructor.name]' => $reserve->meeting->creator->full_name,
                    '[time.date]' => dateTimeFormat($reserve->start_at, 'j M Y , H:i'),
                ];

                sendNotification('meeting_reserve_reminder', $notifyOptions, $reserve->user_id);
            } catch (\Exception $exception) {

            }
        }

        return response()->json([
            'reserve_count' => count($reserves),
            'message' => "Notifications were sent for meetings starting from (" . dateTimeFormat($time, 'j M Y, H:i') . ')  to  (' . dateTimeFormat($hoursLater, 'j M Y, H:i') . ')'
        ]);
    }


    public function sendSubscribeReminder($request)
    {
        $sendCount = 0;
        $hour = getRemindersSettings('subscribe_reminder_schedule') ?? 1;
        $time = time();
        $hoursLater = $time + ($hour * 60 * 60);

        $bigSubscribeDay = Subscribe::orderBy('days', 'desc')->first();

        $saleTime = $time - ($bigSubscribeDay->days * 24 * 60 * 60);

        $subscribeSale = Sale::where('type', Sale::$subscribe)
            ->whereNull('refund_at')
            ->whereBetween('created_at', [$saleTime, $time])
            ->with([
                'subscribe'
            ])
            ->get();

        foreach ($subscribeSale as $sale) {
            try {
                $subscribe = $sale->subscribe;

                $checkReminder = SubscribeRemind::where('user_id', $sale->buyer_id)
                    ->where('subscribe_id', $subscribe->id)
                    ->first();

                if (empty($checkReminder)) {
                    $expireDate = $sale->created_at + ($subscribe->days * 24 * 60 * 60);

                    $createReminderRecord = false;

                    if ($expireDate >= $time and $expireDate <= $hoursLater) {
                        $sendCount += 1;
                        $createReminderRecord = true;

                        $notifyOptions = [
                            '[time.date]' => dateTimeFormat($expireDate, 'j M Y , H:i'),
                        ];

                        sendNotification('subscribe_reminder', $notifyOptions, $sale->buyer_id);
                    } elseif ($expireDate < $time) {
                        $createReminderRecord = true;
                    }

                    if ($createReminderRecord) {
                        SubscribeRemind::create([
                            'user_id' => $sale->buyer_id,
                            'subscribe_id' => $subscribe->id,
                            'created_at' => $time
                        ]);
                    }
                }
            } catch (\Exception $exception) {

            }
        }

        return response()->json([
            'count' => $sendCount,
            'message' => "Notifications were sent for users expiring subscribe from (" . dateTimeFormat($time, 'j M Y, H:i') . ')  to  (' . dateTimeFormat($hoursLater, 'j M Y, H:i') . ')'
        ]);
    }
}
