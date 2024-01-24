<div>


    <div class="page-body">
        <div class="container-xl">
            @include('includes.alerts')

            <div class="row">
                <div class="col-lg-4">
                    @livewire('settings.employee.employee-handler', ['employeeId' => $employeeId])
                </div>

                <div class="col-lg-8">
                    <h4>List of Employees</h4>

                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Emp.No.</th>
                                        <th>Phone.No</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($employees) && count($employees) > 0)
                                        @foreach ($employees as $employeeIndex => $employee)
                                            <tr>
                                                <td>
                                                    {{ $employeeIndex + $employees->firstItem() }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span @class([
                                                            'badge',
                                                            'me-1',
                                                            'bg-success' => $employee->is_active,
                                                            'bg-danger' => !$employee->is_active,
                                                        ])></span>
                                                        <div class="text-capitalize">{{ $employee->name }}</div>

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize">{{ $employee->emp_no }}</div>
                                                </td>
                                                <td>
                                                    <div class="text-capitalize">{{ $employee->mobile_number }}</div>
                                                </td>

                                                <td>

                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="{{ route('employees.index', ['employeeId' => $employee->id, 'p' => $this->paginators['p'], 'pp' => $this->perPage]) }}"
                                                            title="Edit" data-toggle="tooltip" data-placement="top">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-edit" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path
                                                                    d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                                </path>
                                                                <path
                                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                                </path>
                                                                <path d="M16 5l3 3"></path>
                                                            </svg>
                                                        </a>
                                                        <a href='#' type="button"
                                                            wire:click.prevent="resetPassword({{ $employee->id }})"
                                                            wire:confirm="Are you sure you want to reset the password?"
                                                            title="Reset the password" data-toggle="tooltip"
                                                            data-placement="top">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-cloud-lock-open text-warning"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="1" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path
                                                                    d="M19 18a3.5 3.5 0 0 0 0 -7h-1c.397 -1.768 -.285 -3.593 -1.788 -4.787c-1.503 -1.193 -3.6 -1.575 -5.5 -1s-3.315 2.019 -3.712 3.787c-2.199 -.088 -4.155 1.326 -4.666 3.373c-.512 2.047 .564 4.154 2.566 5.027">
                                                                </path>
                                                                <path
                                                                    d="M8 15m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z">
                                                                </path>
                                                                <path d="M10 15v-2a2 2 0 0 1 3.736 -1"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if (isset($employees) && count($employees) == 0)
                                        @livewire('not-found-record-row', ['colspan' => 6])
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{-- {{ $employees->links() }} --}}
                            <div class="row d-flex flex-row mb-3">
                                @if (isset($employees) && count($employees) != 0)
                                    <div class="col">
                                        <div class="d-flex flex-row mb-3">
                                            <div>
                                                <label class="p-2" for="perPage">Per Page</label>
                                            </div>
                                            <div>
                                                <select class="form-select" id="perPage" name="perPage"
                                                    wire:model="perPage"
                                                    wire:change="changePageValue($event.target.value)">
                                                    <option value=10>10</option>
                                                    <option value=50>50</option>
                                                    <option value=100>100</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col d-flex justify-content-end">
                                    @if (isset($employees) && count($employees) >= 0)
                                        {{ $employees->links() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>

