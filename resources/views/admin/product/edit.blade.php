@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <div id="controller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Edit Product
                        </h3>
                    </div>
                    <form action="{{ route('products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input :value="product.name" required type="text" class="form-control"
                                    placeholder="Enter Name" name="name">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" placeholder="Enter Description" name="description">@{{ product.description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input :value="product.qty" required class="form-control" type="number" name="qty"
                                    required min="0" max="1000" placeholder="Enter Quantity">
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <input :value="product.price" class="form-control" type="number" name="price"
                                    placeholder="Enter Price" min="1000" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select required class="form-control" name="category_id">
                                    <option :selected="category.id == product.category_id" v-for="category in categories"
                                        :value="category.id">
                                        @{{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Choose Image</label>
                                <div id="img" class="card" style="width: 4rem">
                                    <img v-if="exists" class="card-img-top" :src="'/' + product.image_path">
                                    <img v-else class="card-img-top" :src="'/storage/default.jpg'">
                                    <input hidden type="text" name="image_path" :value="product.image_path">
                                </div>
                                <div hidden id="new-img" class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image" accept=".jpg,.jpeg,.png"
                                            disabled>
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                                <div class="input-group pt-2">
                                    <button type="button" class="btn btn-default" @click="handleEdit()">
                                        <span v-if="edit">Edit</span>
                                        <span v-else>Batalkan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        const {
            createApp
        } = Vue

        createApp({
            data() {
                return {
                    product: {!! json_encode($product) !!},
                    categories: {!! json_encode($categories) !!},
                    exists: {!! json_encode($exists) !!},
                    edit: true
                }
            },

            methods: {
                initFileInput() {

                    $(function() {
                        bsCustomFileInput.init();
                    });
                },
                handleEdit() {
                    if (this.edit) {
                        $('#img').prop('hidden', true);
                        $('input[name=image_path]').prop('disabled', true)
                        $('#new-img').prop('hidden', false)
                        $('input[name=image]').prop('disabled', false)
                        this.edit = !this.edit
                    } else {
                        $('#img').prop('hidden', false)
                        $('input[name=image_path]').prop('disabled', false)
                        $('#new-img').prop('hidden', true)
                        $('input[name=image]').prop('disabled', true)
                        this.edit = !this.edit

                    }
                }
            },

            mounted() {
                this.initFileInput()
            },

        }).mount('#controller')
    </script>
@endsection
