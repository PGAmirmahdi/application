@extends('panel.layouts.master')

@section('title', 'ایجاد آفر')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ایجاد آفر</h6>
            </div>
            <form action="{{ route('offers.store') }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="product_id">محصول<span class="text-danger">*</span></label>
                        <select class="form-control" name="product_id" id="product_id">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="percentage">درصد آفر<span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">%</span>
                            </div>
                            <input type="number" name="percentage" class="form-control" id="percentage" min="0" value="{{ old('percentage') }}">
                        </div>
                        @error('percentage')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="price_before">قیمت قبل</label>
                        <input type="number" name="price_before" class="form-control" id="price_before" value="{{ old('price_before') }}">
                        @error('price_before')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="price_after">قیمت بعد</label>
                        <input type="number" name="price_after" class="form-control" id="price_after" value="{{ old('price_after') }}">
                        @error('price_after')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="difference">اختلاف قیمت</label>
                        <input type="number" name="difference" class="form-control" id="difference" value="{{ old('difference') }}" readonly>
                        @error('difference')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        function calculateDifference() {
            var priceBefore = parseFloat(document.getElementById('price_before').value) || 0;
            var priceAfter = parseFloat(document.getElementById('price_after').value) || 0;
            document.getElementById('difference').value = priceBefore - priceAfter;
        }

        document.getElementById('price_before').addEventListener('input', calculateDifference);
        document.getElementById('price_after').addEventListener('input', calculateDifference);

        // Initial calculation
        calculateDifference();
    </script>
@endsection
