@extends('layouts.admin')

@section('title', 'ThĂªm hĂ¬nh áº£nh sĂ¢n')

@section('content')
    <h3>ThĂªm hĂ¬nh áº£nh sĂ¢n thá»ƒ thao</h3>

    <div class="adminedit">
        <form action="{{ route('luu-hinh-anh-san') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="yard_id">SĂ¢n thá»ƒ thao:</label>
                <select class="admin-time-select" name="yard_id" required>
                    <option value="">Chá»n sĂ¢n</option>
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
                <label for="image">Chá»n hĂ¬nh áº£nh:</label>
                <input type="file" name="image" id="image" required>
                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <button type="submit" class="update-btn">ThĂªm hĂ¬nh áº£nh</button>
            </div>
        </form>
    </div>
@endsection
