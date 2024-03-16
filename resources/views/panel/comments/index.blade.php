@extends('panel.layouts.master')
@section('title', 'نظرات')
@section('content')
    {{--  comment Modal  --}}
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">مشاهده متن نظر</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="comment_id" value="" id="comment_id">
                    <div class="form-group">
                        <label for="comment_text" style="font-size: 1rem;">متن نظر</label>
                        <div class="text-justify" style="line-height: 1.7rem; color: #606060" id="comment_text"></div>
                    </div>
                    <div class="form-group">
                        <label for="comment_status">وضعیت</label>
                        <select name="comment_status" class="form-control" id="comment_status">
                            <option value="pending">در حال بررسی</option>
                            <option value="accepted">تایید شده</option>
                            <option value="rejected">رد شده</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn_change_status">تایید</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end comment Modal  --}}
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>نظرات</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام و نام خانوادگی</th>
                        <th>شماره تماس</th>
                        <th>عنوان نظر</th>
                        <th>متن نظر</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>مشاهده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $key => $comment)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $comment->user->fullName() }}</td>
                            <td>{{ $comment->user->phone }}</td>
                            <td>{{ $comment->title }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($comment->text, 60) }}</td>
                            <td>
                                @if($comment->status == 'accepted')
                                    <span class="badge badge-success">{{ \App\Models\Comment::STATUS[$comment->status] }}</span>
                                @elseif($comment->status == 'rejected')
                                    <span class="badge badge-danger">{{ \App\Models\Comment::STATUS[$comment->status] }}</span>
                                @else
                                    <span class="badge badge-warning">{{ \App\Models\Comment::STATUS[$comment->status] }}</span>
                                @endif
                            </td>
                            <td>{{ verta($comment->created_at)->format('H:i - Y/m/d') }}</td>
                            <td>
                                <button class="btn btn-info btn-floating btn_comment" data-toggle="modal" data-target="#commentModal" data-id="{{ $comment->id }}" data-status="{{ $comment->status }}" data-text="{{ $comment->text }}">
                                    <i class="fa fa-eye"></i>
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
            <div class="d-flex justify-content-center">{{ $comments->appends(request()->all())->links() }}</div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click','.btn_comment', function () {
                let comment_text = $(this).data('text');
                let comment_id = $(this).data('id');
                let comment_status = $(this).data('status');

                $('#comment_id').val(comment_id);
                $('#comment_text').text(comment_text);
                $('#comment_status').val(comment_status);
            })

            $(document).on('click','#btn_change_status', function () {
                let comment_id = $('#comment_id').val();
                let comment_status = $('#comment_status').val();

                $(this).attr('disabled','disabled').text('در حال تایید...');
                $.ajax({
                    url: '/panel/comment-change-status',
                    type: 'post',
                    data: {
                        comment_id,
                        comment_status
                    },
                    success: function (res) {
                        $('tbody:not(.internal_tels)').html($(res).find('tbody:not(.internal_tels)').html());

                        $('#btn_change_status').removeAttr('disabled').text('تایید');

                        Swal.fire({
                            title: 'وضعیت با موفقیت تغییر کرد',
                            icon: 'success',
                            showConfirmButton: false,
                            toast: true,
                            timer: 2000,
                            timerProgressBar: true,
                            position: 'top-start',
                            customClass: {
                                popup: 'my-toast',
                                icon: 'icon-center',
                                title: 'left-gap',
                                content: 'left-gap',
                            }
                        })
                        // $('#commentModal').hide();
                        // $('.modal-backdrop').remove();
                        // $('body').removeClass('modal-open');
                    }
                })
            })
        })
    </script>
@endsection
