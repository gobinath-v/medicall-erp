<?php

namespace App\Http\Livewire;

use Throwable;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Address;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\WithFileUploads;

class EventsHandler extends Component
{
    use WithFileUploads;
    public $event = [
        'country' => 'India',
        'pincode',
        'state',
        'city',
        'address',
        'title',
        'startDate',
        'endDate',
        'organizer' => null,
        'description' => null,
        'contact' => null,
    ];
    public $eventId = null, $pincodeData, $photo;
    public $hallLayout;


    protected $rules = [
        'event.title' => 'required',
        'event.startDate' => 'required|after_or_equal:today',
        'event.endDate' => 'required|after_or_equal:event.startDate',
        // 'event.organizer' => 'required|string',
        // 'event.contact' => 'required|regex:/^[0-9]{10}$/|starts_with:6,7,8,9',
        'event.country' => 'required',
        'event.pincode' => 'required',
        'event.address' => 'required',

    ];

    protected $messages = [
        'event.title.required' => 'Enter The Event Title.',
        'event.startDate.required' => 'Enter The Event Start Date.',
        'event.startDate.after_or_equal' => 'Enter Currnet or Future Date',
        'event.endDate.required' => 'Enter The Event End Date.',
        'event.endDate.after_or_equal' => 'Enter Currnet or Future Date From The Start Date',
        // 'event.organizer.required' => 'Organizer Field is Required.',
        // 'event.organizer.string' => 'Organizer Field Must Be a String.',
        // 'event.contact.required' => 'Contact field is Required.',
        // 'event.contact.regex' => 'Enter Valid Contact Number',
        // 'event.contact.starts_with' => 'Check The Contact Number is Valid',
        'event.address.required' => 'Address Field is Required.',
        'event.country.required' => 'Country Field is Required.',
        'event.pincode.required' => 'This Field is Required.',

    ];

    public function pincode()
    {
        if ($this->event['country'] == 'India' && isset($this->event['pincode'])) {
            $pincodeData = getPincodeData($this->event['pincode']);
            if ($pincodeData['state'] === null && $pincodeData['city'] === null) {
                $this->addError("event.pincode", "Pincode is not Exists");
                $this->event['state'] = null;
                $this->event['city'] = null;
            } else {
                $this->resetErrorBag('event.pincode');
                $this->event['state'] = $pincodeData['state'];
                $this->event['city'] = $pincodeData['city'];
            }
        } else {
            $this->event['pincode'] = null;
            $this->event['city'] = null;
            $this->event['state'] = null;
        }
    }

    public function resetFields()
    {
        $this->reset();
    }

    public function create()
    {
        $this->validate();

        $eventExists = Event::where('title', $this->event['title'])->first();
        if ($eventExists) {
            $this->addError('event.title', 'Event Title Already Exists.');
            return;
        }

        DB::beginTransaction();
        try {
            $authId = getAuthData()->id;
            $layoutPath = '';
            $imagePath = '';
            // dd($this->photo,);
            if ($this->photo) {
                $imageFolderPath = 'thumbnail/' . date('Y/m');
                $imageName = $this->photo->getClientOriginalName();
                $imagePath = $this->photo->storeAs($imageFolderPath, $imageName, 'public');
            }
            if ($this->hallLayout) {
                $fileFolderPath = 'layout/' . date('Y/m');
                $fileName = $this->hallLayout->getClientOriginalName();
                $layoutPath = $this->hallLayout->storeAs($fileFolderPath, $fileName, 'public');
            }
            $event = Event::create(
                [
                    "created_by" => $authId,
                    "updated_by" => $authId,
                    "title" => $this->event['title'],
                    "start_date" => $this->event['startDate'],
                    "end_date" => $this->event['endDate'],
                    "_meta" => [
                        'thumbnail' => $imagePath,
                        'layout' => $layoutPath,
                    ]
                    // "organizer" => $this->event['organizer'],
                    // "contact" => $this->event['contact'],
                    // "description" => $this->event['description'] ?? null,
                ]
            );

            $event->address()->create([
                'country' => $this->event['country'],
                'state' => $this->event['country'] == "India" ? $this->event['state'] : null,
                'city' => $this->event['country'] == "India" ? $this->event['city'] : null,
                'pincode' => $this->event['pincode'],
                'address' => $this->event['address'],
            ]);
            DB::commit();

            if ($event) {
                session()->flash('success', 'Event created successfully.');
                return redirect(route('events'));
            }
            session()->flash('error', 'Event was not created ');
            return;
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', $e->getMessage());
            return;
        }
    }

    public function update()
    {
        $this->validate();
        $eventExists = Event::where('title', $this->event['title'])->where('id', '!=', $this->eventId)->first();
        if ($eventExists) {
            $this->addError('event.title', 'Event Title Already Exists.');
            return;
        }

        try {
            $event = Event::find($this->eventId);
            if ($event) {
                $imagePath = $event['_meta']['thumbnail'] ?? '';
                if (isset($this->photo)) {
                    if (isset($event['_meta']['thumbnail']) && !empty($event['_meta']['thumbnail'])) {
                        $filepath = public_path('storage/' . $event['_meta']['thumbnail']);
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                    }

                    $imageFolderPath = 'thumbnail/' . date('Y/m');
                    $imageName = $this->photo->getClientOriginalName();
                    $imagePath = $this->photo->storeAs($imageFolderPath, $imageName, 'public');
                }

                $layoutPath = $event['_meta']['layout'] ?? '';
                if (isset($this->hallLayout)) {
                    // dd($event['_meta']['layout']);
                    if (isset($event['_meta']['layout']) && !empty($event['_meta']['layout'])) {
                        $filepath = public_path('storage/' . $event['_meta']['layout']);
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                    }

                    $fileFolderPath = 'layout/' . date('Y/m');
                    $fileName = $this->hallLayout->getClientOriginalName();
                    $layoutPath = $this->hallLayout->storeAs($fileFolderPath, $fileName, 'public');
                }

                $event->update([
                    "updated_by" => getAuthData()->id,
                    "title" => $this->event['title'],
                    "start_date" => $this->event['startDate'],
                    "end_date" => $this->event['endDate'],
                    "_meta" => [
                        'thumbnail' => $imagePath,
                        'layout' => $layoutPath,
                    ]
                    // "organizer" => $this->event['organizer'],
                    // "contact" => $this->event['contact'],
                    // "description" => $this->event['description'] ?? null,


                ]);

                $event->address()->update([
                    'country' => $this->event['country'],
                    'state' => $this->event['country'] == "India" ? $this->event['state'] : null,
                    'city' => $this->event['country'] == "India" ? $this->event['city'] : null,
                    'pincode' => $this->event['country'] == "India" ? $this->event['pincode'] : null,
                    'address' => $this->event['address'],
                ]);

                $isEventUpdated = $event->wasChanged('title', 'start_date', 'end_date', 'country', 'state', 'city', 'pincode', 'address');

                if ($isEventUpdated) {
                    session()->flash("success", "Event Details Successfully Updated");
                    return redirect(route('events'));
                }
                session()->flash("info", "No Changes Made");
                return;
            }
            session()->flash("error", "Unable to Update the Event Details");
            return;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }
    }

    public function mount($eventId = null)
    {
        $this->eventId = $eventId;

        if ($this->eventId) {
            $event = Event::find($this->eventId);
            // $event ? $eventDetails = $event->toArray() : [];
            // $event ? $eventAddress =$event->address->toArray() :[];
            if (!(empty($event))) {
                $this->event['title'] = $event['title'];
                $this->event['startDate'] = Carbon::parse($event['start_date'])->format('Y-m-d');
                $this->event['endDate'] = Carbon::parse($event['end_date'])->format('Y-m-d');
                $this->event['organizer'] = $event['organizer'];
                $this->event['contact'] = $event['contact'];
                $this->event['description'] = $event['description'] ?? null;
                $this->event['_meta']['thumbnail'] = $event['_meta']['thumbnail'] ?? null;
                // $this->event['_meta']['layout'] = $eventDetails['_meta']['layout'] ?? null;
                $this->event['pincode'] = $event->address->pincode ?? '';
                $this->event['country'] = $event->address->country ?? '';
                $this->event['state'] = $event->address->state ?? '';
                $this->event['city'] = $event->address->city ?? '';
                $this->event['address'] = $event->address->address ?? '';
            }
            return;
        }
    }

    public function render()
    {
        $countries = getCountries();

        return view(
            'livewire.events-handler',
            [
                'countries' => $countries,
            ]
        )->layout('layouts.admin');
    }
}
