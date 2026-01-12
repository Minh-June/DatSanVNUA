@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@if ($errors->any())
    <script>alert("{{ $errors->first() }}");</script>
@endif

<h2>Thêm sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-san-pham', $store->store_id) }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn">
        <a href="#" class="update-btn" style="margin-right:10px;" id="submitProduct">Lưu sản phẩm</a>
        <a class="update-btn" href="#" id="addContentRow">Thêm ảnh sản phẩm</a>
    </div>
</div>

<form id="productForm" method="POST" action="{{ route('luu-san-pham', $store->store_id) }}" enctype="multipart/form-data">
    @csrf
    <table id="ListCustomers">
        <thead>
            <tr>
                <th>Loại sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Mô tả</th>
                <th>Hình Ảnh</th>
            </tr>
        </thead>
        <tbody id="contentBody">
            <tr>
                <td>
                    <select name="product_type_id" required>
                        <option value="" disabled selected>Chọn</option>
                        @foreach($types as $type)
                            <option value="{{ $type->product_type_id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <textarea name="name" rows="1" required oninput="autoResize(this)"></textarea>
                </td>
                <td>
                    <textarea name="description[]" rows="1" required oninput="autoResize(this)"></textarea>
                </td>
                <td class="image-cell">
                    <div class="image-item">
                        <div class="product-image-preview" style="display:none; text-align:center; margin-bottom:5px;">
                            <img src="" alt="Preview">
                        </div>
                        <input type="file" name="image[]" accept="image/*" class="custom-file-input" required>
                        <button type="button" class="delete-btn" style="display:none;">Xóa</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<script>
const addContentBtn = document.getElementById('addContentRow');
const contentBody = document.getElementById('contentBody');

function autoResize(el){
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}

// Resize textarea
document.querySelectorAll('#contentBody textarea').forEach(el => {
    autoResize(el);
    el.addEventListener('input', e => autoResize(e.target));
});

// Preview ảnh
contentBody.addEventListener('change', e => {
    if(e.target.classList.contains('custom-file-input')){
        const file = e.target.files[0];
        const td = e.target.closest('.image-item');
        const previewDiv = td.querySelector('.product-image-preview');
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

// Xóa file
contentBody.addEventListener('click', e => {
    if(e.target.classList.contains('delete-btn')){
        const td = e.target.closest('.image-item');
        td.querySelector('.custom-file-input').value = '';
        td.querySelector('.product-image-preview').style.display = 'none';
        e.target.style.display = 'none';
    }
});

// Thêm ảnh mới
addContentBtn.addEventListener('click', e => {
    e.preventDefault();
    const firstRow = contentBody.rows[0];
    const imageCell = firstRow.querySelector('.image-cell');

    const newImageDiv = document.createElement('div');
    newImageDiv.classList.add('image-item');
    newImageDiv.style.marginTop = '10px';
    newImageDiv.innerHTML = `
        <div class="product-image-preview" style="display:none; text-align:center; margin-bottom:5px;">
            <img src="" alt="Preview">
        </div>
        <input type="file" name="image[]" accept="image/*" class="custom-file-input" required>
        <button type="button" class="delete-btn" style="display:none;">Xóa</button>
    `;
    imageCell.appendChild(newImageDiv);
});

document.getElementById('submitProduct').addEventListener('click', e => {
    e.preventDefault();

    const form = document.getElementById('productForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json'
        }
    })
    .then(async res => {
        const data = await res.json();

        // ✅ Thành công
        if (res.ok && data.success) {
            alert(data.message);
            window.location.href = data.redirect;
            return;
        }

        // ❌ Lỗi validate (422)
        if (res.status === 422 && data.errors) {
            const firstError = Object.values(data.errors)[0][0];
            alert(firstError);
            return;
        }

        // ❌ Lỗi khác
        alert(data.message ?? 'Có lỗi xảy ra!');
    })
    .catch(() => {
        alert('Không thể gửi dữ liệu. Vui lòng thử lại.');
    });
});
</script>

@endsection
