// ---------------- Biến toàn cục ----------------
let selectedTimes = [];    // Danh sách khung giờ đã chọn (vd: "06:00 - 07:00")
let selectedPrices = [];  // Giá tương ứng với từng khung giờ
let totalPrice = 0;       // Tổng tiền của tất cả khung giờ đã chọn
// 3 biến này LUÔN đi cùng nhau theo cùng thứ tự

// ---------------- Lấy giá theo thời gian ----------------
function getPriceByTimeRange(range, baseBtn = null) {

    // Lấy ngày người dùng đang chọn (yyyy-mm-dd)
    const dateStr = document.getElementById('date').value;

    // Xác định thứ trong tuần
    // 0 = Chủ nhật, 1 = Thứ 2, ..., 6 = Thứ 7
    const day = new Date(dateStr).getDay();

    // Tách giờ bắt đầu và giờ kết thúc từ chuỗi "HH:mm - HH:mm"
    const [start, end] = range.split(' - ');

    // ===================== TÌM SLOT GỐC =====================
    // Nếu không truyền baseBtn thì tự tìm slot gốc trong danh sách btn-time
    // Slot gốc là slot chứa giờ start
    if (!baseBtn) {
        baseBtn = Array.from(document.querySelectorAll('.btn-time'))
            .find(b => {
                const [s, e] = b.dataset.time.split(' - ');
                // start nằm trong khoảng [s, e)
                return start >= s && start < e;
            });
    }

    // Nếu không tìm được slot gốc → không tính được giá
    if (!baseBtn) return 0;

    // ===================== LẤY GIÁ GỐC =====================
    // Ngày thường (Thứ 2 → Thứ 6) → dùng giá weekday
    // Cuối tuần (Thứ 7, CN) → dùng giá weekend
    const basePrice =
        (day >= 1 && day <= 5)
            ? parseInt(baseBtn.dataset.priceWeekday) || 0
            : parseInt(baseBtn.dataset.priceWeekend) || 0;

    // ===================== TÍNH SỐ PHÚT SLOT GỐC =====================
    const [mainStartStr, mainEndStr] = baseBtn.dataset.time.split(' - ');

    const mainStart = new Date(`1970-01-01T${mainStartStr}:00`);
    const mainEnd   = new Date(`1970-01-01T${mainEndStr}:00`);

    // Tổng số phút của slot gốc (vd: 60 phút)
    const mainMinutes = (mainEnd - mainStart) / 60000;

    // ===================== TÍNH SỐ PHÚT KHUNG ĐƯỢC CHỌN =====================
    const startDate = new Date(`1970-01-01T${start}:00`);
    const endDate   = new Date(`1970-01-01T${end}:00`);

    // Số phút người dùng chọn (vd: 30 phút)
    const minutes = (endDate - startDate) / 60000;

    // Nếu chọn sai (end <= start) → không tính tiền
    if (minutes <= 0) return 0;

    // ===================== TÍNH GIÁ THEO TỶ LỆ =====================
    // Ví dụ:
    // slot gốc: 60 phút = 120.000đ
    // chọn 30 phút → 120.000 * 30 / 60 = 60.000đ
    return Math.round(basePrice * minutes / mainMinutes);
}

// ---------------- Chọn / bỏ chọn khung giờ chính ----------------
function changeTimeSlot(btn) {

    // Nếu nút bị disable hoặc đã booked → không làm gì
    if (btn.disabled || btn.classList.contains('booked')) return;

    // Lấy wrapper chứa khung giờ + nút +
    const wrapper = btn.closest('.time-slot-wrapper');

    // Tách giờ bắt đầu và giờ kết thúc gốc
    const [start, originalEnd] = btn.dataset.time.split(' - ');

    // Nếu đã chọn extra (+30/+60) thì dùng currentEnd
    // nếu không có thì dùng giờ gốc
    const currentEnd = btn.dataset.currentEnd || originalEnd;

    // Lấy toàn bộ các nút khung giờ cùng hàng
    const allBtns = Array.from(wrapper.parentNode.querySelectorAll('.btn-time'));

    // Vị trí của nút hiện tại
    const idx = allBtns.indexOf(btn);

    // Nút liền trước và liền sau
    const prevBtn = idx > 0 ? allBtns[idx - 1] : null;
    const nextBtn = idx < allBtns.length - 1 ? allBtns[idx + 1] : null;

    // Kiểm tra slot trước / sau có đang được chọn không
    const prevSelected = prevBtn?.classList.contains('selected');
    const nextSelected = nextBtn?.classList.contains('selected');

    // ===================== TRƯỜNG HỢP 1: ĐANG CHỌN → BỎ CHỌN =====================
    if (btn.classList.contains('selected')) {

        // Tìm vị trí khung giờ trong mảng dữ liệu
        const removeIdx = selectedTimes.findIndex(t => t.startsWith(start));

        // Nếu tìm thấy → xóa khung giờ + giá tương ứng
        if (removeIdx !== -1) {
            selectedTimes.splice(removeIdx, 1);
            selectedPrices.splice(removeIdx, 1);
        }

        // Bỏ class selected và compact
        btn.classList.remove('selected', 'compact');
        btn.style.backgroundColor = '';

        // Nếu trước đó có chọn extra → reset về giờ gốc
        if (btn.dataset.currentEnd && btn.dataset.currentEnd !== originalEnd) {
            btn.dataset.currentEnd = originalEnd;
            btn.dataset.isClassic = "0";
            btn.textContent = `${start} - ${originalEnd}`;
        }

        // Ẩn nút + và danh sách +30/+60
        wrapper.querySelector('.btn-plus')?.classList.add('hidden');
        wrapper.querySelector('.extra-options')?.classList.add('hidden');

        // Nếu slot trước đang chọn → cập nhật lại nút + cho nó
        if (prevSelected) {
            updatePlusButton(
                prevBtn.closest('.time-slot-wrapper'),
                prevBtn
            );
        }

    // ===================== TRƯỜNG HỢP 2: CHƯA CHỌN → CHỌN MỚI =====================
    } else {

        // Tính giá cho khung giờ được chọn
        const price = getPriceByTimeRange(`${start} - ${currentEnd}`);

        // Lưu khung giờ và giá vào mảng
        selectedTimes.push(`${start} - ${currentEnd}`);
        selectedPrices.push(price);

        // Đánh dấu nút đang được chọn
        btn.classList.add('selected');

        // Nếu đứng 1 mình (không liền kề slot khác) → compact
        if (!prevSelected && !nextSelected && btn !== allBtns[allBtns.length - 1]) {
            btn.classList.add('compact');
        } else {
            btn.classList.remove('compact');
        }

        // Nếu slot trước đang chọn
        if (prevSelected) {
            // Slot trước không được compact nữa
            prevBtn.classList.remove('compact');

            // Ẩn nút + của slot trước
            prevBtn
                .closest('.time-slot-wrapper')
                .querySelector('.btn-plus')
                ?.classList.add('hidden');
        }

        // Cập nhật nút + cho slot hiện tại
        if (wrapper) updatePlusButton(wrapper, btn);
    }

    // ===================== CẬP NHẬT TỔNG TIỀN + INPUT ẨN =====================
    updateSelectedTimes();
}

// ---------------- Cập nhật nút + và compact ----------------
function updatePlusButton(wrapper, btn) {

    // Lấy toàn bộ các nút khung giờ trong cùng một hàng
    const allBtns = Array.from(wrapper.parentNode.querySelectorAll('.btn-time'));

    // Lấy nút + nằm cạnh khung giờ
    const plusBtn = wrapper.querySelector('.btn-plus');

    // Nếu không có nút + thì không cần xử lý
    if (!plusBtn) return;

    // Vị trí của khung giờ hiện tại trong danh sách
    const idx = allBtns.indexOf(btn);

    // Lấy khung giờ phía sau (nếu có)
    const nextBtn = idx < allBtns.length - 1 ? allBtns[idx + 1] : null;

    // Kiểm tra khung giờ phía sau:
    // - đã được chọn
    // - hoặc đã bị booked
    const nextSelectedOrBooked = nextBtn && (nextBtn.classList.contains('selected') || nextBtn.classList.contains('booked'));
    const lastBtn = allBtns[allBtns.length - 1];

    // ===================== XỬ LÝ NÚT + =====================
    // Nếu:
    // - đang là slot cuối
    // - hoặc slot sau đã chọn / booked
    // → không thể kéo dài → ẩn nút +
    if (btn === lastBtn || nextSelectedOrBooked) plusBtn.classList.add('hidden');
    else plusBtn.classList.remove('hidden');

    // ===================== XỬ LÝ COMPACT =====================
    // Khung giờ chỉ được compact khi:
    // - đang được chọn
    // - KHÔNG bị chặn phía sau
    // - KHÔNG phải slot cuối
    btn.classList.toggle('compact', btn.classList.contains('selected') && !nextSelectedOrBooked && btn !== lastBtn);
}

// ---------------- Chọn +30/+60 ----------------
function selectExtraTime(btn) {

    // Lấy wrapper chứa slot chính + nút +
    const wrapper = btn.closest('.time-slot-wrapper');

    // Lấy nút khung giờ chính
    const mainBtn = wrapper.querySelector('.btn-time');

    // Số phút cộng thêm (30 hoặc 60)
    const extraMin = parseInt(btn.dataset.extra);

    // Tách giờ bắt đầu và giờ kết thúc gốc của slot
    const [start, originalEnd] = mainBtn.dataset.time.split(' - ');

    // ===================== TRƯỜNG HỢP 1: BẤM LẠI → BỎ EXTRA =====================
    if (btn.classList.contains('active')) {

        // Trả giờ kết thúc về như ban đầu
        mainBtn.dataset.currentEnd = originalEnd;

        // Đánh dấu lại là khung chuẩn
        mainBtn.dataset.isClassic = "0";

        // Cập nhật lại text hiển thị
        mainBtn.textContent = `${start} - ${originalEnd}`;

        // Bỏ trạng thái active của nút extra
        btn.classList.remove('active');

        // Tìm slot trong mảng đã chọn
        const idx = selectedTimes.findIndex(t => t.startsWith(start));

        // Nếu tồn tại → cập nhật lại khung giờ và giá
        if (idx !== -1) {
            selectedTimes[idx]  = `${start} - ${originalEnd}`;
            selectedPrices[idx] = getPriceByTimeRange(`${start} - ${originalEnd}`);
        }

        // Cập nhật tổng tiền + input ẩn + UI
        updateSelectedTimes();
        return;
    }

    // ===================== TRƯỜNG HỢP 2: CHỌN EXTRA (+30 / +60) =====================
    // Bỏ active tất cả các nút extra khác trong cùng slot
    wrapper.querySelectorAll('.btn-extra.active').forEach(b => b.classList.remove('active'));

    // Tính giờ kết thúc mới = giờ gốc + extraMin phút
    mainBtn.dataset.currentEnd = new Date(new Date(`1970-01-01T${originalEnd}:00`).getTime() + extraMin * 60000)
                                    .toTimeString().slice(0,5);

    // Đánh dấu đây là khung không chuẩn
    mainBtn.dataset.isClassic = "1";

    // Cập nhật text hiển thị trên nút
    mainBtn.textContent = `${start} - ${mainBtn.dataset.currentEnd}`;

    // Tìm slot trong mảng đã chọn
    const idx = selectedTimes.findIndex(t => t.startsWith(start));

    // Tính lại giá cho khung giờ mới
    const newPrice = getPriceByTimeRange(`${start} - ${mainBtn.dataset.currentEnd}`);

    // Nếu slot đã tồn tại → cập nhật
    if (idx !== -1) {
        selectedTimes[idx] = `${start} - ${mainBtn.dataset.currentEnd}`;
        selectedPrices[idx] = newPrice;
    } else {    // Nếu slot chưa tồn tại → thêm mới
        selectedTimes.push(`${start} - ${mainBtn.dataset.currentEnd}`);
        selectedPrices.push(newPrice);
    }

    // Đánh dấu nút extra đang active
    btn.classList.add('active');

    // Cập nhật tổng tiền + input ẩn + UI
    updateSelectedTimes();
}

// ---------------- Bật / tắt ExtraOptions ----------------
function toggleExtraOptions(btn) {
    
    // Tìm thẻ cha gần nhất bao bọc 1 slot giờ
    // (thường chứa: btn-time, btn +, extra-options)
    const wrapper = btn.closest('.time-slot-wrapper');

    // Lấy khối chứa các nút +30 / +60
    const extraOpts = wrapper.querySelector('.extra-options');

    // Toggle class 'hidden'
    // - Nếu đang ẩn → hiện
    // - Nếu đang hiện → ẩn
    extraOpts.classList.toggle('hidden');

    // Toggle trạng thái active cho nút +
    // - Nếu extra-options đang hiện → nút + active
    // - Nếu extra-options bị ẩn → nút + không active
    btn.classList.toggle('active', !extraOpts.classList.contains('hidden'));
}

// ---------------- Khóa quá khứ + trùng lặp ----------------
function disableSlots() {

    // Lấy tất cả nút giờ chưa bị booked
    const allBtns = document.querySelectorAll('.btn-time:not(.booked)');

    // Lấy ngày người dùng chọn từ input #date
    const selectedDate = document.getElementById('date').value;

    // Ngày hôm nay dạng "yyyy-mm-dd"
    const today = new Date().toISOString().slice(0,10);

    // Thời điểm hiện tại
    const now = new Date();

    // ===================== XỬ LÝ SLOT ĐÃ CHỌN =====================
    // Tạo mảng chứa các khung giờ đã chọn + currentEnd (nếu kéo dài)
    const extended = [];
    document.querySelectorAll('.btn-time.selected').forEach(btn => {
        const [s, e] = btn.dataset.time.split(' - ');          // giờ gốc
        const curEnd = btn.dataset.currentEnd || e;           // giờ kết thúc hiện tại
        extended.push({ start: s, end: curEnd });             // thêm vào mảng
    });

    // ===================== LẶP QUA TẤT CẢ NÚT =====================
    allBtns.forEach(btn => {

        const [s, e] = btn.dataset.time.split(' - ');        // giờ bắt đầu và kết thúc của slot
        // Kiểm tra trùng với các slot đã chọn
        // Nếu slot đang xét giao nhau với bất kỳ slot nào trong extended → disable
        let disable = extended.some(slot => 
            !(e <= slot.start || s >= slot.end) && !btn.classList.contains('selected')
        );

        // Khóa slot quá khứ nếu ngày chọn là hôm nay
        if (selectedDate === today) {
            const [h, m] = s.split(':').map(Number);           // tách giờ, phút
            const slotTime = new Date();                        // tạo object thời gian
            slotTime.setHours(h, m, 0, 0);                     // set giờ slot

            // Nếu slot <= thời gian hiện tại → disable
            disable ||= slotTime <= now;

            // Thêm class CSS 'past' để đánh dấu UI
            btn.classList.toggle('past', slotTime <= now);
        }

        // Cập nhật nút
        btn.disabled = disable || btn.classList.contains('booked'); // khóa nếu booked hoặc disable
        btn.classList.toggle('disabled', disable);                  // thêm class CSS 'disabled' nếu disable
    });
}

// ---------------- Xử lý gộp adminBookedTimes + sessionBookedTimes + monthRent + tạo gợi ý ----------------
async function processBookedAndSuggest() {

    // Lấy tất cả nút khung giờ trên trang
    const allBtns = Array.from(document.querySelectorAll('.btn-time'));

    // Khởi tạo các biến toàn cục nếu chưa có
    if (!window.adminBookedTimes) window.adminBookedTimes = [];
    if (!window.sessionBookedTimes) window.sessionBookedTimes = [];
    if (!window.monthRents) window.monthRents = [];

    // Lấy container hiển thị gợi ý
    const wrapper = document.getElementById('suggested_times_wrapper');
    const container = document.getElementById('suggested_times');
    if (!wrapper || !container) return; // Nếu không có container thì thôi

    // Xóa hết gợi ý cũ và ẩn wrapper trước khi tạo mới
    container.innerHTML = '';
    wrapper.style.display = 'none';

    // ----------------- HÀM HỖ TRỢ -----------------
    // Chuyển giờ 'hh:mm' → số phút từ 0h
    function timeToMinutes(t) {
        const [h,m] = t.split(':').map(Number);
        return h*60 + m;
    }
    // Chuyển số phút → 'hh:mm'
    function minutesToTime(m) {
        const h = Math.floor(m / 60).toString().padStart(2,'0');
        const min = (m % 60).toString().padStart(2,'0');
        return `${h}:${min}`;
    }

    // Lấy ngày người dùng chọn từ input #date
    const selectedDate = document.getElementById('date').value;

    // Chuyển ngày sang thứ trong tuần (0 = Thứ 2, 6 = CN)
    const dayOfWeek = (() => {
        const d = new Date(selectedDate).getDay(); // 0 = CN
        return d === 0 ? 6 : d - 1;                // đổi lại cho dễ xử lý
    })();

    // ----------------- Gộp các slot đã booked -----------------
    // Gộp adminBookedTimes + sessionBookedTimes → tạo mảng allBookedTimes
    // Ví dụ: adminBookedTimes = ["08:00 - 09:00"], sessionBookedTimes = ["10:00 - 11:00"]
    let allBookedTimes = [...adminBookedTimes, ...sessionBookedTimes];

    // ----------------- Thêm khung giờ thuê theo tháng (MonthRent) -----------------
    // Lặp qua các đơn thuê theo tháng (admin đã xác nhận theo tháng)
    window.monthRents.forEach(rent => {
        // rent.weekday = "0,2,4" → thứ áp dụng
        const weekdays = rent.weekday.split(',').map(s => parseInt(s.trim(),10));

        // Nếu ngày đang chọn thuộc weekday của rent → thêm vào booked
        if (weekdays.includes(dayOfWeek)) {
            allBookedTimes.push(`${rent.start.trim()} - ${rent.end.trim()}`);
        }
    });

    // Loại bỏ trùng lặp và sắp xếp theo thời gian bắt đầu
    allBookedTimes = Array.from(new Set(allBookedTimes.map(t=>t.trim())))
                          .sort((a,b)=>timeToMinutes(a.split(' - ')[0]) - timeToMinutes(b.split(' - ')[0]));

    // ----------------- Khóa các nút trùng với booked -----------------
    allBtns.forEach(btn => {
        const [sBtn, eBtn] = btn.dataset.time.split(' - ').map(timeToMinutes);

        // Kiểm tra nút btn có trùng với bất kỳ slot booked nào không
        const isBooked = allBookedTimes.some(slot => {
            const [sB,eB] = slot.split(' - ').map(timeToMinutes);
            return !(eBtn <= sB || sBtn >= eB); // trùng nhau → return true
        });

        // Nếu trùng booked → disable + thêm class booked
        if(isBooked){
            btn.disabled = true;
            btn.classList.add('booked');
        }
    });

    // ----------------- Tạo gợi ý cho khoảng trống -----------------
    // Ý tưởng: nếu nút btn hiện tại có booked → tạo nút gợi ý trước, giữa, sau booked
    // Duyệt tất cả nút, nếu có slot booked → tạo nút gợi ý
    allBtns.forEach(btn => {
        let [sBtn, eBtn] = btn.dataset.time.split(' - ').map(timeToMinutes);

        // Lấy các booked trùng với btn hiện tại
        const conflicts = allBookedTimes
            .map(slot => slot.split(' - ').map(timeToMinutes))
            .filter(([sB,eB]) => !(eBtn <= sB || sBtn >= eB));

        if(conflicts.length === 0) return; // nếu không trùng booked → bỏ qua

        conflicts.sort((a,b) => a[0]-b[0]); // sắp xếp theo giờ bắt đầu

        // Khoảng trống trước booked đầu
        if(sBtn < conflicts[0][0]) createSuggestedBtn(minutesToTime(sBtn), minutesToTime(conflicts[0][0]));

        // Khoảng trống giữa các booked
        for(let i=0; i<conflicts.length-1; i++){
            const endCurrent = conflicts[i][1];
            const startNext = conflicts[i+1][0];
            if(endCurrent < startNext) createSuggestedBtn(minutesToTime(endCurrent), minutesToTime(startNext));
        }

        // Khoảng trống sau booked cuối
        const lastEnd = conflicts[conflicts.length-1][1];
        if(lastEnd < eBtn) createSuggestedBtn(minutesToTime(lastEnd), minutesToTime(eBtn));
    });

    // ----------------- Hàm tạo nút gợi ý -----------------
    function createSuggestedBtn(start, end) {
        // Nếu trùng booked → không tạo
        const conflict = allBookedTimes.some(slot => {
            const [s,e] = slot.split(' - ').map(timeToMinutes);
            return !(timeToMinutes(end) <= s || timeToMinutes(start) >= e);
        });
        if(conflict) return;

        // Tạo nút gợi ý
        const suggestedBtn = document.createElement('button');
        suggestedBtn.type = 'button';
        suggestedBtn.className = 'btn-time btn-suggested';
        suggestedBtn.dataset.time = `${start} - ${end}`;
        suggestedBtn.dataset.isClassic = "1";
        suggestedBtn.style.marginLeft = '15px';

        // Lấy giá theo khung gợi ý
        const price = getPriceByTimeRange(`${start} - ${end}`);
        suggestedBtn.dataset.priceWeekday = price;
        suggestedBtn.dataset.priceWeekend = price;
        suggestedBtn.textContent = `${start} - ${end}`;

        // Thêm vào container gợi ý
        container.appendChild(suggestedBtn);
        wrapper.style.display = 'flex';

        // Click vào nút gợi ý → chọn hoặc bỏ chọn
        suggestedBtn.addEventListener('click', function() {
            const idx = selectedTimes.indexOf(this.dataset.time);
            if(idx !== -1){
                // Nếu đã chọn → bỏ chọn
                selectedTimes.splice(idx, 1);
                selectedPrices.splice(idx, 1);
                this.classList.remove('selected');
                this.style.backgroundColor = '';
            } else {
                // Nếu chưa chọn → thêm vào mảng
                selectedTimes.push(this.dataset.time);
                selectedPrices.push(price);
                this.classList.add('selected');
                this.style.backgroundColor = 'var(--primary-color-hover-text)';
            }
            // Cập nhật tổng tiền, hidden input, thông tin
            updateSelectedTimes();
        });
    }
    
// Giải thích logic chính:
// Bước 1: Lấy toàn bộ khung giờ trên trang.
// Bước 2: Gộp các khung giờ đã bị admin hoặc session booking.
// Bước 3: Thêm các khung giờ thuê theo tháng vào booked.
// Bước 4: Khóa các nút đã booked.
// Bước 5: Tạo gợi ý cho khoảng trống (trước, giữa, sau booked) bằng các nút màu khác.
// Bước 6: Click vào gợi ý → chọn/bỏ chọn → cập nhật mảng selectedTimes và selectedPrices.
}

// ---------------- Khởi tạo ----------------
window.onload = function(){
    // Khi trang web load xong, thực hiện các bước khởi tạo
    // 1. Xử lý gộp các slot đã booked (admin + session) và tạo gợi ý
    processBookedAndSuggest();

    // 2. Khóa các slot không hợp lệ: 
    //    - đã trùng với booked
    //    - đã qua thời gian hiện tại
    disableSlots();

    // 3. Gắn sự kiện click cho các nút giờ (không bị booked)
    // Khi click vào 1 khung giờ → hàm changeTimeSlot sẽ xử lý:
    //    - chọn / bỏ chọn khung chính
    //    - cập nhật mảng selectedTimes, selectedPrices
    //    - cập nhật hiển thị (màu, nút +, compact...)
    document.querySelectorAll('.btn-time:not(.booked)').forEach(btn => btn.addEventListener('click',()=>changeTimeSlot(btn)));
    
    // 4. Gắn sự kiện click cho các nút '+' mở extra options
    // Khi click nút '+' → sẽ hiển thị hoặc ẩn các tùy chọn mở rộng (extra time)
    document.querySelectorAll('.btn-plus').forEach(btn => btn.addEventListener('click',()=>toggleExtraOptions(btn)));
    
    // 5. Gắn sự kiện click cho các nút extra time (+30/+60 phút)
    // Khi click → chọn hoặc bỏ chọn thời gian thêm
    //    - cập nhật dataset.currentEnd trên nút chính
    //    - tính lại giá tương ứng
    //    - cập nhật mảng selectedTimes, selectedPrices
    document.querySelectorAll('.btn-extra').forEach(btn => btn.addEventListener('click',()=>selectExtraTime(btn)));
};

// ---------------- Cập nhật tổng tiền + info ----------------
function updateSelectedTimes() {

    // Tính tổng tiền từ tất cả các slot đã chọn
    totalPrice = selectedPrices.reduce((a,b)=>a+b,0);

    // Hiển thị tổng tiền ra phần tử HTML
    document.getElementById('total_price').innerText = totalPrice.toLocaleString('vi-VN') + 'đ';

    // Lưu tổng tiền vào input ẩn để gửi lên server khi submit form
    document.getElementById('total_price_input').value = totalPrice;

    // Container để lưu các input ẩn của từng slot đã chọn
    const container = document.getElementById('selected_times');
    container.innerHTML = '';

    // Input ẩn lưu thông tin mỗi slot có phải là classic hay không
    const isClassicInput = document.getElementById('is_classic_per_slot_input');
    const isClassics = [];

    // Sắp xếp các slot theo giờ bắt đầu để hiển thị gọn gàng
    const sortedTimes = [...selectedTimes].sort((a,b)=>a.split(' - ')[0].localeCompare(b.split(' - ')[0]));

    // Lặp qua từng slot đã chọn
    sortedTimes.forEach(t=>{
        // Tạo input ẩn lưu giá trị slot để submit form
        const input = document.createElement('input');
        input.type='hidden';
        input.name='selected_times[]';
        input.value=t;
        container.appendChild(input);

        // Kiểm tra slot này có phải là classic (khung chính) hay là extra (+30/+60)
        const [start] = t.split(' - ');
        const btn = Array.from(document.querySelectorAll('.btn-time')).find(b=>b.dataset.time.split(' - ')[0]===start);
        const isClassic = btn ? parseInt(btn.dataset.isClassic||0) : 0;
        isClassics.push(isClassic);
    });

    // Lưu mảng isClassic vào input ẩn (JSON) để submit
    isClassicInput.value = JSON.stringify(isClassics);

    // Lưu giá từng slot vào input ẩn (JSON) để submit
    let priceInput = document.getElementById('price_per_slot_input');
    if(!priceInput){
        priceInput = document.createElement('input');
        priceInput.type='hidden';
        priceInput.name='price_per_slot';
        priceInput.id='price_per_slot_input';
        container.appendChild(priceInput);
    }
    priceInput.value = JSON.stringify(selectedPrices);

    // Cập nhật thông tin hiển thị cho người dùng
    const info = document.getElementById('selected_times_info');
    info.textContent = sortedTimes.length ? 'Bạn đã chọn khung giờ '+sortedTimes.join(', ') : 'Bạn chưa chọn khung giờ nào';

    // Khóa lại các slot trùng hoặc quá khứ
    disableSlots();
}

// ---------------- Xác nhận đặt sân ----------------
function confirmBooking(event){

    // Ngăn form submit mặc định (tránh reload trang)
    event.preventDefault();

    // Kiểm tra xem người dùng đã chọn slot nào chưa
    if(!selectedTimes.length){ alert('Vui lòng chọn khung giờ và ngày đặt!'); return false; }
    const form = event.target; // Lấy form đang submit

    // Bước 1: Hỏi người dùng có chắc chắn muốn đặt khung giờ này không
    Swal.fire({
        title:'Bạn có chắc chắn muốn đặt khung giờ này?',
        showCancelButton:true,
        confirmButtonText:'Có',
        cancelButtonText:'Không',
        icon:'question'
    }).then(result1=>{
        if(!result1.isConfirmed) return; // Nếu người dùng chọn 'Không' → dừng lại

        // Bước 2: Hỏi tiếp xem có muốn tiếp tục đặt thêm sân khác không
        Swal.fire({
            title:'Bạn có muốn tiếp tục đặt sân không?',
            showCancelButton:true,
            confirmButtonText:'Có',
            cancelButtonText:'Không',
            icon:'question'
        }).then(result2=>{
            // Lấy dữ liệu từ form
            const formData = new FormData(form);
            // Gửi dữ liệu (không reload trang)
            fetch(form.action,{
                method: form.method, // POST
                body: formData,
                headers: {
                    'X-Requested-With':'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                }
            })
            .then(r => r.ok?r:r.text().then(t=>{throw new Error(t)}))
            .then(()=>{
                // Lấy ID người dùng hoặc chủ sân để điều hướng
                const userId = form.querySelector('input[name="yard_owner_id"]')?.value
                            || form.querySelector('input[name="user_id"]')?.value || '';
                // Nếu muốn tiếp tục đặt sân → về trang chủ
                // Nếu không → chuyển đến trang xác nhận đơn vừa đặt
                if(result2.isConfirmed) window.location.href='/trang-chu';
                else window.location.href=`/xac-nhan-dat-san/${userId}`;
            })

            // Nếu gửi dữ liệu bị lỗi → hiển thị thông báo lỗi
            .catch(e => Swal.fire('Lỗi','Đã xảy ra lỗi khi lưu đơn đặt sân: '+e.message,'error'));
        });
    });
}
