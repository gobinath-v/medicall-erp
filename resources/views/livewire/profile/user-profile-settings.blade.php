<div>
    <div class="container mt-4  ">
        @include('includes.alerts')
        <div class="row d-flex ">
            <div class="col-4">
                <div class="card mb-2">
                    <div class="card-body text-center">
                        <span class="avatar avatar-xl mb-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-user-hexagon w-100 h-100" width="32"
                                height="32" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z"></path>
                                <path d="M6.201 18.744a4 4 0 0 1 3.799 -2.744h4a4 4 0 0 1 3.798 2.741"></path>
                                <path
                                    d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z">
                                </path>
                            </svg>
                        </span>

                        <h2 class="m-0 mb-1">{{ getAuthData()->name ??'' }}</h2>
                        {{-- <h4 class="text-secondary">{{ $userData->emp_id ?? '' }}</h4> --}}
                        {{-- <h5 class="text-secondary">{{ $userData->department->name ?? '' }}</h5> --}}
                        {{-- <span class="text-secondary">{{ $userData->level }}</span> --}}
                        {{-- <div class="mt-3">
                            <span class="badge bg-purple-lt">{{ $userData->type }}</span>
                        </div> --}}
                    </div>
                    {{-- <div class="d-flex">
                        <a href="#" class="card-btn" wire:click.prevent="updateUserProfile">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-recharging me-1"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M7.038 4.5a9 9 0 0 0 -2.495 2.47"></path>
                                <path d="M3.186 10.209a9 9 0 0 0 0 3.508"></path>
                                <path d="M4.5 16.962a9 9 0 0 0 2.47 2.495"></path>
                                <path d="M10.209 20.814a9 9 0 0 0 3.5 0"></path>
                                <path d="M16.962 19.5a9 9 0 0 0 2.495 -2.47"></path>
                                <path d="M20.814 13.791a9 9 0 0 0 0 -3.508"></path>
                                <path d="M19.5 7.038a9 9 0 0 0 -2.47 -2.495"></path>
                                <path d="M13.791 3.186a9 9 0 0 0 -3.508 -.02"></path>
                                <path d="M12 8l-2 4h4l-2 4"></path>
                                <path d="M12 21a9 9 0 0 0 0 -18"></path>
                            </svg>
                            Update Profile
                        </a>
                        <a href="#" class="card-btn" wire:click.prevent="changeUserPassword">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exchange"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M19 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M19 8v5a5 5 0 0 1 -5 5h-3l3 -3m0 6l-3 -3"></path>
                                <path d="M5 16v-5a5  5 0 0 1 5 -5h3l-3 -3m0 6l3 -3"></path>
                            </svg>
                            Change Password
                        </a>
                    </div> --}}
                </div>
                {{-- <div class="col-4">
                    <div class="card">
                        <div class="card-body text-center">
                        </div>

                    </div>
                </div> --}}
            </div>










            {{-- update Profile --}}
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Profile</h3>
                    </div>
                    @if (auth()->guard('visitor')->check())
                    <form wire:submit="updateVisitorDetails" id="visitorProfile">
                    @elseif (auth()->guard('exhibitor')->check())
                    <form wire:submit="updateExhibitorDetails" id="exhibitorProfile">
                    @else
                    <form wire:submit="updateUserDetails" id="userProfile">
                    @endif
                        <div class="card-body bg-white ">

                        <div class="row row-cards ">

                                {{-- Name --}}
                                {{-- <div class="col-md-4"> --}}
                                <div>
                                    <label class="form-label required " f or="name"><strong>Name
                                        </strong>
                                    </label>
                                    <div>
                                        <input type="text" wire:model="user.name" id="name"
                                            class="form-control @if ($errors->has('user.name')) is-invalid @endif ">
                                        @error('user.name')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- </div> --}}

                            {{-- Email --}}
                            {{-- <div class="col-md-4"> --}}
                            <div>
                                <label class="form-label required " for="email"><strong>Email
                                    </strong></label>
                                <div>
                                    <input id="email"
                                        class="form-control @if ($errors->has('user.email')) is-invalid @endif "
                                        type="text" wire:model="user.email">
                                    @error('user.email')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- </div> --}}

                                {{-- Phone no --}}
                                {{-- <div class="col-md-4"> --}}
                                <div>
                                    <label class="form-label required" for="mobile_number"><strong>Phone
                                            No</strong></label>
                                    <div>
                                        <input id="mobile_number"
                                            class="form-control @if ($errors->has('user.mobile_number')) is-invalid @endif "
                                            type="text" wire:model="user.mobile_number">
                                        @error('user.mobile_number')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- </div> --}}

                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('user.profile') }}" class="text-danger me-3 mt-2" name="cancel">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                    </form>

                </div>
            </div>


            {{-- Change Password --}}
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    @if (auth()->guard('visitor')->check())
                    <form wire:submit="updateVisitorPassword" id="changePassword">
                    @elseif (auth()->guard('exhibitor')->check())
                    <form wire:submit="updateExhibitorPassword" id="changeExhibitorPassword">
                    @else
                    <form wire:submit="updatePassword" id="changePassword">
                    @endif
                        <div class="card-body bg-white ">
                            <div class="row row-cards">

                            <div>
                                <label class="form-label required "><strong>Current Password
                                    </strong>
                                </label>
                                <div>
                                    <label class="d-flex">
                                        <input
                                            class = " form-control @if ($errors->has('currentPassword')) border-red @endif "
                                            type="{{ $showPassword ? 'text' : 'password' }}"
                                            wire:model="currentPassword" placeholder="Current Password">
                                        <span wire:click="toggleVisibility"class="p-2"
                                            style="margin-left:-13%; cursor: pointer;">
                                            @if (!$showPassword)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye-off" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                    </path>
                                                    <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                                                    <path
                                                        d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87">
                                                    </path>
                                                    <path d="M3 3l18 18"></path>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                    </path>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6">
                                                    </path>
                                                </svg>
                                            @endif
                                        </span>
                                    </label>

                                    @error('currentPassword')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="form-label required"><strong>New Password</strong></label>
                                <div>
                                    <label class="d-flex">
                                        <input
                                            class="form-control @if ($errors->has('newPassword')) border-red @endif "
                                            type="{{ $showNewPassword ? 'text' : 'password' }}"
                                            wire:model="newPassword" placeholder="New Password">

                                        <span wire:click="toggleNewPassword" class="p-2"
                                            style="margin-left:-13%; cursor: pointer;">
                                            @if (!$showNewPassword)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye-off" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                    </path>
                                                    <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                                                    <path
                                                        d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87">
                                                    </path>
                                                    <path d="M3 3l18 18"></path>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                    </path>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6">
                                                    </path>
                                                </svg>
                                            @endif
                                        </span>
                                    </label>
                                    @error('newPassword')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="form-label required"><strong>Confirm
                                        Password</strong></label>
                                <div>
                                    <label class="d-flex">
                                        <input
                                            class="form-control @if ($errors->has('confirmPassword')) border-red @endif "
                                            type="{{ $showConfirmPassword ? 'text' : 'password' }}"
                                            wire:model="confirmPassword" placeholder="Confirm Password">

                                        <span wire:click="toggleConfirmPassword"class="p-2"
                                            style="margin-left:-13%; cursor: pointer;">
                                            @if (!$showConfirmPassword)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye-off" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                    </path>
                                                    <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                                                    <path
                                                        d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87">
                                                    </path>
                                                    <path d="M3 3l18 18"></path>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                    </path>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6">
                                                    </path>
                                                </svg>
                                            @endif
                                        </span>
                                    </label>
                                    @error('confirmPassword')
                                        <div class="error text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('user.profile') }}" class="text-danger me-3 mt-2" name="cancel">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
