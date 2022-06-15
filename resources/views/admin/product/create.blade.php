@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
    <div id="controller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Create a Product
                        </h3>
                    </div>
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input required type="text" class="form-control" placeholder="Enter Name" name="name">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" placeholder="Enter Description" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input required class="form-control" type="number" name="qty" required min="1" max="1000"
                                    placeholder="Enter Quantity">
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <input class="form-control" type="number" name="price" placeholder="Enter Price"
                                    min="1000" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select required class="form-control" name="category_id">
                                    <option value="" selected hidden>Choose Category</option>
                                    <option v-for="category in categories" :value="category.id">@{{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Choose Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image" accept=".jpg,.jpeg,.png">
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
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
                    categories: {!! json_encode($categories) !!}
                }
            },

            methods: {
                initFileInput() {

                    $(function() {
                        bsCustomFileInput.init();
                    });
                }
            },

            mounted() {
                this.initFileInput()
            }

        }).mount('#controller')
    </script>
@endsection
