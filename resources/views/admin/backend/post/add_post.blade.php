@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Blog Post</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">

            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('store.blog.post') }}" method="post" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Blog Category </label>
                    <select name="blogcat_id" class="form-select mb-3" aria-label="Default select example">
                        <option selected="">Select the Blog Category</option>
                            @foreach ($blogcat as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Post Title </label>
                    <input type="text" name="post_title" class="form-control" id="input1">
                </div>

                <div class="col-md-6">
                </div>

                <div class="form-group col-md-12">
                    <label for="input1" class="form-label">Post Description </label>
                    <textarea name="long_descp" class="form-control" id="summernote"></textarea>
                </div>

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Post Tags</label>
                    <input type="text" name="post_tags" class="form-control" data-role="tagsinput" value="jQuery">
                </div>

                <div class="col-md-6">
                </div>

                <div class="form-group col-md-6">
                    <label for="input2" class="form-label">Post Image </label>
                    <input class="form-control" name="post_image" type="file" id="image">
                </div>

                <div class="col-md-6">
                    <img id="showImage" src="{{ url('upload/no_image.jpg')}}" alt="image" class="rounded-circle p-1 bg-primary" width="80">
                </div>

                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                category_name: {
                    required : true,
                },

                image: {
                    required : true,
                },

            },
            messages :{
                category_name: {
                    required : 'Please Enter Category Name',
                },

                image: {
                    required : 'Please Enter Category Image',
                },

            },
            errorElement : 'span',
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>






@endsection
