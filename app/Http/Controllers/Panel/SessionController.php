<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AgoraHistory;
use App\Models\Sale;
use App\Models\Session;
use App\Models\Translation\SessionTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use Illuminate\Http\Request;
use App\Sessions\Zoom;
use Validator;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:64',
            'date' => 'required|date',
            'duration' => 'required|numeric',
            'link' => ($data['session_api'] == 'local') ? 'required|url' : 'nullable',
            'api_secret' => (($data['session_api'] != 'zoom') and ($data['session_api'] != 'agora')) ? 'required' : 'nullable',
            'moderator_secret' => ($data['session_api'] == 'big_blue_button') ? 'required' : 'nullable',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($data['session_api']) and $data['session_api'] == 'zoom' and (empty($user->zoomApi) or empty($user->zoomApi->jwt_token))) {
            $error = [
                'zoom-not-complete-alert' => []
            ];

            return response([
                'code' => 422,
                'errors' => $error,
            ], 422);
        }

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            $sessionDate = convertTimeToUTCzone($data['date'], $webinar->timezone);

            if ($sessionDate->getTimestamp() < $webinar->start_date) {
                $error = [
                    'date' => [trans('webinars.session_date_must_larger_webinar_start_date', ['start_date' => dateTimeFormat($webinar->start_date, 'j M Y')])]
                ];

                return response([
                    'code' => 422,
                    'errors' => $error,
                ], 422);
            }

            $session = Session::create([
                'creator_id' => $user->id,
                'webinar_id' => $data['webinar_id'],
                'chapter_id' => $data['chapter_id'],
                'date' => $sessionDate->getTimestamp(),
                'duration' => $data['duration'],
                'link' => $data['link'] ?? null,
                'session_api' => $data['session_api'],
                'api_secret' => $data['api_secret'] ?? null,
                'moderator_secret' => $data['moderator_secret'] ?? null,
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'extra_time_to_join' => $data['extra_time_to_join'] ?? null,
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? Session::$Active : Session::$Inactive,
                'created_at' => time()
            ]);

            if (!empty($session)) {
                SessionTranslation::updateOrCreate([
                    'session_id' => $session->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                WebinarChapterItem::makeItem($user->id, $session->chapter_id, $session->id, WebinarChapterItem::$chapterSession);
            }

            if ($data['session_api'] == 'big_blue_button') {
                $this->handleBigBlueButtonApi($session, $user);
            } else if ($data['session_api'] == 'zoom') {
                return $this->handleZoomApi($session, $user);
            } else if ($data['session_api'] == 'agora') {
                $agoraSettings = [
                    'chat' => (!empty($data['agora_chat']) and $data['agora_chat'] == 'on'),
                    'record' => (!empty($data['agora_record']) and $data['agora_record'] == 'on'),
                ];
                $session->agora_settings = json_encode($agoraSettings);

                $session->save();
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $session = Session::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        $session_api = !empty($data['session_api']) ? $data['session_api'] : $session->session_api;

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:64',
            'date' => ($session_api == 'local') ? 'required|date' : 'nullable',
            'duration' => ($session_api == 'local') ? 'required|numeric' : 'nullable',
            'link' => ($session_api == 'local') ? 'required|url' : 'nullable',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            if (!empty($session)) {
                $sessionDate = $session->date;

                if (!empty($data['date'])) {
                    $sessionDate = convertTimeToUTCzone($data['date'], $webinar->timezone);

                    if ($sessionDate->getTimestamp() < $webinar->start_date) {
                        $error = [
                            'date' => [trans('webinars.session_date_must_larger_webinar_start_date', ['start_date' => dateTimeFormat($webinar->start_date, 'j M Y')])]
                        ];

                        return response([
                            'code' => 422,
                            'errors' => $error,
                        ], 422);
                    }

                    $sessionDate = $sessionDate->getTimestamp();
                }

                $agoraSettings = null;
                if ($session_api == 'agora') {
                    $agoraSettings = [
                        'chat' => (!empty($data['agora_chat']) and $data['agora_chat'] == 'on'),
                        'record' => (!empty($data['agora_record']) and $data['agora_record'] == 'on'),
                    ];
                    $agoraSettings = json_encode($agoraSettings);
                }

                $session->update([
                    'date' => $sessionDate,
                    'duration' => $data['duration'] ?? $session->duration,
                    'link' => $data['link'] ?? $session->link,
                    'session_api' => $session_api,
                    'api_secret' => $data['api_secret'] ?? $session->api_secret,
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? Session::$Active : Session::$Inactive,
                    'agora_settings' => $agoraSettings,
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
                    'extra_time_to_join' => $data['extra_time_to_join'] ?? null,
                    'updated_at' => time()
                ]);

                if (!empty($session)) {
                    SessionTranslation::updateOrCreate([
                        'session_id' => $session->id,
                        'locale' => mb_strtolower($data['locale']),
                    ], [
                        'title' => $data['title'],
                        'description' => $data['description'],
                    ]);
                }

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function destroy(Request $request, $id)
    {
        $session = Session::where('id', $id)
            ->where('creator_id', auth()->id())
            ->first();

        if (!empty($session)) {
            WebinarChapterItem::where('user_id', $session->creator_id)
                ->where('item_id', $session->id)
                ->where('type', WebinarChapterItem::$chapterSession)
                ->delete();

            $session->delete();
        }

        return response()->json([
            'code' => 200
        ], 200);
    }

    private function handleZoomApi($session, $user)
    {
        $zoom = new Zoom();

        if (!empty($user->zoomApi) and !empty($user->zoomApi->jwt_token)) {
            $zoomUser = $zoom->getUserByJwt($user->zoomApi->jwt_token);

            if (!empty($zoomUser)) {
                $meeting = $zoom->storeUserMeeting($session, $zoomUser, $user->zoomApi->jwt_token);

                if (!empty($meeting)) {
                    if (!empty($session->title)) {
                        unset($session->title);
                    }

                    if (!empty($session->locale)) {
                        unset($session->locale);
                    }

                    $session->update([
                        'link' => $meeting['join_url'],
                        'zoom_start_link' => $meeting['start_url'],
                    ]);

                    return response()->json([
                        'code' => 200,
                    ], 200);
                }
            }
        }

        $session->delete();

        return response()->json([
            'code' => 422,
            'status' => 'zoom_jwt_token_invalid'
        ], 422);
    }

    private function handleBigBlueButtonApi($session, $user)
    {
        $createMeeting = \Bigbluebutton::initCreateMeeting([
            'meetingID' => $session->id,
            'meetingName' => $session->title,
            'attendeePW' => $session->api_secret,
            'moderatorPW' => $session->moderator_secret,
        ]);

        $createMeeting->setDuration($session->duration);
        \Bigbluebutton::create($createMeeting);

        return true;
    }

    public function joinToBigBlueButton($id)
    {
        $session = Session::where('id', $id)
            ->where('session_api', 'big_blue_button')
            ->where('status', Session::$Active)
            ->first();

        if (!empty($session)) {
            $user = auth()->user();

            if ($user->id == $session->creator_id) {
                $url = \Bigbluebutton::join([
                    'meetingID' => $session->id,
                    'userName' => $user->full_name,
                    'password' => $session->moderator_secret
                ]);

                if ($url) {
                    return redirect($url);
                }
            } else {
                $checkSale = Sale::where('buyer_id', $user->id)
                    ->where('webinar_id', $session->webinar_id)
                    ->where('type', 'webinar')
                    ->whereNull('refund_at')
                    ->first();

                if (!empty($checkSale)) {

                    $url = \Bigbluebutton::join([
                        'meetingID' => $session->id,
                        'userName' => $user->full_name,
                        'password' => $session->api_secret
                    ]);

                    if ($url) {
                        return redirect($url);
                    }
                }
            }
        }

        abort(404);
    }

    public function joinToAgora($id)
    {
        $user = auth()->user();
        $session = Session::where('id', $id)
            ->where('session_api', 'agora')
            ->where('status', Session::$Active)
            ->first();

        if (!empty($session) and !empty($user)) {
            $session->agora_settings = json_decode($session->agora_settings);

            $agoraHistory = AgoraHistory::where('session_id', $session->id)->first();

            if (!empty($agoraHistory) and !empty($agoraHistory->end_at)) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.this_live_has_been_ended'),
                    'status' => 'error'
                ];
                return redirect('/panel')->with(['toast' => $toastData]);
            }


            $canAccess = false;
            $streamRole = 'audience'; // host | audience
            $channelName = "session_$session->id";
            $accountName = $user->full_name;

            if ($user->id == $session->creator_id) {
                AgoraHistory::updateOrCreate([
                    'session_id' => $session->id,
                ], [
                    'start_at' => time()
                ]);

                $canAccess = true;
                $streamRole = 'host';
            } else {
                $checkSale = Sale::where('buyer_id', $user->id)
                    ->where('webinar_id', $session->webinar_id)
                    ->where('type', 'webinar')
                    ->whereNull('refund_at')
                    ->first();

                if (!empty($checkSale)) {
                    $canAccess = true;
                }
            }

            if ($canAccess) {
                $agoraController = new AgoraController();

                $isHost = ($streamRole === 'host');
                $appId = $agoraController->appId;
                $rtcToken = $agoraController->getRTCToken($channelName, $isHost);
                $rtmToken = $agoraController->getRTMToken($accountName);

                $data = [
                    'session' => $session,
                    'isHost' => $isHost,
                    'appId' => $appId,
                    'accountName' => $accountName,
                    'channelName' => $channelName,
                    'rtcToken' => $rtcToken,
                    'rtmToken' => $rtmToken,
                    'streamRole' => $streamRole,
                    'notStarted' => (!$isHost and empty($agoraHistory)),
                    'streamStartAt' => (!$isHost and !empty($agoraHistory)) ? $agoraHistory->start_at : time()
                ];

                return view('web.default.course.agora.index', $data);
            }
        }

        abort(404);
    }

    public function endAgora($id)
    {
        $user = auth()->user();
        $session = Session::where('id', $id)
            ->where('creator_id', $user->id)
            ->where('status', Session::$Active)
            ->first();

        if (!empty($session) and !empty($user)) {
            $agoraHistory = AgoraHistory::where('session_id', $session->id)
                ->whereNull('end_at')
                ->first();

            if (!empty($agoraHistory)) {
                $agoraHistory->update([
                    'end_at' => time()
                ]);

                return response()->json([
                    'code' => 200
                ]);
            }
        }

        return response()->json([
            'code' => 422
        ]);
    }
}
