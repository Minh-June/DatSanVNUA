@extends('layouts.admin')

@section('title', 'Danh sĂ¡ch ngÆ°á»i dĂ¹ng')

@section('content')
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if ($errors->any())
        <script>
            alert("{{ $errors->first('keyword') }}");
        </script>
    @endif


    <h3>{{ isset($xem_user) ? 'ThĂ´ng tin ngÆ°á»i dĂ¹ng' : 'Danh sĂ¡ch ngÆ°á»i dĂ¹ng' }}</h3>

    <!-- Thanh top-bar luĂ´n hiá»ƒn thá»‹ -->
    <div class="admin-top-bar">
        <div class="admin-search">
            @if(!isset($xem_user))
            <form method="GET" action="{{ route('quan-ly-nguoi-dung') }}">
                <label for="type_id">TĂ¬m ngÆ°á»i dĂ¹ng:</label>
                <input type="text" name="keyword" placeholder="Nháº­p thĂ´ng tin cáº§n tĂ¬m" value="{{ request('keyword') }}" required pattern="^[\p{L}\s]+$" title="Chá»‰ nháº­p chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng">
                <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
            </form>
            @endif
        </div>

        <div class="admin-add-btn">
            @if(isset($xem_user))
                <a href="{{ route('quan-ly-nguoi-dung') }}">Quay láº¡i danh sĂ¡ch</a>
            @else
                <a href="{{ route('dang-ky') }}">ThĂªm ngÆ°á»i dĂ¹ng má»›i</a>
            @endif
        </div>
    </div>

    @if(isset($xem_user))
        <!-- Hiá»ƒn thá»‹ thĂ´ng tin ngÆ°á»i dĂ¹ng -->
        <div class="adminedit">
            <form>
                @csrf
                <label for="fullname">Há» vĂ  tĂªn:</label>
                <input type="text" name="fullname" value="{{ $xem_user->fullname }}" disabled><br>

                <label for="gender">Giá»›i tĂ­nh:</label>
                <input type="text" name="gender" value="{{ $xem_user->gender }}" disabled><br>

                <label for="birthdate">NgĂ y sinh:</label>
                <input type="date" name="birthdate" value="{{ $xem_user->birthdate }}" disabled><br>

                <label for="phonenb">Sá»‘ Ä‘iá»‡n thoáº¡i:</label>
                <input type="text" name="phonenb" value="{{ $xem_user->phonenb }}" disabled><br>

                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ $xem_user->email }}" disabled><br>
            </form>                          
        </div>
        <br>
    @else
        <!-- Hiá»ƒn thá»‹ báº£ng dá»¯ liá»‡u -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Há» vĂ  tĂªn</th>
                    <th>TĂªn tĂ i khoáº£n</th>
                    <th>Vai trĂ²</th>
                    <th>ThĂ´ng tin</th>
                    <th colspan="2">TĂ¹y chá»n</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->role == 0 ? 'Admin' : 'KhĂ¡ch hĂ ng' }}</td>
                    <td>
                        <a href="{{ route('quan-ly-nguoi-dung', ['xem' => $user->user_id]) }}">Xem chi tiáº¿t</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cap-nhat-vai-tro-nguoi-dung', $user->user_id) }}">
                            @csrf
                            <select name="role">
                                <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>Admin</option>
                                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>KhĂ¡ch hĂ ng</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cáº­p nháº­t</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('xoa-nguoi-dung', ['user_id' => $user->user_id]) }}" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a ngÆ°á»i dĂ¹ng nĂ y khĂ´ng?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="update-btn">XĂ³a</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
