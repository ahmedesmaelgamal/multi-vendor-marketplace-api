@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="mt-4 p-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">⬅ رجوع</a>
        </div>


        {{-- تفاصيل العروض --}}
        <div class="card">
            <div class="card-header bg-secondary text-white">
                العروض
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th> المنتج</th>
                                <th>النوع</th>
                                <th> السعر</th>
                                <th> اجمالى السعر</th>
                                <th>الكمية المتاحة</th>
                                <th>منتجات اخرى</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offersDetails as $offer)
                                <tr>
                                    <td>{{ $offer->product->title_ar }}</td>
                                    <td>
                                        @if ($offer->type == 'price')
                                            السعر
                                        @elseif ($offer->type == 'less')
                                            الكمية اقل
                                        @else
                                            اخرى
                                        @endif
                                    </td>

                                    <td>{{ $offer->price . ' ر.س' }}</td>
                                    </td>
                                    <td>{{ $offer->total_price . 'ر.س' }}</td>
                                    <td>{{ $offer->available_qty }}</td>
                                    <td>{{ @$offer->other_product->title_ar }}</td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
