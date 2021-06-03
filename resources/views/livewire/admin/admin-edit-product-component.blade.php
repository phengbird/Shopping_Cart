<div>
    <div class="container" style="padding:30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                              <div class="col-md-6">
                                Edit Product
                            </div>
                            <div class="cold-md-6">
                                <a href="{{route('admin.products')}}" class="btn btn-success pull-right">All Products</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if (Session::has('message'))
                            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <form class="form-horizontal" enctype="multipart\form-data" action="" wire:submit.prevent="updateProduct">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Product Name</label>
                                <div class="col-md-4">
                                    <input type="text" placeholder="Product Name" class="form-control input-md" wire:model='Name' wire:keyup="generateSlug">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Product Slug</label>
                                <div class="col-md-4">
                                    <input type="text" placeholder="Product Slug" class="form-control input-md" wire:model="slug">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Short Description</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" placeholder="Short Description" wire:model="short_description"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Description</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" placeholder="Description" wire:model="description"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Price</label>
                                <div class="col-md-4">
                                    <input type="text" placeholder="Regular Price" class="form-control input-md" wire:model="regular_price">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Sale Price</label>
                                <div class="col-md-4">
                                    <input type="text" placeholder="Sale Price" class="form-control input-md" wire:model="sale_price">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">SKU</label>
                                <div class="col-md-4">
                                    <input type="text" placeholder="SKU" class="form-control input-md" wire:model="SKU">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Stock</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="" id="" wire:model="stock_status">
                                        <option value="instock">InStock</option>
                                        <option value="instock">Out of Stock</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Featured</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="" id="">
                                        <option value="instock">No</option>
                                        <option value="instock">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Quantity</label>
                                <div class="col-md-4">
                                    <input type="text" placeholder="Quantity" class="form-control input-md" wire:model="quantity">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Product Image</label>
                                <div class="col-md-4">
                                    <input type="file" class="input-file" wire:model="newimage">
                                    @if ($newimage)
                                        <img src="{{$newimage->temporaryUrl()}}" width="120">
                                    @else
                                        <img src="{{asset('assets/images/products')}}/{{$image}}" alt="">
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="">Category</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="" id="" wire:model="category_id">
                                        <option value="instock">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for=""></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
