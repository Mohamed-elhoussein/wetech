@extends('layout.partials.app')

@section('title', 'الترجمة')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 "> الترجمة </h4>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid mt-5 translations-page">
                                <div class="col-12">
                                    <div class="card card-table-two">
                                        @if (count($translations))
                                            <form method="POST" action="{{ route('translate.store') }}">
                                                @csrf

                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Key') }}</th>
                                                            @foreach ($translations as $lang => $translation)
                                                                <th>{{ $lang }}</th>
                                                            @endforeach
                                                            <th class="col-1"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($translations[array_key_first($translations)] as $key => $translation)
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control" name="keys[]"
                                                                        value="{{ $key }}">
                                                                </td>
                                                                @foreach ($translations as $lang => $tr)
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="{{ $lang }}[]"
                                                                            value="{{ $tr->$key ?? '' }}">
                                                                    </td>
                                                                @endforeach
                                                                <td class="col-1">
                                                                    <a id="removeRow"
                                                                        class="Delete btn btn-danger">{{ __('Delete') }}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-warning  mb-4 col-2 offset-5 "
                                                    id="addRow">{{ __('Add') }}</a>
                                                <button class="btn btn-success col-12">{{ __('Save') }}</button>
                                            </form>
                                        @else
                                            <p style="font-size: 40px; text-align: center;">{{ __('No data exist') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
@section('alpine_scripts')
    <script>
        function filter(langJson) {

            // console.log((object) Object.keys(langJson));
            return Object.keys(langJson);
        }
    </script>
@endsection
