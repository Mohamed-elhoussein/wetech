<style>
    .close {
        background: none;
        border: none;
        font-size: 25px;
    }
</style>
<div class="modal fade" id="add-new-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">إضافة حي جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="m-5" action="{{ route('street.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="url" value="{{ request()->url ?? route('main.create') }}">
                    <div class="row mb-4">
                        <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                            الاسم</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" placeholder="أدخل الاسم" class="form-control"
                                id="horizontal-title-input">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                            الدولة</label>
                        <div class="col-sm-9">
                            <select name="" class="form-select" id="countries">
                                <option value="">----- إختر الدولة -----</option>

                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                            المدينة</label>
                        <div class="col-sm-9">
                            <select name="city_id" class="form-select" id="cities">
                                <option value="">----- إختر المدينة -----</option>
                            </select>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-end mb-4">
                        <button type="submit" class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">أضف
                            الحي</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
