@section('titre', 'Ajouter un coupon')
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
                                <li class="breadcrumb-item active">Ajouter un coupon</li>
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
                                Formulaire d'ajout d'un coupon
                            </h5>
                        </div>
                        <div class="card-body">

                            
<div class="card">
   
    <div class="card-body">
      <form method="post"  action="{{url('savecoupon')}}">
        {{csrf_field()}}
        <div class="form-group">
            <label for="inputTitle" class="col-form-label">
                Coupon Code <span class="text-danger">*</span>
            </label>
        
            <!-- Champ de saisie pour le code du coupon -->
            <div class="input-group">
                <input id="inputTitle" 
                       type="text" 
                       name="code" 
                       placeholder="Le coupon"  
                       value="{{ old('code') }}" 
                       maxlength="4" 
                       class="form-control @error('code') is-invalid @enderror">
                       
                
                <!-- Bouton pour générer un code aléatoire -->
                <button type="button" id="generateCodeBtn" class="btn btn-secondary">Générer</button>
            </div>
        
            <!-- Message d'erreur -->
            @error('code')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
   
    

        <div class="form-group">
            <label for="type" class="col-form-label">Type <span class="text-danger">*</span></label>
            <select name="type" class="form-control">
                <option value="fixed">Fixed</option>
                <option value="percent">Percent</option>
            </select>
            @error('type')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

       <div class="form-group">
    <label for="commercial_id" class="form-label">Commercial</label>
    <select name="commercial_id" id="commercial_id" class="form-control">
        <option value="">-- Sélectionnez un commercial --</option>
        @foreach($commercials as $commercial)
            <option value="{{ $commercial->id }}">{{ $commercial->nom }}</option>
        @endforeach
    </select>
</div>


        <div class="form-group">
            <label for="inputTitle" class="col-form-label">Value <span class="text-danger">*</span></label>
            <input id="inputTitle" type="number" name="value" placeholder="Enter Coupon value"  value="{{old('value')}}" class="form-control">
            @error('value')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <br>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>
                           {{--  @livewire('Categories.AddCategory', ['category'=> null] ) --}}
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div> <!-- end row-->
        </div> <!-- container -->
    </div>



    <script>
        document.getElementById('generateCodeBtn').addEventListener('click', function() {
            var generatedCode = generateRandomCode(10); // Générer un code aléatoire de 10 caractères
            document.getElementById('inputTitle').value = generatedCode;
        });
    
        function generateRandomCode(length) {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var result = '';
            var charactersLength = characters.length;
            for (var i = 0; i < 4; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
    </script>
@endsection