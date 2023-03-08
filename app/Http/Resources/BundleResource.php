<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BundleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public $show = false;

    public function toArray($request)
    {
        $purchase = apiAuth() ? apiAuth()->purchases()->where('bundle_id', $this->id)->first() : null;
        return [
            'id' => $this->id,
            'image' => url($this->getImage()),
            'image_cover' => url($this->getImageCover()),
            'status' => $this->status,
            'label' => trans('update.bundle'),
            'link' => url($this->getUrl()),
            'title' => $this->title,
            'type' => 'bundle',
            'rate' => $this->getRate(),
            'rates_count' => $this->reviews->pluck('creator_id')->count(),
            'reviews_count' => $this->reviews->count(),
            'price' => $this->price,
            'price_details' => handleCoursePagePrice($this->price),
            'active_special_offer' => $this->activeSpecialOffer() ?: null,
            'best_ticket' => $this->bestTicket(),
            'category' => $this->category->title ?? null,
            'access_days' => $this->access_days,
            $this->mergeWhen($purchase, function () use ($purchase) {
                return [
                    'expired' => ($this->access_days and !$this->checkHasExpiredAccessDays($purchase->created_at)),
                    'expire_on' => $this->getExpiredAccessDays($purchase->created_at) ?: null,
                ];
            }),
            //  'ex' => $this->checkHasExpiredAccessDays($sale->created_at),
            'duration' => $this->getBundleDuration(),
            'webinar_count' => $this->bundleWebinars->where('webinar.status', 'active')->count(),
            'teacher' => $this->teacher->brief,
            'sale_amount' => ($this->sales) ? $this->sales->sum('amount') : 0,
            'sales_count' => $this->sales->count(),
            'students_count' => $this->sales->count(),
            'created_at' => $this->created_at,
            $this->mergeWhen($this->show, function () {
                return [
                    'rate_type' => [
                        'content_quality' => $this->reviews->count() > 0 ? round($this->reviews->avg('content_quality'), 1) : 0,
                        'instructor_skills' => $this->reviews->count() > 0 ? round($this->reviews->avg('instructor_skills'), 1) : 0,
                        'purchase_worth' => $this->reviews->count() > 0 ? round($this->reviews->avg('purchase_worth'), 1) : 0,
                        'support_quality' => $this->reviews->count() > 0 ? round($this->reviews->avg('support_quality'), 1) : 0,

                    ],
                    'video_demo' => $this->video_demo,
                    'tickets' => TicketResource::collection($this->tickets),
                    'subscribable' => $this->subscribe,
                    'points' => $this->points,
                    'description' => $this->description,
                    'tags' => TagResource::collection($this->tags),
                    'faqs' => FaqResource::collection($this->faqs),
                    'comments' => CommentResource::collection($this->comments),
                    'reviews' => ReviewResource::collection($this->reviews),
                    // bundleWebinars
                    $this->mergeWhen((bool)apiAuth(), function () {
                        return [
                            'has_bought' => $this->checkUserHasBought(apiAuth()),
                            'can_sale' => ($this->canSale() and !$this->checkUserHasBought(apiAuth())),
                            'can_buy_with_points' => ($this->canSale() and !$this->checkUserHasBought(apiAuth()) and $this->price > 0 and !empty($bundle->points)),
                            'can_buy_with_subscribe' => ($this->canSale() and !$this->checkUserHasBought(apiAuth()) and $this->price > 0 and $this->subscribe),
                            'is_favorite' => $this->is_favorite,

                        ];
                    })
                ];
            }),
        ];
    }
}
