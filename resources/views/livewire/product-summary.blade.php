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
                            <h4 class="text">List all Products</h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        {{-- <th>Image</th> --}}
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>tags</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($products) && count($products) > 0)
                                        @foreach ($products as $productIndex => $product)
                                            <tr wire:key='item-{{ $product->id }}'>
                                                <td>
                                                    {{ $productIndex + $products->firstItem() }}
                                                </td>

                                                {{-- <td>
                                                    <div class="row col-md-12 d-flex justify-content-start">
                                                        @if (isset($product->_meta['images']))
                                                            @foreach ($product->_meta['images'] as $imagePaths)
                                                                <div class="col-md-4">
                                                                    <img src="storage\{{ $imagePaths['imagePath'] }}"
                                                                        class="avatar-md" height="30"
                                                                        width="300" />
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </td> --}}

                                                <td>
                                                    <div class="text-capitalize">{{ $product->name ?? '' }}</div>
                                                </td>

                                                <td>

                                                    <div class='text-capitalize'>
                                                        {{ $product->categoryName->name ?? '' }}</div>
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
                                                            href="{{ route('products', ['productId' => $product->id, 'page' => $this->paginators['page'], 'pp' => $this->perPage]) }}">
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

                                                        <a href="#"
                                                            wire:click.prevent="$dispatch('canDeleteProduct',{{ $product->id }})"
                                                            class="text-danger">
                                                            @include('icons.trash')
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
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
                Livewire.dispatch('deleteProduct', {productId});
            }
        });
    </script>
@endpush
