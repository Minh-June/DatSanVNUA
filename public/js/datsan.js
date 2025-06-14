let selectedTimes = [];
let selectedPrices = [];
let totalPrice = 0;

function onDateChange() {
    const yardId = document.getElementById('yard_id_input').value;
    const selectedDate = document.getElementById('date').value;
    window.location.href = `/trang-chu/dat-san/${yardId}?date=${selectedDate}`;
}

function changeTimeSlot(button) {
    const time = button.getAttribute('data-time');
    const price = parseInt(button.getAttribute('data-price'));

    if (button.classList.contains('selected')) {
        button.classList.remove('selected');
        const index = selectedTimes.indexOf(time);
        if (index > -1) {
            selectedTimes.splice(index, 1);
            selectedPrices.splice(index, 1);
            totalPrice -= price;
        }
    } else {
        button.classList.add('selected');
        selectedTimes.push(time);
        selectedPrices.push(price);
        totalPrice += price;
    }

    document.getElementById('total_price').innerText = totalPrice.toLocaleString('vi-VN') + 'đ';
    document.getElementById('total_price_input').value = totalPrice;

    // Cập nhật các input hidden cho selected_times[]
    const container = document.getElementById('selected_times');
    container.innerHTML = '';
    selectedTimes.forEach(time => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_times[]';
        input.value = time;
        container.appendChild(input);
    });

    // Cập nhật input hidden price_per_slot dưới dạng JSON string
    let pricePerSlotInput = document.getElementById('price_per_slot_input');
    if (!pricePerSlotInput) {
        pricePerSlotInput = document.createElement('input');
        pricePerSlotInput.type = 'hidden';
        pricePerSlotInput.name = 'price_per_slot';
        pricePerSlotInput.id = 'price_per_slot_input';
        container.appendChild(pricePerSlotInput);
    }
    pricePerSlotInput.value = JSON.stringify(selectedPrices);
}

window.onload = function() {
    const buttons = document.querySelectorAll('.btn-time:not(.booked)');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => changeTimeSlot(btn));
    });
};

function confirmBooking(event) {
    event.preventDefault();

    if (selectedTimes.length === 0) {
        alert('Vui lòng chọn khung giờ và ngày đặt !');
        return false;
    }

    if (!confirm('Bạn muốn tiếp tục đặt sân không ?')) {
        event.target.submit();
        return true;
    }

    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
        },
    })
    .then(response => {
        if (response.ok) {
            window.location.href = '/trang-chu';
        } else {
            return response.text().then(text => { throw new Error(text) });
        }
    })
    .catch(error => {
        alert('Đã xảy ra lỗi khi lưu đơn đặt sân: ' + error.message);
    });

    return false;
}

