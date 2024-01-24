<?php

namespace App\Http\Livewire;

use App\Models\EventExhibitor;
use App\Models\Exhibitor;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Livewire\Component;

class ExhibitorDirectory extends Component
{
    public $eventId;

    public $selectedExhibitor;
    public $selectedExhibitorId;
    public $searchTerm;
    public $wishlist;
    protected $listeners = ['message' => 'alertStatus'];
    public function mount(Request $request)
    {
        $this->eventId = $request->get('eventId');
        $this->wishlist = Wishlist::where('visitor_id', getAuthData()->id)->where('event_id', $this->eventId)->pluck('exhibitor_id')->toArray();
    }

    public function render()
    {

        // Fetch product IDs matching the search term
        $productIds = Product::where('name', 'like', '%' . $this->searchTerm . '%')->pluck('id')->toArray();

        // Fetch exhibitors associated with the found product IDs
        $exhibitorIds = EventExhibitor::where('event_id', $this->eventId)
            ->whereIn('exhibitor_id', function ($query) use ($productIds) {
                $query->select('exhibitor_id')
                    ->from('exhibitor_products')
                    ->whereIn('product_id', $productIds);
            })
            ->pluck('exhibitor_id')
            ->toArray();

        // Retrieve additional exhibitors based on the search term (e.g., exhibitor name or stall number)
        $additionalExhibitors = Exhibitor::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhereHas('eventExhibitors', function ($subquery) {
                    $subquery->where('stall_no', 'like', '%' . $this->searchTerm . '%');
                });
        })
            ->whereHas('eventExhibitors', function ($query) {
                $query->where('event_id', $this->eventId);
            })
            ->pluck('id')
            ->toArray();

        $exhibitorIds = array_merge($exhibitorIds, $additionalExhibitors);
        $exhibitorIds = array_unique($exhibitorIds);

        $exhibitors = Exhibitor::whereIn('id', $exhibitorIds)->get();

        return view('livewire.exhibitor-directory', [
            'exhibitors' => $exhibitors,
            'selectedExhibitor' => $this->selectedExhibitor,
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
