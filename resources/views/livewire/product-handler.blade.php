@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush
<div>
    <h4>{{ isset($productId) ? 'Edit Product' : 'New Product' }}</h4>
    <div class="card">
        <form id ="productForm" wire:submit={{ isset($productId) ? 'update' : 'create' }}
            enctype="multipart/form-data">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row row-cards">

                            @if (isset($productId))

                                <div style="margin-left: 35%">
                                    <img src="storage\{{ $this->product['image'] ?? '' }}"
                                        class="rounded-circle avatar-xl" height="70" width="70" />
                                </div>
                                <input type="file"class="form-control" id="updateImage"  wire:model="photo" hidden />
                                <br>
                                <button class="w-25 btn mx-auto border-0 bg-default mt-3"
                                    onclick="document.getElementById('updateImage').click(event.preventDefault())">
                                    @include('icons.pencil')
                                </button>
                            @else
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="avatar-edit ">
                                            <label class="form-label" for="imageUpload">Image Updload</label>
                                            <input type='file' class="form-control" wire:model="photo"
                                                id="imageUpload" />
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label required" for="name">Name</label>
                                    <input type="text" id ="name" class="form-control"placeholder="Name"
                                        wire:model='product.name'>
                                    @error('product.name')
                                        <div class="error text-danger fs-5 " style="color: #ab2e2e;">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required" for="category">Category</label>
                                    <div wire:ignore id ="ts1">
                                        <select id="category" wire:model="product.category_id"
                                            @class([
                                                'form-select',
                                                'is-invalid' => $errors->has('product.category_id') ? true : false,
                                            ])>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value={{ $category->id }}>{{ $category->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('product.category_id')
                                        <div class="error text-danger fs-5 " style="color: #ab2e2e;">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label " for="tags">Tags</label>
                                    <div wire:ignore id="ts">
                                        <select id="tags" wire:model="product.tags" class="form-select"
                                            multiple>
                                            <option value="">Choose Tags</option>
                                            @foreach ($tags as $tag)
                                                <option value={{ $tag->id }}>{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="desc">Description</label>
                                    <textarea id="desc" class="form-control" wire:model="product.description" placeholder="Description"></textarea>
                                </div>
                            </div>


                            @if (isset($productId) && isset($this->product['_meta']['images']) && count($this->product['_meta']['images']) > 0)
                                <div class="col-md-12">
                                    <div class="mb-3 d-flex align-items-center">
                                        <label class="form-label pt-2" for="desc">Product Images</label>
                                        <div>
                                            <input type="file"class="form-control" id="updateImage"
                                                wire:model.defer="photo" hidden multiple />
                                            <button class="btn border-0 bg-default text-blue ms-2"
                                                onclick="document.getElementById('updateImage').click(event.preventDefault())">
                                                @include('icons.pin')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($this->product['_meta']['images'] as $productImage)
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <img src="{{ asset('storage/' . $productImage['filePath']) }}" class="avatar-md"
                                            height="70" width="70" />
                                        <a href="javascript:void(0);" class="text-danger pt-4"
                                            wire:click.prevent="deleteImg('{{ $productImage['id'] ?? '' }}')">
                                            @include('icons.trash')
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="avatar-edit ">
                                            <label class="form-label" for="imageUpload">Image Upload</label>
                                            <input type='file' class="form-control" wire:model.defer="photo"
                                                id="imageUpload" multiple />
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                @if (isset($productId))
                    <a href={{ auth()->guard('exhibitor')->check()? route('myproducts'): route('products') }}
                        class="text-danger me-2"> Cancel </a>
                @else
                    <a href=# wire:click="resetFields" class="text-danger me-2"> Reset </a>
                @endif
                <button type="submit" class="btn btn-primary ">{{ isset($productId) ? 'Update' : 'Create' }}</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <!-- Tom Select Link and Script -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('livewire:initialized', function() {
            var category = new TomSelect('#category', {
                plugins: ['dropdown_input'],

            });
            var tags = new TomSelect('#tags', {
                plugins: ['dropdown_input'],
            });
        });
    </script>
@endpush
