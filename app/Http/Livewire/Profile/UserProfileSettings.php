<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use App\Models\Visitor;
use Livewire\Component;
use App\Models\Category;
use App\Models\Exhibitor;
use Illuminate\Support\Facades\Hash;


class UserProfileSettings extends Component
{
    public $updateProfile, $changePassword = false;

    public $showConfirmPassword, $showNewPassword, $showPassword  = false;

    public $departments, $userData;

    public $currentPassword, $newPassword, $confirmPassword;

    public $user = [
        'name',
        'email',
        'mobile_number',
    ];


    protected $rules = [
        'user.name' => 'required|string',
        'user.email' => 'required|email',
        'user.mobile_number' => 'required|integer|digits:10',
    ];

    protected $messages = [
        'user.name.required' => 'This is required',
        'user.name.string' => 'Enter valid name',
        'user.email.required' => 'This is required',
        'user.email.email' => 'Enter valid email',
        'user.mobile_number.required' => 'This is required',
        'user.mobile_number.integer' => 'Enter valid phone number',
        'user.mobile_number.digits' => 'Phone number has less or greater no of digits',
    ];

    public function toggleVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }
    public function toggleNewPassword()
    {
        $this->showNewPassword = !$this->showNewPassword;
    }
    public function toggleConfirmPassword()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function updateUserProfile()
    {
        $this->updateProfile = true;
        $this->changePassword = false;
    }

    public function changeUserPassword()
    {
        $this->changePassword = true;
        $this->updateProfile = false;
    }

    public function updateUserDetails()
    {
        $nameExists = User::where('name', $this->user['name'])
            ->where('id', '!=', auth()->user()->id)
            ->first();

        if ($nameExists) {
            $this->addError('user.name', 'Name already exists.');
            return;
        }

        $emailExists = User::where('email', $this->user['email'])
            ->where('id', '!=', auth()->user()->id)
            ->first();

        if ($emailExists) {
            $this->addError('user.email', 'Email already exists.');
            return;
        }

        $mobileNoExists = User::where('mobile_number', $this->user['mobile_number'])
            ->where('id', '!=', auth()->user()->id)
            ->first();

        if ($mobileNoExists) {
            $this->addError('user.mobile_number', 'Mobile Number already exists.');
            return;
        }

        $this->validate();
        $userInfo = User::find(auth()->user()->id);
        $userInfo->update([
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'phone_no' => $this->user['mobile_number'],
            'updated_by' => auth()->user()->id,

        ]);

        $isUpdate = $userInfo->wasChanged('name', 'email', 'phone_no');

        if ($isUpdate) {
            session()->flash("success", "Successfully updated");
            return;
        } else if ($isUpdate === false) {
            session()->flash("info", "Made Some Changes to Update");
            return;
        } else {
            session()->flash("error", "Cannot Update User Details");
            return;
        }
    }
    public function updateExhibitorDetails()
    {
        $nameExists = Exhibitor::where('name', $this->user['name'])
            ->where('id', '!=', getAuthData()->id)
            ->first();

        if ($nameExists) {
            $this->addError('user.name', 'Name already exists.');
            return;
        }

        $emailExists = Exhibitor::where('email', $this->user['email'])
            ->where('id', '!=', getAuthData()->id)
            ->first();

        if ($emailExists) {
            $this->addError('user.email', 'Email already exists.');
            return;
        }

        $mobileNoExists = Exhibitor::where('mobile_number', $this->user['mobile_number'])
            ->where('id', '!=', getAuthData()->id)
            ->first();

        if ($mobileNoExists) {
            $this->addError('user.mobile_number', 'Mobile Number already exists.');
            return;
        }

        $this->validate();
        $exhibitorInfo = Exhibitor::find(getAuthData()->id);
        $exhibitorInfo->update([
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'mobile_number' => $this->user['mobile_number'],
            'updated_by' => getAuthData()->id,

        ]);

        $isUpdate = $exhibitorInfo->wasChanged('name', 'email', 'mobile_number');

        if ($isUpdate) {
            session()->flash("success", "Successfully updated");
            return;
        } else if ($isUpdate === false) {
            session()->flash("info", "Made Some Changes to Update");
            return;
        } else {
            session()->flash("error", "Cannot Update Exhibitor Details");
            return;
        }
    }


    public function updatePassword()
    {

        $messages = [
            'currentPassword' => 'This is Required',
            'newPassword' => 'This is Required',
            'newPassword.different' => 'New Password same as current password',
            'confirmPassword' => 'This is Required',
            'confirmPassword.same' => 'Password does not match with new password',
        ];
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|different:currentPassword',
            'confirmPassword' => 'required|same:newPassword',
        ], $messages);

        $userInfo = User::find(auth()->user()->id);

        if (Hash::check($this->currentPassword, $userInfo->password)) {
            $userInfo->update([
                'password' => Hash::make($this->confirmPassword),
                'updated_by' => auth()->user()->id,
            ]);
            session()->flash('success', 'Passwords Changed Successfully');
            return redirect(route('user.profile'));
        }

        session()->flash('error', 'Current password is does not match with existing password');
        return;
    }
    public function updateVisitorDetails()
    {
        $nameExists = Visitor::where('name', $this->user['name'])
            ->where('id', '!=', getAuthData()->id)
            ->first();

        if ($nameExists) {
            $this->addError('user.name', 'Name already exists.');
            return;
        }

        $emailExists = Visitor::where('email', $this->user['email'])
            ->where('id', '!=', getAuthData()->id)
            ->first();

        if ($emailExists) {
            $this->addError('user.email', 'Email already exists.');
            return;
        }

        $mobileNoExists = Visitor::where('mobile_number', $this->user['mobile_number'])
            ->where('id', '!=', getAuthData()->id)
            ->first();

        if ($mobileNoExists) {
            $this->addError('user.mobile_number', 'Mobile Number already exists.');
            return;
        }

        $this->validate();
        $visitorInfo = Visitor::find(getAuthData()->id);
        $visitorInfo->update([
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'mobile_number' => $this->user['mobile_number'],
            // 'updated_by' => getAuthData()->id,
        ]);

        $isUpdate = $visitorInfo->wasChanged('name', 'email', 'mobile_number');

        if ($isUpdate) {
            session()->flash("success", "Successfully updated");
            return;
        } else if ($isUpdate === false) {
            session()->flash("info", "Made Some Changes to Update");
            return;
        } else {
            session()->flash("error", "Cannot Update Visitor Details");
            return;
        }
    }


    public function updateVisitorPassword()
    {

        $messages = [
            'currentPassword' => 'This is Required',
            'newPassword' => 'This is Required',
            'newPassword.different' => 'New Password same as current password',
            'confirmPassword' => 'This is Required',
            'confirmPassword.same' => 'Password does not match with new password',
        ];
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|different:currentPassword',
            'confirmPassword' => 'required|same:newPassword',
        ], $messages);

        $VisitorInfo = Visitor::find(getAuthData()->id);

        if (Hash::check($this->currentPassword, $VisitorInfo->password)) {
            $VisitorInfo->update([
                'password' => Hash::make($this->confirmPassword),
                'updated_by' => getAuthData()->id,
            ]);
            session()->flash('success', 'Passwords Changed Successfully');
            return redirect(route('user.profile'));
        }

        session()->flash('error', 'Current password is does not match with existing password');
        return;
    }

    public function updateExhibitorPassword()
    {
        $messages = [
            'currentPassword' => 'This is Required',
            'newPassword' => 'This is Required',
            'newPassword.different' => 'New Password same as current password',
            'confirmPassword' => 'This is Required',
            'confirmPassword.same' => 'Password does not match with new password',
        ];
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|different:currentPassword',
            'confirmPassword' => 'required|same:newPassword',
        ], $messages);

        $exhibitorInfo = Exhibitor::find(getAuthData()->id);

        if (Hash::check($this->currentPassword, $exhibitorInfo->password)) {
            $exhibitorInfo->update([
                'password' => Hash::make($this->confirmPassword),
                'updated_by' => getAuthData()->id,
            ]);
            session()->flash('success', 'Passwords Changed Successfully');
            return redirect(route('user.profile'));
        }

        session()->flash('error', 'Current password is does not match with existing password');
        return;
    }

    public function mount()
    {
        $authData = getAuthData();
        // dd($authData);

        $this->user['name'] = $authData->name ?? '';
        $this->user['email'] = $authData->email ?? '';
        $this->user['mobile_number'] = $authData->mobile_number ?? '';
    }
    public function render()
    {
        return view('livewire.profile.user-profile-settings')->layout('layouts.admin');
    }
}
