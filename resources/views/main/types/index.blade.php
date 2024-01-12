@extends('maintenance')


@section('content')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h2 class="mb-sm-0">أنواع طلب الصيانة</h2>

        <a href="{{ route('main.types.create') }}" class="btn btn-success w-md fs-5">إضافة نوع جديد</a>
    </div>


    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">إسم النوع</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($types as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <li class=" list-inline-item px-2">
                                            <form action="{{ route('main.types.destroy', $type) }}" method="POST" id="delete-request-{{ $type->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a data-delete-form="#delete-request-{{ $type->id }}" href="javascript:;" title="Delete" class="delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item px-2">
                                            <a href="{{ route('main.types.edit', $type) }}" title="Edit">
                                                <i class="bx bx-pencil"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $types->links() }}
            </div>
        </div>
    </div>
@endsection
