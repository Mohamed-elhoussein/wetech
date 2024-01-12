@extends('layout.partials.app')

@section('title', 'قائمة المنتوجات')

@section('dashbord_content')
<div class="page-content">
    <div class="container-fluid">
        @if (session('created'))
        <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
            {{ session('created') }}
        </div>
        @endif
        @if (session('deleted'))
        <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
            {{ session('deleted') }}
        </div>
        @endif
        @if (session('updated'))
        <div class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
            {{ session('updated') }}
        </div>
        @endif
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18 ">قائمة المنتوجات</h4>
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-2">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        <a href="{{ route('product.create') }}" class="btn btn-success w-md">أضف منتوج</a>
                        <span onclick="exportTasks(event.target);" data-href="{{ route('product.export') }}" id="export" class="btn btn-primary ms-2">
                            إستخراج
                        </span>
                        <button data-bs-toggle="modal" data-bs-target="#importModal" id="import" class="btn ms-2 btn-primary">
                            رفع
                        </button>

                        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">رفع المزودين</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="file">إختر الملف (csv)</label>
                                            <input type="file" class="form-control @error('file') is-invalid mt-1 @enderror" style="display: block" name="file" accept=".csv" required>
                                            @error('file')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                            <a class="mt-2" style="display: block" download href="{{ asset('csv/products.csv') }}">تحميل مثال الملف</a>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                            <button type="submit" class="btn btn-primary">رفع</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <form method="GET" class="d-flex align-items-center ms-2">
                            <select class="form-select" name="status">
                                <option @if (!request()->has('status')) selected @endif value="">إختر حالة</option>
                                <option @if (request()->get('status') === 'NEW') selected @endif value="NEW">جديد</option>
                                <option @if (request()->get('status') === 'USED') selected @endif value="USED">مستعمل</option>
                            </select>

                            <button class="btn btn-success ms-2">فرز</button>
                            @if (request()->has('status'))
                            <a href="{{ route('product.index') }}" class="d-block ms-2 btn btn-danger w-100" style="white-space: nowrap">إعادة التعيين</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="me-2" style="position: relative">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>تعديل</span>
                                    <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                </button>
                                <div class="dropdown-menu">
                                    <button data-bulk-url="{{ route('product.bulk-action') }}" class="dropdown-item bulk__submit" data-value="NEW" data-action="status">تحويل
                                        إلى جديد</button>
                                    <button data-bulk-url="{{ route('product.bulk-action') }}" class="dropdown-item bulk__submit" data-value="USED" data-action="status">
                                        تحويل إلى مستعمل
                                    </button>
                                    <button data-bulk-url="{{ route('product.bulk-action') }}" class="dropdown-item bulk__submit" data-action="delete">حذف</button>
                                </div>
                                <input type="hidden" name="ids" id="ids">
                            </div>
                            @include('partials.search-input', [
                            'placeholder' => 'إبحث من منتوج عبر الإسم او الوصف',
                            'hide' => true,
                            ])
                        </div>

                        <div class="col-sm-4 offset-sm-8 mb-4">
                            <form action="" id="search">
                                <label class="visually-hidden" for="inlineFormInputGroupUsername"></label>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" class="form-check" id="checkAll">
                                        </th>
                                        <th scope="col"> الصورة</th>
                                        <th scope="col"> المنتوج</th>
                                        <th scope="col">الزبون</th>
                                        <th scope="col" style=" overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
                                            الوصف</th>
                                        <th scope="col">هل يظهر المنتج</th>
                                        <th scope="col">الحالة</th>
                                        <th scope="col">حالة المراجعة</th>
                                        <th scope="col">وقت الانشاء</th>
                                        <th scope="col">تعديل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk__check form-check" value="{{ $product->id }}">
                                        </td>
                                        <td>
                                            <div class="avatar-sm">
                                                <span class="avatar-title avatar-md rounded-circle">
                                                    <img class="avatar-md rounded-circle " src="{{ $product->images ? url($product->images[0]) : default_image() }}" alt=" product image">
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{ $product->name }}</a>
                                            </h5>
                                        </td>
                                        <td>
                                            <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{ $product->user->username }}</a>
                                            </h5>
                                        </td>
                                        <td style=" overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
                                            {{ $product->description ?: 'لا يوجد' }}
                                        </td>
                                        <td style="font-weight: bold">
                                            @if ($product->active)
                                            <span class=" border  badge-soft-success px-1 rounded border-2 border-success ">
                                                {{ ' يظهر ' }}
                                                @else
                                            <span class=" border  badge-soft-danger px-1 rounded border-2 border-danger ">
                                                {{ ' لا يظهر ' }}
                                                @endif
                                            </span>
                                        </td>
                                        <td style="font-weight: bold">

                                            @if ($product->status == 'NEW')
                                            <span class=" border  badge-soft-success px-1 rounded border-2 border-success ">
                                                {{ ' جديد ' }}
                                                @else
                                                <span class=" border  badge-soft-primary px-1 rounded border-2 border-primary ">
                                                    {{ ' مستعمل' }}
                                                    @endif
                                                </span>
                                        </td>
                                        <td style="font-weight: bold">
                                            <select class="product_status form-select" data-product-id="{{ $product->id }}">
                                                @foreach (App\Enum\RevisionProductStatus::all() as $status)
                                                <option value="{{ $status }}" @if($product->revision_status === $status) selected @endif>{{ __($status) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="javascript: void(0);" class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($product->created_at)) }}</a>

                                            </div>
                                        </td>

                                        <td>
                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                <li class="list-inline-item px-2">
                                                    <a href="{{ route('product.edit', ['id' => $product->id]) }}" title="Edit"><i class="bx bx-pencil"></i></a>
                                                </li>
                                                <li class="list-inline-item px-2">
                                                    <a class="delete" href="{{ route('product.delete', ['id' => $product->id]) }}" title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                </li>


                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection

@section('scripts')

<script>
    $('.product_status').on('change', function(e) {
        $.ajax({
            url: '{{ route("product.revision.status") }}',
            method: "POST",
            data: {
                status: e.target.value,
                product_id: $(e.target).data('product-id')
            },
            success: r => {
                if (r.success) {
                    alertSucces('تم التحديث !', "تم تحديث حالة المراجعة")
                }
            }
        })
    })
</script>

@endsection
