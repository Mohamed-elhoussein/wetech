@extends('maintenance')


@section('content')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h2 class="mb-sm-0">الألوان</h2>

        <a href="{{ route('main.colors.create') }}" class="btn btn-success w-md fs-5">لون جديد</a>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">اللون</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colors as $color)
                            <tr>
                                <td>{{ $color->name }}</td>
                                <td>
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <li class=" list-inline-item px-2">
                                            <form action="{{ route('main.colors.destroy', $color) }}" method="POST" id="delete-item-{{ $color->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a data-delete-form="#delete-item-{{ $color->id }}" href="javascript:;" title="Delete" class="delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item px-2">
                                            <a href="{{ route('main.colors.edit', $color) }}" title="Edit">
                                                <i class="bx bx-pencil"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $colors->links() }}
            </div>
        </div>
    </div>
@endsection
