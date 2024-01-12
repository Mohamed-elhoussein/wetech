@extends('maintenance')


@section('content')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h2 class="mb-sm-0">المشاكل</h2>

        <a href="{{ route('main.issues.create') }}" class="btn btn-success w-md fs-5">مشكلة جديدة</a>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">المشكلة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issues as $issue)
                            <tr>
                                <td>{{ $issue->name }}</td>
                                <td>
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <li class=" list-inline-item px-2">
                                            <form action="{{ route('main.issues.destroy', $issue) }}" method="POST" id="delete-item-{{ $issue->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a data-delete-form="#delete-item-{{ $issue->id }}" href="javascript:;" title="Delete" class="delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item px-2">
                                            <a href="{{ route('main.issues.edit', $issue) }}" title="Edit">
                                                <i class="bx bx-pencil"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $issues->links() }}
            </div>
        </div>
    </div>
@endsection
