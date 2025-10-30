@section('titre', 'Mise à jour')
@extends('admin.fixe')

@section('body')
    <div class="page-content-wrapper">
        <div class="page-content">


            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('coupons') }}">Coupons</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    {{ $coupon->code }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="header-title">
                                modification du coupon
                            </h5>
                        </div>
                        <div class="card-body">


                            <div class="card">
                               
                                <div class="card-body">
                                    <form method="post" action="{{ route('coupons.update', $coupon->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class=" row form-group">

                                            <div class=" col-sm-6 form-group">
                                                <label for="inputTitle" class="col-form-label">Coupon Code <span
                                                        class="text-danger">*</span></label>
                                                <input id="inputTitle" type="text" name="code"
                                                    placeholder="Enter Coupon Code" value="{{ $coupon->code }}"
                                                    class="form-control">
                                                @error('code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-sm-6 form-group">
                                                <label for="type" class="col-form-label">Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="type" class="form-control">
                                                    <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>
                                                        Fixed</option>
                                                    <option value="percent"
                                                        {{ $coupon->type == 'percent' ? 'selected' : '' }}>Percent
                                                    </option>
                                                </select>
                                                @error('type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-sm-6 form-group">
                                                <label for="inputTitle" class="col-form-label">Value <span
                                                        class="text-danger">*</span></label>
                                                <input id="inputTitle" type="number" name="value"
                                                    placeholder="Enter Coupon value" value="{{ $coupon->value }}"
                                                    class="form-control">
                                                @error('value')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-sm-6 form-group">
                                                <label for="status" class="col-form-label">Status <span
                                                        class="text-danger">*</span></label>
                                                <select name="status" class="form-control">
                                                    <option value="active"
                                                        {{ $coupon->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive"
                                                        {{ $coupon->status == 'inactive' ? 'selected' : '' }}>Inactive
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group mb-3">
                                            <button class="btn btn-success" type="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{--  @livewire('Categories.AddCategory', ['category' => $category] ) --}}
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div> <!-- end row-->
        </div> <!-- container -->
    </div>
@endsection
