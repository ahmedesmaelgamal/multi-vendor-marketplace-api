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
                                <th> مقدم العرض</th>
                                <th> سعر العرض</th>
                                <th>حالة العرض</th>
                                <th>وقت التوصيل (من)</th>
                                <th>وقت التوصيل (إلى)</th>
                                <th>ملحوظة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offers as $offer)
                                <tr>
                                    <td>{{ $offer->provider->name }}</td>
                                    <td>{{ $offer->total_price . ' ر.س ' }}</td>
                                    <td>
                                        @if ($offer->status == 'new')
                                            <span class="badge badge-primary">جديد</span>
                                        @elseif ($offer->status == 'accepted')
                                            <span class="badge badge-success">مقبول</span>
                                        @elseif ($offer->status == 'rejected')
                                            <span class="badge badge-danger">مرفوض</span>
                                        @endif
                                    </td>
                                    </td>
                                    <td>{{ @$offer->delivery_time->from }}</td>
                                    <td>{{ @$offer->delivery_time->to }}</td>
                                    <td>{{ $offer->note }}</td>
                                    <td>
                                        <a href="{{ route('offer_details', $offer->id) }}" class="btn btn-primary">تفاصيل
                                            العرض</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
