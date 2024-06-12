@php use Illuminate\Support\Str; @endphp
@extends('panel.layouts.master')
@section('title', 'محصولات')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویدئو ها</h6>
                <div>
                    <a href="{{ route('GuideVideos.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>
                        آپلود ویدئو
                    </a>
                </div>
            </div>
            <form action="{{ route('GuideVideos.search') }}" method="get" id="search_form"></form>
            <div class="row mb-3">
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                    <input type="text" name="product_id" class="form-control" placeholder="نام محصول"
                           value="{{ request()->product_id ?? null }}" form="search_form">
                </div>
                <div class="col-xl-3 xl-lg-3 col-md-4 col-sm-12">
                    <input type="text" name="title" class="form-control" placeholder="عنوان ویدئو"
                           value="{{ request()->title ?? null }}" form="search_form">
                </div>
                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ویدئو</th>
                        <th>نام محصول</th>
                        <th>موضوع ویدئو</th>
                        <th>متن ویدئو</th>
                        <th>آپلود کننده ویدئو</th>
                        <th>زمان آپلود</th>
                        <th>ویرایش</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($videos as $key => $video)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>
                                <a href="{{ $video->main_video }}" target="_blank">
                                    <video width="40px" controls>
                                        <source src="{{ $video->main_video }}" type="video/mp4">
                                        مرورگر شما پشتیبانی نمیکند.
                                    </video>
                                </a>

                            </td>
                            <td>{{ $video->product->title}}</td>
                            <td>{{ Str::limit($video->title, 60) }}</td>
                            <td>{{ Str::limit($video->text, 60) }}</td>
                            <td>{{ $video->user->name . " " .  $video->user->family }}</td>
                            <td>{{ verta($video->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <a class="btn btn-warning btn-floating"
                                   href="{{ route('GuideVideos.edit', $video->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-floating trashRow"
                                        data-url="{{ route('GuideVideos.destroy',$video->id) }}"
                                        data-id="{{ $video->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-center">{{ $videos->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/lazysizes.min.js') }}"></script>
@endsection
