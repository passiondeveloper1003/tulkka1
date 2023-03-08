<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Translation\SessionTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use App\Sessions\Zoom;
use Illuminate\Http\Request;
use Validator;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('admin_webinars_edit');

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

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::where('id', $data['webinar_id'])->first();

            if (!empty($webinar)) {
                $teacher = $webinar->creator;

                if (!empty($data['session_api']) and $data['session_api'] == 'zoom' and (empty($teacher->zoomApi) or empty($teacher->zoomApi->jwt_token))) {
                    $error = [
                        'zoom-not-complete-alert' => []
                    ];

                    return response([
                        'code' => 422,
                        'errors' => $error,
                    ], 422);
                }


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
                    'creator_id' => $teacher->id,
                    'webinar_id' => $data['webinar_id'],
                    'chapter_id' => $data['chapter_id'] ?? null,
                    'date' => $sessionDate->getTimestamp(),
                    'duration' => $data['duration'],
                    'extra_time_to_join' => $data['extra_time_to_join'] ?? null,
                    'link' => $data['link'] ?? '',
                    'session_api' => $data['session_api'],
                    'api_secret' => $data['api_secret'] ?? '',
                    'moderator_secret' => $data['moderator_secret'] ?? '',
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
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

                    if (!empty($session->chapter_id)) {
                        WebinarChapterItem::makeItem($webinar->creator_id, $session->chapter_id, $session->id, WebinarChapterItem::$chapterSession);
                    }
                }

                if ($data['session_api'] == 'big_blue_button') {
                    $this->handleBigBlueButtonApi($session, $teacher);
                } elseif ($data['session_api'] == 'zoom') {
                    return $this->handleZoomApi($session, $teacher);
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
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        $data = $request->get('ajax')[$id];
        $session = Session::where('id', $id)
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

        $webinar = Webinar::where('id', $data['webinar_id'])->first();

        if (!empty($webinar)) {
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
                    'chapter_id' => $data['chapter_id'] ?? null,
                    'date' => $sessionDate,
                    'duration' => $data['duration'] ?? $session->duration,
                    'extra_time_to_join' => $data['extra_time_to_join'] ?? null,
                    'link' => $data['link'] ?? $session->link,
                    'session_api' => $session_api,
                    'api_secret' => $data['api_secret'] ?? $session->api_secret,
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? Session::$Active : Session::$Inactive,
                    'agora_settings' => $agoraSettings,
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
                    'updated_at' => time()
                ]);

                SessionTranslation::updateOrCreate([
                    'session_id' => $session->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                WebinarChapterItem::where('user_id', $session->creator_id)
                    ->where('item_id', $session->id)
                    ->where('type', WebinarChapterItem::$chapterSession)
                    ->delete();

                if (!empty($session->chapter_id)) {
                    WebinarChapterItem::makeItem($webinar->creator_id, $session->chapter_id, $session->id, WebinarChapterItem::$chapterSession);
                }

                removeContentLocale();

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        removeContentLocale();

        return response()->json([], 422);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        $session = Session::find($id);

        if (!empty($session)) {
            WebinarChapterItem::where('user_id', $session->creator_id)
                ->where('item_id', $session->id)
                ->where('type', WebinarChapterItem::$chapterSession)
                ->delete();

            $session->delete();
        }

        return response()->json([
            'code' => 200,
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
}
