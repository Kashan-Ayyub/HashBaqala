@extends('components.layout')
@section('container')
<style>
    input#fluency {
    width: 57px;
    padding: 14px;
}
.form-check {
  display: block;
  min-height: 1.35rem;
  padding-left: 1.643em;
  margin-bottom: 0.125rem; }
  .form-check .form-check-input {
    float: left;
    margin-left: -1.643em; }

.form-check-input {
  width: 1.143em;
  height: 1.143em;
  margin-top: 0.1785em;
  vertical-align: top;
  background-color: #fff;
  background-repeat: no-repeat;
  background-position: center;
  background-size: contain;
  border: 1px solid rgba(0, 0, 0, 0.25);
  -webkit-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact; }
  .form-check-input[type="checkbox"] {
    border-radius: 0.25em; }
  .form-check-input[type="radio"] {
    border-radius: 50%; }
  .form-check-input:active {
    filter: brightness(90%); }
  .form-check-input:focus {
    border-color: #b1bbc4;
    outline: 0;
    box-shadow: none; }
  .form-check-input:checked {
    background-color: #71b6f9;
    border-color: #71b6f9; }
    .form-check-input:checked[type="checkbox"] {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e"); }
    .form-check-input:checked[type="radio"] {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e"); }
  .form-check-input[type="checkbox"]:indeterminate {
    background-color: #71b6f9;
    border-color: #71b6f9;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e"); }
  .form-check-input:disabled {
    pointer-events: none;
    filter: none;
    opacity: 0.5; }
  .form-check-input[disabled] ~ .form-check-label, .form-check-input:disabled ~ .form-check-label {
    opacity: 0.5; }

.form-switch {
  padding-left: 2.5em; }
  .form-switch .form-check-input {
    width: 2em;
    margin-left: -2.5em;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
    background-position: left center;
    border-radius: 2em;
    transition: background-position 0.15s ease-in-out; }
    @media (prefers-reduced-motion: reduce) {
      .form-switch .form-check-input {
        transition: none; } }
    .form-switch .form-check-input:focus {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23b1bbc4'/%3e%3c/svg%3e"); }
    .form-switch .form-check-input:checked {
      background-position: right center;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e"); }

.form-check-inline {
  display: inline-block;
  margin-right: 0.75rem; }

</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Product Update</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Product</a></li>
                        <li class="breadcrumb-item active">Update</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if(session('exist'))
                    <div class="alert alert-danger">
                        {{ session('exist') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{route('dashboard.product.update', $data['id'])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Product Image<span style="color: red">*</span></label>
                                <input class="form-control" name="image" type="file" id="formFile">
                              </div>
                            <div class="mb-3">
                              <label for="exampleInputPassword1" class="form-label">Product Name<span style="color: red">*</span> </label>
                              <input type="text" class="form-control" required name="p_name" value="{{$data['p_name']}}" id="exampleInputPassword1">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Product Price<span style="color: red">*</span> </label>
                                <input type="text" class="form-control" required name="Product_Price" value="{{$data['price']}}" id="exampleInputPassword1">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Product Description (optional)</label>
                                <input type="text" class="form-control" value="{{$data['description']}}" name="p_desc" >
                            </div>
                            <div class="mb-3">
                                <label for="">Category<span style="color: red">*</span></label>
                                <select class="form-control" name="cat_id" aria-label="Default select example">
                                    <option disabled>-- Category Select --</option>
                                    @if(count($category) > 1)
                                        @foreach($category as $val)
                                            @if($val['id'] == $data['category_id'])
                                            <option value="{{$val['id']}}" selected>{{$val['name']}}</option>
                                            @else
                                            <option value="{{$val['id']}}">{{$val['name']}}</option>
                                            @endif
                                        @endforeach
                                    @elseif(count($category) == 0)
                                    @else
                                    <option value="{{$category[0]['id']}}">{{$category[0]['name']}}</option>
                                    @endif  
                                  </select>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" onclick="checkFluency()" name="status" id="fluency" type="checkbox" role="switch" {{ $data->status == 1 ? 'checked' : '' }}>
                                    <label class="mx-4 form-check-label" for="btn-active" id="view_status" style="color:red; padding: 9px;">Non Active</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                          </form>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>
<script>
    checkFluency();
    function checkFluency(){
    var checkbox = document.getElementById('fluency');
    var view_checkbox = document.getElementById('view_status');
    if (checkbox.checked != true)
    {
        view_checkbox.innerHTML = 'Non Active';
        view_checkbox.style.color = "red";
        checkbox.value = 'Non-Active';
    }else{
    view_checkbox.innerHTML = 'Active';
    view_checkbox.style.color = "green";
    checkbox.value = 'Active';
    }
}
</script>
@endsection