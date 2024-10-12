function toggleTimeSlot(button) {
    const selectedTimesInput = document.getElementById('selected_times');
    const totalPriceElement = document.getElementById('total_price');
    
    // Lấy thông tin từ button
    const time = button.getAttribute('data-time');
    const price = parseFloat(button.getAttribute('data-price'));
    
    // Kiểm tra xem khung giờ đã được chọn hay chưa
    if (button.classList.contains('booked')) {
        alert('Khung giờ này đã được đặt.');
        return;
    }
    
    // Thay đổi trạng thái của button
    button.classList.toggle('selected');
    
    // Cập nhật danh sách thời gian đã chọn
    let selectedTimes = selectedTimesInput.value.split(',').filter(Boolean);
    
    if (button.classList.contains('selected')) {
        selectedTimes.push(time);
    } else {
        selectedTimes = selectedTimes.filter(t => t !== time);
    }
    
    selectedTimesInput.value = selectedTimes.join(',');
    
    const totalPrice = selectedTimes.reduce((sum, t) => {
        const selectedTimeSlot = Array.from(document.querySelectorAll('.btn-time')).find(b => b.getAttribute('data-time') === t);
        return sum + (selectedTimeSlot ? parseFloat(selectedTimeSlot.getAttribute('data-price')) : 0);
    }, 0);
    
    // Cập nhật giá tiền hiển thị
    totalPriceElement.textContent = totalPrice + ' VND';
    
    // Cập nhật giá trị vào input ẩn
    document.getElementById('total_price_input').value = totalPrice;
}

function fetchBookedTimes() {
    const selectedDate = document.getElementById('date').value;

    // Gửi yêu cầu AJAX để lấy các khung giờ đã đặt cho ngày đã chọn
    fetch(`/get-booked-times?date=${selectedDate}&san_id=${document.querySelector('input[name="san_id"]').value}`)
        .then(response => response.json())
        .then(data => {
            const bookedTimes = data.booked_times;

            // Cập nhật trạng thái các nút khung giờ
            const buttons = document.querySelectorAll('.btn-time');
            buttons.forEach(button => {
                const time = button.getAttribute('data-time');
                if (bookedTimes.includes(time)) {
                    button.disabled = true; // Khung giờ đã đặt
                    button.classList.add('booked'); // Thêm lớp để hiển thị
                } else {
                    button.disabled = false; // Khung giờ có thể chọn
                    button.classList.remove('booked'); // Xóa lớp
                }
            });
        });
}

function openModal(imgSrc) {
    document.getElementById("modalImg").src = imgSrc;
    document.getElementById("myModal").style.display = "block";
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}
