<form method="GET" class="d-flex align-items-center ms-2">

    <select name="status" class="form-select">
        <option value="" @if (!request()->has('status')) selected @endif>إختر حالة</option>
        <option value="active" @if (request()->get('status') === 'active') selected @endif>مفعل</option>
        <option value="inactive" @if (request()->get('status') === 'inactive') selected @endif>غير مفعل</option>
    </select>

    <button class="btn btn-success ms-2">
        فرز
    </button>
    @if (request()->has('status'))
        <a href="{{ $route }}" class="btn btn-danger ms-2" style="white-space: nowrap">إعادة التعيين</a>
    @endif
</form>
