@extends('layouts.admin')

@section('title', 'Thêm hình ảnh sân')

@section('content')
    <h3>Thêm hình ảnh sân thể thao</h3>

    <div class="adminedit">
        <form action="{{ route('luu-hinh-anh-san') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="yard_id">Sân thể thao:</label>
                <select class="admin-time-select" name="yard_id" required>
                    <option value="">Chọn sân</option>
                    @foreach ($yards as $yard)
                        <option value="{{ $yard->yard_id }}" {{ old('yard_id', request('yard_id')) == $yard->yard_id ? 'selected' : '' }}>
                            {{ $yard->name }}
                        </option>
                    @endforeach
                </select>
                @error('yard_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="image">Chọn hình ảnh:</label>
                <input type="file" name="image" id="image" required>
                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <button type="submit" class="update-btn">Thêm hình ảnh</button>
            </div>
        </form>
    </div>
@endsection
