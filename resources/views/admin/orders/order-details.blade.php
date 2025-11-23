@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="mt-4 p-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">⬅ رجوع</a>
        </div>

        {{-- بيانات الطلب الأساسية --}}
        <div class="card mb-4">
            <div class="card-body">
                <p><strong> رقم الطلب: </strong>{{ $orderDetails->order_id }} </p>
                <p><strong>العميل: </strong>{{ $orderDetails->user->name ?? '-' }} </p>
                <p><strong>وقت الطلب:</strong> {{ $orderDetails->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        {{-- تفاصيل المنتجات --}}
        <div class="card">
            <div class="card-header bg-secondary text-white">
                المنتجات المطلوبة
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>التصنيف الرئيسي</th>
                                <th>التصنيف الفرعي</th>
                                <th>المنتج</th>
                                <th>الكمية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetailsCollection as $orderDetails)
                                <tr>
                                    <td>{{ $orderDetails->category->title_ar }}</td>
                                    <td>
                                        @isset($orderDetails->sub_category)
                                            {{ $orderDetails->sub_category->subCategory->title_ar }}
                                        @endisset
                                    </td>
                                    <td>{{ $orderDetails->product->title_ar }}</td>
                                    <td>{{ $orderDetails->qty }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
