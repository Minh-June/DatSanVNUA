@extends('layouts.admin')

@section('title', 'Thêm hình ảnh sân')

@section('content')
    <h3>Thêm hình ảnh sân thể thao</h3>

    <div class="adminedit">
        <form action="{{ route('store.hinh-anh-san') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="san_id">Sân thể thao:</label>
                <select class="admin-time-select" name="san_id" required>
                    <option value="">Chọn sân</option>
                    @foreach ($available_san_ids as $san)
                        <option value="{{ $san->san_id }}">{{ $san->tensan }} - {{ $san->sosan }}</option>
                    @endforeach
                </select>
                @error('san_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <br>
            <div>
                <label for="image">Chọn hình ảnh:</label>
                <input type="file" name="image" id="image" required>
                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <br>
            <div>
                <button type="submit" class="update-btn">Thêm ảnh</button>
            </div>
        </form>                                                      
    </div>
@endsection
