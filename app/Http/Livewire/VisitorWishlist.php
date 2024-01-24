<?php

namespace App\Http\Livewire;

use App\Models\EventExhibitor;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Livewire\Component;

class VisitorWishlist extends Component
{
    public $eventId;
    public $selectedExhibitorId;
    protected $listeners = ['message' => 'alertStatus'];

    public function mount(Request $request)
    {
        $this->eventId = $request->eventId;
    }

    public function alertStatus($status, $message)
    {
        session()->flash($status, $message);
    }

    public function render()
    {
        $exhibitorWhishlists = Wishlist::where('event_id', $this->eventId)
            ->where('visitor_id', getAuthData()->id)
            ->where('product_id', null)
            ->get();
        $productWhishlists = Wishlist::where('event_id', $this->eventId)
            ->where('visitor_id', getAuthData()->id)
            ->whereNull('exhibitor_id')
            ->get();
        $whislistExhibitorIds = $exhibitorWhishlists->pluck('exhibitor_id')->toArray();
        // dd($whislistExhibitorIds, getAuthData()->id, $this->eventId);
        $wishlistProductIds = $productWhishlists->pluck('product_id')->toArray();

        $productNames = Product::whereIn('id', $wishlistProductIds)->pluck('name');
        $similarProducts = [];
        if ($productWhishlists->count() > 0) {
            $similarProducts = Product::where(function ($query) use ($productNames, $wishlistProductIds) {
                foreach ($productNames as $productName) {
                    $splitProduct = explode(' ', $productName);

                    foreach ($splitProduct as $product) {
                        $query->orWhere('name', 'like', '%' . $product . '%');
                    }
                }
            })
                ->whereNotIn('id', $wishlistProductIds)
                ->get();
        }
        $productsFromExhibitors = EventExhibitor::whereIn('exhibitor_id', $whislistExhibitorIds)
            ->where('event_id', $this->eventId)
            ->get();

        $exhibitorProductsCollection = $productsFromExhibitors->pluck('products')->toArray();

        $exhibitorProducts = collect($exhibitorProductsCollection)->flatten()->unique()->toArray();

        $similarExhibitors = [];

        if ($exhibitorWhishlists->count() > 0) {
            $wishlistProductIds = $productWhishlists->pluck('product_id')->toArray();
            // $productNames = Product::whereIn('id', $wishlistProductIds)->pluck('name')->toArray();
            $similarExhibitors = EventExhibitor::whereNotIn('exhibitor_id', $whislistExhibitorIds)
                ->where('event_id', $this->eventId)
                ->where(function ($subquery) use ($exhibitorProducts) {
                    foreach ($exhibitorProducts as $productId) {
                        $subquery->orWhereJsonContains('products', strval($productId));
                    }
                })
                ->get();

            // dd($similarExhibitors, $whislistExhibitorIds);
        }
        // dd($exhibitorWhishlists);
        return view('livewire.visitor-wishlist', [
            'exhibitorWhishlists' => $exhibitorWhishlists,
            'productWhishlists' => $productWhishlists,
            'similarExhibitors' => $similarExhibitors,
            'similarProducts' => $similarProducts,
            'relatedExhibitors' => $relatedExhibitors ?? [],
        ])->layout('layouts.admin');

    }
    public function addAppointment($exhibitorId)
    {

        $this->selectedExhibitorId = $exhibitorId;

        $this->dispatch('exhibitorSelected', [$this->selectedExhibitorId, $this->eventId]);
    }
    public function toggleWishlist($targetId, $eventId, $type = 'exhibitor')
    {
        if ($type == 'product') {
            $wishlistItem = Wishlist::where('product_id', $targetId)
                ->where('visitor_id', getAuthData()->id)
                ->where('event_id', $eventId)
                ->first();

            if (!$wishlistItem) {
                Wishlist::create([
                    'product_id' => $targetId,
                    'visitor_id' => getAuthData()->id,
                    'event_id' => $eventId,
                ]);
                session()->flash('success', 'Product added to wishlist');
            } else {
                $wishlistItem->delete();
                session()->flash('success', 'Product removed from wishlist');
            }
        } else {
            $wishlistItem = Wishlist::where('exhibitor_id', $targetId)
                ->where('visitor_id', getAuthData()->id)
                ->where('event_id', $eventId)
                ->first();

            if (!$wishlistItem) {
                Wishlist::create([
                    'exhibitor_id' => $targetId,
                    'visitor_id' => getAuthData()->id,
                    'event_id' => $eventId,
                ]);
                session()->flash('success', 'Exhibitor added to wishlist');
            } else {
                $wishlistItem->delete();
                session()->flash('success', 'Exhibitor removed from wishlist');
            }
        }

    }
    public function targetIdExistsInWishlist($targetId, $eventId, $type = 'exhibitor')
    {

        if ($type == 'product') {
            $wishlistItem = Wishlist::where('product_id', $targetId)
                ->where('visitor_id', getAuthData()->id)
                ->where('event_id', $eventId)
                ->count();

            return $wishlistItem > 0 ? true : false;
        } else {

            $wishlistItem = Wishlist::where('exhibitor_id', $targetId)
                ->where('visitor_id', getAuthData()->id)
                ->where('event_id', $eventId)
                ->first();

            return $wishlistItem ? true : false;
        }
    }
}
