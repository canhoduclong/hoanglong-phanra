@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Import khách hàng từ Excel</h2>
    <div class="alert alert-info">
        <b>Hướng dẫn file Excel import:</b><br>
        - Hàng đầu tiên phải có các cột: <b>name</b> (bắt buộc), <b>phone</b> (bắt buộc, dạng chuỗi), <b>address</b> (bắt buộc), <b>email</b> (email, không bắt buộc), <b>gender</b> (male/female/other), <b>dob</b> (YYYY-MM-DD), <b>customer_type_id</b> (ID loại KH), <b>note</b> (ghi chú).<br>
        - Cột <b>address</b> không được để trống.<br>
        - Cột <b>phone</b> nên để dạng chuỗi, ví dụ: '0123456789'.<br>
        - Nếu thiếu cột hoặc sai tên cột sẽ báo lỗi.<br>
        <a href="/sample/customer_import_sample.xlsx" target="_blank">Tải file mẫu</a>
    </div>
    <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls" required>
        <button class="btn btn-primary">Import</button>
    </form>
    @if(isset($success))
        <div class="alert alert-success">{{ $success }}</div>
    @endif
    @if(isset($errors) && count($errors))
        <div class="alert alert-danger">
            <strong>Các dòng lỗi khi import:</strong>
            <ul>
                @foreach($errors as $err)
                    <li>
                        <b>Dòng:</b> {{ $err['row'] }} | <b>Cột:</b> {{ $err['attribute'] }}<br>
                        <b>Lỗi:</b> {{ implode('; ', $err['errors']) }}<br>
                        <b>Giá trị:</b> {{ json_encode($err['values']) }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(isset($imported) && count($imported))
        <div class="alert alert-info mt-3">
            <strong>Kết quả import từng dòng:</strong>
            <ul>
                @foreach($imported as $rec)
                    <li>
                        @if($rec['status']==='success')
                            <span class="text-success">✔</span> Thành công: {{ json_encode($rec['row']) }}
                        @else
                            <span class="text-danger">✖</span> Thất bại: {{ json_encode($rec['row']) }}<br>
                            <b>Lỗi:</b> {{ $rec['error'] ?? '' }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-2">Quay lại danh sách khách hàng</a>
</div>
@endsection
