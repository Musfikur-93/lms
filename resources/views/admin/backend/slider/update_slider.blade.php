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
                    <li class="breadcrumb-item active" aria-current="page">Update Slider</li>
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
            <form action="{{ route('update.slider') }}" method="post" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $slider->id }}">
                <input type="hidden" name="old_img" value="{{ $slider->image }}">

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Slider Heading </label>
                    <input type="text" name="heading" class="form-control" id="input1" value="{{ $slider->heading }}">
                </div>

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Short Description </label>
                    <input type="text" name="short_desc" class="form-control" id="input1" value="{{ $slider->short_desc }}">
                </div>

                <div class="form-group col-md-6">
                    <label for="input1" class="form-label">Video </label>
                    <input type="text" name="video" class="form-control" id="input1" value="{{ $slider->video }}">
                </div>

                <div class="col-md-6">
                </div>


                <div class="form-group col-md-6">
                    <label for="input2" class="form-label">Slider Image </label>
                    <input class="form-control" name="image" type="file" id="image">
                </div>

                <div class="col-md-6">
                    <img id="showImage" src="{{ asset($slider->image) }}" alt="image" class="rounded-circle p-1 bg-primary" width="80">
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
