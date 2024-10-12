@extends('layouts.client.client')

@section('title', 'Thanh toán')

@section('content')
    <!-- Hiển thị thông báo thành công -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiển thị thông báo lỗi -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

        <!-- Begin: Content -->
        <div id="content" class="order-section">
            <h2 class="order-heading">THANH TOÁN</h2>

            <div class="pay-content">
                <div class="pay-information">
                    <div class="bank-account">
                        Tài khoản ngân hàng
                    </div>
                    <div class="bank-account">
                        Tên tài khoản: Nguyễn Hữu Quang Minh
                    </div>
                    <div class="bank-account">
                        Số tài khoản: 1903 6786 8800 12
                    </div>
                    <div class="bank-account">
                        Ngân hàng: Techcombank
                    </div>
                </div>
                <div class="pay-information">
                    <div class="bank-qr">
                        Mã QR
                        <br><img class="bank-qr-img" src="{{ asset('./image/qr/qr.jpg') }}" alt="Mã QR">
                    </div>
                </div>
            </div>
            <div class="clear"></div>

            <div class="pay-customer">
                <h3>Thông tin khách hàng và đặt sân</h3><br>
                
                <table id='ListCustomers'>
                    <tr>
                        <th>Họ và tên</th>
                        <th>Số điện thoại</th>
                        <th>Sân thể thao</th>
                        <th>Ngày đặt</th>
                        <th>Khung giờ đã chọn</th>
                        <th>Thành tiền</th>
                        <th>Ghi chú</th>
                        
                    </tr>
                    <tr>
                        <td>{{ session('name') }}</td>
                        <td>{{ session('phone') }}</td>
                        <td>{{ session('tensan') }} - Sân số {{ session('sosan') }}</td>
                        <td>{{ date('d/m/Y', strtotime(session('date'))) }}</td>
                        <td>
                            @foreach (session('time', []) as $time)
                                {{ $time }}<br>
                            @endforeach
                        </td>
                        <td>{{ number_format(session('price')) }} VND</td>
                        <td>{{ session('notes', 'Không có ghi chú') }}</td>
                    </tr>
                </table>
                
                <div class="pay-upload">

                    <p>* Lưu ý: Nếu bạn muốn thanh toán trước<br><br>
                        Chuyển khoản ĐÚNG số tiền ở cột "Thành tiền"<br><br>
                        Nội dung chuyển khoản: TÊN + SĐT<br><br>
                        Chuyển khoản xong bạn chụp lại màn hình phần giao dịch, gửi hình ảnh vào ô dưới đây</p>
                    
                        <form action="{{ route('pay.upload') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input class="pay_upload admin-time-select" type="file" name="images[]" id="images" multiple accept="image/*"><br>
                            <div class="pay-btn">
                                <input class="order-football-btn" type="submit" value="Xác nhận đặt sân">
                            </div>
                        </form>

                    </div>
                </div>                                                          

            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->
@endsection