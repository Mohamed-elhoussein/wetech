@extends('layout.partials.app')

@section('title', 'قائمة الهويات')

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
                    <div class="page-title-box d-sm-flex align-items-end justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 ">قائمة الهويات</h4>

                        <form method="GET" class="d-flex align-items-center">
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
                            <select class="form-select" name="status">
                                <option @if (!request()->has('status')) selected @endif>إختر حالة</option>
                                <option @if (request()->get('status') === 'approved') selected @endif value="approved">مقبولة</option>
                                <option @if (request()->get('status') === 'pending') selected @endif value="pending">في الإنتظار
                                </option>
                                <option @if (request()->get('status') === 'denied') selected @endif value="denied">مرفوضة</option>
                            </select>

                            <button class="btn btn-success ms-2">فرز</button>
                            @if (request()->has('status'))
                                <a href="{{ route('identities.index') }}" class="d-block ms-2 btn btn-danger w-100"
                                    style="white-space: nowrap">إعادة التعيين</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">صاحب الهوبة</th>
                                            <th scope="col">الهوبة</th>
                                            <th scope="col">هل تم القبول</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($identities as $identity)
                                            <tr>
                                                <td>{{ $identity->id }}</td>
                                                <td>{{ $identity->user->first_name }}</td>
                                                <td>
                                                    <button class="btn btn-info show_identity_proof"
                                                        data-id="{{ $identity->id }}"
                                                        data-is-accepted="{{ $identity->status !== 'pending' }}"
                                                        data-identity="{{ \Storage::url('identity/' . $identity->image) }}">
                                                        إظهار الهوية
                                                    </button>
                                                </td>
                                                <td>
                                                    <span style="font-weight: bold"
                                                        class="font-size-13 badge @if ($identity->status === 'pending') badge-soft-warning @elseif($identity->status === 'denied') badge-soft-danger @else badge-soft-success @endif">
                                                        {{ __($identity->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($identity->status === 'pending')
                                                        <form method="POST"
                                                            action="{{ route('identities.accept', compact('identity')) }}"
                                                            id="form-accept-{{ $identity->id }}">
                                                            @csrf
                                                        </form>
                                                        <form method="POST"
                                                            action="{{ route('identities.deny', compact('identity')) }}"
                                                            id="form-deny-{{ $identity->id }}">
                                                            @csrf
                                                        </form>
                                                        <a href="#" data-form="#form-accept-{{ $identity->id }}"
                                                            class="identity_control btn btn-success">قبول الهوية</a>
                                                        <a href="#" data-form="#form-deny-{{ $identity->id }}"
                                                            class="identity_control btn btn-danger">رفض الهوية</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $identities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>

    <div class="modal fade" id="identityProof" tabindex="-1" aria-labelledby="composemodalTitle" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="composemodalTitle"> الهوية </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" data-form="" class="identity_control btn btn-danger">رفض</button>
                    <button type="button" data-form="" class="identity_control btn btn-success">قبول</button>
                </div>
            </div>
        </div>
    </div>
@endsection
