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
                <h5 class="modal-title" id="exampleModalLabel">إضافة دولة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="m-5" action="{{ route('countries.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="url" value="{{ request()->url ?? route('main.create') }}">

                    <div class="row mb-4">
                        <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                            الإسم</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" placeholder="أدخل الإسم" class="form-control"
                                id="horizontal-title-input">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                            الاسم الدولي</label>
                        <div class="col-sm-9">
                            <input type="text" name="code" placeholder="أدخل الاسم الدولي (  كمثال SA  ) "
                                class="form-control" id="horizontal-title-input">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-unit-input" class="col-sm-3 col-form-label fs-4">
                            العملة</label>
                        <div class="col-sm-9">
                            <input type="text" name="unit" placeholder=" أدخل العملة ($)"
                                class="form-control" id="horizontal-unit-input">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-unit-input" class="col-sm-3 col-form-label fs-4">
                            العملة بالإنجليزية</label>
                        <div class="col-sm-9">
                            <input type="text" name="unit_en" placeholder=" أدخل العملة ($)"
                                class="form-control" id="horizontal-unit-input">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-country_code-input" class="col-sm-3 col-form-label fs-4">
                            الرقم الدولي</label>
                        <div class="col-sm-9">
                            <input type="text" name="country_code" placeholder=" (+212)أدخل الرقم الدولي"
                                class="form-control" id="horizontal-country_code-input" dir="ltr"
                                style="text-align: right">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-status-input" class="col-sm-3 col-form-label fs-4">
                            الحالة</label>
                        <div class="col-sm-9">
                            <select name="status" class="form-select">
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="UNACTIVE">UNACTIVE</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-description-input"
                            class="col-sm-3 col-form-label fs-4">الرسالة</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" placeholder="أدخل الرسالة" name="message" id="horizontal-description-input"
                                rows="4"></textarea>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="horizontal-pin-input" class="col-sm-3 col-form-label fs-4">
                            الإثبات</label>
                        <div class="col-sm-9">
                            <select name="pin" class="form-select">
                                <option value="PINED">PINED</option>
                                <option value="UNPINED">UNPINED</option>
                            </select>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-end mb-4">
                        <button type="submit" class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">أضف
                            الدولة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
