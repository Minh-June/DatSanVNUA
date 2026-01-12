@extends('layouts.admin')
@section('title', 'Cập nhật tin tức')
@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Cập nhật bài đăng tin tức</h2>

{{-- Alert lỗi --}}
@if ($errors->any())
<script>
    let errorMessages = '';
    @foreach ($errors->all() as $error)
        errorMessages += '{{ $error }}\n';
    @endforeach
    alert(errorMessages);
</script>
@endif

@php
    $user = auth()->user();
    $canEdit = false;
    if($user->role == 0){ // Admin
        if($news->user_id == $user->user_id) $canEdit = true;
    } elseif($user->role == 2){ // Chủ thầu
        if($news->user_id == $user->user_id) $canEdit = true;
        elseif($news->user && $news->user->manager_id == $user->user_id) $canEdit = true;
    } elseif($user->role == 3){ // Nhân viên
        if($news->user_id == $user->user_id) $canEdit = true;
        elseif($news->user && $news->user->user_id == $user->manager_id) $canEdit = true;
    }
@endphp

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-tin-tuc') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn">
        @if($canEdit)
            <a style="margin-right: 10px;" href="#" class="update-btn" id="submitNews">Cập nhật tin tức</a>
            <a class="update-btn" href="#" id="addContentRow">Thêm nội dung</a>
        @endif
    </div>
</div>

<form id="newsForm" method="POST" action="{{ route('update.news', $news->news_id) }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <table id="ListCustomers">
        <thead>
            <tr>
                <th>Loại tin tức</th>
                <th>Tiêu đề bài đăng</th>
                <th>Nội dung</th>
                <th>Ảnh tin tức</th>
                <th>Chú thích ảnh</th>
                @if($canEdit)
                    <th class="optional-col" style="{{ count($news->contents) > 1 ? '' : 'display:none;' }}">Tùy chọn</th>
                @endif
            </tr>
        </thead>
        <tbody id="contentBody">
            @foreach($news->contents as $index => $content)
            <tr data-id="{{ $content->news_content_id }}">
                @if($loop->first)
                <td rowspan="{{ count($news->contents) }}">
                    <select name="news_type_id" required {{ $canEdit ? '' : 'disabled' }}>
                        @foreach($types as $type)
                        <option value="{{ $type->news_type_id }}" {{ $news->news_type_id == $type->news_type_id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td rowspan="{{ count($news->contents) }}">
                    <textarea name="title" rows="1" required {{ $canEdit ? '' : 'readonly' }}>{{ old('title', $news->title) }}</textarea>
                </td>
                @endif
                <td><textarea name="content[]" rows="1" required {{ $canEdit ? '' : 'readonly' }}>{{ old('content.' . $index, $content->content) }}</textarea></td>
                <td>
                    @if($canEdit)
                        <div class="news-image-preview" style="{{ $content->image ? '' : 'display:none;' }}">
                            @if($content->image)
                                <img src="{{ asset($content->image) }}">
                            @endif
                        </div>
                        <input type="file" name="image[]" accept="image/*" class="custom-file-input">
                        @if($content->image)
                            <button type="button" class="delete-btn" data-id="{{ $content->news_content_id }}">Xóa</button>
                        @else
                            <button type="button" class="delete-btn" style="display:none;">Xóa</button>
                        @endif
                    @else
                        @if($content->image)
                            <img src="{{ asset($content->image) }}">
                        @endif
                    @endif
                </td>
                <td>
                    <textarea name="note[]" rows="1" {{ $canEdit ? '' : 'readonly' }}>{{ old('note.' . $index, $content->note) }}</textarea>
                </td>
                @if($canEdit)
                <td>
                    <button type="button" class="delete-row-btn" style="{{ $loop->first ? 'display:none;' : '' }}" data-id="{{ $content->news_content_id }}">Xóa</button>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

<script>
const addContentBtn = document.getElementById('addContentRow');
const contentBody = document.getElementById('contentBody');
const optionalCol = document.querySelector('.optional-col');

@if(!$canEdit)
document.querySelectorAll('#newsForm input, #newsForm textarea, #newsForm select, #newsForm button.update-btn')
    .forEach(el => { el.disabled = true; });
@endif

function autoResize(el){
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}
document.querySelectorAll('#contentBody textarea').forEach(el => {
    autoResize(el);
    el.addEventListener('input', e => autoResize(e.target));
});

@if($canEdit)
// Xử lý preview ảnh, xóa ảnh và xóa row
contentBody.addEventListener('change', function(e){
    if(e.target.classList.contains('custom-file-input')){
        const file = e.target.files[0];
        const td = e.target.closest('td');
        const previewDiv = td.querySelector('.news-image-preview');
        let previewImg = previewDiv.querySelector('img');
        const deleteBtn = td.querySelector('.delete-btn');
        if(!previewImg){ previewImg = document.createElement('img'); previewDiv.appendChild(previewImg); }
        if(file){ previewImg.src = URL.createObjectURL(file); previewDiv.style.display = 'block'; deleteBtn.style.display = 'inline-block'; }
        else { previewImg.src = ''; previewDiv.style.display = 'none'; deleteBtn.style.display = 'none'; }
    }
});

contentBody.addEventListener('click', function(e){
    const target = e.target;
    if(target.classList.contains('delete-btn')){
        e.preventDefault();
        const td = target.closest('td');
        const fileInput = td.querySelector('.custom-file-input');
        const previewDiv = td.querySelector('.news-image-preview');
        const contentId = target.getAttribute('data-id');
        if(contentId){
            if(confirm("Bạn có chắc muốn xóa ảnh này?")){
                fetch(`/admin/quan-ly-tin-tuc/xoa-anh/${contentId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(res => res.json())
                  .then(data => { if(data.success){ fileInput.value=''; previewDiv.style.display='none'; target.style.display='none'; } else { alert('Xóa ảnh không thành công!'); } })
                  .catch(()=>alert('Lỗi kết nối khi xóa ảnh!'));
            }
        } else { fileInput.value=''; previewDiv.style.display='none'; target.style.display='none'; }
    }

    if(target.classList.contains('delete-row-btn')){
        e.preventDefault();
        const row = target.closest('tr');
        const contentId = target.getAttribute('data-id');
        if(contentId){
            if(confirm("Bạn có chắc muốn xóa nội dung này?")){
                fetch(`/admin/quan-ly-tin-tuc/xoa-noi-dung/${contentId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(res => res.json())
                  .then(data => { if(data.success){ row.remove(); adjustRowSpan(); } else { alert('Xóa nội dung không thành công!'); } })
                  .catch(()=>alert('Lỗi kết nối khi xóa nội dung!'));
            }
        } else { row.remove(); adjustRowSpan(); }
    }
});

addContentBtn.addEventListener('click', function(e){
    e.preventDefault();
    optionalCol.style.display = '';
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><textarea name="content[]" rows="1" required></textarea></td>
        <td>
            <div class="news-image-preview" style="display:none;"><img></div>
            <input type="file" name="image[]" accept="image/*" class="custom-file-input">
            <button type="button" class="delete-btn" style="display:none;">Xóa</button>
        </td>
        <td><textarea name="note[]" rows="1"></textarea></td>
        <td><button type="button" class="delete-row-btn">Xóa</button></td>
    `;
    contentBody.appendChild(newRow);
    adjustRowSpan();
    newRow.querySelectorAll('textarea').forEach(el => {
        autoResize(el);
        el.addEventListener('input', e => autoResize(e.target));
    });
});

document.getElementById('submitNews').addEventListener('click', function(e){
    e.preventDefault();
    document.getElementById('newsForm').submit();
});
@endif

function adjustRowSpan(){
    const rows = contentBody.querySelectorAll('tr');
    if(rows.length === 0) return;
    const firstRow = rows[0];
    const rowSpan = rows.length;
    if(firstRow.cells[0]) firstRow.cells[0].rowSpan = rowSpan;
    if(firstRow.cells[1]) firstRow.cells[1].rowSpan = rowSpan;
    if(optionalCol) optionalCol.style.display = rowSpan > 1 ? '' : 'none';
    const firstRowDeleteBtn = firstRow.querySelector('.delete-row-btn');
    if(firstRowDeleteBtn) firstRowDeleteBtn.style.display = 'none';
}
</script>
@endsection
