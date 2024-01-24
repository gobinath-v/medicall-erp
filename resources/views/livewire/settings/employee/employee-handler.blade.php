<div>
    @if (isset($employee['id']))
        <h4>Edit Employee <a class="btn btn-outline-primary btn-sm ms-3" href="{{ route('employees.index') }}">Add New</a>
        </h4>
    @else
        <h4>Add new Employee</h4>
    @endif
    <div class='card'>
        @if (isset($employee['id']))
            <form wire:submit="update">
            @else
                <form wire:submit="create">
        @endif
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row row-cards">
                        <div class="col-md-12">
                            <div class="mb-1">
                                <label class="form-label required">Emp.No.</label>
                                <input type="text" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('employee.emp_no') ? true : false,
                                ]) placeholder="Enter employee no"
                                    wire:model="employee.emp_no">
                                @error('employee.emp_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-1">
                                <label class="form-label required">Name</label>
                                <input type="text" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('employee.name') ? true : false,
                                ]) placeholder="Enter employee Name"
                                    wire:model="employee.name">
                                @error('employee.name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-1">
                                <label class="form-label required">Email</label>
                                <input type="text" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('employee.email') ? true : false,
                                ])
                                    placeholder="Enter Email Address Employee " wire:model="employee.email">
                                @error('employee.email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-1">
                                <label class="form-label required">Phone Number</label>
                                <input type="text" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('employee.mobile_number') ? true : false,
                                ])
                                    placeholder="Enter employee Phone Number " wire:model="employee.mobile_number">
                                @error('employee.mobile_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-1">
                                <div class="form-label">Type</div>
                                <select wire:model.live="employee.type" @class([
                                    'form-select',
                                    'is-invalid' => $errors->has('employee.type') ? true : false,
                                ])>
                                    <option value="sales_person">Sales Person</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="user">User</option>

                                </select>
                                @error('employee.type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="mb-1">
                                <div class="form-label">Status</div>
                                <select wire:model="employee.is_active" @class([
                                    'form-select',
                                    'is-invalid' => $errors->has('employee.is_active') ? true : false,
                                ])>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('employee.is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('employees.index') }}" class="text-danger me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </form>
    </div>
</div>
