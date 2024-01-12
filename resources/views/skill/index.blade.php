@extends('layout.partials.app')

@section('title', 'قائمة المهارات')

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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">قائمة المهارات</h4>
                        <div class="d-flex align-items-center">
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
                            <a href="{{ route('skills.create') }}"
                                class="btn btn-primary w-md  btn-block waves-effect waves-light">
                                إضافة مهارة جديدة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                @include('partials.search-input', [
                                    'placeholder' => 'إبحث عن المهارة عبر الإسم',
                                ])
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">إسم المهارة</th>
                                            <th scope="col">تاريخ الإنشاء</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($skills as $skill)
                                            <tr>
                                                <td>{{ $skill->id }}</td>
                                                <td>{{ $skill->name }}</td>
                                                <td>
                                                    <span class="badge badge-soft-primary font-size-11 m-1">
                                                        {{ $skill->created_at->format('M d, Y') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a href="{{ route('skills.edit', compact('skill')) }}"><i
                                                                    class="bx bx-pencil "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <form class="d-none" id="form-{{ $skill->id }}"
                                                                method="POST"
                                                                action="{{ route('skills.destroy', compact('skill')) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <a href="#" class="delete_form"
                                                                data-form="#form-{{ $skill->id }}" title="Delete"><i
                                                                    class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>
                            {{ $skills->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
