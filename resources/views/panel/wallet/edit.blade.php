@extends('panel.layouts.master')

@section('title', 'ویرایش کیف پول')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویرایش کیف پول</h6>
            </div>
            <form id="wallet-form" action="{{ route('wallet.update', $wallet->id) }}" method="post">
                @method('PUT')
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="user_id">نام کاربر<span class="text-danger">*</span></label>
                        <select class="form-control" name="user_id" id="user_id">
                                <option value="{{ old('user_id', $wallet->users->name . ' ' . $wallet->users->family) }}">
                                    {{ $wallet->users->name . ' ' . $wallet->users->family }}
                                </option>
                        </select>
                        @error('user_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="balance">موجودی<span class="text-danger">*</span></label>
                        <input type="number" name="balance" class="form-control" id="balance" value="{{ old('balance', $wallet->balance) }}">
                        @error('balance')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var form = $('#wallet-form');

            form.on('submit', function (event) {
                event.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش‌فرض

                var formData = new FormData(this); // جمع‌آوری داده‌های فرم

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log("آپلود موفق", 'کیف پول با موفقیت ویرایش شد');

                        window.location.href = "{{ route('wallet.index') }}";
                    },
                    error: function (xhr) {
                        console.log("Error", xhr);
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = "خطا در اعتبارسنجی:<br>";
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorMessage += "- " + errors[key][0] + "<br>";
                                }
                            }
                            alert(errorMessage);
                        } else {
                            alert("مشکلی در ارسال وجود دارد");
                        }
                    }
                });
            });
        });
    </script>
@endsection
