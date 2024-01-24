<?php

namespace App\Http\Livewire;

use App\Models\EventExhibitor;
use App\Models\Exhibitor;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Livewire\Component;

class FindProducts extends Component
{
    public $selectedProducts;
    public $selectedExhibitorId;
    protected $listeners = ['message' => 'alertStatus'];
    public $eventId;
    public $search;

    protected $queryString = ['search'];
    public function mount(Request $request)
    {
        $this->eventId = $request->get('eventId');
    }

    public function render()
    {
        $wishlist = Wishlist::where('visitor_id', getAuthData()->id)->pluck('exhibitor_id')->toArray();
        $exhibitors = [];
        $products = [];

        if (!empty($this->search)) {
            $products = Product::where('name', 'like', '%' . $this->search . '%')->get();
            $eventExhibitorIds = [];
            if (count($products) > 0) {
                $eventExhibitorIds = EventExhibitor::where('event_id', $this->eventId)
                    ->where(function ($subquery) use ($products) {
                        foreach ($products as $product) {
                            $subquery->orWhereJsonContains('products', strval($product->id));
                        }
                    })
                    ->pluck('exhibitor_id')
                    ->toArray();
            }

            $exhibitorIds = Exhibitor::where('name', 'like', '%' . $this->search . '%')
                ->whereHas('eventExhibitors', function ($query) {
                    $query->where('event_id', $this->eventId);
                })
                ->pluck('id')
                ->toArray();

            $exhibitorIds = array_merge($exhibitorIds, $eventExhibitorIds);
            $exhibitorIds = array_unique($exhibitorIds);

            $exhibitors = Exhibitor::whereIn('id', $exhibitorIds)->get();
        }
        return view('livewire.find-products', [
            'wishlist' => $wishlist,
            'products' => $products,
            'exhibitors' => $exhibitors,
        ])->layout('layouts.admin');
    }

    public function alertStatus($status, $message)
    {
        session()->flash($status, $message);
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

    public function addAppointment($exhibitorId)
    {
        $this->selectedExhibitorId = $exhibitorId;
        $this->dispatch('exhibitorSelected', [$this->selectedExhibitorId, $this->eventId]);
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
