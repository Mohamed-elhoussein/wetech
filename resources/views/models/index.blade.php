@extends('maintenance')


@section('content')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h2 class="mb-sm-0">الموديلات</h2>

        <a href="{{ route('main.models.create') }}" class="btn btn-success w-md fs-5">موديل جديد</a>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">الجهاز</th>
                            <th scope="col">الموديل</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($models as $model)
                            <tr>
                                <td>{{ optional($model->brand)->name }}</td>
                                <td>{{ $model->name }}</td>
                                <td>
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <li class=" list-inline-item px-2">
                                            <form action="{{ route('main.models.destroy', $model) }}" method="POST" id="delete-item-{{ $model->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a data-delete-form="#delete-item-{{ $model->id }}" href="javascript:;" title="Delete" class="delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item px-2">
                                            <a href="{{ route('main.models.edit', $model) }}" title="Edit">
                                                <i class="bx bx-pencil"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $models->links() }}
            </div>
        </div>
    </div>
@endsection
