@extends('panel.layouts.master')

@section('title', 'ویرایش آفر')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویرایش آفر</h6>
            </div>
            <form action="{{ route('offers.update', $offer->id) }}" method="post" id="offer-form">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="product_id">محصول<span class="text-danger">*</span></label>
                        <select class="form-control" name="product_id" id="product_id">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $offer->product_id) == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
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
                            <input type="number" name="percentage" class="form-control" id="percentage" min="0" value="{{ old('percentage', $offer->percentage) }}">
                        </div>
                        @error('percentage')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="price_before">قیمت قبل</label>
                        <input type="number" name="price_before" class="form-control" id="price_before" value="{{ old('price_before', $offer->price_before) }}">
                        @error('price_before')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="price_after">قیمت بعد</label>
                        <input type="number" name="price_after" class="form-control" id="price_after" value="{{ old('price_after', $offer->price_after) }}">
                        @error('price_after')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="difference">اختلاف قیمت</label>
                        <input type="number" name="difference" class="form-control" id="difference" value="{{ old('difference', $offer->price_before - $offer->price_after) }}" readonly>
                        @error('difference')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">به‌روزرسانی</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        function calculateDifference() {
            var priceBefore = parseFloat(document.getElementById('price_before').value) || 0;
            var priceAfter = parseFloat(document.getElementById('price_after').value) || 0;
            var difference = priceBefore - priceAfter;
            document.getElementById('difference').value = difference;
        }

        $(document).ready(function() {
            $('#price_before').on('input', calculateDifference);
            $('#price_after').on('input', calculateDifference);

            // Initial calculation
            calculateDifference();

            // Fetch product price on product selection change
            $('#product_id').on('change', function() {
                var productId = $(this).val();
                if (productId) {
                    $.ajax({
                        url: '{{ route("products.price", ["id" => ""]) }}/' + productId,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                $('#price_before').val(response.price);
                                calculateDifference();
                            } else {
                                alert('مشکلی در دریافت قیمت محصول وجود دارد.');
                            }
                        },
                        error: function(xhr) {
                            console.log("Error", xhr);
                            alert('مشکلی در دریافت قیمت محصول وجود دارد.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
