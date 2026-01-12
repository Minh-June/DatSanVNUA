

<?php $__env->startSection('title', 'Cập nhật thông tin sản phẩm'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>

<?php if($errors->any()): ?>
    <script>alert("<?php echo e($errors->first()); ?>");</script>
<?php endif; ?>

<h2>Cập nhật thông tin sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-san-pham', $store->store_id)); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn">
        <button type="submit" form="productForm" style="margin-right:10px;" class="update-btn">Cập nhật thông tin</button>
        <a class="update-btn" href="#" id="addContentRow">Thêm ảnh sản phẩm</a>
    </div>
</div>

<form id="productForm" method="POST" action="<?php echo e(route('cap-nhat-san-pham', $product->product_id)); ?>" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('POST'); ?>

    
    <table id="ListCustomers">
        <thead>
            <tr>
                <th>Loại SP</th>
                <th>Sản phẩm</th>
                <th>Size</th>
                <th>Mô tả</th>
                <th>Giá tiền (đ)</th>
                <th>Số lượng</th>
                <th>Hình Ảnh</th>
                <th class="optional-col" style="<?php echo e(count($product->images) > 1 ? '' : 'display:none;'); ?>">Tùy chọn</th>
            </tr>
        </thead>
        <tbody id="contentBody">
            <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr data-id="<?php echo e($img->product_image_id); ?>">
                <?php if($loop->first): ?>
                <td rowspan="<?php echo e(count($product->images)); ?>">
                    <select name="product_type_id" required>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->product_type_id); ?>" <?php echo e($type->product_type_id == $product->product_type_id ? 'selected' : ''); ?>>
                                <?php echo e($type->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>

                <td rowspan="<?php echo e(count($product->images)); ?>">
                    <textarea name="name" rows="1" required oninput="autoResize(this)"><?php echo e(old('name', $product->name)); ?></textarea>
                </td>

                <td rowspan="<?php echo e(count($product->images)); ?>">
                    <?php
                        $hasSize = $productSizes->count() > 0;
                    ?>

                    <select name="product_size_id" id="productSizeSelect">
                        <option value="" <?php echo e(!$hasSize ? 'selected' : ''); ?>>Không</option>
                        <option value="1" <?php echo e($hasSize ? 'selected' : ''); ?>>Có</option>
                    </select>
                </td>

                <td rowspan="<?php echo e(count($product->images)); ?>">
                    <textarea name="description[]" rows="1" required oninput="autoResize(this)"><?php echo e(old('description.0', $product->description)); ?></textarea>
                </td>

                <td rowspan="<?php echo e(count($product->images)); ?>">
                    <input type="number" name="price" min="0" 
                        value="<?php echo e($product->price && $product->price > 0 ? $product->price : ''); ?>">
                </td>

                <td rowspan="<?php echo e(count($product->images)); ?>">
                    <input type="number" name="quantity" min="1" 
                        value="<?php echo e($product->quantity && $product->quantity > 0 ? $product->quantity : ''); ?>">
                </td>
                <?php endif; ?>

                <td>
                    <div class="product-image-preview" style="<?php echo e($img->image ? '' : 'display:none;'); ?>">
                        <?php if($img->image): ?>
                            <img src="<?php echo e(asset('storage/'.$img->image)); ?>" data-old="<?php echo e($img->image); ?>" data-id="<?php echo e($img->product_image_id); ?>">
                        <?php else: ?>
                            <img style="display:none;">
                        <?php endif; ?>
                    </div>
                    <input type="file" name="image[]" accept="image/*" class="custom-file-input">
                    <button type="button" class="delete-btn" <?php echo e($img->image ? '' : 'style=display:none;'); ?>>Xóa</button>
                </td>

                <td>
                    <button type="button" class="delete-row-btn" <?php echo e($loop->first ? 'style=display:none;' : ''); ?> data-id="<?php echo e($img->product_image_id); ?>">Xóa</button>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    
    <div id="sizeManagementSection">
        <p style="text-align:center; margin:0; font-size:22px; font-weight:bold;">Quản lý Size</p>

        <div class="admin-top-bar">
            <div class="admin-search"></div>

            <div class="admin-add-btn">
                <button type="button" id="addSizeBtn" style="margin-right:10px" class="update-btn">Thêm Size</button>
                <a href="<?php echo e(route('quan-ly-size', $product->product_id)); ?>" class="update-btn">Danh sách các loại Size</a>
            </div>
        </div>

        <table id="sizeTable">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Size</th>
                    <th>Giá tiền (đ)</th>
                    <th>Số lượng</th>
                    <th>Tùy chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $productSizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pSize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i + 1); ?></td>
                    <td>
                        <select name="sizes[<?php echo e($i); ?>][name]" required>
                            <option value="">Chọn</option>
                            <?php $__currentLoopData = $productSizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->name); ?>"
                                    data-price="<?php echo e($s->price); ?>"
                                    data-quantity="<?php echo e($s->quantity); ?>"
                                    <?php echo e($s->name == $pSize->name ? 'selected' : ''); ?>>
                                    <?php echo e($s->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="sizes[<?php echo e($i); ?>][price]" value="<?php echo e($pSize->price); ?>" readonly>
                    </td>
                    <td>
                        <input type="number" name="sizes[<?php echo e($i); ?>][quantity]" value="<?php echo e($pSize->quantity); ?>" readonly>
                    </td>
                    <td>
                        <button type="button" class="delete-size-btn">Xóa</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</form>

<script>
const addContentBtn = document.getElementById('addContentRow');
const contentBody = document.getElementById('contentBody');
const optionalCol = document.querySelector('.optional-col');

// --- Tự động điền giá & số lượng khi chọn size, khóa input + màu xám ---
document.addEventListener('DOMContentLoaded', function() {
    const sizeSelect = document.getElementById('productSizeSelect');
    const priceInput = document.querySelector('input[name="price"]');
    const quantityInput = document.querySelector('input[name="quantity"]');

    // Lưu giá & số lượng mặc định (gốc) để dùng khi chọn "Không có"
    const defaultPrice = priceInput.value;
    const defaultQuantity = quantityInput.value;

    function updatePriceQuantity(){
        const selected = sizeSelect.options[sizeSelect.selectedIndex];

        if(selected.value === ''){
            // Chọn "Không có" → hiển thị giá & số lượng gốc, mở input
            priceInput.value = defaultPrice;
            quantityInput.value = defaultQuantity;
            priceInput.readOnly = false;
            quantityInput.readOnly = false;
            priceInput.style.backgroundColor = '';
            quantityInput.style.backgroundColor = '';
        } else {
            // Chọn size → điền giá & số lượng theo data-* + khóa + màu xám
            priceInput.value = selected.dataset.price || 0;
            quantityInput.value = selected.dataset.quantity || 0;
            priceInput.readOnly = true;
            quantityInput.readOnly = true;
            priceInput.style.backgroundColor = '#e9ecef';
            quantityInput.style.backgroundColor = '#e9ecef';
        }
    }

    // Khi load lần đầu
    updatePriceQuantity();

    // Khi thay đổi size
    sizeSelect.addEventListener('change', updatePriceQuantity);
});

function autoResize(el){
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}

document.querySelectorAll('#contentBody textarea').forEach(el => {
    autoResize(el);
    el.addEventListener('input', e => autoResize(e.target));
});

contentBody.addEventListener('change', function(e){
    if(e.target.classList.contains('custom-file-input')){
        const file = e.target.files[0];
        const td = e.target.closest('td');
        const previewDiv = td.querySelector('.product-image-preview');
        const img = previewDiv.querySelector('img');
        const deleteBtn = td.querySelector('.delete-btn');

        if(file){
            img.src = URL.createObjectURL(file);
            previewDiv.style.display = 'block';
            deleteBtn.style.display = 'inline-block';
        }
    }
});

contentBody.addEventListener('click', function(e){
    const target = e.target;

    if(target.classList.contains('delete-btn')){
        const td = target.closest('td');
        const img = td.querySelector('img');
        const fileInput = td.querySelector('.custom-file-input');

        if(img && img.getAttribute('data-id')){
            const deletedInput = document.createElement('input');
            deletedInput.type = 'hidden';
            deletedInput.name = 'deleted_images[]';
            deletedInput.value = img.getAttribute('data-id');
            document.getElementById('productForm').appendChild(deletedInput);
        }

        img.src = '';
        td.querySelector('.product-image-preview').style.display = 'none';
        target.style.display = 'none';
        fileInput.value = '';
    }

    if(target.classList.contains('delete-row-btn')){
        const row = target.closest('tr');
        const img = row.querySelector('img');
        if(img && img.getAttribute('data-id')){
            const deletedRecordInput = document.createElement('input');
            deletedRecordInput.type = 'hidden';
            deletedRecordInput.name = 'deleted_records[]';
            deletedRecordInput.value = img.getAttribute('data-id');
            document.getElementById('productForm').appendChild(deletedRecordInput);
        }
        row.remove();
        adjustRowSpan();
    }
});

addContentBtn.addEventListener('click', function(e){
    e.preventDefault();

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>
            <div class="product-image-preview" style="display:none;">
                <img>
            </div>
            <input type="file" name="image[]" accept="image/*" class="custom-file-input">
            <button type="button" class="delete-btn" style="display:none;">Xóa</button>
        </td>
        <td><button type="button" class="delete-row-btn">Xóa</button></td>
    `;
    contentBody.appendChild(newRow);
    adjustRowSpan();
});

function adjustRowSpan(){
    const rows = contentBody.querySelectorAll('tr');
    if(rows.length === 0) return;

    const firstRow = rows[0];
    const rowSpan = rows.length;

    for(let i=0; i<6; i++){
        if(firstRow.cells[i]) firstRow.cells[i].rowSpan = rowSpan;
    }

    if(rowSpan > 1){
        optionalCol.style.display = '';
        rows.forEach(row => {
            const tdOptional = row.querySelector('td:last-child');
            if(!tdOptional){
                const td = document.createElement('td');
                td.innerHTML = `<button type="button" class="delete-row-btn">Xóa</button>`;
                row.appendChild(td);
            } else {
                tdOptional.style.display = '';
            }
        });
    } else {
        optionalCol.style.display = 'none';
        rows.forEach(row => {
            const tdOptional = row.querySelector('td:last-child');
            if(tdOptional) tdOptional.remove();
        });
    }

    const firstRowDeleteBtn = firstRow.querySelector('.delete-row-btn');
    if(firstRowDeleteBtn) firstRowDeleteBtn.style.display = 'none';
}

// --- Size management ---
const addSizeBtn = document.getElementById('addSizeBtn');
const sizeTableBody = document.querySelector('#sizeTable tbody');

// --- Hàm điền giá & số lượng khi chọn size ---
function updateRowPriceQuantity(select) {
    const row = select.closest('tr');
    const priceInput = row.querySelector('input[name*="[price]"]');
    const quantityInput = row.querySelector('input[name*="[quantity]"]');
    const selectedOption = select.options[select.selectedIndex];

    if (selectedOption.value === '') { // Chọn "Chọn"
        priceInput.value = 0;
        quantityInput.value = 0;
        priceInput.readOnly = false;
        quantityInput.readOnly = false;
        priceInput.style.backgroundColor = '';
        quantityInput.style.backgroundColor = '';
    } else {
        priceInput.value = selectedOption.dataset.price || 0;
        quantityInput.value = selectedOption.dataset.quantity || 0;
        priceInput.readOnly = true;
        quantityInput.readOnly = true;
        priceInput.style.backgroundColor = '#e9ecef';
        quantityInput.style.backgroundColor = '#e9ecef';
    }

    // ---- Khóa hoặc mở option "Chọn" ----
    const firstOption = select.querySelector('option[value=""]');
    if(selectedOption.value !== '') {
        firstOption.disabled = true;  // khóa "Chọn"
    } else {
        firstOption.disabled = false; // mở "Chọn"
    }
}

// --- Khởi tạo các row hiện tại ---
sizeTableBody.querySelectorAll('tr').forEach(row => {
    const select = row.querySelector('select');
    if(select) select.dataset.old = select.value;

    // Gán data-price & data-quantity cho từng option
    select.querySelectorAll('option').forEach(option => {
        const sizeName = option.value;
        <?php $__currentLoopData = $productSizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            if(sizeName === '<?php echo e($s->name); ?>'){
                option.dataset.price = '<?php echo e($s->price); ?>';
                option.dataset.quantity = '<?php echo e($s->quantity); ?>';
            }
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });

    // Khi load lần đầu
    updateRowPriceQuantity(select);
    hiddenDuplicateSizes();

    // Khi chọn size
    select.addEventListener('change', function() {
        updateRowPriceQuantity(this);
        hiddenDuplicateSizes();
    });
});

// --- Cập nhật STT ---
function updateSTT() {
    sizeTableBody.querySelectorAll('tr').forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

// --- Thêm size mới ---
addSizeBtn.addEventListener('click', function(){
    const index = sizeTableBody.querySelectorAll('tr').length;
    const row = document.createElement('tr');

    let options = `<option value="">Chọn</option>`;
    <?php $__currentLoopData = $productSizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        options += `
            <option value="<?php echo e($s->name); ?>"
                data-price="<?php echo e($s->price); ?>"
                data-quantity="<?php echo e($s->quantity); ?>">
                <?php echo e($s->name); ?>

            </option>`;
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    row.innerHTML = `
        <td>${index + 1}</td>
        <td>
            <select name="sizes[${index}][name]" required>
                ${options}
            </select>
        </td>
        <td><input type="number" name="sizes[${index}][price]" min="0" required readonly style="background-color:#e9ecef;"></td>
        <td><input type="number" name="sizes[${index}][quantity]" min="0" required readonly style="background-color:#e9ecef;"></td>
        <td><button type="button" class="delete-size-btn">Xóa</button></td>
    `;

    const select = row.querySelector('select');
    select.dataset.old = '';
    select.addEventListener('change', function() {
        updateRowPriceQuantity(this);
        hiddenDuplicateSizes();
    });

    sizeTableBody.appendChild(row);
    updateSTT();
    hiddenDuplicateSizes();
});

// --- Xóa size ---
sizeTableBody.addEventListener('click', function(e){
    if(e.target.classList.contains('delete-size-btn')){
        const row = e.target.closest('tr');
        const select = row.querySelector('select');
        const sizeName = select.value;

        if(!confirm('Bạn có chắc chắn muốn xóa size này ?')) return;

        if(sizeName){
            const deletedInput = document.createElement('input');
            deletedInput.type = 'hidden';
            deletedInput.name = 'deleted_sizes[]';
            deletedInput.value = sizeName;
            document.getElementById('productForm').appendChild(deletedInput);
        }

        row.remove();
        updateSTT();
        hiddenDuplicateSizes();
    }
});

// --- Khi submit form, gửi deleted_sizes nếu đổi tên ---
document.getElementById('productForm').addEventListener('submit', function(){
    sizeTableBody.querySelectorAll('tr').forEach(row => {
        const select = row.querySelector('select');
        if(select && select.dataset.old && select.dataset.old !== select.value){
            const deletedInput = document.createElement('input');
            deletedInput.type = 'hidden';
            deletedInput.name = 'deleted_sizes[]';
            deletedInput.value = select.dataset.old;
            this.appendChild(deletedInput);

            select.dataset.old = select.value; // cập nhật data-old mới
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const sizeSelect = document.getElementById('productSizeSelect');
    const priceTh = document.querySelector('th:nth-child(5)');   // cột Giá tiền
    const quantityTh = document.querySelector('th:nth-child(6)'); // cột Số lượng
    const priceTd = document.querySelector('input[name="price"]').closest('td');
    const quantityTd = document.querySelector('input[name="quantity"]').closest('td');

    // --- CẬP NHẬT HÀM togglePriceQuantity ---
    function togglePriceQuantity() {
        const hasSize = sizeSelect.value === '1'; // true nếu có size
        
        priceTh.style.display = hasSize ? 'none' : '';
        quantityTh.style.display = hasSize ? 'none' : '';
        priceTd.style.display = hasSize ? 'none' : '';
        quantityTd.style.display = hasSize ? 'none' : '';

        document.getElementById('sizeManagementSection').style.display = hasSize ? '' : 'none';
    }

    // Load lần đầu
    togglePriceQuantity();

    // Khi thay đổi select
    sizeSelect.addEventListener('change', togglePriceQuantity);
});

// --- Ẩn các size đã chọn trong các select khác ---
function hiddenDuplicateSizes() {
    const selects = document.querySelectorAll('#sizeTable select');

    // Lấy các size đang được chọn
    const selectedValues = [];
    selects.forEach(select => {
        if (select.value !== '') {
            selectedValues.push(select.value);
        }
    });

    selects.forEach(select => {
        const currentValue = select.value;

        select.querySelectorAll('option').forEach(option => {
            // Reset trước
            option.hidden = false;

            if (
                option.value !== '' &&
                selectedValues.includes(option.value) &&
                option.value !== currentValue
            ) {
                option.hidden = true;
            }
        });
    });
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/products/update.blade.php ENDPATH**/ ?>