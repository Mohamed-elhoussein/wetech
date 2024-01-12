@extends('layout.partials.app')

@section('title', 'قائمة البلاغات')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            @if (session('created'))
                <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('created') }}</div>
            @endif
            @if (session('deleted'))
                <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('deleted') }}</div>
            @endif
            @if (session('updated'))
                <div class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('updated') }}</div>
            @endif
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 ">قائمة البلاغات</h4>

                        <div class="d-flex align-items-end">
                            <div class="position-relative me-2">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span>عرض</span>
                                    <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="?limit=5">5</a>
                                    <a class="dropdown-item" href="?limit=10">10</a>
                                    <a class="dropdown-item" href="?limit=15">15</a>
                                    <a class="dropdown-item" href="?limit=20">20</a>
                                    <a class="dropdown-item" href="?limit=50">50</a>
                                </div>
                            </div>
                            <form class="d-flex align-items-end">
                                <div>
                                    <label for="report-status">الحالة</label>
                                    <select class="form-select" name="status" id="report-status">
                                        <option @if (request()->get('status') === 'resolved') selected @endif value="resolved">ثم حله
                                        </option>
                                        <option @if (request()->get('status') === 'not_resolved') selected @endif value="not_resolved">لم
                                            يحل
                                            بعد
                                        </option>
                                    </select>
                                </div>

                                <button class="btn btn-primary ms-2">
                                    فرز
                                </button>

                                @if (request()->has('status'))
                                    <a href="{{ route('reports.index') }}" class="btn btn-danger ms-2">
                                        اعادة تعيين
                                    </a>
                                @endif
                            </form>

                            <span onclick="exportTasks(event.target);" data-href="{{ route('reports.export') }}"
                                id="export" class="btn btn-primary ms-2">
                                إستخراج
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="mb-2 d-flex align-items-center">

                                <div class="me-2" style="position: relative">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        تعديل
                                        <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                    </button>
                                    <div class="dropdown-menu">
                                        <button data-bulk-url="{{ route('reports.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="solved" data-action="status">ثم
                                            حله</button>
                                        <button data-bulk-url="{{ route('reports.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="not_solved"
                                            data-action="status">لم يحل
                                            بعد</button>
                                        <button data-bulk-url="{{ route('reports.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-action="delete">حذف</button>
                                    </div>
                                    <input type="hidden" name="ids" id="ids">
                                </div>

                                @include('partials.search-input', [
                                    'placeholder' => 'إبحث عن البلاغ عبر صاحب البلاع او العنوان',
                                    'hide' => true,
                                ])

                            </div>


                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="form-check" id="checkAll">
                                            </th>
                                            <th scope="col">تاريخ البلاغ</th>
                                            <th scope="col">صاحب البلاغ</th>
                                            <th scope="col"> العنوان</th>
                                            <th scope="col"> الاشكالية</th>
                                            <th scope="col"> الحالة</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports as $report)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk__check form-check"
                                                        value="{{ $report->id }}">
                                                </td>
                                                <td>
                                                    {{ $report->created_at->format('M d, Y') }}
                                                </td>
                                                <td>
                                                    {{ $report->user->username }}
                                                </td>
                                                <td>
                                                    {{ $report->title }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $report->content }}
                                                </td>
                                                <td style="font-weight: bold">
                                                    {{ !$report->solved ? 'لم يحل بعد' : 'ثم حله' }}
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/reports/delete/{{ $report->id }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        @if (!$report->solved)
                                                            <li class="list-inline-item px-2">
                                                                <a class="delete"
                                                                    href="/reports/solved/{{ $report->id }}"
                                                                    title="was solved"><i class="bx bx-file-find"></i></a>
                                                            </li>
                                                        @endif

                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $reports->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
