<div>
    <div class="page-body">
        <div class="container-xl">
            @include('includes.alerts')
            <div class="row">
                <div class="col-lg-4">
                    @livewire('product-handler', ['productId' => $productId])
                </div>
                <div class="col-lg-8">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div>
                            <h4 class="text">List My Products</h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>tags</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($products as $product)
                                    <tr wire:key='item-{{ $product->id }}'>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <div class="position-relative">
                                                <img src="storage\{{ $product->image }}"
                                                    class="rounded-circle avatar-xl" height="30" width="30" />
                                            </div>

                                        </td>

                                        <td>
                                            <div class="text-capitalize">{{ $product->name ?? '' }}</div>
                                        </td>

                                        <td>

                                            <div class='text-capitalize'>
                                                {{ $product->categoryName->name ?? ''}}</div>
                                        </td>

                                        <td>
                                            <div class="text-capitalize">
                                                @if (isset($productTags[$product->id]))
                                                @foreach ($productTags[$product->id] as $tag)
                                                {{ $tag }}
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                                @endforeach
                                                @else
                                                {{ '' }}
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a
                                                    href="{{ route('myproducts', ['productId' => $product->id, 'p' => $page, 'pp' => $this->perPage]) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-edit" width="24" height="24"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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

                                                <a href="#"
                                                    wire:click.prevent="$dispatch('canDeleteProduct',{{ $product->id }})"
                                                    class="text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-trash" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                        </path>
                                                        <path d="M4 7l16 0"></path>
                                                        <path d="M10 11l0 6"></path>
                                                        <path d="M14 11l0 6"></path>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                                        </path>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @if (isset($products) && count($products) == 0)
                                    @livewire('not-found-record-row', ['colspan' => 6])
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row d-flex flex-row mb-3">
                                @if (isset($products) && count($products) != 0)
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
                                    @if (isset($products) && count($products) >= 0)
                                    {{ $products->links() }}
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
@push('scripts')
<script>
    Livewire.on('canDeleteProduct', (productId) => {
            if (confirm('Are you sure you want to delete this Product ?')) {
                Livewire.dispatch('deleteProduct', productId);
            }
        });
</script>
@endpush
