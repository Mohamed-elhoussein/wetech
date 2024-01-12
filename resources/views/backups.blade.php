@extends('layout.partials.app')

@section('title', 'قائمة النسخ الإحتياطية')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            @if (session('success'))
                <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('error') }}</div>
            @endif
            @if (session('updated'))
                <div class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('updated') }}</div>
            @endif
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 ">قائمة النسخ الإحتياطية </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">النسخ الإحتياطية
                                            </th>
                                            <th scope="col"> التاريخ</th>
                                            <th scope="col"> الوقت</th>
                                            <th scope="col"> التحميل</th>
                                            <th scope="col">حدف</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($backups as $backup)
                                            <tr>
                                                <td>
                                                    {{ $backup['name'] }}
                                                </td>
                                                <td>
                                                    {{ $backup['date'] }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $backup['time'] }}
                                                </td>
                                                <td style="font-weight: bold">
                                                    <a href="{{ asset('storage/backups/' . $backup['name']) }}">تحميل</a>
                                                </td>

                                                <td>
                                                    <a class="delete"
                                                        href="{{ route('backup.delete', $backup['name']) }}"
                                                        title="Delete"><i class="bx bx-trash-alt "></i></a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

@endsection
