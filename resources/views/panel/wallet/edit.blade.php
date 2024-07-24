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
                        <input type="text" class="form-control" name="user_id" id="user_id" value="{{ $wallet->users->name . ' ' . $wallet->users->family }}" readonly>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="balance">موجودی کیف پول<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="balance" id="balance" value="{{ old('balance', $wallet->balance) }}">
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
@endsection
