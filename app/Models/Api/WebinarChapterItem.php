<?php

namespace App\Models\Api;

use App\Http\Resources\FileResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TextLessonResource;
use App\Http\Resources\WebinarAssignmentResource;
use App\Models\WebinarChapterItem as Model;

class WebinarChapterItem extends Model
{
    public function getItemResource()
    {
        $type = $this->type;
        if ($type == self::$chapterFile) {
            return [
                'id' => $this->item->id,
                'title' => $this->item->title,
                'file_type' => $this->item->file_type,
                'storage' => $this->item->storage,
                'volume' => $this->item->volume,
                'downloadable' => $this->item->downloadable,
                //  'auth_has_read'=>$this->item->auth_has_read

            ];
            //    return new FileResource($this->file);
        } elseif ($type == self::$chapterSession) {
            return [
                'id' => $this->item->id,
                'title' => $this->item->title,
                'date' => $this->item->date,
                'auth_has_read' => $this->item->auth_has_read
            ];
            //  return new SessionResource($this->session);
        } elseif ($type == self::$chapterTextLesson) {
            return [
                'id' => $this->item->id,
                'title' => $this->item->title,
                'summary' => $this->item->summary,
            ];
            return new TextLessonResource($this->textLesson);
        } elseif ($type == self::$chapterQuiz) {
            return [
                'id' => $this->item->id,
                'title' => $this->item->title,
                'time' => $this->item->time,
                'question_count' => $this->item->quizQuestions->count(),
                'auth_status' => $this->auth_status,
                //   'created_at' => $this->item->created_at,
            ];
            // return $this->quiz();
        } elseif ($type == self::$chapterAssignment) {
            return [
                'id' => $this->item->id,
                'title' => $this->item->title,
            ];
            return new WebinarAssignmentResource($this->assignment);
        }
        return [];
    }

    public function item()
    {
        $type = $this->type;
        if ($type == self::$chapterFile) {
            return $this->file();
        } elseif ($type == self::$chapterSession) {
            return $this->session();
        } elseif ($type == self::$chapterTextLesson) {
            return $this->textLesson();
        } elseif ($type == self::$chapterQuiz) {
            return $this->quiz();
        } elseif ($type == self::$chapterAssignment) {
            return $this->assignment();
        }
        return [];
    }


    public function session()
    {
        return $this->belongsTo('App\Models\Api\Session', 'item_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\Api\File', 'item_id', 'id');
    }

    public function textLesson()
    {
        return $this->belongsTo('App\Models\Api\TextLesson', 'item_id', 'id');
    }

    public function assignment()
    {
        return $this->belongsTo('App\Models\Api\WebinarAssignment', 'item_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Models\Api\Quiz', 'item_id', 'id');
    }

}
