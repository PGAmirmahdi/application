@extends('panel.layouts.master')
@section('title', 'ایجاد تراکنش')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ایجاد تراکنش</h6>
            </div>
            <form id="transaction-form" action="{{ route('charging.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="user_id">نام کاربر<span class="text-danger">*</span></label>
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="">انتخاب کنید</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name . ' ' . $user->family }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-none" id="user-error"></div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="amount">مقدار<span class="text-danger">*</span></label>
                        <input type="text" name="amount" class="form-control" id="amount" value="{{ old('amount') }}">
                        <div class="invalid-feedback d-none" id="amount-error"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="type">نوع تراکنش<span class="text-danger">*</span></label>
                        <select class="form-control" name="type" id="type">
                            <option value="deposit">واریز</option>
                            <option value="withdrawal">برداشت</option>
                        </select>
                        <div class="invalid-feedback d-none" id="type-error"></div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="balance">موجودی<span class="text-danger">*</span></label>
                        <input type="text" name="balance" class="form-control" id="balance" value="0" readonly>
                        <div class="invalid-feedback d-none" id="balance-error"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="description">توضیحات<span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control textarea">{{ old('description') }}</textarea>
                        <div class="invalid-feedback d-none" id="description-error"></div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>
    <style>
        .textarea {
            width: 100%;
            min-height: 200px;
            border-radius: 5px;
            border: 1px solid gainsboro;
            padding: 5px;
        }
    </style>
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('change', 'select[name="user_id"]', function () {
            let user_id = this.value;
            $.ajax({
                url: '/panel/getUserBalance/'+user_id,
                type: 'post',
                success: function(res) {
                    $('#balance').val(res.data);
                },
                error: function(error) {
                    $('#balance').val("کیف پول موجود نیست");
                }
            });
        });
    </script>
@endsection
