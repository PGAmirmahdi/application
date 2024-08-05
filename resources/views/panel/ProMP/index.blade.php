@extends('panel.layouts.master')
@section('title', 'موجودی محصولات')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>موجودی محصولات</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center" id="products_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان محصول</th>
                        <th>کد محصول</th>
                        <th>موجودی</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($inventories as $key => $inventory)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $inventory->title }}</td>
                            <td>{{ $inventory->code }}</td>
                            <td>{{ number_format($inventory->current_count) }} واحد</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
