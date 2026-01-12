@extends('layouts.admin')

@section('title', 'Thêm tin tức mới')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if ($errors->any())
        <script>alert("{{ $errors->first() }}");</script>
    @endif
    
<h2>Thêm bài đăng tin tức mới</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-tin-tuc') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
    
    <div class="admin-add-btn">
        <a style="margin-right: 10px;" href="#" class="update-btn" id="submitNews">Đăng tin tức</a>
        <a class="update-btn" href="#" id="addContentRow">Thêm nội dung</a>
    </div>
</div>

<form id="newsForm" method="POST" action="{{ route('luu-tin-tuc') }}" enctype="multipart/form-data">
    @csrf
    <table id="ListCustomers">
        <thead>
            <tr>
                <th>Loại tin tức</th>
                <th>Tiêu đề bài đăng</th>
                <th>Nội dung</th>
                <th>Ảnh tin tức</th>
                <th>Chú thích ảnh</th>
                <th class="optional-col" style="display:none;">Tùy chọn</th>
            </tr>
        </thead>
        <tbody id="contentBody">
            <tr>
                <td rowspan="1">
                    <select name="news_type_id" required>
                        <option value="" disabled {{ old('news_type_id') ? '' : 'selected' }}>Loại tin tức</option>
                        @foreach($types as $type)
                            <option value="{{ $type->news_type_id }}" {{ old('news_type_id') == $type->news_type_id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td rowspan="1">
                    <textarea name="title" rows="1" required>{{ old('title') }}</textarea>
                </td>
                <td><textarea name="content[]" rows="1" required>{{ old('content.0') }}</textarea></td>
                <td>
                    <div class="news-image-preview" style="display:none; text-align:center;">
                        <img src="" alt="Preview">
                    </div>
                    <input type="file" name="image[]" accept="image/*" class="custom-file-input">
                    <button type="button" class="delete-btn" style="display:none;">Xóa</button>
                </td>
                <td><textarea name="note[]" rows="1">{{ old('note.0') }}</textarea></td>
            </tr>
        </tbody>
    </table>
</form>

<script>
const addContentBtn = document.getElementById('addContentRow');
const contentBody = document.getElementById('contentBody');
const optionalCol = document.querySelector('.optional-col');

// Hàm auto-resize textarea và input
function autoResize(el){
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}

// Áp dụng auto-resize cho tất cả textarea hiện tại
document.querySelectorAll('#contentBody textarea').forEach(el => {
    autoResize(el);
    el.addEventListener('input', e => autoResize(e.target));
});

// Xử lý preview ảnh khi chọn file
contentBody.addEventListener('change', e => {
    if(e.target.classList.contains('custom-file-input')){
        const file = e.target.files[0];
        const td = e.target.closest('td');
        const previewDiv = td.querySelector('.news-image-preview');
        const img = previewDiv.querySelector('img');
        const deleteBtn = td.querySelector('.delete-btn');

        if(file){
            img.src = URL.createObjectURL(file);
            previewDiv.style.display = 'block';
            deleteBtn.style.display = 'inline-block';
        } else {
            previewDiv.style.display = 'none';
            deleteBtn.style.display = 'none';
        }
    }
});

// Xóa ảnh hoặc dòng nội dung
contentBody.addEventListener('click', e => {
    const target = e.target;
    if(target.classList.contains('delete-btn')){
        const td = target.closest('td');
        const fileInput = td.querySelector('.custom-file-input');
        fileInput.value = '';
        td.querySelector('.news-image-preview').style.display = 'none';
        target.style.display = 'none';
    }

    if(target.classList.contains('delete-row-btn')){
        e.preventDefault();
        target.closest('tr').remove();
        const firstRow = contentBody.rows[0];
        if(firstRow){
            firstRow.cells[0].rowSpan = contentBody.rows.length;
            firstRow.cells[1].rowSpan = contentBody.rows.length;
        }
        if(contentBody.rows.length === 1) optionalCol.style.display = 'none';
    }
});

// Thêm dòng nội dung mới
addContentBtn.addEventListener('click', e => {
    e.preventDefault();
    optionalCol.style.display = '';
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><textarea name="content[]" rows="1"></textarea></td>
        <td>
            <div class="news-image-preview" style="display:none; text-align:center;">
                <img src="" alt="Preview">
            </div>
            <input type="file" name="image[]" accept="image/*" class="custom-file-input">
            <button type="button" class="delete-btn" style="display:none;">Xóa</button>
        </td>
        <td><textarea name="note[]" rows="1"></textarea></td>
        <td><button type="button" class="delete-row-btn">Xóa</button></td>
    `;
    contentBody.appendChild(newRow);

    // Auto-resize cho textarea và note mới
    newRow.querySelectorAll('textarea').forEach(el => {
        autoResize(el);
        el.addEventListener('input', e => autoResize(e.target));
    });

    const firstRow = contentBody.rows[0];
    firstRow.cells[0].rowSpan = contentBody.rows.length;
    firstRow.cells[1].rowSpan = contentBody.rows.length;
});

// Submit form sau khi kiểm tra
document.getElementById('submitNews').addEventListener('click', e => {
    e.preventDefault();
    const form = document.getElementById('newsForm');

    // Lấy giá trị các trường
    const newsType = form.querySelector('select[name="news_type_id"]').value;
    const title = form.querySelector('textarea[name="title"]').value.trim();
    const firstContent = form.querySelector('textarea[name="content[]"]').value.trim();

    // Kiểm tra loại tin tức
    if(!newsType){
        alert('Vui lòng chọn loại tin tức !');
        return;
    }

    // Kiểm tra tiêu đề
    if(!title){
        alert('Vui lòng nhập tiêu đề bài đăng !');
        return;
    }

    // Kiểm tra nội dung đầu tiên
    if(!firstContent){
        alert('Vui lòng nhập nội dung đầu tiên !');
        return;
    }

    // Nếu hợp lệ → submit form
    form.submit();
});
</script>
@endsection
