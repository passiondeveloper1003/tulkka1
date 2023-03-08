<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebinarChapterItem extends Model
{
    protected $table = 'webinar_chapter_items';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $chapterFile = 'file';
    static $chapterSession = 'session';
    static $chapterTextLesson = 'text_lesson';
    static $chapterQuiz = 'quiz';
    static $chapterAssignment = 'assignment';

    static public function makeItem($userId, $chapterId, $itemId, $type)
    {
        $order = 1;

        $chapterLastItem = WebinarChapterItem::where('chapter_id', $chapterId)
            ->orderBy('order', 'desc')
            ->first();

        if (!empty($chapterLastItem)) {
            $order = $chapterLastItem->order + 1;
        }

        WebinarChapterItem::updateOrCreate([
            'user_id' => $userId,
            'chapter_id' => $chapterId,
            'item_id' => $itemId,
            'type' => $type,
        ], [
            'order' => $order,
            'created_at' => time()
        ]);
    }

    public function session()
    {
        return $this->belongsTo('App\Models\Session', 'item_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\File', 'item_id', 'id');
    }

    public function textLesson()
    {
        return $this->belongsTo('App\Models\TextLesson', 'item_id', 'id');
    }

    public function assignment()
    {
        return $this->belongsTo('App\Models\WebinarAssignment', 'item_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Models\Quiz', 'item_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\WebinarChapter', 'chapter_id', 'id');
    }
}
